<?php

namespace AppBundle\Controller\Web\Portal;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
    * @Route("/", name="portal_index")
    * @Method("GET")
    */
    public function indexAction()
    {
        return $this->render(
            'portal/default/index.html.twig'
        );
    }
}