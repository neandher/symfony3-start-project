<?php

namespace AppBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;

class AdminUser extends EntityRepository
{
    public function findUserByEmail($emailCononical)
    {
        return $this->createQueryBuilder('adminUser')
            ->innerJoin('adminUser.user','user')
            ->andWhere('user.emailCanonical = :emailCanonical')->setParameter('emailCanonical', $emailCononical)
            ->andWhere('user.roles like :role')->setParameter(':role', '%ROLE_ADMIN_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }
}