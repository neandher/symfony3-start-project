<?php

namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class OrderingPaginationSubscriber implements EventSubscriber
{

    /**
     * @return mixed
     */
    public function getSubscribedEvents()
    {
        return [
            'postLoad'
        ];
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        dump($args->getObjectManager());
        exit;
    }
}