<?php

/*
 * This file is part of Moonshot Project 2017.
 *
 * @author Omar Makled <omar.makled@aqarmap.com>
 */

namespace Aqarmap\NotificationBundle\Tests;

use Mockery;
use Aqarmap\NotificationBundle\NotificationConfig;
use Aqarmap\NotificationBundle\Tests\Notification;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NotificationConfigTest extends WebTestCase
{
    protected $manager;

    protected $notification;

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetFromProperty()
    {
        $notification = new Notification();
        $notification->channel = ['foo', 'bar', 'baz'];
        $notification->queue = ['foo', 'bar', 'baz'];
        $config = new NotificationConfig($notification);
        $this->assertEquals([
                'channel' => ['foo', 'bar', 'baz'],
                'queue' => ['foo', 'bar', 'baz']
            ],
            $config->getConfig()
        );
    }

    public function testGetFromFile()
    {
        $notification = new Notification();
        $notification->channel = [];
        $notification->queue = [];
        $config = new NotificationConfig($notification, __DIR__.'/config.yml');
        $this->assertEquals([
                'class'   => Notification::class,
                'channel' => ['sms', 'database', 'mail'],
                'queue' => ['sms', 'database', 'mail']
            ],
            $config->getConfig()
        );
    }
}


