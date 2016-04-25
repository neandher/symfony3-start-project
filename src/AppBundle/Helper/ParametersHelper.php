<?php

namespace AppBundle\Helper;

class ParametersHelper
{

    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getParams($param = null)
    {
        return isset($this->params[$param]) ? $this->params[$param] : $this->params;
    }
}