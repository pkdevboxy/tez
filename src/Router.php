<?php

namespace Tez;

class Router
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var Route[]
     */
    private $routes = array();

    /**
     * Collector constructor.
     * @param string $prefix
     */
    function __construct($prefix = '/')
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $path
     * @param mixed $target
     * @param null|string $name
     * @return Route
     */
    public function any($path, $target, $name = null)
    {
        $r = new Route($this->prefix . trim($path, '/'), $target);
        if ($name == null) {
            $this->routes[] = $r;
        } else {
            $this->routes[$name] = $r;
        }
        return $r;
    }

    /**
     * @param string $path
     * @param mixed $target
     * @param null|string $name
     * @return Route
     */
    public function delete($path, $target, $name = null)
    {
        $r = $this->any($path, $target, $name);
        $r->setAllowed('DELETE');
        return $r;
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed|null
     */
    public function generate($name, array $params = array())
    {
        if (isset($this->routes[$name])) {
            return $this->routes[$name]->reverse($params);
        }
        return null;
    }

    /**
     * @param string $path
     * @param mixed $target
     * @param null|string $name
     * @return Route
     */
    public function get($path, $target, $name = null)
    {
        $r = $this->any($path, $target, $name);
        $r->setAllowed('GET');
        return $r;
    }

    /**
     * @param $prefix
     * @param \Closure $callback
     */
    public function group($prefix, \Closure $callback)
    {
        $old = $this->prefix;
        $this->prefix = $this->prefix . trim($prefix, '/') . '/';
        $callback = $callback->bindTo($this);
        $callback();
        $this->prefix = $old;
    }

    /**
     * @param string $path
     * @param mixed $target
     * @param null|string $name
     * @return Route
     */
    public function head($path, $target, $name = null)
    {
        $r = $this->any($path, $target, $name);
        $r->setAllowed('HEAD');
        return $r;
    }

    /**
     * @param string $path
     * @param mixed $target
     * @param null|string $name
     * @return Route
     */
    public function options($path, $target, $name = null)
    {
        $r = $this->any($path, $target, $name);
        $r->setAllowed('OPTIONS');
        return $r;
    }

    /**
     * @param string $path
     * @param mixed $target
     * @param null|string $name
     * @return Route
     */
    public function patch($path, $target, $name = null)
    {
        $r = $this->any($path, $target, $name);
        $r->setAllowed('PATCH');
        return $r;
    }

    /**
     * @param string $path
     * @param mixed $target
     * @param null|string $name
     * @return Route
     */
    public function post($path, $target, $name = null)
    {
        $r = $this->any($path, $target, $name);
        $r->setAllowed('POST');
        return $r;
    }

    /**
     * @param string $path
     * @param mixed $target
     * @param null|string $name
     * @return Route
     */
    public function put($path, $target, $name = null)
    {
        $r = $this->any($path, $target, $name);
        $r->setAllowed('PUT');
        return $r;
    }

    /**
     * @param string $path
     * @return bool|Route
     */
    public function match($path)
    {
        foreach ($this->routes as $route) {
            if ($route->matches($path)) {
                return $route;
            }
        }
        return false;
    }
}
