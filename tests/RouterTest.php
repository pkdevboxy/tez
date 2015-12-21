<?php

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $router = new Tez\Router();
        $router->any('/', function () {});
        $router->get('/hello', function () {});
        $this->assertTrue($router->match('/bye') === false);
        $this->assertTrue(($route = $router->match('/hello')) instanceof Tez\Route);
        $this->assertTrue($route->isAllowed('GET'));
        $this->assertFalse($route->isAllowed('POST'));
    }

    public function testGeneration()
    {
        $router = new Tez\Router();
        $router->any('/home', function () {}, 'home');
        $router->get('/hello/{name}', function () {}, 'hello');
        $router->group('/say', function ()
        {
            /** @var Tez\Router $this */
            $this->get('/hi/to/{name}', function () {}, 'hi');
            $this->get('/bye/to/{name}', function () {}, 'bye');
        });
        $this->assertEquals($router->generate('home'), '/home');
        $this->assertEquals($router->generate('hello', array('name' => 'Vaibhav')), '/hello/Vaibhav');
        $this->assertEquals($router->generate('hi', array('name' => 'Me')), '/say/hi/to/Me');
        $this->assertEquals($router->generate('bye', array('name' => 'Me')), '/say/bye/to/Me');
    }
}
