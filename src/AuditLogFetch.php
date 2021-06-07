<?php

namespace Flowork;

class AuditLogFetch
{
    protected $client;

    protected $defaults = [];

    protected $attributes = [];

    public static function instance($config)
    {
        return new static(
            Client::instance(['token' => $config['token'], 'uri' => $config['uri']])
        );
    }

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function search($values = null)
    {
        if (isset($values['user']['id'])) {
            $values['user-id'] = $values['user']['id'];
        }

        $response = $this->client->get('/audit-log', $values);

        return json_decode($response, true);
    }

    public function find($id)
    {
        return json_decode($this->client->get("/audit-log/{$id}"), true);
    }
}
