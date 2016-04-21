<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormHandler
{

    protected function processForm(FormInterface $form, Request $request)
    {
        if (strpos($request->getPathInfo(), '/api') !== 0) {

            $form->handleRequest($request);

            if (!$form->isValid()) {
                return false;
            }
            
        } else {
            
            // processFormApi

            /*$this->processFormApi($request, $form);

            if (!$form->isValid()) {
                return $this->throwApiProblemValidationException($form);
            }*/
        }


    }

    /*private function processFormApi(FormInterface $form, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

            throw new ApiProblemException($apiProblem);
        }

        $clearMissing = $request->getMethod() != 'PATCH';

        $form->submit($data, $clearMissing);
    }*/
}