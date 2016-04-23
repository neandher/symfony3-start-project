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
    private $params;

    /**
     * ResettingRequestFormHandler constructor.
     * 
     * @param ProfileManagerInterface $profileManager
     * @param TokenGeneratorHelper $tokenGeneratorHelper
     * @param Translator $translator
     * @param $params
     */
    public function __construct(
        ProfileManagerInterface $profileManager,
        TokenGeneratorHelper $tokenGeneratorHelper,
        Translator $translator,
        $params
    ) {
        $this->profileManager = $profileManager;
        $this->tokenGeneratorHelper = $tokenGeneratorHelper;
        $this->translator = $translator;
        $this->params = $params;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function handle(FormInterface $form, Request $request)
    {
        if(!$this->processForm($form, $request)){
            return false;
        }

        $data = $form->getData();

        if ($form->isSubmitted()) {

            $profile = $this->profileManager->findByEmail($data['email']);

            if (!$profile) {
                
                $form->addError(
                    new FormError($this->translator->trans('security.resetting.request.errors.email_not_found'))
                );

                return $this->formHasError($request, $form);
            }
            
            $user = $profile->getUser();

            $tokenTtl = $this->params['security']['resetting']['token_ttl'];

            if ($user->isPasswordRequestNonExpired($tokenTtl)) {
                
                $form->addError(
                    new FormError(
                        $this->translator->trans('security.resetting.request.errors.password_already_requested')
                    )
                );

                return $this->formHasError($request, $form);
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