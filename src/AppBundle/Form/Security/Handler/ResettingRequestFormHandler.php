<?php

namespace AppBundle\Form\Security\Handler;

use AppBundle\DomainManager\UserManagerInterface;
use AppBundle\Entity\User;
use AppBundle\Helper\TokenGeneratorHelper;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;

class ResettingRequestFormHandler
{

    /**
     * @var UserManagerInterface
     */
    private $userManager;

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
        UserManagerInterface $userManager,
        TokenGeneratorHelper $tokenGeneratorHelper,
        Translator $translator,
        $tokenTll
    ) {
        $this->userManager = $userManager;
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

            $abstractUser = $this->userManager->findByEmail($data['email']);

            if (!$abstractUser) {
                
                $form->addError(
                    new FormError($this->translator->trans('security.resetting.request.errors.email_not_found'))
                );

                return false;
            }
            
            /** @var User $user */
            $user = $abstractUser->getUser();
            
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

            $this->userManager->resettingRequest($user);
        }

        return true;
    }
}