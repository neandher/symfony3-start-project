<?php

namespace AppBundle\DomainManager\Admin;

use AppBundle\DomainManager\AbstractProfileManager;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Repository\Admin\AdminProfile;
use Doctrine\ORM\EntityManager;

class AdminProfileManager extends AbstractProfileManager
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AdminProfile
     */
    protected $repository;

    /**
     * @var CanonicalizerHelper
     */
    protected $canonicalizerHelper;

    /**
     * AdminProfileManager constructor.
     *
     * @param EntityManager $em
     * @param AdminProfile $repository
     * @param CanonicalizerHelper $canonicalizerHelper
     */
    public function __construct(EntityManager $em, AdminProfile $repository, CanonicalizerHelper $canonicalizerHelper)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->canonicalizerHelper = $canonicalizerHelper;
    }
}