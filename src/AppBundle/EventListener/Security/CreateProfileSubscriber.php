<?php

namespace AppBundle\EventListener\Security;

use AppBundle\Entity\AbstractProfile;
use AppBundle\Entity\Admin\AdminProfile;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use AppBundle\DomainManager\AbstractProfileManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CreateProfileSubscriber implements EventSubscriberInterface
{

    /**
     * @var AbstractProfileManager
     */
    private $manager;

    public function __construct(AbstractProfileManager $manager)
    {
        $this->manager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return
        [
            ProfileEvents::CREATE_SUCCESS => 'createSuccess'
        ];
    }


    public function createSuccess(ProfileEvent $event)
    {
        $entity = $event->getProfile();

        if (!($entity instanceof AbstractProfile)) {
            return;
        }

        $user = $entity->getUser();

        if ($entity instanceof AdminProfile) {
            $user->setAdminProfile($entity);
        }
        
        $this->manager->setUserAbstractProfile($user);
    }

}