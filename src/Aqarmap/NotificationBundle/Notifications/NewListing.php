<?php

namespace Aqarmap\NotificationBundle\Notifications;

class NewListing {

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