<?php

namespace AppBundle\Form\Security\Handler;

use AppBundle\DomainManager\ProfileManagerInterface;
use AppBundle\Form\AbstractFormHandler;
use AppBundle\Helper\TokenGeneratorHelper;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;

class ResettingRequestFormHandler extends AbstractFormHandler
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
    /**
     * @var string
     */
    private $tokenTll;

    /**
     * ResettingRequestFormHandler constructor.
     * 
     * @param ProfileManagerInterface $profileManager
     * @param TokenGeneratorHelper $tokenGeneratorHelper
     * @param Translator $translator
     * @param $tokenTll
     */
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

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function handle(FormInterface $form, Request $request)
    {
        $this->processForm($form, $request);

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