<?php

namespace Aqarmap\NotificationBundle\Events;

use Symfony\Component\EventDispatcher\Event;

class NotificationSent extends Event{

    public function __construct($channel)
    {

    }
}
