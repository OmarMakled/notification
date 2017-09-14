<?php

/*
 * This file is part of Moonshot Project 2017.
 *
 * @author Omar Makled <omar.makled@aqarmap.com>
 */

namespace Aqarmap\NotificationBundle\Tests;

use Mockery;
use Aqarmap\NotificationBundle\Config;
use Aqarmap\NotificationBundle\ChannelManager;
use Aqarmap\NotificationBundle\NotificationSender;
use Aqarmap\NotificationBundle\Tests\Notification;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Aqarmap\NotificationBundle\Exceptions\DriverNotFoundException;
use Aqarmap\NotificationBundle\Exceptions\NotificationConfigException;
use Aqarmap\NotificationBundle\Notifications\Notification as BaseNotification;

class ChannelManagerTest extends WebTestCase
{
    protected $manager;

    protected $app;

    protected $notification;

    public function tearDown()
    {
        Mockery::close();
    }

    public function setUp()
    {
        self::bootKernel();

        $this->app = static::$kernel->getContainer();

        $this->manager = new ChannelManager($this->app);

        $this->notification = new Notification($this->app);
    }

    public function testDriverNotFoundException()
    {
        $this->expectException(DriverNotFoundException::class);
        $this->expectExceptionMessage("We don't support foo Driver");
        $this->manager->driver('foo');
    }

    public function testNotificationConfigException()
    {
        $this->expectException(NotificationConfigException::class);
        $this->expectExceptionMessage(
                sprintf("We can't find config for %s inside %s or class %s",
                    'foo',
                    (new Config)::CONFIG_PATH,
                    \Aqarmap\NotificationBundle\Tests\Notification::class
                )
        );
        $this->notification->channel = ['sms', 'database', 'mail'];
        $this->notification->queue = ['mail'];

        $this->manager->getConfig($this->notification, 'foo');
    }

    public function testGetChannelAndQueueFromNotification()
    {
        $this->notification->channel = ['sms', 'database', 'mail'];
        $this->notification->queue = ['mail'];

        $this->assertEquals(
            ['sms', 'database', 'mail'], $this->manager->getConfig($this->notification, 'channel')
        );
        $this->assertEquals(
            ['mail'], $this->manager->getConfig($this->notification, 'queue')
        );
    }

    public function testGetChannelAndQueueFromConfigFile()
    {
        $config = $this->manager->getConfig($this->notification, 'channel', __DIR__.'/stubs/config.yml');
        $this->assertEquals(['sms', 'database', 'mail'], $config);

        $config = $this->manager->getConfig($this->notification, 'queue', __DIR__.'/stubs/config.yml');
        $this->assertEquals(['sms', 'database', 'mail'], $config);
    }
}

class Notification extends BaseNotification
{
}
