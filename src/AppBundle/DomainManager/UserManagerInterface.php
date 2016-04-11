<?php

namespace AppBundle\DomainManager;

use AppBundle\Entity\User;

interface UserManagerInterface
{

    /**
     * @param User $user
     * @return void
     */
    public function editLastLogin(User $user);

    /**
     * @param string $email
     * @return mixed
     */
    public function findByEmail($email);

    /**
     * @param User $user
     * @return void
     */
    public function resettingRequest(User $user);

    /**
     * @param string $token
     * @return mixed
     */
    public function findByConfirmationToken($token);

    /**
     * @param User $user
     * @return void
     */
    public function resettingReset(User $user);

    /**
     * @param User $user
     * @return void
     */
    public function changePassword(User $user);

}