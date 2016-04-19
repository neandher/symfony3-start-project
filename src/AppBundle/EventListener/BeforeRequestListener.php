<?php
namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class BeforeRequestListener
{

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->query->has('sorting')) {

            $filter = $this->em
                ->getFilters()
                ->enable('ordering_pagination');

            
            $filter->setRequest($request);
            $filter->setEntityManager($this->em);
        }
    }
}