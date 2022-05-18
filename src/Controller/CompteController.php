<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateCompteType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CompteController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    /**
     * @Route("/compte", name="app_compte")
     */
    public function index(): Response
    {
        
        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
        ]);
    }

     /**
       * Undocumented function
       *
       * @param UserRepository $repository
       * @route("/compte/AfficherCompte", name="affichageC")
       */
      public function Afficher(UserRepository $repository){
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user=$repository->findAll();
  
        
        return $this->render('compte/Affiche.html.twig',[
            'user' => $user

            
        ])
        ;

}
/**
 * Undocumented function
 *
 * @param UserRepository $repository
 * @route("/compte/AfficherCompteMobile", name="affichageCM")
 */
public function AfficherMobile(UserRepository $repository){
    $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
    $user=$repository->findAll();
    $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);
    

}
/**
 * Undocumented function
 *
 * @Route("/modifier_compte/{id}",name="modifier_compte")
 */
public function modifier_compte(Request $request,$id){
    $repository = $this->getDoctrine()->getRepository(User::class);
    $user=$repository->find($id);

    return $this->render('compte/Update.html.twig',[
        'user'=>$user
    ]);
}



/**
 * Undocumented function
 * 
 * @param Request $request
 * @Route("/UpdateCmpt/{id}",name="UpdateCmpt")
 */
function UpdateCmpt(UserRepository $repository, $id, Request $request){
    //dd($request->files->get('file'));
    $repository = $this->getDoctrine()->getRepository(User::class);
    $user=$repository->find($id);
    $user->setName($request->get('nom'));
    $user->setTel($request->get('tel'));
    if($request->files->get('file') == null ){
        $user->setImageName($request->get('img'));
    }
    else{
    
 //Move image
 $filename=$request->files->get('file')->getClientOriginalName();
 $request->files->get('file')->move($this->getParameter('kernel.project_dir'). '/public/images/user',$filename);
 //end move image
 $user->setImageName($filename);
 $user->setImgStatus("normal");
    }
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute("affichageC");
}

}

