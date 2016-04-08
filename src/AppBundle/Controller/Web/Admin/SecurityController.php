<?php

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Controller\Web\SecurityControllerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller implements SecurityControllerInterface
{
    /**
     * @Route("/login", name="admin_security_login")
     */
    public function loginAction()
    {
        $error = $this->get('security.authentication_utils')
            ->getLastAuthenticationError();

        return new Response('Login action. Erros:' . $error);
    }

    /**
     * @Route("/login_check", name="admin_security_login_check")
     */
    public function loginCheckAction()
    {
        // TODO: Implement loginCheckAction() method.
    }

    /**
     * @Route("/logout", name="admin_security_logout")
     */
    public function logoutAction()
    {
        // TODO: Implement logoutAction() method.
    }

    /**
     * @Route("/resetting/request", name="admin_security_resetting_request")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return mixed
     */
    public function resettingRequestAction(Request $request)
    {
        // TODO: Implement resettingRequestAction() method.
    }

    /**
     * @Route("/resetting/reset/{token}", name="admin_security_resetting_reset")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param $token
     * @return mixed
     */
    public function resettingResetAction(Request $request, $token)
    {
        // TODO: Implement resettingResetAction() method.
    }

    /**
     * @Route("/change-password", name="admin_security_change_password")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return mixed
     */
    public function changePassword(Request $request)
    {
        // TODO: Implement changePassword() method.
    }

}