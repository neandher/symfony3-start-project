<?php

namespace AppBundle\EventListener\Security;

use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use AppBundle\Helper\FlashBagHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResettingResetSubscriber implements EventSubscriberInterface
{

    /**
     * @var FlashBagHelper
     */
    private $flashBagHelper;

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
     * @param FlashBagHelper $flashBagHelper
     * @param UrlGeneratorInterface $router
     * @param $tokenTll
     */
    public function __construct(
        FlashBagHelper $flashBagHelper,
        UrlGeneratorInterface $router,
        $tokenTll
    ) {
        $this->flashBagHelper = $flashBagHelper;
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

            $this->flashBagHelper->newMessage(
                FlashBagEvents::MESSAGE_TYPE_ERROR,
                'security.resetting.reset.errors.invalid_token'
            );

            $request->attributes->add(['error' => 'true']);

        } elseif (!$profile->getUser()->isPasswordRequestNonExpired($this->tokenTll)) {

            $this->flashBagHelper->newMessage(
                FlashBagEvents::MESSAGE_TYPE_ERROR,
                'security.resetting.reset.errors.expired_token'
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