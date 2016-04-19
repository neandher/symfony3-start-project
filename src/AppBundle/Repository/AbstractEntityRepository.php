<?php

namespace AppBundle\Repository;

use AppBundle\Helper\PaginationHelper;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AbstractEntityRepository extends EntityRepository
{
    protected function addOrderingQueryBuilder(QueryBuilder $qb, PaginationHelper $paginationHelper)
    {
        $sorting = $paginationHelper->getRouteParams()['sorting'];
        $fields = $paginationHelper->getFields();

        foreach ($sorting as $ind => $val) {
            
            if (in_array($ind, $fields)) {

                if (strstr($ind, '.')) {
                    
                    $qb->addOrderBy($ind, $val);
                } else {

                    $aliases = $qb->getRootAliases();

                    $qb->addOrderBy($aliases[0] . '.' . $ind, $val);
                }
            }
        }

        return $qb;
    }
}