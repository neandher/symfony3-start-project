<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormHandler
{

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    protected function processForm(FormInterface $form, Request $request)
    {
        if (!$this->isApi($request)) {

            $form->handleRequest($request);

            if (!$form->isValid()) {
                return false;
            }

            return true;
            
        } else {

            // return $this->processFormApi($request, $form);
        }
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     * @return bool
     */
    protected function formHasError(Request $request, FormInterface $form = null)
    {
        if ($this->isApi($request)) {
            // return api error
            /*return $this->throwApiProblemValidationException($form);*/
        }

        return false;
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isApi(Request $request)
    {
        return strpos($request->getPathInfo(), '/api') === 0;
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
    
        if (!$form->isValid()) {
            return $this->throwApiProblemValidationException($form);
        }
    
        return true;
    }*/
}