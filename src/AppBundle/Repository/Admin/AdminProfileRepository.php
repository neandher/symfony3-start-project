<?php

namespace AppBundle\Repository\Admin;

use AppBundle\Entity\Admin\AdminProfile;
use AppBundle\Repository\ProfileRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
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
     * @param array $routeParams
     * @return Query
     */
    public function queryLatest(array $routeParams)
    {
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

            /*$metaData = $this->getClassMetadata();
            $fields = $metaData->getFieldNames();
            $associations = $metaData->getAssociationMappings();

            foreach ($associations as $assoc_ind => $assoc_val) {

                $associationFields = $this->getEntityManager()->getClassMetadata($assoc_val['targetEntity'])->getFieldNames();

                foreach ($associationFields as $val) {
                    $fields[] = $assoc_ind . '.' . $val;
                }
            }

            $orderCount = 0;

            foreach ($fields as $field) {

                if (isset($routeParams['sorting'][$field])) {

                    $alias = '';

                    if (!strstr($field, '.')) {
                        $alias = 'adminProfile.';
                    }

                    if ($orderCount == 0) {
                        $qb->orderBy($alias . $field, $routeParams['sorting'][$field]);
                    } else {
                        $qb->addOrderBy($alias . $field, $routeParams['sorting'][$field]);
                    }

                    $orderCount++;
                }
            }*/
        }

        return $qb->getQuery();
    }

    /**
     * @param array $routeParams
     * @return Pagerfanta
     */
    public function findLatest(array $routeParams)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryLatest($routeParams), false));
        $paginator->setMaxPerPage($routeParams['num_items']);
        $paginator->setCurrentPage($routeParams['page']);

        return $paginator;
    }
}