<?php

namespace Flowork;

class Document
{
    protected $_config;

    protected $_defaults = [];

    public static function instance($token, $defaults = [], $uri = null)
    {
        return new static(
            ['token' => $token, 'uri' => $uri], $defaults
        );
    }

    public static function builder()
    {
        return new Builder();
    }

    public function __construct($config, $defaults = [])
    {
        $this->_config = $config;
        $this->_defaults = $defaults;
    }

    public function create($values)
    {
        if (is_callable($values)) {
            $values = call_user_func($values, new Builder());
        }

        if ($values instanceof Builder) {
            $values = $values->expose();
        }

        return new Proxy(
            Client::instance($this->_config), 'generic', array_merge($this->_defaults, $values)
        );
    }
}
