<?php

namespace AppBundle\Event\FlashBag;

class FlashBagEvents
{
    const MESSAGE_TYPE_SUCCESS = 'success';
    const MESSAGE_TYPE_ERROR = 'danger';

    const MESSAGE_SUCCESS_INSERTED = 'flashbag.success.inserted';
    const MESSAGE_ERROR_INSERTED = 'flashbag.error.inserted';

    const MESSAGE_SUCCESS_UPDATED = 'flashbag.success.updated';
    const MESSAGE_ERROR_UPDATED = 'flashbag.error.updated';

    const MESSAGE_SUCCESS_DELETED = 'flashbag.success.deleted';
    const MESSAGE_ERROR_DELETED = 'flashbag.error.deleted';
}