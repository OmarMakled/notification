<?php

namespace Aqarmap\NotificationBundle\Notifications;

abstract class Notification {
    /**
     * List of channels
     *
     * @var array
     */
    public $channel = ['database'];

    /**
     * List of queues
     *
     * @var array
     */
    public $queue = [];
}
