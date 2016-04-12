<?php

namespace AppBundle\Event\Security;

use AppBundle\Entity\AbstractProfile;
use Symfony\Component\EventDispatcher\Event;

class ProfileEvent extends Event
{

    const PARAM_RESETTING_EMAIL_ROUTE = 'security.resetting_email.route';
    const PARAM_RESETTING_EMAIL_FROM = 'security.resetting_email.from';
    const PARAM_RESETTING_EMAIL_TEMPLATE = 'security.resetting_email.template';
    
    /**
     * @var AbstractProfile
     */
    private $profile;

    /**
     * @var array
     */
    private $params;

    /**
     * ProfileEvent constructor.
     *
     * @param AbstractProfile $profile
     */
    public function __construct(AbstractProfile $profile, $params = array())
    {
        $this->profile = $profile;
        $this->params = $params;
    }

    /**
     * @return AbstractProfile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}