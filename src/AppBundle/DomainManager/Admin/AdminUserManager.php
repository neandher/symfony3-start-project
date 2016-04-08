<?php

namespace AppBundle\DomainManager\Admin;

use Doctrine\ORM\EntityManager;

class AdminUserManager
{
    /**
     * @var EntityManager
     */
    private $em;
    
    /**
     * AdminUserManager constructor.
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function findUserByEmail($emailCanoninal)
    {
        return $this->getRepository()->findUserByEmail($emailCanoninal);
    }

    private function getRepository()
    {
        return $this->em->getRepository('AppBundle:Admin\AdminUser');
    }
}