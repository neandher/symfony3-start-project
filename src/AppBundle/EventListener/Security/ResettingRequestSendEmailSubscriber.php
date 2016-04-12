<?php

namespace AppBundle\EventListener\Security;

use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Event\Security\ProfileEvent;
use AppBundle\Event\Security\ProfileEvents;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\Translator;

class ResettingRequestSendEmailSubscriber implements EventSubscriberInterface
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var TwigEngine
     */
    private $twigEngine;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var ParameterBag
     */
    private $parameter;

    public function __construct(
        \Swift_Mailer $mailer,
        Translator $translator,
        TwigEngine $twigEngine,
        UrlGeneratorInterface $router,
        FlashBag $flashBag,
        ParameterBag $parameter
    )
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->twigEngine = $twigEngine;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->parameter = $parameter;
    }

    /**
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            ProfileEvents::RESETTING_REQUEST_SUCCESS => 'onResettingRequestSuccess'
        );
    }

    /**
     * @param ProfileEvent $event
     * @throws \Exception
     * @throws \Twig_Error
     */
    public function onResettingRequestSuccess(ProfileEvent $event)
    {

        $profile = $event->getProfile();
        $user = $profile->getUser();
        $params = $event->getParams();

        $urlToReset = $this->router->generate(
            $params[$event::PARAM_RESETTING_EMAIL_ROUTE],
            ['token' => $user->getConfirmationToken()],
            true
        );

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('security.resetting.request.email.subject'))
            ->setFrom($this->parameter->get($params[$event::PARAM_RESETTING_EMAIL_FROM]))
            ->setTo($profile->getEmail())
            ->setBody(
                $this->twigEngine->render(
                    $params[$event::PARAM_RESETTING_EMAIL_TEMPLATE],
                    array('full_name' => $profile->getFullName(), 'url' => $urlToReset)
                )
            );

        $this->mailer->send($message);

        $this->flashBag->add(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            $this->translator->trans(
                'security.resetting.request.check_email',
                array('profile_email' => $profile->getObfuscatedEmail())
            )
        );
    }

}