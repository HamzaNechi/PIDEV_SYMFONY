<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\UpdateUserType;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AdminController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * 
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * Undocumented function
     *
     * @param UserRepository $repository
     * @route("/admin/AfficherU", name="affichage")
     */
    public function Afficher(UserRepository $repository)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findAll();

        return $this->render('admin/Affiche.html.twig', [
            'user' => $user
        ]);
    }
    

    /**
     * Undocumented function
     *
     * @param Request $request
     *
     * 
     * @route("/User/add", name="ajout")
     */
    function Add(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setImgStatus("normal");
            //Move image
            $filename = $form->get('imagename')->getData()->getClientOriginalName();
            $form->get('imagename')->getData()->move($this->getParameter('kernel.project_dir') . '/public/images/user', $filename);
            //end move image
            $user->setImageName($filename);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));;
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("affichage");
        }
        return $this->render('admin/Add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Undocumented function
     * 
     * @param Request $request
     * @Route("/Update/{id}",name="update")
     */
    function Update(UserRepository $repository, $id, Request $request)
    {
        $user = $repository->find($id);
        $form = $this->createForm(UpdateUserType::class, $user);
        $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("affichage");
        }
        return $this->render('admin/Update.html.twig', [
            'f' => $form->createView()
        ]);
    }


    /**
     * Undocumented function
     *
     * 
     * @param UserRepository $repository
     * @Route("/Supprimer/{id}",name="supp")
     */
    function Delete($id, UserRepository $repository)
    {
        $user = $repository->find($id);
        $count=$repository->getAccess($id);
        if($count > 0){
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return  $this->redirectToRoute('affichage');
        }else{
            echo "tu n'a pas l'accés";die;
        }
        
    }

    function Excel()
    {
    }

/****************************Méthode Mobile**************************************************** */
    /**
     * Undocumented function
     *
     * @param UserRepository $repository
     * @route("/AfficherAdminMobile", name="affichageA")
     */
    public function AfficherMobile(UserRepository $repository)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        $user = $repository->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);
    }

    /**
 * Undocumented function
 *
 * @param Request $request
 * @Route("/AddMobileUser", name="app_Add_User_Mobile")
 */
public function AddMobile(Request $request, UserPasswordEncoderInterface $passwordEncoder){
    $email = $request->query->get("email");
    $password = $request->query->get("password");
    $name = $request->query->get("name");
    $tel = $request->query->get("tel");


    $user = new User();
    $user->setName($name);
    $user->setEmail($email);
    $user->setPassword($passwordEncoder->encodePassword($user,$password));
    $user->setTel($tel);
    try{
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse("Utilisateur a ete ajouté avec succés", 200);
    }catch(\Exception $ex){
        return new Response("exception".$ex->getMessage());
    }
}

/**
 * Undocumented function
 * 
 * @param Request $request
 * @Route("/UpdateCmptMobileAdmin/{id}",name="UpdateCmptMobile")
 */
function UpdateCmptMobile(UserRepository $repository, $id, Request $request){
 // $data = $request->request->all();
   // dd($data);
    //dd($request->files->get('file'));
    
    $repository = $this->getDoctrine()->getRepository(User::class);
    $user=$repository->find($id);
    
    $roles =$request->get('roles');
    $user->setRoles(array($roles));

    

    ///if($request->files->get('file') == null ){
///$user->setImageName($request->get('img'));
   // }
    //else{
       
 //Move image
 //$filename=$request->files->get('file')->getClientOriginalName();
 //$request->files->get('file')->move($this->getParameter('kernel.project_dir'). '/public/images/user',$filename);
 //end move image
 //$user->setImageName($filename);
   // }
  
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);
}

/**
     * @Route("/deleteUserMobileAdmin/{id}", name="deleteUserMobileAdmin")
     */
    public function deleteUserMobile($id){
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        if($user != null){
            $em=$this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return new JsonResponse("utilisateur supprimé avec succé");
        }else{
            return new JsonResponse("utilisateur pas trouvé");
        }
        
    }

}
