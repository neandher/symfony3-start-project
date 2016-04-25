<?php

namespace AppBundle\DomainManager\Portal;


use AppBundle\DomainManager\AbstractProfileManager;
use AppBundle\Entity\Portal\PortalProfile;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Helper\FlashBagHelper;
use AppBundle\Helper\PaginationHelper;
use AppBundle\Helper\ParametersHelper;
use AppBundle\Repository\Portal\PortalProfileRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PortalProfileManager extends AbstractProfileManager
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PortalProfileRepository
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
     * PortalProfileManager constructor.
     *
     * @param EntityManager $em
     * @param PortalProfileRepository $repository
     * @param CanonicalizerHelper $canonicalizerHelper
     * @param FlashBagHelper $flashBagHelper
     * @param EventDispatcherInterface $eventDispatcher
     * @param ParametersHelper $parametersHelper
     */
    public function __construct(
        EntityManager $em,
        PortalProfileRepository $repository,
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
        return $this->parametersHelper->getParams('portal');
    }

    /**
     * @param PaginationHelper $paginationHelper
     * @return PortalProfile[]
     */
    /*public function findLatest(PaginationHelper $paginationHelper)
    {
        return $this->repository->findLatest($paginationHelper);
    }

    public function create(PortalProfile $portalProfile)
    {
        $this->persistAndFlush($portalProfile);
        
        $this->flashBagHelper->newMessage(FlashBagEvents::MESSAGE_TYPE_SUCCESS, FlashBagEvents::MESSAGE_SUCCESS_INSERTED);
    }

    public function edit(PortalProfile $portalProfile)
    {
        $this->persistAndFlush($portalProfile);

        $this->flashBagHelper->newMessage(FlashBagEvents::MESSAGE_TYPE_SUCCESS, FlashBagEvents::MESSAGE_SUCCESS_UPDATED);
    }
    public function delete(PortalProfile $portalProfile)
    {
        $this->removeAndFlush($portalProfile);

        $this->flashBagHelper->newMessage(FlashBagEvents::MESSAGE_TYPE_SUCCESS, FlashBagEvents::MESSAGE_SUCCESS_DELETED);
    }*/

}