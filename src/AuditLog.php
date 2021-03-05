<?php

namespace Flowork;

class AuditLog
{
    protected $_client;

    protected $_defaults = [];

    protected $_attributes = [];

    public static function instance($config, $defaults = [])
    {
        return new static(
            Client::instance(['token' => $config['token'], 'uri' => $config['uri']]), $defaults
        );
    }

    public function __construct($client, $defaults = [])
    {
        $this->_client = $client;
        $this->_defaults = $defaults;

        $this->agent($_SERVER['HTTP_USER_AGENT'] ?? null);
        $this->ipAddress($_SERVER['REMOTE_ADDR'] ?? null);
        $this->hostname($_SERVER['HTTP_HOST'] ?? null);
    }

    public function create($values = null)
    {
        if (!$values) {
            $values = $this->expose();
        }

        return $this->_client->post('/audit-log', array_merge($this->_defaults, $values));
    }

    public function attribute($key, $value)
    {
        $this->_attributes[$key] = $value;

        return $this;
    }

    protected function merge($key, $value)
    {
        $current = $this->_attributes[$key] ?? [];
        return $this->attribute($key, array_merge($current, $value));
    }

    public function environment($value)
    {
        return $this->attribute('environment', $value);
    }

    public function user($value)
    {
        return $this->attribute('user', ['id' => $value->id, 'name' => $value->name]);
    }

    public function userIdAndName($id, $name)
    {
        return $this->attribute('user', ['id' => $id, 'name' => $name]); 
    }

    public function agent($value)
    {
        return $this->merge('request', ['agent' => $value]); 
    }

    public function ipAddress($value)
    {
        return $this->merge('request', ['ip_address' => $value]); 
    }

    public function hostname($value)
    {
        return $this->merge('request', ['hostname' => $value]); 
    }

    public function category($value)
    {
        return $this->attribute('category', $value);
    }

    public function description($value)
    {
        return $this->attribute('description', $value);
    }

    public function parameters($value)
    {
        return $this->attribute('parameters', $value);
    }

    public function expose()
    {
        return $this->_attributes;
    }
}
