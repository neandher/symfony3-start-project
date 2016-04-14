<?php

namespace AppBundle\DomainManager\Admin;

use AppBundle\DomainManager\AbstractProfileManager;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Repository\Admin\AdminProfileRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AdminProfileManager extends AbstractProfileManager
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AdminProfileRepository
     */
    protected $repository;

    /**
     * @var CanonicalizerHelper
     */
    protected $canonicalizerHelper;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * AdminProfileManager constructor.
     *
     * @param EntityManager $em
     * @param AdminProfileRepository $repository
     * @param CanonicalizerHelper $canonicalizerHelper
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManager $em,
        AdminProfileRepository $repository,
        CanonicalizerHelper $canonicalizerHelper,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->canonicalizerHelper = $canonicalizerHelper;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param array $routeParams
     * @return \AppBundle\Entity\Admin\AdminProfile[]
     */
    public function findLatest(array $routeParams)
    {
        return $this->repository->findLatest($routeParams);
    }
}