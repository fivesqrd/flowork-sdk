<?php

namespace Flowork;

class AuditLog
{
    protected $client;

    protected $defaults = [];

    protected $attributes = [];

    public static function instance($config, $defaults = [])
    {
        return new static(
            Client::instance(['token' => $config['token'], 'uri' => $config['uri']]), $defaults
        );
    }

    public function __construct($client, $defaults = [])
    {
        $this->client = $client;
        $this->defaults = $defaults;

        $this->agent($_SERVER['HTTP_USER_AGENT'] ?? null);
        $this->ipAddress($_SERVER['REMOTE_ADDR'] ?? null);
        $this->hostname($_SERVER['HTTP_HOST'] ?? null);
    }

    /**
     * Post one audit log to the API
     */
    public function send($values = null)
    {
        if (!$values) {
            $values = $this->expose();
        }

        $response = $this->client->post('/audit-log', [array_merge($this->defaults, $values)]);

        return json_decode($response, true);
    }

    /**
     * Add this log to queue for later
     */
    public function push()
    {
        AuditLogBatch::withClient($this->client)->push($this);
        return $this;
    }

    public function find($values = null)
    {
        if (!$values) {
            $values = $this->expose();
        }

        if (isset($values['user']['id'])) {
            $values['user-id'] = $values['user']['id'];
        }

        $response = $this->client->get('/audit-log', array_merge($this->defaults, $values));

        return json_decode($response, true);
    }

    public function fetch($id)
    {
        return json_decode($this->client->get("/audit-log/{$id}"), true);
    }

    public function attribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    protected function merge($key, $value)
    {
        $current = $this->attributes[$key] ?? [];
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

    public function uniqueId($value)
    {
        return $this->attribute('uid', $value);
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
        return $this->attributes;
    }
}
