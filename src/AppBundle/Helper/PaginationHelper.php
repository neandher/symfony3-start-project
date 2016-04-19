<?php

namespace AppBundle\Helper;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class PaginationHelper
{
    const NUM_ITEMS = 15;
    const SORTING_DIRECTION = 'asc';

    /**
     * @var array
     */
    private $routeParams = array();

    /**
     * @var array
     */
    private $sorting = array();

    /**
     * @var Request
     */
    private $request;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var
     */
    private $entityClassName;

    /**
     * PaginationHelper constructor.
     *
     * @param $entityClassName
     * @param Request $request
     * @param EntityManager $em
     */
    public function __construct($entityClassName, Request $request, EntityManager $em)
    {
        $this->entityClassName = $entityClassName;
        $this->request = $request;
        $this->em = $em;

        $this->setRouteParams()
            ->addDefaultRouteParams()
            ->setSorting();
    }

    private function setRouteParams()
    {
        $this->routeParams = $this->request->query->all();

        return $this;
    }

    private function addDefaultRouteParams()
    {
        $this->routeParams['page'] = $this->request->query->get('page', 1);
        $this->routeParams['num_items'] = $this->request->query->get('num_items', self::NUM_ITEMS);

        return $this;
    }

    private function setSorting()
    {
        $fields = ClassFieldsNameHelper::getFieldsName($this->em, $this->entityClassName);

        foreach ($fields as $field) {
            if (isset($this->routeParams['sorting']) && isset($this->routeParams['sorting'][$field])) {
                $this->sorting[$field] = $this->routeParams['sorting'][$field] === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sorting[$field] = self::SORTING_DIRECTION;
            }
        }

        return $this->sorting;
    }

    /**
     * @return array
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * @return string
     */
    public function buildQuery()
    {
        $query = '?';

        foreach ($this->routeParams as $ind => $val) {
            if ($ind <> 'sorting') {
                $query .= $ind . '=' . $val . '&';
            }
        }

        return substr($query, 0, -1);
    }

    /**
     * @param $field
     * @return string
     */
    public function getSortingFieldQuery($field)
    {
        return isset($this->sorting[$field]) ? '&sorting[' . $field . ']=' . $this->sorting[$field] : '&not_found';
    }
}