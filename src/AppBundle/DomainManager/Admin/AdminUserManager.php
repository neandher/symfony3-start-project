<?php

namespace AppBundle\DomainManager\Admin;

use AppBundle\Helper\CanonicalizerHelper;
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
     * @var CanonicalizerHelper
     */
    private $canonicalizerHelper;

    /**
     * AdminUserManager constructor.
     *
     * @param EntityManager $em
     * @param AdminUser $repository
     * @param CanonicalizerHelper $canonicalizerHelper
     */
    public function __construct(EntityManager $em, AdminUser $repository, CanonicalizerHelper $canonicalizerHelper)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->canonicalizerHelper = $canonicalizerHelper;
    }

    public function findByEmail($email)
    {
        return $this->repository->findByEmail($this->canonicalizerHelper->canonicalize($email));
    }
}