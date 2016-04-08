<?php

namespace AppBundle\Controller\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface SecurityControllerInterface
{

    /**
     * @return Response
     */
    public function loginAction();

    /**
     * @return void
     */
    public function loginCheckAction();

    /**
     * @return void
     */
    public function logoutAction();

    /**
     * @param Request $request
     * @return Response
     */
    public function resettingRequestAction(Request $request);

    /**
     * @param Request $request
     * @param $token
     * @return Response
     */
    public function resettingResetAction(Request $request, $token);

    /**
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request);
}