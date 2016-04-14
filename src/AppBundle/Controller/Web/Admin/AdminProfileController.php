<?php

namespace AppBundle\Controller\Web\Admin;

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
        $routeParams = $request->query->all();

        $routeParams['page'] = $request->query->get('page', 1);

        $profiles = $this->get('app.admin_profile_manager')->findLatest($routeParams);

        return $this->render(
            'admin/profile/index.html.twig',
            [
                'profiles' => $profiles
            ]
        );
    }
}