<?php namespace Vaibhav\Tez;

class Dispatcher
{
    /**
     * @var callable
     */
    private $resolver;

    /**
     * @param callable $resolver
     */
    function __construct(callable $resolver = null)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param Route $route
     *
     * @return mixed
     */
    public function dispatch(Route $route)
    {
        $handler = $route->handler();
        if (!is_callable($handler) && ($this->resolver !== null)) {
            $handler = call_user_func($this->resolver, $handler);
        }
        if (is_callable($handler)) {
            return call_user_func_array($handler, $route->attributes());
        }
        throw new \ErrorException("Target specified for '{$route->pattern()}' cannot be called");
    }
}
