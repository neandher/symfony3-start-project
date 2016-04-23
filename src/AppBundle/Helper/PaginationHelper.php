<?php

namespace AppBundle\Helper;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
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
    private $entityManager;

    /**
     * @var
     */
    private $entityClassName;

    /**
     * @var array
     */
    private $fields = array();

    /**
     * PaginationHelper constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param $entityClassName
     * @return PaginationHelper
     */
    public function handle(Request $request, $entityClassName = '')
    {
        $this->request = $request;
        $this->entityClassName = $entityClassName;

        $this->setRouteParams()
            ->addDefaultRouteParams();

        if (!empty($entityClassName)) {
            $this->setFields()
                ->setSorting();
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function setRouteParams()
    {
        $this->routeParams = $this->request->query->all();

        return $this;
    }

    /**
     * @return $this
     */
    private function addDefaultRouteParams()
    {
        $this->routeParams['page'] = $this->request->query->get('page', 1);
        $this->routeParams['num_items'] = $this->request->query->get('num_items', self::NUM_ITEMS);

        return $this;
    }

    /**
     * @return $this
     */
    private function setFields()
    {
        $this->fields = $this->getFieldsName();

        return $this;
    }

    /**
     * @return $this
     */
    private function setSorting()
    {
        foreach ($this->fields as $field) {
            if (isset($this->routeParams['sorting']) && isset($this->routeParams['sorting'][$field])) {
                $this->sorting[$field] = $this->routeParams['sorting'][$field] === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sorting[$field] = self::SORTING_DIRECTION;
            }
        }

        return $this;
    }

    /**
     * @param null $param
     * @return array|mixed
     */
    public function getRouteParams($param = null)
    {
        return isset($this->routeParams[$param]) ? $this->routeParams[$param] : $this->routeParams;
    }

    /**
     * @param string $without
     * @return string
     */
    public function buildQuery($without = '')
    {
        $routeParams = $this->routeParams;

        if (isset($routeParams[$without])) {
            unset($routeParams[$without]);
        }

        $query = '?';

        foreach ($routeParams as $ind => $val) {
            if (is_array($val)) {
                foreach ($val as $item => $item_val) {
                    $query .= $ind . '[' . $item . ']=' . $item_val . '&';
                }
            } else {
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

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    private function getFieldsName()
    {
        $metaData = $this->entityManager->getClassMetadata($this->entityClassName);

        $fields = $metaData->getFieldNames();
        $associations = $metaData->getAssociationMappings();

        foreach ($associations as $assoc_ind => $assoc_val) {

            $associationFields = $this->entityManager->getClassMetadata($assoc_val['targetEntity'])->getFieldNames();

            foreach ($associationFields as $val) {
                $fields[] = $assoc_ind . '.' . $val;
            }
        }

        return $fields;
    }
}