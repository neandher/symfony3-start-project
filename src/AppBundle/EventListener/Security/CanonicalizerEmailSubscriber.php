<?php

namespace AppBundle\EventListener\Security;

use AppBundle\Entity\AbstractProfile;
use AppBundle\Helper\CanonicalizerHelper;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CanonicalizerEmailSubscriber implements EventSubscriber
{

    /**
     * @var CanonicalizerHelper
     */
    private $canonicalizer;

    public function __construct(CanonicalizerHelper $canonicalizer)
    {
        $this->canonicalizer = $canonicalizer;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate'
        );
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (!$this->isAbstractProfile($entity)) {
            return;
        }

        $this->canonicalizerEmail($entity);
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (!$this->isAbstractProfile($entity)) {
            return;
        }

        $this->canonicalizerEmail($entity);
    }

    private function canonicalizerEmail(AbstractProfile $profile)
    {
        $email = $profile->getEmail();

        $profile->setEmailCanonical($this->canonicalizer->canonicalize($email));
    }

    private function isAbstractProfile($entity)
    {
        return $entity instanceof AbstractProfile;
    }
}