<?php

namespace AppBundle\Helper;

use AppBundle\Entity\AbstractEntity;
use Symfony\Component\HttpFoundation\Request;

class PaginationHelper
{
    public static function getRouteParams(Request $request, AbstractEntity $entity)
    {
        $routeParams = $request->query->all();
        
        $routeParams['page'] = $request->query->get('page', 1);
        $routeParams['num_items'] = $request->query->get('num_items', $entity::$NUM_ITEMS);

        return $routeParams;
    }
}