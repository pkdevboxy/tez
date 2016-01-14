<?php namespace Vaibhav\Tez;

class Router
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var Route[]
     */
    protected $routes = [];

    /**
     * @param string $prefix
     */
    function __construct($prefix = '/')
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $pattern
     * @param mixed $handler
     * @param string $name
     *
     * @return \Vaibhav\Tez\Route
     */
    public function any($pattern, $handler, $name = null)
    {
        return $this->map(null, new Route($pattern, $handler), $name);
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    public function generate($name, array $params = array())
    {
        if (isset($this->routes[$name])) {
            return $this->routes[$name]->generate($params);
        }
        return null;
    }

    /**
     * @param string $pattern
     * @param mixed $handler
     * @param string $name
     *
     * @return \Vaibhav\Tez\Route
     */
    public function get($pattern, $handler, $name = null)
    {
        return $this->map('GET', new Route($pattern, $handler), $name);
    }

    /**
     * @param string $prefix
     * @param \Closure $closure
     */
    public function group($prefix, \Closure $closure)
    {
        $original = $this->prefix;
        $this->prefix = sprintf('/%s/%s', trim($original, '/'), trim($prefix, '/'));
        $callback = $closure->bindTo($this);
        $callback();
        $this->prefix = $original;
    }

    /**
     * @param string $pattern
     * @param mixed $handler
     * @param string $name
     *
     * @return \Vaibhav\Tez\Route
     */
    public function head($pattern, $handler, $name = null)
    {
        return $this->map('HEAD', new Route($pattern, $handler), $name);
    }

    /**
     * @param string $verb
     * @param Route $route
     * @param string $name
     * @return Route
     */
    public function map($verb, Route $route, $name = null)
    {
        $route->allow($verb);
        if ($this->prefix !== '/') {
            $route->prefix($this->prefix);
        }
        if ($name == null) {
            $this->routes[] = $route;
        } else {
            $this->routes[$name] = $route;
        }
        return $route;
    }

    /**
     * @param string $path
     * @return Route
     */
    public function match($path)
    {
        foreach ($this->routes as $route) {
            if ($route->matches($path)) {
                return $route;
            }
        }
        return null;
    }

    /**
     * @param string $pattern
     * @param mixed $handler
     * @param string $name
     *
     * @return \Vaibhav\Tez\Route
     */
    public function options($pattern, $handler, $name = null)
    {
        return $this->map('OPTIONS', new Route($pattern, $handler), $name);
    }

    /**
     * @param string $pattern
     * @param mixed $handler
     * @param string $name
     *
     * @return \Vaibhav\Tez\Route
     */
    public function patch($pattern, $handler, $name = null)
    {
        return $this->map('PATCH', new Route($pattern, $handler), $name);
    }

    /**
     * @param string $pattern
     * @param mixed $handler
     * @param string $name
     *
     * @return \Vaibhav\Tez\Route
     */
    public function post($pattern, $handler, $name = null)
    {
        return $this->map('POST', new Route($pattern, $handler), $name);
    }

    /**
     * @param string $pattern
     * @param mixed $handler
     * @param string $name
     *
     * @return \Vaibhav\Tez\Route
     */
    public function put($pattern, $handler, $name = null)
    {
        return $this->map('PUT', new Route($pattern, $handler), $name);
    }
}
