<?php

namespace AppBundle\DomainManager;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractManager
{
    /**
     * @var EntityManager
     */
    protected $em;
    
    /**
     * @param $entity
     * @param bool $flush
     */
    protected function persistAndFlush($entity, $flush = true)
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * @param $entity
     * @param bool $flush
     */
    protected function removeAndFlush($entity, $flush = true)
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }
}