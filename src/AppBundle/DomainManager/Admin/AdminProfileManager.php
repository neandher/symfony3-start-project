<?php

namespace AppBundle\DomainManager\Admin;

use AppBundle\DomainManager\AbstractManager;
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

class AdminProfileManager extends AbstractManager implements ProfileManagerInterface
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
    private $parametersHelper;

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
        $params = $this->parametersHelper->getParams('admin');

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