<?php

namespace Aqarmap\NotificationBundle\Channels;

class SmsChannel {

    public function __construct()
    {
    }

    public function send($to, $obj)
    {
        return $obj->toSms();
    }
}
