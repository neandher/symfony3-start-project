<?php

namespace AppBundle\DomainManager;

use AppBundle\Entity\AbstractProfile;
use AppBundle\Entity\User;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Repository\Admin\AdminProfile;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractProfileManager extends AbstractManager implements ProfileManagerInterface
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

        $dispatcher = $this->eventDispatcher->dispatch(
            ProfileEvents::RESETTING_REQUEST_SUCCESS,
            new ProfileEvent($profile)
        );

        $profile = $dispatcher->getProfile();

        //$this->persistAndFlush($profile->getUser());
    }

    /**
     * @param string $token
     * @return AbstractProfile
     */
    public function findByConfirmationToken($token)
    {
        // TODO: Implement findByConfirmationToken() method.
    }

    /**
     * @param User $user
     * @return void
     */
    public function resettingReset(User $user)
    {
        // TODO: Implement resettingReset() method.
    }

    /**
     * @param User $user
     * @return void
     */
    public function changePassword(User $user)
    {
        // TODO: Implement changePassword() method.
    }
}