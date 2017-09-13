<?php

/*
 * This file is part of Moonshot Project 2017.
 *
 * @author Omar Makled <omar.makled@aqarmap.com>
 */

namespace Aqarmap\NotificationBundle\Tests;

use Aqarmap\NotificationBundle\ChannelManager;
use Aqarmap\NotificationBundle\NotificationSender;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Aqarmap\NotificationBundle\Exceptions\DriverNotFoundException;
use Aqarmap\NotificationBundle\Exceptions\NotificationConfigException;
use Aqarmap\NotificationBundle\Notifications\Notification as BaseNotification;
use Mockery;

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

        $this->manager->driver('foo');
    }

    public function testNotificationConfigException()
    {
        $this->expectException(NotificationConfigException::class);
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
        $manager = Mockery::mock(ChannelManager::class.'[driver]', [$this->app]);

        dump($manager); die();
        // $manager = Mockery::mock(new ChannelManager($this->app));

        $manager->shouldReceive('parseCadadsonfig')->times(1);//;->andReturn(['foo']);

        $manager->foo();
    }

    public function testMock()
    {
        $foo = Mockery::mock(Foo::class);

        // $foo->shouldReceive('m1')->once()->andReturn('m1');
        $foo->shouldReceive('m2')->once()->andReturn('m11');
        $foo->shouldReceive('m1')->times(10)->andReturn('m11');

        $this->assertEquals('m1', $foo->m2());
    }
}

class Notification extends BaseNotification
{
}

class Foo {
    public function m1()
    {
        return 'm1';
    }

    public function m2()
    {
        return $this->m1();
    }
}
