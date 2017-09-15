<?php

namespace Aqarmap\NotificationBundle;

use ReflectionClass;
use Symfony\Component\Yaml\Parser;

class NotificationConfig
{
    const CONFIG_PATH = __DIR__.'/Resources/config/notifications.yml';

    private $path;

    private $notifiction;

    public function __construct($notifiction, $path = null)
    {
        $this->path = ($path) ? : Self::CONFIG_PATH;

        $this->notifiction = $notifiction;
    }

    public function getConfig()
    {
        return (! empty($this->notifiction->channel))
            ? $this->getFromProperty()
            : $this->getFromFile();
    }

    private function getFromFile()
    {
        $config = (new Parser())->parse(file_get_contents($this->path));
        $key = (new ReflectionClass($this->notifiction))->getShortName();

        return (! isset($config[$key])) ? $this->getFromProperty() : $config[$key];
    }

    private function getFromProperty()
    {
        return [
            'channel'   => $this->notifiction->channel,
            'queue'     =>  $this->notifiction->queue
        ];
    }
}
