<?php namespace Vaibhav\Tez;

class Route
{
    const REGEX_ATTR = '~{([\w]+)(?:(?::(.*?))?)}~';

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var mixed
     */
    private $handler;

    /**
     * @var array
     */
    private $names = [];

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var array
     */
    private $verbs;

    /**
     * @param string $pattern
     * @param mixed $handler
     */
    public function __construct($pattern, $handler)
    {
        $this->pattern = $pattern;
        $this->handler = $handler;
    }

    /**
     * @param string $verb
     *
     * @return Route
     */
    public function allow($verb)
    {
        $this->verbs[] = $verb;
    }

    /**
     * @param string $verb
     *
     * @return bool
     */
    public function allows($verb)
    {
        return empty($this->verbs) || in_array($verb, $this->verbs);
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $matches
     * @return string
     */
    private function compile(array $matches)
    {
        $this->names[] = $matches[1];
        if (isset($matches[2]) && !empty($matches[2])) {
            return "(?P<{$matches[1]}>{$matches[2]})";
        } else {
            return "(?P<{$matches[1]}>[\\w-_%]+)";
        }
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function generate(array $attributes)
    {
        return preg_replace_callback(
            self::REGEX_ATTR,
            function (array $matches) use ($attributes)
            {
                return $attributes[$matches[1]];
            },
            $this->pattern
        );
    }

    /**
     * @return mixed
     */
    public function handler()
    {
        return $this->handler;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function matches($path)
    {
        if (preg_match("~^{$this->regex()}$~", $path, $matches))
        {
            foreach ($this->names as $k) {
                $this->attributes[$k] = $matches[$k];
            }
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function pattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $prefix
     */
    public function prefix($prefix)
    {
        $this->pattern = sprintf('/%s/%s', trim($prefix, '/'), trim($this->pattern, '/'));
    }

    /**
     * @return string
     */
    public function regex()
    {
        return preg_replace_callback(
            self::REGEX_ATTR,
            [$this, 'compile'],
            $this->pattern
        );
    }
}
