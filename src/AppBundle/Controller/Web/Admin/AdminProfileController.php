<?php

namespace AppBundle\Controller\Web\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function indexAction()
    {
        
        $profiles = $this->get('app.admin_profile_manager')->findLatest();
        
        return $this->render(
            'admin/profile/index.html.twig',
            [
                'profiles' => $profiles
            ]
        );
    }
}