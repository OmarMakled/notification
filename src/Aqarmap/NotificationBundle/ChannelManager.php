<?php

namespace Aqarmap\NotificationBundle;

use Exception;
use ReflectionClass;
use Symfony\Component\Yaml\Parser;
use Aqarmap\NotificationBundle\NotificationSender;
use Aqarmap\NotificationBundle\Channels\SmsChannel;
use Aqarmap\NotificationBundle\Channels\MailChannel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Aqarmap\NotificationBundle\Channels\DatabaseChannel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Aqarmap\NotificationBundle\Exceptions\DriverNotFoundException;
use Aqarmap\NotificationBundle\Exceptions\NotificationConfigException;

class ChannelManager {

    public $app;

    public $config;

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

        if (!method_exists($this, $method)){
            throw new DriverNotFoundException("We don't support {$type} Driver");
        }

        return $this->$method();
    }

    public function getConfig($notifiction, $type)
    {
        try {
            $notifiction = (new ReflectionClass($notifiction))->getShortName();

            if (isset($this->config[$notifiction][$type])){
                return $this->config[$notifiction][$type];
            }

            return $this->config[$notifiction][$type]= $this->parserConfig($notifiction)[$type];

        } catch (Exception $e) {
            throw new NotificationConfigException("
                We can't find config for [$notifiction][$type] go to notifications.yml
            ");
        }
    }

    private function parserConfig($notifiction)
    {
        $yaml = new Parser();
        return $yaml->parse(file_get_contents(__DIR__.'/Resources/config/notifications.yml'))['notifications'][$notifiction];
    }
}
