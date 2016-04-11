<?php

namespace AppBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;

class AdminUser extends EntityRepository
{

    public function findUserByEmail($emailCanonical)
    {

        $adminUser = $this->createQueryBuilder('adminUser')
            ->innerJoin('adminUser.user', 'user')
            ->andWhere('adminUser.emailCanonical = :emailCanonical')->setParameter('emailCanonical', $emailCanonical)
            ->andWhere('user.roles like :role')->setParameter(':role', '%ROLE_ADMIN_USER%')
            ->getQuery()
            ->getOneOrNullResult();

        var_dump($adminUser);exit;
        
        /*return $this->createQueryBuilder('adminUser')
            ->innerJoin('adminUser.user', 'user')
            ->andWhere('adminUser.emailCanonical = :emailCanonical')->setParameter('emailCanonical', $emailCanonical)
            ->andWhere('user.roles like :role')->setParameter(':role', '%ROLE_ADMIN_USER%')
            ->getQuery()
            ->getOneOrNullResult();*/
    }
}