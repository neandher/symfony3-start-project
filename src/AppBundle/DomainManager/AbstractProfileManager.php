<?php

namespace AppBundle\DomainManager;

use AppBundle\Entity\AbstractProfile;
use AppBundle\Entity\User;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Repository\Admin\AdminProfile;
use Doctrine\ORM\EntityManager;

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
     * @param User $user
     * @return void
     */
    public function resettingRequest(User $user)
    {
        // TODO: Implement resettingRequest() method.
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