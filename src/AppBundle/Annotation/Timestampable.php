<?php

namespace AppBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Timestampable
 *
 * @Annotation()
 * @Target("PROPERTY")
 */
class Timestampable extends Annotation
{
    /**
     * @var string
     */
    public $on = "update";
}