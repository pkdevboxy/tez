<?php

use Vaibhav\Tez\Dispatcher;

class DispatcherTest extends RouterTest
{
    public function testDefault()
    {
        $dispatcher = new Dispatcher();
        $this->assertEquals('Home', $dispatcher->dispatch($this->router->match('/')));
        $this->assertEquals('Hello me!', $dispatcher->dispatch($this->router->match('/hello/me')));
        $this->assertEquals('Hi me. You are no. 1!', $dispatcher->dispatch($this->router->match('/hi/me/1')));
    }
}
