<?php

namespace AppBundle\EventListener;

use AppBundle\Annotation\Timestampable;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TimestampableSubscriber implements EventSubscriber
{

    /**
     * @var Reader
     */
    private $reader;

    /**
     * TimestampableSubscriber constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }


    /**
     * @return mixed
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate'
        ];
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->setDateMethod($eventArgs, 'create');
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->setDateMethod($eventArgs, 'update');
    }

    private function setDateMethod(LifecycleEventArgs $eventArgs, $on)
    {
        $entity = $eventArgs->getEntity();

        $reflectionClass = new \ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $prop) {

            /** @var Timestampable $annotation */
            $annotation = $this->reader->getPropertyAnnotation($prop, Timestampable::class);

            if (!empty($annotation)) {

                $property = $prop->getName();

                if (!empty($property)) {

                    $setMethod = 'set' . ucFirst($property);
                    $getMethod = 'get' . ucFirst($property);

                    if (method_exists($entity, $setMethod) && method_exists($entity, $getMethod)) {

                        if (
                            ($annotation->on == 'create' && $on == 'create')
                            ||
                            ($annotation->on == 'update' && $on == 'update')
                        ) {
                            $entity->{$setMethod}(new \DateTime());
                        }
                    }
                }
            }
        }
    }

}