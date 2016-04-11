<?php

namespace AppBundle\DomainManager;

use Doctrine\ORM\EntityManager;

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
    public function persistAndFlush($entity, $flush = true)
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }
}