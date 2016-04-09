<?php

namespace AppBundle\Security;

use AppBundle\DomainManager\Admin\AdminUserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AdminFormAuthenticator extends AbstractFormAuthenticator
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
     *
     * @param AdminUserManager $adminUserManager
     * @param UserPasswordEncoder $encoder
     * @param RouterInterface $router
     */
    public function __construct(
        AdminUserManager $adminUserManager,
        UserPasswordEncoder $encoder,
        RouterInterface $router
    ) {
        $this->adminUserManager = $adminUserManager;
        $this->encoder = $encoder;
        $this->router = $router;
    }

    public function getLoginUrl()
    {
        return $this->router->generate('admin_security_login');
    }

    public function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('admin_index');
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/admin/login' || !$request->isMethod('POST')) {
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

}