<?php

namespace Aqarmap\NotificationBundle\Tests;

use Aqarmap\NotificationBundle\Notifications\Notification as BaseNotification;

class Notification extends BaseNotification
{

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
