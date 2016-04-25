<?php

namespace AppBundle\Controller\Web\Portal;

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
     * @Route("/login", name="portal_security_login")
     */
    public function loginAction()
    {
        $utils = $this->get('security.authentication_utils');

        $form = $this->createForm(LoginType::class);

        return $this->render(
            'portal/security/login.html.twig',
            [
                'form' => $form->createView(),
                'last_username' => $utils->getLastUsername(),
                'error' => $utils->getLastAuthenticationError(),
            ]
        );
    }

    /**
     * @Route("/login_check", name="portal_security_login_check")
     */
    public function loginCheckAction()
    {
        // TODO: Implement loginCheckAction() method.
    }

    /**
     * @Route("/logout", name="portal_security_logout")
     */
    public function logoutAction()
    {
        // TODO: Implement logoutAction() method.
    }

    /**
     * @Route("/resetting/request", name="portal_security_resetting_request")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return mixed
     */
    public function resettingRequestAction(Request $request)
    {
        $form = $this->createForm(ResettingRequestType::class);

        $formHandler = $this->get('app.portal_resetting_request_form_handler');

        if ($formHandler->handle($form, $request)) {
            return $this->redirectToRoute('portal_security_login');
        }

        return $this->render(
            'portal/security/resetting/resettingRequest.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/resetting/reset/{token}", name="portal_security_resetting_reset")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param $token
     * @return mixed
     */
    public function resettingResetAction(Request $request, $token)
    {
        $manager = $this->get('app.portal_profile_manager');
        $params = $this->get('app.helper.parameters')->getParams('portal');

        $event = new ProfileEvent(null, $manager, $request);
        $event->setParams($params['security']['resetting']);

        $dispatcher = $this->get('event_dispatcher')->dispatch(
            ProfileEvents::RESETTING_RESET_INITIALIZE,
            $event
        );

        if ($request->attributes->has('error')) {
            return $this->redirectToRoute('portal_security_login');
        }

        $form = $this->createForm(ResettingResetType::class, $dispatcher->getProfile());

        $formHandler = $this->get('app.portal_resetting_reset_form_handler');

        if ($formHandler->handle($form, $request)) {
            return $this->redirectToRoute('portal_security_login');
        }

        return $this->render(
            'portal/security/resetting/resettingReset.html.twig',
            [
                'form' => $form->createView(),
                'token' => $token
            ]
        );
    }

    /**
     * @Route("/change-password", name="portal_security_change_password")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return mixed
     */
    public function changePassword(Request $request)
    {
        $portalProfile = $this->getUser()->getPortalProfile();

        $form = $this->createForm(ChangePasswordType::class, $portalProfile);

        $formHandle = $this->get('app.portal_change_password_form_handler');

        if ($formHandle->handle($form, $request)) {
            return $this->redirectToRoute('portal_security_change_password');
        }

        return $this->render(
            'portal/security/changePassword/changePassword.html.twig',
            ['form' => $form->createView()]
        );
    }

}