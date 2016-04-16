<?php

namespace AppBundle\Event\Security;

class ProfileEvents
{
    const RESETTING_REQUEST_SUCCESS = 'profile.resetting.request.success';
    const RESETTING_RESET_INITIALIZE = 'profile.resetting.reset.initialize';
    const RESETTING_RESET_SUCCESS = 'profile.resetting.reset.success';
    
    const CREATE_SUCCESS = 'profile.create.success';
}