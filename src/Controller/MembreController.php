<?php

namespace App\Controller;

use App\Entity\Equipes;
use App\Entity\Membres;
use App\Entity\Pilotes;
use App\Form\MembreType;
use App\Repository\EquipesRepository;
use App\Repository\MembresRepository;
use App\Repository\PilotesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class MembreController extends AbstractController
{
    /**
     * @Route("/membre", name="membre")
     */
    public function index(MembresRepository $repo,Request $request,PaginatorInterface $paginator): Response
    {
        $repoEquipe=$this->getDoctrine()->getRepository(Equipes::class);
        $equipe=$repoEquipe->findAll();
        $Allmembres=$repo->findOneWithPilote();
        $membres = $paginator->paginate(
            // Doctrine Query, not results
            $Allmembres,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('membre/index.html.twig', [
            'membres' => $membres,
            'equipe'=>$equipe,
        ]);
    }


    /**
     * @Route("/addMembre" , name="addMembre")
     */
    public function addMembre(Request $request): Response
    {
        $membre=new Membres();
        $form=$this->createForm(MembreType::class,$membre);
        $form->add('Ajouter',SubmitType::class);
        $form->add('Annuler',ResetType::class,[
            'attr'=>[
                'class'=>'btn btn-dark'
            ]
            ]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            //Move image
            $filename=$form->get('image')->getData()->getClientOriginalName();
            $form->get('image')->getData()->move($this->getParameter('kernel.project_dir'). '/public/images/membre',$filename);
            //end move image
            $membre->setImage($filename);
            $em=$this->getDoctrine()->getManager();
            $em->persist($membre);
            $em->flush();
            //add pilote
            if($request->get('numero') != NULL && $request->get('numero') != ""){
                echo "fel if";
                $id=$membre->getId();
                $num=$request->get('numero');
                $pilote=new Pilotes();
                $pilote->setId($id);
                $pilote->setNumero($num);
                $em->persist($pilote);
                $em->flush();
            }
            //end addPilote

            return $this->redirectToRoute('membre');
        }
        return $this->render('membre/ajouterMembre.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/editMembre/{id}" , name="editMembre")
     */
    public function editMembre(MembresRepository $repo,$id){
        $repoEquipe=$this->getDoctrine()->getRepository(Equipes::class);
        $equipes=$repoEquipe->findAll();
        $membre=$repo->find($id);
        if($membre->getRole()=="Pilote"){
            $repoPilote=$this->getDoctrine()->getRepository(Pilotes::class);
            $pilote=$repoPilote->find($membre->getId());
            return $this->render('membre/modifierMembre.html.twig',[
                'membre'=>$membre,
                'equipes'=>$equipes,
                'pilote'=>$pilote,
            ]);
        }else{
            return $this->render('membre/modifierMembre.html.twig',[
                'membre'=>$membre,
                'equipes'=>$equipes,
                'pilote'=>NULL,
            ]);
        }
    }


    /**
     * @Route("/update_membre/{id}", name="update_membre")
     */
    public function update_membre(MembresRepository $repo,Request $request,$id){
        $em=$this->getDoctrine()->getManager();
        $repoEquipe=$this->getDoctrine()->getRepository(Equipes::class);
        $repoPilote=$this->getDoctrine()->getRepository(Pilotes::class);
        $equipe=$repoEquipe->find($request->get('equipe'));
        $membre=$repo->find($id);
        $membre->setNom($request->get('nom'));
        $membre->setEquipe($equipe);
        $date = new \DateTime('@'.strtotime($request->get('date')));
        $membre->setDateNaissance($date);
        $membre->setNationalite($request->get('nationalite'));
        if( $request->files->get('image') == null ){
            $membre->setImage($request->get('path'));
            
        }else{
            //Move image
            $filename= $request->files->get('image')->getClientOriginalName();
            $request->files->get('image')->move($this->getParameter('kernel.project_dir'). '/public/images/membre',$filename);
            //end move image
            $membre->setImage($filename);
        }
        if($membre->getRole() == "Pilote" and $request->get('role') != "Pilote" ){
            $pilote=$repoPilote->find($membre->getId());
            $em->remove($pilote);
            $membre->setRole($request->get('role'));
        }else{
            if($membre->getRole() != "Pilote" and $request->get('role') == "Pilote" ){
                $pilote=new Pilotes();
                $pilote->setId($membre->getId());
                $pilote->setNumero($request->get('numero'));
                $em->persist($pilote);
                $em->flush();
                $membre->setRole($request->get('role'));
            }else{
                if($membre->getRole() == "Pilote" and $request->get('role') == "Pilote"){
                    $pilote=$repoPilote->find($membre->getId());
                    $pilote->setNumero($request->get('numero'));
                    $em->persist($pilote);
                    $em->flush();
                    $membre->setRole($request->get('role'));
                }else{
                    $membre->setRole($request->get('role'));
                }
            }
        }
        $em->persist($membre);
        $em->flush();
        return $this->redirectToRoute('membre');
    }

    /**
     * @Route("/SupprimerMembre/{id}" , name="SupprimerMembre")
     */
    public function SupprimerMembre(MembresRepository $repo,$id){
        $em=$this->getDoctrine()->getManager();
        $repoPilote=$this->getDoctrine()->getRepository(Pilotes::class);
        $membre=$repo->find($id);
        if($membre->getRole() == "Pilote"){
            $pilote=$repoPilote->find($id);
            $em->remove($pilote);
        }
        $em->remove($membre);
        $em->flush();
        return $this->redirectToRoute('membre');
    }

    /**
     * @Route("/affichePilote" , name="affichePilote")
     */
    public function affichePilote(MembresRepository $repo,Request $request,PaginatorInterface $paginator): Response
    {   $repoEquipe=$this->getDoctrine()->getRepository(Equipes::class);
        $equipe=$repoEquipe->findAll();
        $Allmembres=$repo->findPilote();
        $membres = $paginator->paginate(
            // Doctrine Query, not results
            $Allmembres,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('membre/index.html.twig', [
            'membres' => $membres,
            'equipe' =>  $equipe     
        ]);
    }


    /**
     * @Route("/getEquipeDuMembre/{id}" , name="getEquipeDuMembre")
     */
    public function getEquipeDuMembre(MembresRepository $repo,$id)
    {
        $membre=$repo->find($id);
        $equipe=$membre->getEquipe();
         return new Response($equipe->getNom());
    }




    /**
     * @Route("/getMembreDequipe/{id}" , name="getMembreDequipe")
     */
    public function getMembreDequipe(MembresRepository $repo,$id,Request $request,PaginatorInterface $paginator){
        $repoEquipe=$this->getDoctrine()->getRepository(Equipes::class);
        $eq=$repoEquipe->find($id);
        $Allmembres=$repo->findByEquipe($eq);
        $equipe=$repoEquipe->findAll();
        $membre = $paginator->paginate(
            // Doctrine Query, not results
            $Allmembres,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('membre/index.html.twig', [
            'membres' => $membre,
            'equipe'=>$equipe,
        ]);
    }



    /**
     * @Route("/search_membre" , name="search_membre")
     */
    public function search_membre(MembresRepository $repo,Request $request)
    {
        $name=$request->get('name');
        $membre=$repo->findByName($name);
        if(!$membre){
            $result['membre']['error']="membre not found";
        }
        else{
            $result['membre']=$this->getRealEntities($membre);
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($membre){
        foreach($membre as $membre){
            if($membre->getRole() == "Pilote"){
                $repo=$this->getDoctrine()->getRepository(Pilotes::class);
                $pilote=$repo->find($membre->getId());
                $realEntities[$membre->getId()] = [$membre->getId(),$membre->getNom(),$membre->getImage(),$membre->getRole(),$membre->getNationalite(),$membre->getDateNaissance(),$pilote->getNumero()];
            }else{
                $realEntities[$membre->getId()] = [$membre->getId(),$membre->getNom(),$membre->getImage(),$membre->getRole(),$membre->getNationalite(),$membre->getDateNaissance()];
            }
            
        }
        return $realEntities;
    }
    //end search



    
}
