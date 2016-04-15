<?php

namespace AppBundle\Form\Security\Handler;

use AppBundle\DomainManager\ProfileManagerInterface;
use AppBundle\Event\FlashBag\FlashBagEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Translation\Translator;

class ResettingResetFormHandler
{

    /**
     * @var ProfileManagerInterface
     */
    private $profileManager;

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * ResettingResetFormHandler constructor.
     * 
     * @param ProfileManagerInterface $profileManager
     * @param FlashBag $flashBag
     * @param Translator $translator
     */
    public function __construct(ProfileManagerInterface $profileManager, FlashBag $flashBag, Translator $translator)
    {
        $this->profileManager = $profileManager;
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $entity = $form->getData();

        $this->profileManager->resettingReset($entity);

        $this->flashBag->add(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            $this->translator->trans('security.resetting.reset.success')
        );

        return true;
    }
}