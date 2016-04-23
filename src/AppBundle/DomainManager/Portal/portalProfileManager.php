<?php

namespace AppBundle\DomainManager\Portal;

use AppBundle\DomainManager\AbstractManager;
use AppBundle\DomainManager\ProfileManagerInterface;
use AppBundle\Entity\AbstractProfile;
use AppBundle\Entity\Portal\PortalProfile;
use AppBundle\Entity\User;
use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Helper\FlashBagHelper;
use AppBundle\Helper\PaginationHelper;
use AppBundle\Helper\ParametersHelper;
use AppBundle\Repository\Portal\PortalProfileRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PortalProfileManager extends AbstractManager implements ProfileManagerInterface
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PortalProfileRepository
     */
    private $repository;

    /**
     * @var CanonicalizerHelper
     */
    private $canonicalizerHelper;

    /**
     * @var FlashBagHelper
     */
    private $flashBagHelper;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ParametersHelper
     */
    private $parametersHelper;

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
     * @param User $user
     * @return void
     */
    public function editLastLogin(User $user)
    {
        $this->persistAndFlush($user);
    }

    /**
     * @param string $email
     * @return AbstractProfile
     */
    public function findByEmail($email)
    {
        return $this->repository->findByEmail($this->canonicalizerHelper->canonicalize($email));
    }

    /**
     * @param AbstractProfile $profile
     * @return void
     */
    public function resettingRequest(AbstractProfile $profile)
    {
        $params = $this->parametersHelper->getParams('portal');
        
        $event = new ProfileEvent($profile);
        $event->setParams($params['security']['resetting']['email']);

        $this->eventDispatcher->dispatch(
            ProfileEvents::RESETTING_REQUEST_SUCCESS,
            $event
        );

        $this->persistAndFlush($profile->getUser());
    }

    /**
     * @param string $token
     * @return AbstractProfile
     */
    public function findByConfirmationToken($token)
    {
        return $this->repository->findByConfirmationToken($token);
    }

    /**
     * @param AbstractProfile $profile
     * @return void
     */
    public function resettingReset(AbstractProfile $profile)
    {
        $this->eventDispatcher->dispatch(ProfileEvents::RESETTING_RESET_SUCCESS, new ProfileEvent($profile));

        $this->persistAndFlush($profile->getUser());
    }

    /**
     * @param AbstractProfile $profile
     * @return void
     */
    public function changePassword(AbstractProfile $profile)
    {
        $this->persistAndFlush($profile);
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