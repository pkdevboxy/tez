<?php

use Vaibhav\Tez\Router;

class RouterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Router
     */
    protected $router;

    protected function setUp()
    {
        $this->router = new Router();
        $this->router->get('/', function ()
        {
            return 'Home';
        }, 'home');
        $this->router->get('/hello/{name}', function ($name)
        {
            return sprintf('Hello %s!', $name);
        }, 'hello');
        $this->router->get('/hi/{name}/{num:[0-9]+}', function ($name, $no)
        {
            return sprintf('Hi %s. You are no. %d!', $name, $no);
        });
        $this->router->group('/group', function ()
        {
            /** @var $this Router */
            $this->get('/', function () {}, 'g-home');
            $this->get('/one/{name}', function ($name) {}, 'g-one');
            $this->get('/two/{name}', function ($name) {}, 'g-two');
            $this->group('/sub', function ()
            {
                /** @var $this Router */
                $this->get('/', function () {}, 'sg-home');
                $this->get('/one/{name}', function ($name) {}, 'sg-one');
                $this->get('/two/{name}', function ($name) {}, 'sg-two');
            });
        });
    }

    public function testGeneration()
    {
        $this->assertEquals('/', $this->router->generate('home'));
        $this->assertEquals('/hello/me', $this->router->generate('hello', ['name' => 'me']));
        $this->assertEquals('/group/', $this->router->generate('g-home'));
        $this->assertEquals('/group/one/me', $this->router->generate('g-one', ['name' => 'me']));
        $this->assertEquals('/group/two/me', $this->router->generate('g-two', ['name' => 'me']));
        $this->assertEquals('/group/sub/', $this->router->generate('sg-home'));
        $this->assertEquals('/group/sub/one/me', $this->router->generate('sg-one', ['name' => 'me']));
        $this->assertEquals('/group/sub/two/me', $this->router->generate('sg-two', ['name' => 'me']));
    }

    public function testMatching()
    {
        $this->assertInstanceOf('Vaibhav\\Tez\\Route', $this->router->match('/'));
        $this->assertInstanceOf('Vaibhav\\Tez\\Route', $this->router->match('/hello/me'));
        $this->assertInstanceOf('Vaibhav\\Tez\\Route', $r = $this->router->match('/hi/me/1'));
        $this->assertTrue($r->allows('GET'));
        $this->assertFalse($r->allows('POST'));
    }
}
