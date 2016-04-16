<?php

namespace AppBundle\Helper;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Translation\Translator;

class FlashBagHelper
{

    /**
     * @var FlashBag
     */
    private $flashBag;
    /**
     * @var Translator
     */
    private $translator;


    public function __construct(FlashBag $flashBag, Translator $translator)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    public function newMessage($type, $message, array $params = [])
    {
        $this->flashBag->add(
            $type,
            $this->translator->trans($message, $params)
        );
    }
}