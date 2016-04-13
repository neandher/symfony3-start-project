<?php

namespace AppBundle\EventListener\Security;

use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use AppBundle\Mailer\Security\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Translation\Translator;

class ResettingRequestSendEmailSubscriber implements EventSubscriberInterface
{

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var FlashBag
     */
    private $flashBag;

    public function __construct(
        Mailer $mailer,
        Translator $translator,
        FlashBag $flashBag
    )
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->flashBag = $flashBag;
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

        $this->flashBag->add(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            $this->translator->trans(
                'security.resetting.request.check_email',
                array('profile_email' => $profile->getObfuscatedEmail())
            )
        );
    }

}