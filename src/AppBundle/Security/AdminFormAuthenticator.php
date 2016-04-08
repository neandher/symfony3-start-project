<?php

namespace AppBundle\Security;

use AppBundle\DomainManager\Admin\AdminUserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class AdminFormAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var AdminUserManager
     */
    private $adminUserManager;

    /**
     * AdminFormAuthenticator constructor.
     * @param AdminUserManager $adminUserManager
     */
    public function __construct(AdminUserManager $adminUserManager)
    {
        $this->adminUserManager = $adminUserManager;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return mixed
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getCredentials(Request $request)
    {
        if($request->getPathInfo() != '/admin/login' || !$request->isMethod('POST')){
            return;
        }

        return [
            'username' => $request->request->get('email'),
            'password' => $request->request->get('password'),
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return mixed
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['email'];

        return $this->adminUserManager->findUserByEmail($email);;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return mixed
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return mixed
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return mixed
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    /**
     * @return mixed
     */
    public function supportsRememberMe()
    {
        // TODO: Implement supportsRememberMe() method.
    }

}