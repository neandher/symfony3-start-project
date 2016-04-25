<?php

namespace AppBundle\DomainManager\Admin;

use AppBundle\DomainManager\AbstractManager;
use AppBundle\DomainManager\AbstractProfileManager;
use AppBundle\DomainManager\ProfileManagerInterface;
use AppBundle\Entity\AbstractProfile;
use AppBundle\Entity\Admin\AdminProfile;
use AppBundle\Entity\User;
use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Helper\FlashBagHelper;
use AppBundle\Helper\PaginationHelper;
use AppBundle\Helper\ParametersHelper;
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
     * @var FlashBagHelper
     */
    protected $flashBagHelper;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var ParametersHelper
     */
    protected $parametersHelper;

    /**
     * AdminProfileManager constructor.
     *
     * @param EntityManager $em
     * @param AdminProfileRepository $repository
     * @param CanonicalizerHelper $canonicalizerHelper
     * @param FlashBagHelper $flashBagHelper
     * @param EventDispatcherInterface $eventDispatcher
     * @param ParametersHelper $parametersHelper
     */
    public function __construct(
        EntityManager $em,
        AdminProfileRepository $repository,
        CanonicalizerHelper $canonicalizerHelper,
        FlashBagHelper $flashBagHelper,
        EventDispatcherInterface $eventDispatcher,
        ParametersHelper $parametersHelper
    ) {
        $this->em = $em;
        $this->repository = $repository;
        $this->canonicalizerHelper = $canonicalizerHelper;
        $this->flashBagHelper = $flashBagHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->parametersHelper = $parametersHelper;
    }

    /**
     * @return array
     */
    protected function getProfileParams()
    {
        return $this->parametersHelper->getParams('admin');
    }
    
    /**
     * @param PaginationHelper $paginationHelper
     * @return AdminProfile[]
     */
    public function findLatest(PaginationHelper $paginationHelper)
    {
        return $this->repository->findLatest($paginationHelper);
    }

    public function create(AdminProfile $adminProfile)
    {
        $this->persistAndFlush($adminProfile);

        $this->flashBagHelper->newMessage(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            FlashBagEvents::MESSAGE_SUCCESS_INSERTED
        );
    }

    public function edit(AdminProfile $adminProfile)
    {
        $this->persistAndFlush($adminProfile);

        $this->flashBagHelper->newMessage(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            FlashBagEvents::MESSAGE_SUCCESS_UPDATED
        );
    }

    public function delete(AdminProfile $adminProfile)
    {
        $this->removeAndFlush($adminProfile);

        $this->flashBagHelper->newMessage(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            FlashBagEvents::MESSAGE_SUCCESS_DELETED
        );
    }
}