<?php

namespace AppBundle\Form\Admin\Handler;

use AppBundle\DomainManager\Admin\AdminProfileManager;
use AppBundle\Entity\Admin\AdminProfile;
use AppBundle\Form\AbstractFormHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminProfileFormHandler extends AbstractFormHandler
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
        if(!$this->processForm($form, $request)){
            return false;
        }

        /** @var AdminProfile $entity */
        $entity = $form->getData();

        $entity->getUser()
            ->addRole('ROLE_ADMIN_USER')
            ->setIsEnabled(true)
            ->setAdminProfile($entity);

        $this->manager->create($entity);

        return true;
    }

    public function edit(FormInterface $form, Request $request)
    {
        if(!$this->processForm($form, $request)){
            return false;
        }
        
        /** @var AdminProfile $entity */
        $entity = $form->getData();

        $this->manager->edit($entity);

        return true;
    }
}
