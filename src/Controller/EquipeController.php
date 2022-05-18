<?php

namespace App\Controller;

use App\Entity\Equipes;
use App\Form\EquipeType;
use App\Repository\EquipesRepository;
use App\Repository\MembresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\File\File;


class EquipeController extends AbstractController
{
    /**
     * @Route("/equipe", name="equipe")
     */
    public function index(Request $request,PaginatorInterface $paginator): Response
    {
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $Allequipe=$repo->findAll();
        $equipes = $paginator->paginate(
            // Doctrine Query, not results
            $Allequipe,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            9
        );
        return $this->render('equipe/index.html.twig',[
            'equipes'=>$equipes
        ]);
    }


    /**
     * @Route("/addEquipe", name="addEquipe")
     */
    public function addEquipe(Request $request): Response
    {

        $equipe=new Equipes();
        $form=$this->createForm(EquipeType::class,$equipe);
        $form->add('Ajouter',SubmitType::class);
        $form->add('Annuler',ResetType::class,[
            'attr'=>[
                'class'=>'btn btn-dark'
            ]
            ]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            //Move image
            $filename=$form->get('logo')->getData()->getClientOriginalName();
            $form->get('logo')->getData()->move($this->getParameter('kernel.project_dir'). '/public/images/equipe',$filename);
            //end move image
            $equipe->setLogo($filename);
            $em=$this->getDoctrine()->getManager();
            $em->persist($equipe);
            $em->flush();

            return $this->redirectToRoute('equipe');
        }
        return $this->render('equipe/ajouterEquipe.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/modifier_equipe/{id}" , name="modifier_equipe")
     */
    public function modifier_equipe(Request $request,$id){
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $equipe=$repo->find($id);
        
        return $this->render('equipe/modifierEquipe.html.twig',[
            'equipe'=>$equipe
        ]);
    }

    /**
     * @Route("/modifierE/{id}" , name="modifierE")
     */
    public function modifierE(Request $request,$id){
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $equipe=$repo->find($id);
        $equipe->setNom($request->get('nom'));
        $equipe->setEmail($request->get('email'));
        if($request->files->get('logo') == null){
            $equipe->setLogo($request->get('img'));
            
        }else{
            //Move image
            $filename= $request->files->get('logo')->getClientOriginalName();
            $request->files->get('logo')->move($this->getParameter('kernel.project_dir'). '/public/images/equipe',$filename);
            //end move image
            $equipe->setLogo($filename);
        }
        $equipe->setVoiture($request->get('voiture'));
        $equipe->setPaysOrigine($request->get('pays'));
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('equipe');
    }



    /**
     * @Route("/search" , name="search")
     */
    public function search(EquipesRepository $repo,Request $request)
    {
        $name=$request->get('name');
        $equipe=$repo->findByName($name);
        if(!$equipe){
            $result['equipe']['error']="équipe not found";
        }
        else{
            $result['equipe']=$this->getRealEntities($equipe);
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($equipe){
        foreach($equipe as $equipe){
            $realEntities[$equipe->getId()] = [$equipe->getId(),$equipe->getNom(),$equipe->getEmail(),$equipe->getLogo(),$equipe->getVoiture(),$equipe->getPaysOrigine()];
        }
        return $realEntities;
    }
    //end search

    /**
     * @Route("/supprimer_equipe/{id}" , name="supprimer_equipe")
     */
    public function supprimer_equipe($id){
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $equipe=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($equipe);
        $em->flush();
        return $this->redirectToRoute('equipe');
    }



    //generate_attestation
    /**
     * @Route("/generate_attestation/{id}" , name="generate_attestation")
     */
    public function generate_attestation(MembresRepository $repom,$id){
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $equipe=$repo->find($id);
        $membre=$repom->findByEquipe($equipe);
        //dd($membre);
        return $this->render('equipe/attestation.html.twig',[
            'equipe'=>$equipe,
            'membre'=>$membre,
        ]);
    }


    //generate_pdf
    /**
     * @Route("/generate_pdf/{id}" , name="generate_pdf")
     */
    public function generate_pdf(MembresRepository $repom,$id){
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $pdfOptions=new Options();
        $pdfOptions->set('defaultFont','Arial');


        $dompdf=new Dompdf($pdfOptions);
        $equipe=$repo->find($id);
        $membre=$repom->findByEquipe($equipe);


        $html=$this->renderView('equipe/pdf.html.twig',[
            'equipe'=>$equipe,
            'membre'=>$membre,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','portrait');
      //  $dompdf->set_base_path("C:\wamp64_3.2\www\Symfony\F1-hamza-v3\public\assets");
        $dompdf->render();
        $nomfichier=$equipe->getNom().$equipe->getId().".pdf";
        $dompdf->stream($nomfichier , [ "Attachment" => true ]);
    }



    /**
     * @Route("/email/{id}" , name="email")
     */
    public function sendEmail(MailerInterface $mailer,$id)
    {
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $equipe=$repo->find($id);
        $email = (new Email())
            ->from('Formula3A11@gmail.com')
            ->to($equipe->getEmail())
            ->subject("Attestation d'inscription")
            ->html('<p>Votre équipe '.$equipe->getNom().' a été inscrit dans la tournoi de FormulaOne tunisie. </p>');
        $mailer->send($email);

        return $this->redirectToRoute('equipe');
    }

    /*****************************Web service Mobile************************************/
    //ajouter equipe
    /**
     *@Route("/addEquipeMobile" , name="addEquipeMobile")
     * @return void
     */
    public function addEquipeMobile(Request $request){
        $file = new File($request->get('logo'));
        //Move image
        $filename="equipe";
        $file->move($this->getParameter('kernel.project_dir'). '/public/images/equipe',$filename);
        //end move image

        //dd("hhhh.png");
        $equipe=new Equipes();
        $equipe->setNom($request->get('nom'));
        $equipe->setEmail($request->get('email'));
        $equipe->setLogo($filename.$request->get('ex'));
        $equipe->setVoiture($request->get('voiture'));
        $equipe->setPaysOrigine($request->get('pays'));
        $em=$this->getDoctrine()->getManager();
        $em->persist($equipe);
        $em->flush();

        $response = new JsonResponse([$equipe]);
        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
        return $response;
    }

    //afficher les équipes
    /**
     * @Route("/displayEquipeMobile", name="displayEquipeMobile")
     */
    public function displayEquipeMobile(){
        $equipes=$this->getDoctrine()->getRepository(Equipes::class)->findAll();
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($equipes);

        return new JsonResponse($formatted);
    }


    //Supprimer equipe
    /**
     * @Route("/deleteEquipeMobile/{id}", name="deleteEquipeMobile")
     */
    public function deleteEquipeMobile($id){
        $equipe=$this->getDoctrine()->getRepository(Equipes::class)->find($id);
        if($equipe != null){
            $em=$this->getDoctrine()->getManager();
            $em->remove($equipe);
            $em->flush();
            return new JsonResponse("équipe supprimé avec succé");
        }else{
            return new JsonResponse("id équipe n'a pas trouvé");
        }
        
    }


    //generate_pdf
    /**
     * @Route("/generate_pdf_mobile/{id}" , name="generate_pdf")
     */
    public function generate_pdf_mobile(MembresRepository $repom,$id){
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $pdfOptions=new Options();
        $pdfOptions->set('defaultFont','Arial');


        $dompdf=new Dompdf($pdfOptions);
        $equipe=$repo->find($id);
        $membre=$repom->findByEquipe($equipe);


        $html=$this->renderView('equipe/pdf.html.twig',[
            'equipe'=>$equipe,
            'membre'=>$membre,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','portrait');
      //  $dompdf->set_base_path("C:\wamp64_3.2\www\Symfony\F1-hamza-v3\public\assets");
        $dompdf->render();
        $nomfichier=$equipe->getNom().$equipe->getId().".pdf";
        $dompdf->stream($nomfichier , [ "Attachment" => true ]);
        return new JsonResponse("success");
    }

    


    /**
     * @Route("/getMembreDequipeMobile/{id}" , name="getMembreDequipeMobile")
     */
    public function getMembreDequipeMobile(MembresRepository $repo,$id){
        $repoEquipe=$this->getDoctrine()->getRepository(Equipes::class);
        $eq=$repoEquipe->find($id);
        $Allmembres=$repo->findByEquipe($eq);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($Allmembres);

        return new JsonResponse($formatted);
    }



    //update
    /**
     *@Route("/updateEquipeMobile" , name="updateEquipeMobile")
     * @return void
     */
    public function updateEquipeMobile(EquipesRepository $repo,Request $request){
        $equipe=$repo->find(intval($request->get('id')));
        $equipe->setNom($request->get('nom'));
        $equipe->setEmail($request->get('email'));
        $equipe->setLogo($request->get('logo'));
        $equipe->setVoiture($request->get('voiture'));
        $equipe->setPaysOrigine($request->get('pays'));
        $em=$this->getDoctrine()->getManager();
        $em->persist($equipe);
        $em->flush();

        $response = new JsonResponse([$equipe]);
        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
        return $response;
    }
}
