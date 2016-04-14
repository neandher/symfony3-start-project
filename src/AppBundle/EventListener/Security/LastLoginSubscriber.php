<?php

namespace AppBundle\EventListener\Security;

use AppBundle\DomainManager\ProfileManagerInterface;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LastLoginSubscriber implements EventSubscriberInterface
{

    /**
     * @var ProfileManagerInterface
     */
    private $profileManager;

    public function __construct(ProfileManagerInterface $profileManager)
    {
        $this->profileManager = $profileManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin'
        );
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if( $user instanceof User ){
            $user->setLastLoginAt(new \DateTime());
            $this->profileManager->editLastLogin($user);
        }
    }

}