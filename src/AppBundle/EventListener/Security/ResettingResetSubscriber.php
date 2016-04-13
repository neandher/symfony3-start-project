<?php

namespace AppBundle\EventListener\Security;

use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\Translator;

class ResettingResetSubscriber implements EventSubscriberInterface
{

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var int
     */
    private $tokenTll;

    /**
     * ResettingResetSubscriber constructor.
     * 
     * @param FlashBag $flashBag
     * @param Translator $translator
     * @param UrlGeneratorInterface $router
     * @param $tokenTll
     */
    public function __construct(
        FlashBag $flashBag,
        Translator $translator,
        UrlGeneratorInterface $router,
        $tokenTll
    ) {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
        $this->router = $router;
        $this->tokenTll = $tokenTll;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            ProfileEvents::RESETTING_RESET_INITIALIZE => 'onResettingResetInitialize',
            ProfileEvents::RESETTING_RESET_SUCCESS    => 'onResettingResetSuccess',
        );
    }


    public function onResettingResetInitialize(ProfileEvent $event)
    {
        $request = $event->getRequest();
        $token = $request->attributes->get('token');
        $profile = $event->getManager()->findByConfirmationToken($token);

        if (!$profile) {

            $this->flashBag->add(
                FlashBagEvents::MESSAGE_TYPE_ERROR,
                $this->translator->trans('security.resetting.reset.errors.invalid_token')
            );

            $request->attributes->add(['error' => 'true']);

        } elseif (!$profile->getUser()->isPasswordRequestNonExpired($this->tokenTll)) {

            $this->flashBag->add(
                FlashBagEvents::MESSAGE_TYPE_ERROR,
                $this->translator->trans('security.resetting.reset.errors.expired_token')
            );

            $request->attributes->add(['error' => 'true']);

        } else {
            $event->setProfile($profile);
        }
    }

    public function onResettingResetSuccess(ProfileEvent $event)
    {
        $user = $event->getProfile()->getUser();

        $user->setPasswordRequestedAt(null)
            ->setConfirmationToken(null);
    }
}