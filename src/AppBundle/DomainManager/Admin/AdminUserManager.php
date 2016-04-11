<?php

namespace AppBundle\DomainManager\Admin;

use AppBundle\DomainManager\AbstractUserManager;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Repository\Admin\AdminUser;
use Doctrine\ORM\EntityManager;

class AdminUserManager extends AbstractUserManager
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AdminUser
     */
    protected $repository;

    /**
     * @var CanonicalizerHelper
     */
    protected $canonicalizerHelper;

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
}