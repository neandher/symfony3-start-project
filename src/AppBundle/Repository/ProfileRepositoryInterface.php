<?php

namespace AppBundle\Repository;

interface ProfileRepositoryInterface
{
    /**
     * @param $emailCanonical
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByEmail($emailCanonical);

    /**
     * @param $token
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByConfirmationToken($token);
}