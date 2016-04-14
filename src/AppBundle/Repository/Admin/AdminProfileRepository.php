<?php

namespace AppBundle\Repository\Admin;

use AppBundle\Repository\ProfileRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class AdminProfileRepository extends EntityRepository implements ProfileRepositoryInterface
{

    /**
     * @param $emailCanonical
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByEmail($emailCanonical)
    {
        return $this->createQueryBuilder('adminProfile')
            ->innerJoin('adminProfile.user', 'user')
            ->andWhere('adminProfile.emailCanonical = :emailCanonical')->setParameter('emailCanonical', $emailCanonical)
            ->andWhere('user.roles like :role')->setParameter(':role', '%ROLE_ADMIN_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $token
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByConfirmationToken($token)
    {
        return $this->createQueryBuilder('adminProfile')
            ->innerJoin('adminProfile.user','user')
            ->andWhere('user.confirmationToken = :token')->setParameter('token', $token)
            ->andWhere('user.roles like :role')->setParameter(':role', '%ROLE_ADMIN_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return mixed
     */
    public function findLatest()
    {
        return $this->createQueryBuilder('adminProfile')
            ->innerJoin('adminProfile.user', 'user')
            ->getQuery()
            ->execute();
    }
}