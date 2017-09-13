<?php

namespace Aqarmap\NotificationBundle\Channels;

use Swift_Mailer;
use Swift_Message;

class MailChannel {

    protected $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($to, $obj)
    {
        $message = (new Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $obj->toMail(),
                'text/html'
            );
        return $this->mailer->send($message);
    }
}
