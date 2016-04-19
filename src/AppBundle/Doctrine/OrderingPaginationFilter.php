<?php

namespace AppBundle\Doctrine;

use AppBundle\Helper\ClassFieldsNameHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Symfony\Component\HttpFoundation\Request;

class OrderingPaginationFilter extends SQLFilter
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityManager
     */
    protected $em;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {

        $fields = ClassFieldsNameHelper::getFieldsName($this->em, $targetEntity->getName());

        $sorting = $this->request->query->get('sorting');

        $isset = false;

        $query = 'ORDER BY';

        foreach ($sorting as $ind => $val) {
            if (in_array($ind, $fields)) {

                $isset = true;

                $query .= sprintf('%s %s,', $ind, $val);

            }
        }

        if (!$isset) {
            return '';
        }

        return substr($query, 0, -1);
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
}