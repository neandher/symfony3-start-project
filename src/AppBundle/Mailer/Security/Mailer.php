<?php

namespace AppBundle\Mailer\Security;

use AppBundle\Entity\AbstractProfile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Mailer
{

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $parameters;

    public function __construct(
        \Swift_Mailer $mailer,
        UrlGeneratorInterface $router,
        \Twig_Environment $twig,
        array $parameters
    ) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    /**
     * @param AbstractProfile $profile
     * @return int
     */
    public function sendResettingEmailMessage(AbstractProfile $profile)
    {

        $user = $profile->getUser();

        $template = $this->parameters['resetting_email.template'];

        $url = $this->router->generate(
            $this->parameters['resetting_email.route'],
            array('token' => $user->getConfirmationToken()),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $context = array(
            'profile' => $profile,
            'url'     => $url
        );

        return $this->sendMessage($template, $context, $this->parameters['resetting_email.from'], $profile->getEmail());
    }

    /**
     * @param string $templateName
     * @param array $context
     * @param string $fromEmail
     * @param string $toEmail
     * @return int
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }
        
        return $this->mailer->send($message);
    }
}