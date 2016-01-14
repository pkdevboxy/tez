# vaibhavpandeyvpz/tez
Clean & lightweight regex-based router implementation in PHP with support for reverse URL generation.

[![Build Status](https://img.shields.io/travis/vaibhavpandeyvpz/tez/master.svg?style=flat-square)](https://travis-ci.org/vaibhavpandeyvpz/tez)

Install
------
```bash
composer require vaibhavpandeyvpz/tez
```

Testing
------
``` bash
vendor/bin/phpunit
```

Usage
------
```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Vaibhav\Tez\Router();

$router->get('/', 'IndexCtrl@index');

$router->group('/user', function ()
{
    /** @var Vaibhav\Tez\Router $this */
    $this->get('/{name}', function ($name)
    {
        return sprintf('Hello %s!', $name);
    }, 'home');

    $this->get('/{name}/{no:[0-9]+}', function ($name, $no)
    {
        return sprintf('Hello %s. You are no. %d!', $name, $no);
    });
});

// $path = $router->generate('home', ['name' => 'me']);

$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

$route = $router->match($path);

if ($route !== false) {
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
