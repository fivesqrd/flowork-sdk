<?php

namespace Flowork;

class Document
{
    protected $_config;

    protected $_defaults = [];

    protected $_attributes = [];

    public static function instance($config, $defaults = [], $uri = null)
    {
        return new static(
            Client::instance(['token' => $config['token'], 'uri' => $config['uri']]), $defaults
        );
    }

    public function __construct($config, $defaults = [])
    {
        $this->_client = $client;
        $this->_defaults = $defaults;
    }

    public function create($values = null)
    {
        if (!$values) {
            $values = $this->expose();
        }

        return $this->_client->post('/document/generic', array_merge($this->_defaults, $values));
    }

    public function attribute($key, $value)
    {
        $this->_attributes[$key] = $value;

        return $this;
    }

    public function title($value)
    {
        return $this->attribute('title', $value);
    }

    public function currency($value)
    {
        return $this->attribute('currency', $value);
    }

    public function date($value)
    {
        return $this->attribute('date', $value);
    }

    public function template($value)
    {
        return $this->attribute('template', $value);
    }

    public function number($value)
    {
        return $this->attribute('number', $value);
    }

    public function expose()
    {
        return $this->_attributes;
    }
}
