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

class NotificationSenderTest extends WebTestCase
{
    protected $sender;

    protected $dispatcher;

    public function tearDown()
    {
        Mockery::close();
    }

    public function setUp()
    {
        self::bootKernel();

        $app = static::$kernel->getContainer();

        $this->dispatcher = Mockery::mock($app->get('event_dispatcher'));

        $this->sender = new NotificationSender(new ChannelManager($app),
            $this->dispatcher
        );
    }

    public function test_send_now()
    {
        $this->dispatcher->shouldReceive('dispatch')->times(6);

        $response = $this->sender->send(['alex', 'bob'], new InvoicePaid);

        $this->assertTrue(true);
    }

    public function test_send_queue()
    {
        $this->dispatcher->shouldReceive('dispatch')->times(0);

        $response = $this->sender->send(['alex', 'bob'], new NewListing);

        $this->assertTrue(true);
    }

    public function test_notification_config()
    {
        $this->dispatcher->shouldReceive('dispatch')->times(2);

        $response = $this->sender->send(['alex', 'bob'], new Interest);

        $this->assertTrue(true);
    }

    public function test_foo_bar()
    {
        $interest = new Interest;
        $interest->queue = [];
        $interest->channel = ['sms', 'mail', 'database'];

        $this->dispatcher->shouldReceive('dispatch')->times(6);

        $response = $this->sender->send(['alex', 'bob'], $interest);

        $this->assertTrue(true);
    }

    public function test_bar_foo()
    {
        $interest = new Interest;
        $interest->queue = ['sms', 'mail', 'database'];
        $interest->channel = ['sms', 'mail', 'database'];

        $this->dispatcher->shouldReceive('dispatch')->times(0);

        $response = $this->sender->send(['alex', 'bob'], $interest);

        $this->assertTrue(true);
    }
}
