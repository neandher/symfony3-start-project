<?php

namespace AppBundle\Form\Admin\Handler;

use AppBundle\DomainManager\Admin\AdminProfileManager;
use AppBundle\Entity\Admin\AdminProfile;
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

        /** @var AdminProfile $entity */
        $entity = $form->getData();

        $entity->getUser()
            ->addRole('ROLE_ADMIN_USER')
            ->setIsEnabled(true);

        $this->manager->create($entity);

        return true;
    }
}
