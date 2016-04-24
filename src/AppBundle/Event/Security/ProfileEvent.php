<?php

namespace AppBundle\Event\Security;

use AppBundle\DomainManager\ProfileManagerInterface;
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
     * @var ProfileManagerInterface
     */
    private $manager;

    /**
     * @var Request
     */
    private $request;

    private $params = array();

    /**
     * ProfileEvent constructor.
     *
     * @param AbstractProfile $profile
     * @param ProfileManagerInterface $manager
     * @param Request $request
     */
    public function __construct(
        AbstractProfile $profile = null,
        ProfileManagerInterface $manager = null,
        Request $request = null
    ) {
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
     * @return ProfileManagerInterface
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
}