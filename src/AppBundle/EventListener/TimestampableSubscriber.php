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
        $this->setDateMethod($eventArgs);
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->setDateMethod($eventArgs);
    }

    private function setDateMethod(LifecycleEventArgs $eventArgs)
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

                        $date = $entity->{$getMethod}();

                        if (
                            ($annotation->on == 'create' && empty($date))
                            ||
                            ($annotation->on == 'update')
                        ) {
                            $entity->{$setMethod}(new \DateTime());
                        }
                    }
                }
            }
        }
    }

}