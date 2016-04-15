<?php

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Entity\Admin\AdminProfile;
use AppBundle\Form\Admin\Form\AdminProfileType;
use AppBundle\Form\SubmitActions;
use AppBundle\Form\SubmitActionsType;
use AppBundle\Helper\PaginationHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminProfileController
 *
 * @Route("/profile")
 */
class AdminProfileController extends Controller
{

    /**
     * @Route("/", name="admin_profile_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $routeParams = PaginationHelper::getRouteParams($request, new AdminProfile());

        $profiles = $this->get('app.admin_profile_manager')->findLatest($routeParams);

        return $this->render(
            'admin/profile/index.html.twig',
            [
                'profiles' => $profiles
            ]
        );
    }

    /**
     * @Route("/new", name="admin_profile_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $adminProfile = new AdminProfile();

        $form = $this->createForm(AdminProfileType::class, $adminProfile);

        $formHandler = $this->get('app.admin_profile_form_handler');

        if ($formHandler->create($form, $request)) {

            if ($form->get(SubmitActions::SAVE_AND_NEW)) {
                return $this->redirectToRoute('admin_profile_new');
            }

            return $this->redirectToRoute('admin_profile_index');
        }

        return $this->render(
            'admin/profile/new.html.twig',
            [
                'admin_profile' => $adminProfile,
                'form' => $form->createView(),
                //'submit_actions' => new SubmitActions()
            ]
        );
    }
}