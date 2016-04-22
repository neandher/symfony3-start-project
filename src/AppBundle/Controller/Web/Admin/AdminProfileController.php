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

        $paginationHelper = $this->get('app.helper.pagination')->handle($request, AdminProfile::class);

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
                return $this->redirectToRoute('admin_profile_new', $paginationHelper->getRouteParams());
            }
            if ($form->get('buttons')->get(SubmitActions::SAVE_AND_KEEP)->isClicked()) {
                return $this->redirectToRoute(
                    'admin_profile_edit',
                    array_merge(
                        ['id' => $adminProfile->getId()],
                        $paginationHelper->getRouteParams()
                    )
                );
            }

            return $this->redirectToRoute('admin_profile_index', $paginationHelper->getRouteParams());
        }

        return $this->render(
            'admin/profile/new.html.twig',
            [
                'admin_profile'     => $adminProfile,
                'form'              => $form->createView(),
                'pagination_helper' => $paginationHelper
            ]
        );
    }

    /**
     * @Route("/{id}/edit", requirements={"id" : "\d+"}, name="admin_profile_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AdminProfile $adminProfile)
    {

        $paginationHelper = $this->get('app.helper.pagination')->handle($request, AdminProfile::class);

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

        $deleteForm = $this->createDeleteForm($adminProfile);

        $formHandler = $this->get('app.admin_profile_form_handler');

        if ($formHandler->edit($editForm, $request)) {

            if ($editForm->get('buttons')->get(SubmitActions::SAVE_AND_NEW)->isClicked()) {
                return $this->redirectToRoute('admin_profile_new', $paginationHelper->getRouteParams());
            }
            if ($editForm->get('buttons')->get(SubmitActions::SAVE_AND_KEEP)->isClicked()) {
                return $this->redirectToRoute(
                    'admin_profile_edit',
                    array_merge(
                        ['id' => $adminProfile->getId()],
                        $paginationHelper->getRouteParams()
                    )
                );
            }

            return $this->redirectToRoute('admin_profile_index', $paginationHelper->getRouteParams());
        }

        return $this->render(
            'admin/profile/edit.html.twig',
            [
                'admin_profile'     => $adminProfile,
                'form'              => $editForm->createView(),
                'delete_form'       => $deleteForm->createView(),
                'pagination_helper' => $paginationHelper,
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_profile_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AdminProfile $adminProfile)
    {
        $form = $this->createDeleteForm($adminProfile);

        $formHandler = $this->get('app.admin_profile_form_handler');

        if (!$formHandler->delete($form, $request)) {
            return $this->redirectToRoute('admin_profile_edit', ['id' => $adminProfile->getId()]);
        }

        return $this->redirectToRoute('admin_profile_index');
    }

    private function createDeleteForm(AdminProfile $adminProfile)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_profile_delete', ['id' => $adminProfile->getId()]))
            ->setMethod('DELETE')
            ->setData($adminProfile)
            ->getForm();
    }
}