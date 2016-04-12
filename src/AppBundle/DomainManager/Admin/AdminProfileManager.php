<?php

namespace AppBundle\DomainManager\Admin;

use AppBundle\DomainManager\AbstractProfileManager;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Repository\Admin\AdminProfile;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * AdminProfileManager constructor.
     *
     * @param EntityManager $em
     * @param AdminProfile $repository
     * @param CanonicalizerHelper $canonicalizerHelper
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManager $em,
        AdminProfile $repository,
        CanonicalizerHelper $canonicalizerHelper,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->canonicalizerHelper = $canonicalizerHelper;
        $this->eventDispatcher = $eventDispatcher;
    }
    
    public function getResettingRequestParameters()
    {
        return [
            ProfileEvent::PARAM_RESETTING_EMAIL_FROM => 'security.resetting_email.from',
            ProfileEvent::PARAM_RESETTING_EMAIL_ROUTE => 'admin_security_resetting_reset',
            ProfileEvent::PARAM_RESETTING_EMAIL_TEMPLATE => 'admin/security/resetting/email.html.twig'
        ];
    }
}