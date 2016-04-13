<?php

namespace AppBundle\DomainManager;

use AppBundle\Entity\AbstractProfile;
use AppBundle\Entity\User;

interface ProfileManagerInterface
{

    /**
     * @param User $user
     * @return void
     */
    public function editLastLogin(User $user);

    /**
     * @param string $email
     * @return AbstractProfile
     */
    public function findByEmail($email);

    /**
     * @param AbstractProfile $profile
     * @return void
     */
    public function resettingRequest(AbstractProfile $profile);

    /**
     * @param string $token
     * @return AbstractProfile
     */
    public function findByConfirmationToken($token);

    /**
     * @param AbstractProfile $profile
     * @return mixed
     */
    public function resettingReset(AbstractProfile $profile);

    /**
     * @param AbstractProfile $profile
     * @return void
     */
    public function changePassword(AbstractProfile $profile);

}