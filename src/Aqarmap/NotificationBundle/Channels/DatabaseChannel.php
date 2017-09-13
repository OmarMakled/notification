<?php

namespace Aqarmap\NotificationBundle\Channels;

class DatabaseChannel {

    public function __construct()
    {
    }

    public function send($to, $obj)
    {
        return $obj->toDatabase();
    }
}
