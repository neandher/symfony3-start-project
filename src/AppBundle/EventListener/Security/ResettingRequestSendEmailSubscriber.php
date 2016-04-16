<?php

namespace AppBundle\EventListener\Security;

use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use AppBundle\Helper\FlashBagHelper;
use AppBundle\Mailer\Security\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ResettingRequestSendEmailSubscriber implements EventSubscriberInterface
{

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var FlashBagHelper
     */
    private $flashBagHelper;

    public function __construct(
        Mailer $mailer,
        FlashBagHelper $flashBagHelper
    ) {
        $this->mailer = $mailer;
        $this->flashBagHelper = $flashBagHelper;
    }

    /**
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            ProfileEvents::RESETTING_REQUEST_SUCCESS => 'onResettingRequestSuccess'
        );
    }

    public function onResettingRequestSuccess(ProfileEvent $event)
    {
        $profile = $event->getProfile();

        $this->mailer->sendResettingEmailMessage($profile);

        $this->flashBagHelper->newMessage(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            'security.resetting.request.check_email',
            [
                'profile_email' => $profile->getObfuscatedEmail()
            ]

        );
    }

}