<?php

namespace AppBundle\DomainManager;

use AppBundle\Entity\User;
use AppBundle\Helper\CanonicalizerHelper;
use AppBundle\Repository\Admin\AdminUser;
use Doctrine\ORM\EntityManager;

abstract class AbstractUserManager extends AbstractManager implements UserManagerInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AdminUser
     */
    protected $repository;

    /**
     * @var CanonicalizerHelper
     */
    protected $canonicalizerHelper;

    /**
     * @param User $user
     * @return mixed
     */
    public function editLastLogin(User $user)
    {
        $this->persistAndFlush($user);
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        return $this->repository->findByEmail($this->canonicalizerHelper->canonicalize($email));
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function resettingRequest(User $user)
    {
        // TODO: Implement resettingRequest() method.
    }

    /**
     * @param string $token
     * @return mixed
     */
    public function findByConfirmationToken($token)
    {
        // TODO: Implement findByConfirmationToken() method.
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function resettingReset(User $user)
    {
        // TODO: Implement resettingReset() method.
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function changePassword(User $user)
    {
        // TODO: Implement changePassword() method.
    }
}