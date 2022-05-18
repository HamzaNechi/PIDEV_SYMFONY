<?php

namespace App\Security;

use App\Entity\User; // your user entity
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use \KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class GoogleAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $entityManager;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->getPathInfo() == '/connect/google/check' && $request->isMethod('GET');
    }

    public function getCredentials(Request $request){
        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /**
         * @var GoogleUser $googleUser
         * 
         */
       $googleUser = $this->getGoogleClient()
       ->fetchUserFromToken($credentials);

       $email = $googleUser->getEmail();

       $user = $this->entityManager->getRepository(User::class)
       ->findOneBy(['email' => $email]);

       if(!$user){
           $user = new User();
           $user->setEmail($googleUser->getEmail());
           $user->setName($googleUser->getName());
           $user->setImageName($googleUser->getAvatar());
           $this->entityManager->persist($user);
           $this->entityManager->flush();


       }
       return $user;
    }
    
        /**
     * 
     *
     * @return \KnpU\OAuth2ClientBundle\Client\OAuth2Client;
     */
private function getGoogleClient(){
    return $this->clientRegistry->getClient('google');
}

/**
 * Undocumented function
 *
 * @param Request $request
 * @param AuthenticationException|null $authException
 * @return void
 */
public function start(Request $request, ?AuthenticationException $authException = null)
{
    return new RedirectResponse('/login');

}

public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
{
    //
}

public function onAuthenticationSuccess(Request $request, TokenInterface $token, $firewall)
{
 //
}
}