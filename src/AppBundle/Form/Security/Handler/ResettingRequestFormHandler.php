<?php

namespace AppBundle\Form\Security\Handler;

use AppBundle\DomainManager\ProfileManagerInterface;
use AppBundle\Helper\TokenGeneratorHelper;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;

class ResettingRequestFormHandler
{

    /**
     * @var ProfileManagerInterface
     */
    private $profileManager;

    /**
     * @var TokenGeneratorHelper
     */
    private $tokenGeneratorHelper;

    /**
     * @var Translator
     */
    private $translator;

    private $tokenTll;

    public function __construct(
        ProfileManagerInterface $profileManager,
        TokenGeneratorHelper $tokenGeneratorHelper,
        Translator $translator,
        $tokenTll
    ) {
        $this->profileManager = $profileManager;
        $this->tokenGeneratorHelper = $tokenGeneratorHelper;
        $this->tokenTll = $tokenTll;
        $this->translator = $translator;
    }

    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $data = $form->getData();

        if ($form->isSubmitted()) {

            $profile = $this->profileManager->findByEmail($data['email']);

            if (!$profile) {
                
                $form->addError(
                    new FormError($this->translator->trans('security.resetting.request.errors.email_not_found'))
                );

                return false;
            }
            
            $user = $profile->getUser();
            
            if ($user->isPasswordRequestNonExpired($this->tokenTll)) {
                
                $form->addError(
                    new FormError(
                        $this->translator->trans('security.resetting.request.errors.password_already_requested')
                    )
                );

                return false;
            }

            if ($user->getConfirmationToken() === null) {
                $user->setConfirmationToken($this->tokenGeneratorHelper->generateToken());
            }

            $user->setPasswordRequestedAt(new \DateTime());
            
            $this->profileManager->resettingRequest($profile);
        }

        return true;
    }
}