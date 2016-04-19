<?php

namespace AppBundle\Repository\Admin;

use AppBundle\Helper\PaginationHelper;
use AppBundle\Repository\AbstractEntityRepository;
use AppBundle\Repository\ProfileRepositoryInterface;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class AdminProfileRepository extends AbstractEntityRepository implements ProfileRepositoryInterface
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
    /*public function arrayLatest(array $routeParams)
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
    }*/

    /**
     * @param PaginationHelper $paginationHelper
     * @return Query
     */
    public function queryLatest(PaginationHelper $paginationHelper)
    {
        $routeParams = $paginationHelper->getRouteParams();

        $qb = $this->createQueryBuilder('adminProfile')
            ->innerJoin('adminProfile.user', 'user')
            ->addSelect('user');

        if (isset($routeParams['search'])) {

            $qb->andWhere(
                $qb->expr()->like(
                    $qb->expr()->concat('adminProfile.firstName', $qb->expr()->concat($qb->expr()->literal(' '), 'adminProfile.lastName')),
                    ':search'
                )
            )->setParameter('search', '%' . $routeParams['search'] . '%');
        }

        if (!isset($routeParams['sorting'])) {

            $qb->orderBy('adminProfile.id', 'desc');
        } else {
            
            $qb = $this->addOrderingQueryBuilder($qb, $paginationHelper);
        }

        return $qb->getQuery();
    }

    /**
     * @param PaginationHelper $paginationHelper
     * @return Pagerfanta
     */
    public function findLatest(PaginationHelper $paginationHelper)
    {
        $routeParams = $paginationHelper->getRouteParams();

        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryLatest($paginationHelper), false));

        $paginator->setMaxPerPage($routeParams['num_items']);
        $paginator->setCurrentPage($routeParams['page']);

        return $paginator;
    }
}