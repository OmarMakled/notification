<?php

namespace Aqarmap\NotificationBundle;

use Exception;
use ReflectionClass;
use Symfony\Component\Yaml\Parser;
use Aqarmap\NotificationBundle\Config;
use Aqarmap\NotificationBundle\NotificationSender;
use Aqarmap\NotificationBundle\Channels\SmsChannel;
use Aqarmap\NotificationBundle\Channels\MailChannel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Aqarmap\NotificationBundle\Channels\DatabaseChannel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Aqarmap\NotificationBundle\Exceptions\DriverNotFoundException;

class ChannelManager {

    public $app;

    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    public function send($to, $obj)
    {
        return (new NotificationSender(
            $this,
            $this->app->get('event_dispatcher')
        ))->send($to, $obj, $channels);
    }

    private function createMailDriver()
    {
        return new MailChannel($this->app->get('mailer'));
    }

    private function createSmsDriver()
    {
        return new SmsChannel();
    }

    private function createDatabaseDriver()
    {
        return new DatabaseChannel();
    }

    public function driver($type)
    {
        $method = 'create'.ucfirst($type).'Driver';

        if (! method_exists($this, $method)){
            throw new DriverNotFoundException("We don't support {$type} Driver");
        }

        return $this->$method();
    }

    public function getConfig($notifiction, $type, $path = null)
    {
        return (new Config())->getConfig($notifiction, $type, $path);
    }

}
