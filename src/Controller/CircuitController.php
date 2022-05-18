<?php

namespace App\Controller;

use App\Entity\Circuits;
use App\Form\CircuitsType;
use App\Repository\CircuitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;


class CircuitController extends AbstractController
{
    /**
     * @Route("/circuit", name="circuit")
     */
    public function index(): Response
    {   $circuits=$this->getDoctrine()->getManager()->getRepository(Circuits::class)->findAll();
        return $this->render('circuit/index.html.twig', ['c'=>$circuits
        ]);
        
    }
    /**
     * @Route("/circuitfront", name="circuitfront")
     */
    public function indexF(): Response
    {   $circuits=$this->getDoctrine()->getManager()->getRepository(Circuits::class)->findAll();
        return $this->render('circuit/index_front.html.twig', ['c'=>$circuits
        ]);
        
    }
      /**
     * @Route("/addcircuit", name="addcircuit", methods={"POST","GET"})
     */
    public function addcircuit(Request $request): Response
    {
       $circuits = new Circuits();
       $form = $this->createform(CircuitsType::class,$circuits);
       $form->add('Ajouter',SubmitType::class);
       $form->add('Annuler',ResetType::class,[
           'attr'=>[
               'class'=>'btn btn-dark'
           ]
           ]);
       $form -> handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
            //Move image
            $filename=$form->get('image')->getData()->getClientOriginalName();
            $form->get('image')->getData()->move($this->getParameter('kernel.project_dir'). '/public/images/Circuits',$filename);
            //end move image
        $circuits->setImage($filename);
        $em=$this->getDoctrine()->getManager();
        $em->persist($circuits);
        $em->flush();
        return $this->redirectToRoute('circuit');
       }
       return $this->render('circuit/ajouterCircuit.html.twig',[
        'form'=>$form->createView()
    ]);
    }
     /**
     * @Route("/supprimer_circuit/{id}" , name="supprimer_circuit")
     */
    public function supprimer_circuit($id){
        $repo=$this->getDoctrine()->getRepository(Circuits::class);
        $circuit=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($circuit);
        $em->flush();
        return $this->redirectToRoute('circuit');
    }


    /**
     * @Route("/editCircuit/{id}",name="editCircuit")
     */
    public function editCircuit(CircuitsRepository $repo,$id){
        $circuit=$repo->find($id);
        return $this->render('circuit/updatecircuit.html.twig',[
            "circuit"=>$circuit,
        ]);
    }
      /**
     * @Route("/modcircuit/{id}", name="mod_circuit")
     */
    public function modcircuit(Request $request,$id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Circuits::class);
        $circuit=$repository->find($id);
        $circuit->setNom($request->get('nom'));
        $circuit->setPays($request->get('pays'));
        $circuit->setLongeur(intval($request->get('longeur')));
        $circuit->setCourseDistance(intval($request->get('course_distance')));
        $circuit->setDescription($request->get('description'));
        $circuit->setCapacite(intval($request->get('capacite')));
        if($request->files->get('file') == null ){
            $circuit->setImage($request->get('img'));
        }
        else{
            //Move image
            $filename=$request->files->get('file')->getClientOriginalName();
            $request->files->get('file')->move($this->getParameter('kernel.project_dir'). '/public/images/Circuits',$filename);
            //end move image
            $circuit->setImage($filename);
        }
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("circuit");
    }
    /*****************************Mobile************************************** */
    /**
     * @Route("/displayCircuitM", name="displayCircuitMobile")
     */
    public function displayCircuitMobile(){
        $circuit=$this->getDoctrine()->getRepository(Circuits::class)->findAll();
      //  $encoder = new JsonEncoder();
        //$normalizer = new ObjectNormalizer();
      //  $normalizer->setCircularReferenceHandler(function ($object) {
        //    return $object->getId();
        //});

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($circuit);
        return new JsonResponse($formatted);
    }
       /**
     * @Route("/addcircuitM", name="addcircuitM"
     * ,Methods={"POST","GET"})
     * @return void
     */
    public function addcircuitM(Request $request)
    {
        $circuit= new Circuits();
        
        $circuit->setNom($request->get('nom'));
        $circuit->setPays($request->get('pays'));
        $circuit->setLongeur(intval($request->get('longeur')));
        $circuit->setCourseDistance(intval($request->get('course_distance')));
        $circuit->setDescription($request->get('description'));
        $circuit->setCapacite(intval($request->get('capacite')));
        $circuit->setImage($request->get('image'));
        //$circuit->setCourse($request->get('course'));
        $em=$this->getDoctrine()->getManager();
        $em->persist($circuit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($circuit);
        return new JsonResponse($formatted);

        //$response = new JsonResponse([$circuit]);
        //$response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );

        //return $response;
        //$serializer = new Serializer([new ObjectNormalizer()]);
        //$formatted = $serializer->normalize($circuit);
        // return new JsonResponse($formatted);
    }
        //Supprimer equipe
    /**
     * @Route("/deleteCircuitMobile/{id}", name="deleteCircuitMobile")
     */
    public function deleteCircuitMobile($id){
        $circuit=$this->getDoctrine()->getRepository(Circuits::class)->find($id);
        if($circuit != null){
            $em=$this->getDoctrine()->getManager();
            $em->remove($circuit);
            $em->flush();
            return new JsonResponse("circuit supprimé avec succé");
        }else{
            return new JsonResponse(" circuit n'a pas trouvé");
        }
        
    }
    /**
     * @Route("/UpdateCircuitMobile/{id}", name="UpdateCircuitMobile")
     */
    function UpdateCmptMobileUser( $id, Request $request){
        
           $circuit= new Circuits();
           
           $repository = $this->getDoctrine()->getRepository(Circuits::class);
           $circuit=$repository->find($id);
           
           
        
        $circuit->setNom($request->get('nom'));
        $circuit->setPays($request->get('pays'));
        $circuit->setLongeur(intval($request->get('longeur')));
        $circuit->setCourseDistance(intval($request->get('course_distance')));
        $circuit->setDescription($request->get('description'));
        $circuit->setCapacite(intval($request->get('capacite')));
        $circuit->setImage($request->get('image'));
       
       
           
       
         
         
               $em=$this->getDoctrine()->getManager();
               $em->flush();
               
               $serializer = new Serializer([new ObjectNormalizer()]);
               $formatted = $serializer->normalize($circuit);
               return new JsonResponse($formatted);
       
    }
}

