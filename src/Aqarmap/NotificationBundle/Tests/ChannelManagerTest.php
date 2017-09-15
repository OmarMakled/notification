<?php

/*
 * This file is part of Moonshot Project 2017.
 *
 * @author Omar Makled <omar.makled@aqarmap.com>
 */

namespace Aqarmap\NotificationBundle\Tests;

use Mockery;
use Aqarmap\NotificationBundle\NotificationConfig;
use Aqarmap\NotificationBundle\ChannelManager;
use Aqarmap\NotificationBundle\NotificationSender;
use Aqarmap\NotificationBundle\Tests\Notification;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Aqarmap\NotificationBundle\Exceptions\DriverNotFoundException;
use Aqarmap\NotificationBundle\Exceptions\NotificationConfigException;

class ChannelManagerTest extends WebTestCase
{
    protected $manager;

    protected $notification;

    public function tearDown()
    {
        Mockery::close();
    }

    public function setUp()
    {
        self::bootKernel();
        $app = static::$kernel->getContainer();
        $this->manager = new ChannelManager($app);
        $this->notification = new Notification();
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
        $this->expectExceptionMessage("We can't find config foo");
        $this->notification->channel = ['sms', 'database', 'mail'];
        $this->notification->queue = ['mail'];
        $this->manager->getConfig($this->notification, 'foo');
    }

    public function testGetConfigProperty()
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

    public function testGetConfigFile()
    {
        $config = $this->manager->getConfig($this->notification, 'channel', __DIR__.'/config.yml');

        $this->assertEquals(['sms', 'database', 'mail'], $config);
        $config = $this->manager->getConfig($this->notification, 'queue', __DIR__.'/config.yml');
        $this->assertEquals(['sms', 'database', 'mail'], $config);
    }
}


