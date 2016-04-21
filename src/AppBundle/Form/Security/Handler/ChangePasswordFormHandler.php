<?php

namespace AppBundle\Form\Security\Handler;

use AppBundle\DomainManager\ProfileManagerInterface;
use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Form\AbstractFormHandler;
use AppBundle\Helper\FlashBagHelper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordFormHandler extends AbstractFormHandler
{

    /**
     * @var ProfileManagerInterface
     */
    private $profileManager;

    /**
     * @var FlashBagHelper
     */
    private $flashBagHelper;

    /**
     * ChangePasswordFormHandler constructor.
     *
     * @param ProfileManagerInterface $profileManager
     * @param FlashBagHelper $flashBagHelper
     */
    public function __construct(ProfileManagerInterface $profileManager, FlashBagHelper $flashBagHelper)
    {
        $this->profileManager = $profileManager;
        $this->flashBagHelper = $flashBagHelper;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function handle(FormInterface $form, Request $request)
    {
        $this->processForm($form, $request);

        $entity = $form->getData();

        $entity->getUser()->setPassword(null);

        $this->profileManager->changePassword($entity);

        $this->flashBagHelper->newMessage(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            'security.change_password.success'
        );

        return true;
    }
}