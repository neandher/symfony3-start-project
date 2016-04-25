<?php

namespace AppBundle\Repository\Portal;

use AppBundle\Repository\AbstractEntityRepository;
use AppBundle\Repository\ProfileRepositoryInterface;

class PortalProfileRepository extends AbstractEntityRepository implements ProfileRepositoryInterface
{

    /**
     * @param $emailCanonical
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByEmail($emailCanonical)
    {
        return $this->createQueryBuilder('portalProfile')
            ->innerJoin('portalProfile.user', 'user')
            ->andWhere('portalProfile.emailCanonical = :emailCanonical')->setParameter('emailCanonical', $emailCanonical)
            ->andWhere('user.roles like :role')->setParameter(':role', '%ROLE_PORTAL_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $token
     * @return mixed
     */
    public function findByConfirmationToken($token)
    {
        return $this->createQueryBuilder('portalProfile')
            ->innerJoin('portalProfile.user', 'user')
            ->andWhere('user.confirmationToken = :token')->setParameter('token', $token)
            ->andWhere('user.roles like :role')->setParameter(':role', '%ROLE_PORTAL_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }

}