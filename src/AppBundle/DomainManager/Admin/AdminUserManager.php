<?php

namespace AppBundle\DomainManager\Admin;

use AppBundle\Repository\Admin\AdminUser;
use Doctrine\ORM\EntityManager;

class AdminUserManager
{
    /**
     * @var EntityManager
     */
    private $em;
    
    /**
     * @var AdminUser
     */
    private $repository;

    /**
     * AdminUserManager constructor.
     *
     * @param EntityManager $em
     * @param AdminUser $repository
     */
    public function __construct(EntityManager $em, AdminUser $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function findUserByEmail($emailCanonical)
    {
        return $this->repository->findUserByEmail($emailCanonical);
    }
}