<?php

namespace Aqarmap\NotificationBundle\Tests;

use Aqarmap\NotificationBundle\ChannelManager;
use Aqarmap\NotificationBundle\NotificationSender;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChannelManagerTest extends WebTestCase
{
    protected $app;

    public function setUp()
    {
        self::bootKernel();
        $this->app = static::$kernel->getContainer();
    }

    public function test_injection()
    {
        $manager = new ChannelManager($this->app);

        $this->assertInstanceOf(ChannelManager::class, $manager);
    }
}
