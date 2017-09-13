<?php

namespace Aqarmap\NotificationBundle\Notifications;

use Aqarmap\NotificationBundle\Notifications\Notification;

class Interest extends Notification {

    public function toMail()
    {
        return 'message to mail';
    }

    public function toSms()
    {
        return 'message to sms';
    }

    public function toDataBase()
    {
        return 'message to database';
    }

}
