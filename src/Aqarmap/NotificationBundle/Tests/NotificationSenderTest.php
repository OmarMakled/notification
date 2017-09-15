<?php

namespace Aqarmap\NotificationBundle\Tests;

use Mockery;
use Aqarmap\NotificationBundle\ChannelManager;
use Aqarmap\NotificationBundle\NotificationSender;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Aqarmap\NotificationBundle\Notifications\Interest;
use Aqarmap\NotificationBundle\Events\NotificationSent;
use Aqarmap\NotificationBundle\Notifications\NewListing;
use Aqarmap\NotificationBundle\Notifications\InvoicePaid;
use Aqarmap\NotificationBundle\Tests\Notification;

class NotificationSenderTest extends WebTestCase
{
    protected $sender;

    protected $dispatcher;

    protected $notification;

    public function tearDown()
    {
        Mockery::close();
    }

    public function setUp()
    {
        self::bootKernel();
        $app = static::$kernel->getContainer();
        $this->notification = new Notification;
        $this->dispatcher = Mockery::mock($app->get('event_dispatcher'));
        $this->sender = new NotificationSender(new ChannelManager($app),
            $this->dispatcher
        );
    }

    public function testSendNotificationSync()
    {
        $this->notification->channel = ['sms', 'database', 'mail'];
        $this->dispatcher->shouldReceive('dispatch')
            ->times(6)
            ->with('notifiction.sent', NotificationSent::class);
        $response = $this->sender->send(['alex', 'bob'], $this->notification);
        $this->assertTrue(true);
    }

    public function testSendNotificationAsync()
    {
        $this->notification->channel = ['sms', 'database', 'mail'];
        $this->notification->queue = ['sms', 'database', 'mail'];
        $this->dispatcher->shouldReceive('dispatch')->times(0);
        $response = $this->sender->send(['alex', 'bob'], $this->notification);
        $this->assertTrue(true);
    }
}
