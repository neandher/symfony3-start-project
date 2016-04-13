<?php

namespace AppBundle\Event\Security;

use AppBundle\DomainManager\AbstractProfileManager;
use AppBundle\Entity\AbstractProfile;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ProfileEvent extends Event
{

    /**
     * @var AbstractProfile
     */
    private $profile;

    /**
     * @var AbstractProfileManager
     */
    private $manager;

    /**
     * @var Request
     */
    private $request;

    /**
     * ProfileEvent constructor.
     *
     * @param AbstractProfile $profile
     * @param AbstractProfileManager $manager
     * @param Request $request
     */
    public function __construct(AbstractProfile $profile = null, AbstractProfileManager $manager = null, Request $request = null)
    {
        $this->profile = $profile;
        $this->manager = $manager;
        $this->request = $request;
    }

    /**
     * @return AbstractProfile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param AbstractProfile $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return AbstractProfileManager
     */
    public function getManager()
    {
        return $this->manager;
    }
}