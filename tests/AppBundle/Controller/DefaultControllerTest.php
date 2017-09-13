<?php

namespace Tests\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $foo = 'hi';
        $this->assertEquals('hi', $foo);
    }
}
