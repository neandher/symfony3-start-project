<?php

namespace AppBundle\Security;

use AppBundle\DomainManager\Admin\AdminUserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
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
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * AdminFormAuthenticator constructor.
     * @param AdminUserManager $adminUserManager
     * @param UserPasswordEncoder $encoder
     * @param RouterInterface $router
     */
    public function __construct(AdminUserManager $adminUserManager, UserPasswordEncoder $encoder, RouterInterface $router)
    {
        $this->adminUserManager = $adminUserManager;
        $this->encoder = $encoder;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return mixed
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $url = $this->router->generate('admin_security_login');

        return new RedirectResponse($url);
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
        $plainPassword = $credentials['password'];

        if (!$this->encoder->isPasswordValid($user, $plainPassword)) {
            return;
        }

        return true;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return mixed
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        $url = $this->router->generate('admin_security_login');

        return new RedirectResponse($url);
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return mixed
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $request->getSession()->get('_security.'.$providerKey.'.target_path');

        if (!$targetPath) {
            $targetPath = $this->router->generate('admin_index');
        }

        return new RedirectResponse($targetPath);
    }

    /**
     * @return mixed
     */
    public function supportsRememberMe()
    {
        return true;
    }

}