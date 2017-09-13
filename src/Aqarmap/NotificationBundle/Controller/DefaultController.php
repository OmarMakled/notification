<?php

namespace Aqarmap\NotificationBundle\Controller;

use RMS\PushNotificationsBundle\Message\iOSMessage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $this->get('notification')->send();

        return $this->render('NotificationBundle:Default:index.html.twig');
    }
}
