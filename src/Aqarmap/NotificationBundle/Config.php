<?php

namespace Aqarmap\NotificationBundle;

use ReflectionClass;
use Symfony\Component\Yaml\Parser;
use Aqarmap\NotificationBundle\Exceptions\NotificationConfigException;
use Exception;

class Config {

    const CONFIG_PATH = __DIR__.'/Resources/config/notifications.yml';

    public $config;

    public function getConfig($notifiction , $type , $path = null)
    {
        try{
            $key = (new ReflectionClass($notifiction))->getShortName();

            if (isset($this->config[$key][$type])){
                return $this->config[$key][$type];
            }

            $config = $this->parserConfigFile($path);

            return $this->config[$key][$type] = (isset($config[$key][$type]))
                ? $config[$key][$type]
                : $notifiction->$type;

        } catch (Exception $e) {
            throw new NotificationConfigException(
                sprintf("We can't find config for %s inside %s or class %s",
                    $type,
                    $this->getConfigPath($path),
                    (new ReflectionClass($notifiction))->getName()
                )
            );
        }
    }

    private function parserConfigFile($path = null)
    {
        return (new Parser())->parse(file_get_contents($this->getConfigPath($path)));
    }

    private function getConfigPath($path = null)
    {
        return ($path)? : self::CONFIG_PATH;
    }
}
