# vaibhavpandeyvpz/tez
Framework agnostic, lightweight regex-based router implementation in PHP with support for reverse URL generation.

[![Build Status](https://img.shields.io/travis/vaibhavpandeyvpz/tez/master.svg?style=flat-square)](https://travis-ci.org/vaibhavpandeyvpz/tez)

Install
------
```bash
composer require vaibhavpandeyvpz/tez
```

Routing
------
```php
$router = new Vaibhav\Tez\Router();

$router->get('/', function ()
{
    return 'Home';
}, 'home');

$router->get('/hello/{name}', function ($name)
{
    return sprintf('Hello %s!', $name);
}, 'hello');

$router->get('/hi/{name}/{num:[0-9]+}', function ($name, $no)
{
    return sprintf('Hi %s. You are no. %d!', $name, $no);
}, 'hi');

$router->group('/group', function ()
{
    /**
     * Group
     *
     * @var $this Vaibhav\Tez\Router
     */
    $this->get('/', function () {}, 'g-home');

    $this->get('/one/{name}', function ($name) { ... }, 'g-one');

    $this->get('/two/{name}', function ($name) { ... }, 'g-two');

    $this->group('/sub', function ()
    {
        /**
         * Nested group
         *
         * @var $this Vaibhav\Tez\Router
         */
        $this->get('/', function () { ... }, 'sg-home');

        $this->get('/one/{name}', function ($name) { ... }, 'sg-one');

        $this->get('/two/{name}', function ($name) { ... }, 'sg-two');
    });
});
```

Generation
------
```php
$router = new Vaibhav\Tez\Router();

// Setup routes...

$path = $router->generate('hi', [
    'name' => 'me',
    'no' => 1
]);
```

Dispatching
------
```php
$router = new Vaibhav\Tez\Router();

// Setup routes...

$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

$route = $router->match($path);

if ($route !== null) {
    if ($route->allows($_SERVER['REQUEST_METHOD'])) {
        $dispatcher = new Vaibhav\Tez\Dispatcher();
        echo $dispatcher->dispatch($route);
    } else {
        header('HTTP/1.0 405 Method Not Allowed');
    }
} else {
    header('HTTP/1.0 404 Not Found');
}
```

License
------
See [LICENSE.md](https://github.com/vaibhavpandeyvpz/tez/blob/master/LICENSE.md) file.
