<?php

namespace Tez;

class Route
{
    const REGEX_PARAM = '`{([\w]+)(?:(?::(.*?))?)}`';

    /**
     * @var array
     */
    private $filters = array(
        '*'     => '.*?',
        'alpha' => '[a-zA-Z0-9]+}',
        'hex'   => '[a-fA-F0-9]+}',
        'int'   => '[0-9]+'
    );

    /**
     * @var array
     */
    private $methods;

    /**
     * @var array
     */
    private $parameters = array();

    /**
     * @var array
     */
    private $params = array();

    /**
     * @var string
     */
    private $path;

    /**
     * @var mixed
     */
    private $target;

    /**
     * Route constructor.
     * @param string $path
     * @param mixed $target
     */
    function __construct($path, $target)
    {
        $this->path = $path;
        $this->target = $target;
    }

    /**
     * @param string $name
     * @param string $pattern
     * @return $this
     */
    public function addFilter($name, $pattern)
    {
        $this->filters[$name] = $pattern;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getRegex()
    {
        return preg_replace_callback(self::REGEX_PARAM, array($this, 'getRegexReplacement'), $this->path);
    }

    private function getRegexReplacement(array $match)
    {
        $this->params[] = $match[1];
        if (isset($match[2]) && !empty($match[2])) {
            if (isset($this->filters[$match[2]])) {
                $regex = $this->filters[$match[2]];
            } else {
                $regex = $match[2];
            }
            return "(?P<{$match[1]}>{$regex})";
        } else {
            return "(?P<{$match[1]}>[\\w-%]+)";
        }
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isAllowed($method)
    {
        return empty($this->methods) || in_array($method, $this->methods);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function matches($path)
    {
        if (preg_match("`^{$this->getRegex()}$`", $path, $matches))
        {
            foreach ($this->params as $k) {
                $this->parameters[$k] = $matches[$k];
            }
            return true;
        }
        return false;
    }

    public function reverse(array $params)
    {
        return preg_replace_callback(self::REGEX_PARAM, function (array $match) use ($params)
        {
            return $params[$match[1]];
        }, $this->path);
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setAllowed($method)
    {
        $this->methods[] = $method;
        return $this;
    }
}
