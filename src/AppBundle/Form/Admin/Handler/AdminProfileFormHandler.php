<?php

namespace AppBundle\Form\Admin\Handler;

use AppBundle\DomainManager\Admin\AdminProfileManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminProfileFormHandler
{

    /**
     * @var AdminProfileManager
     */
    private $manager;

    public function __construct(AdminProfileManager $manager)
    {
        $this->manager = $manager;
    }

    public function create(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $entity = $form->getData();

        $this->manager->create($entity);

        return true;
    }
}
