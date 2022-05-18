<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * @Route("/registration", name="app_registration")
     */ 
    public function register(Request $request, 
    GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator , \Swift_Mailer $mailer): Response
    {
        $user = new User();
        
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
     if ($form->isSubmitted() && $form->isValid()) {
             if($user->getImageName() != null){
                        //Move image
  $filename=$form->get('imagename')->getData()->getClientOriginalName();
  $form->get('imagename')->getData()->move($this->getParameter('kernel.project_dir'). '/public/images/user',$filename);
  //end move image
  $user->setImageName($filename);
}
            // Encode the new user password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            $user->setActivationToken(md5(uniqid()));
            $user->setImgStatus("normal");
            // Set their role
            $user->setRoles(['ROLE_USER']);



            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            $message = (new \Swift_Message('Nouveau compte'))
    // On attribue l'expéditeur
    ->setFrom('formula1brightlights@gmail.com')
    // On attribue le destinataire
    ->setTo($user->getEmail())
    // On crée le texte avec la vue
    ->setBody(
        $this->renderView(
            'email/activation.html.twig', ['token' => $user->getActivationToken()]
        ),
        'text/html'
    )
;
          $mailer->send($message);



            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );

            
       }

        return $this->render('registration/register.html.twig', [
            'formRegistration' => $form->createView(),
        ]);
        
    }


    /**
 * @Route("/activation/{token}", name="activation")
 */
public function activation($token, UserRepository $userRepository)
{
    // On recherche si un utilisateur avec ce token existe dans la base de données
    $user = $userRepository->findOneBy(['activation_token' => $token]);

    // Si aucun utilisateur n'est associé à ce token
    if(!$user){
        // On renvoie une erreur 404
        throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
    }
   
   

    // On supprime le token
    $user->setActivationToken(null);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($user);
    $entityManager->flush();

    // On génère un message
    $this->addFlash('message', 'Vous avez bien activé votre compte');

    // On retourne à l'accueil
    return $this->redirectToRoute('app_compte');
}



/********************************************** Méthode mobile ************************************************** */



/**
 * Undocumented function
 *
 * @param Request $request
 * @Route("/signup", name="app_signup")
 */
public function registerMobile(Request $request, UserPasswordEncoderInterface $passwordEncoder){
    $email = $request->query->get("email");
    $password = $request->query->get("password");
    $name = $request->query->get("name");
    $tel = $request->query->get("tel");
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return new Response("email invalid.");

    }
    $user = new User();
    $user->setName($name);
    $user->setEmail($email);
    $user->setRoles(['ROLE_USER']);
    $user->setPassword($passwordEncoder->encodePassword($user,$password));
    $user->setTel($tel);
    try{
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse("votre compte a ete créé avec succés", 200);
    }catch(\Exception $ex){
        return new Response("exception".$ex->getMessage());
    }
}

}

