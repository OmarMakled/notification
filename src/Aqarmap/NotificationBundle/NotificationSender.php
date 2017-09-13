<?php

namespace Aqarmap\NotificationBundle;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use Aqarma\NotificationBundle\Builder;
use Aqarmap\NotificationBundle\Events\NotificationSent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ReflectionClass;

class NotificationSender {

    protected $manager;

    protected $dispatcher;

    public function __construct(ChannelManager $manager, EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;

    }

    public function send($users, $notifiction)
    {
        foreach ($users as $user) {
            foreach ($this->manager->getConfig($notifiction, 'channel') as $channel) {

                if (! in_array($channel, $this->manager->getConfig($notifiction, 'queue'))){
                    $this->manager->driver($channel)->send($user, $notifiction);

                    $this->dispatcher->dispatch('notifiction.sent', new NotificationSent($channel));
                }
            }
        }
    }
}
