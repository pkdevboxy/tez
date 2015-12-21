<?php

namespace Tez;

class Dispatcher
{
    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var callable
     */
    private $factory;

    function __construct($delimiter = '@', callable $factory = null)
    {
        $this->delimiter = $delimiter;
        $this->factory = $factory;
    }

    public function dispatch(Route $route)
    {
        $target = $route->getTarget();
        if (is_string($target) && (strpos($target, $this->delimiter) > 0)) {
            list ($controller, $action) = explode($this->delimiter, $target);
            if (is_callable($this->factory)) {
                $target = call_user_func($this->factory, $controller, $action);
            } else {
                $target = array(new $controller(), $action);
            }
        }
        if (is_callable($target)) {
            return call_user_func_array($target, $route->getParameters());
        }
        return false;
    }
}