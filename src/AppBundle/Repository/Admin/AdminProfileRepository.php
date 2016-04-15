<?php

namespace AppBundle\Repository\Admin;

use AppBundle\Repository\ProfileRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

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
            ->innerJoin('adminProfile.user', 'user')
            ->andWhere('user.confirmationToken = :token')->setParameter('token', $token)
            ->andWhere('user.roles like :role')->setParameter(':role', '%ROLE_ADMIN_USER%')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param array $routeParams
     * @return array
     */
    public function arrayLatest(array $routeParams)
    {
        $qb = $this->createQueryBuilder('adminProfile')
            ->select('adminProfile.id')
            ->addSelect('adminProfile.firstName')
            ->addSelect('adminProfile.lastName')
            ->addSelect('adminProfile.email')
            ->innerJoin('adminProfile.user', 'user')
            ->addSelect('user.lastLoginAt')
            ->addSelect('user.createdAt');

        if (isset($routeParams['search'])) {

            $qb->andWhere(
                $qb->expr()->like(
                    $qb->expr()->concat('adminProfile.firstName', $qb->expr()->concat($qb->expr()->literal(' '), 'adminProfile.lastName')),
                    ':search'
                )

            )->setParameter('search', '%' . $routeParams['search'] . '%');
        }

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param array $routeParams
     * @return Pagerfanta
     * @internal param int $page
     */
    public function findLatest(array $routeParams)
    {
        $paginator = new Pagerfanta(new ArrayAdapter($this->arrayLatest($routeParams), false));
        $paginator->setMaxPerPage(5);
        $paginator->setCurrentPage($routeParams['page']);

        return $paginator;
    }
}