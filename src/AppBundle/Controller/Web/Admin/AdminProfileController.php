<?php

namespace AppBundle\Controller\Web\Admin;

use AppBundle\Entity\Admin\AdminProfile;
use AppBundle\Form\Admin\Type\AdminProfileType;
use AppBundle\Form\Admin\Type\AdminProfileUpdateType;
use AppBundle\Form\SubmitActions;
use AppBundle\Form\SubmitActionsType;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $paginationHelper = $this->get('app.helper.pagination')->handle($request, AdminProfile::class);

        $profiles = $this->get('app.admin_profile_manager')->findLatest($paginationHelper);

        return $this->render(
            'admin/profile/index.html.twig',
            [
                'profiles'          => $profiles,
                'pagination_helper' => $paginationHelper,
            ]
        );
    }

    /**
     * @Route("/new", name="admin_profile_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $adminProfile = new AdminProfile();

        $form = $this->createForm(AdminProfileType::class, $adminProfile)
            ->add(
                'buttons',
                SubmitActionsType::class,
                [
                    'mapped'  => false,
                    'actions' => [
                        SubmitActions::SAVE_AND_KEEP,
                        SubmitActions::SAVE_AND_NEW,
                        SubmitActions::SAVE_AND_CLOSE,
                    ]
                ]
            );

        $formHandler = $this->get('app.admin_profile_form_handler');

        if ($formHandler->create($form, $request)) {

            if ($form->get('buttons')->get(SubmitActions::SAVE_AND_NEW)->isClicked()) {
                return $this->redirectToRoute('admin_profile_new');
            }
            if ($form->get('buttons')->get(SubmitActions::SAVE_AND_KEEP)->isClicked()) {
                return $this->redirectToRoute('admin_profile_edit', ['id' => $adminProfile->getId()]);
            }

            return $this->redirectToRoute('admin_profile_index');
        }

        return $this->render(
            'admin/profile/new.html.twig',
            [
                'admin_profile' => $adminProfile,
                'form'          => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", requirements={"id" : "\d+"}, name="admin_profile_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AdminProfile $adminProfile)
    {
        $editForm = $this->createForm(AdminProfileUpdateType::class, $adminProfile)
            ->add(
                'buttons',
                SubmitActionsType::class,
                [
                    'mapped'  => false,
                    'actions' => [
                        SubmitActions::SAVE_AND_KEEP,
                        SubmitActions::SAVE_AND_NEW,
                        SubmitActions::SAVE_AND_CLOSE,
                    ]
                ]
            );

        $formHandler = $this->get('app.admin_profile_form_handler');

        if ($formHandler->edit($editForm, $request)) {

            if ($editForm->get('buttons')->get(SubmitActions::SAVE_AND_NEW)->isClicked()) {
                return $this->redirectToRoute('admin_profile_new');
            }
            if ($editForm->get('buttons')->get(SubmitActions::SAVE_AND_KEEP)->isClicked()) {
                return $this->redirectToRoute('admin_profile_edit', ['id' => $adminProfile->getId()]);
            }

            return $this->redirectToRoute('admin_profile_index');
        }

        return $this->render(
            'admin/profile/edit.html.twig',
            [
                'admin_profile' => $adminProfile,
                'form'          => $editForm->createView()
            ]
        );
    }
}