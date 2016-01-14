<?php

use Vaibhav\Tez\Route;

class RouteTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $home = new Route('/', function () {});
        $hello = new Route('/hello', function () {});
        $me = new Route('/hello/{name}', function () {});
        $this->assertTrue($home->matches('/'));
        $this->assertTrue($hello->matches('/hello'));
        $this->assertTrue($me->matches('/hello/me'));
        $this->assertEquals(['name' => 'me'], $me->attributes());
    }
}
