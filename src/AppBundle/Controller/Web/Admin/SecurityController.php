<?php

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Controller\Web\SecurityControllerInterface;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use AppBundle\Form\Security\Type\ChangePasswordType;
use AppBundle\Form\Security\Type\LoginType;
use AppBundle\Form\Security\Type\ResettingRequestType;
use AppBundle\Form\Security\Type\ResettingResetType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller implements SecurityControllerInterface
{

    /**
     * @Route("/login", name="admin_security_login")
     */
    public function loginAction()
    {
        $utils = $this->get('security.authentication_utils');

        $form = $this->createForm(LoginType::class);

        return $this->render(
            'admin/security/login.html.twig',
            [
                'form' => $form->createView(),
                'last_username' => $utils->getLastUsername(),
                'error' => $utils->getLastAuthenticationError(),
            ]
        );
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
        $form = $this->createForm(ResettingRequestType::class);

        $formHandler = $this->get('app.admin_resetting_request_form_handler');

        if ($formHandler->handle($form, $request)) {
            return $this->redirectToRoute('admin_security_login');
        }

        return $this->render(
            'admin/security/resetting/resettingRequest.html.twig',
            ['form' => $form->createView()]
        );
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
        $manager = $this->get('app.admin_profile_manager');
        $params = $this->get('app.helper.parameters')->getParams('admin');
        
        $event = new ProfileEvent(null, $manager, $request);
        $event->setParams($params['security']['resetting']);
        
        $dispatcher = $this->get('event_dispatcher')->dispatch(
            ProfileEvents::RESETTING_RESET_INITIALIZE,
            $event
        );

        if ($request->attributes->has('error')) {
            return $this->redirectToRoute('admin_security_login');
        }

        $form = $this->createForm(ResettingResetType::class, $dispatcher->getProfile());

        $formHandler = $this->get('app.admin_resetting_reset_form_handler');

        if ($formHandler->handle($form, $request)) {
            return $this->redirectToRoute('admin_security_login');
        }

        return $this->render(
            'admin/security/resetting/resettingReset.html.twig',
            [
                'form' => $form->createView(),
                'token' => $token
            ]
        );
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
        $adminProfile = $this->getUser()->getAdminProfile();

        $form = $this->createForm(ChangePasswordType::class, $adminProfile);

        $formHandle = $this->get('app.admin_change_password_form_handler');

        if ($formHandle->handle($form, $request)) {
            return $this->redirectToRoute('admin_security_change_password');
        }

        return $this->render(
            'admin/security/changePassword/changePassword.html.twig',
            ['form' => $form->createView()]
        );
    }

}