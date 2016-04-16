<?php

namespace AppBundle\DomainManager;

use AppBundle\Entity\AbstractProfile;
use AppBundle\Entity\User;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Helper\FlashBagHelper;
use AppBundle\Repository\ProfileRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractProfileManager extends AbstractManager implements ProfileManagerInterface
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ProfileRepositoryInterface
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
        $this->eventDispatcher->dispatch(
            ProfileEvents::RESETTING_REQUEST_SUCCESS,
            new ProfileEvent($profile)
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
}