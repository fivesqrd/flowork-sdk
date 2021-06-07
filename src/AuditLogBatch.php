<?php

namespace Flowork;

class AuditLogBatch
{
    protected $client;

    protected $logs = [];

    private static $instance = null;

    public static function withClient($client) : AuditLogBatch
    {
        if (static::$instance) {
            return static::$instance;
        }

        static::$instance = new static($client);

        return static::$instance;
    }

    public static function instance($config) : AuditLogBatch
    {
        if (static::$instance) {
            return static::$instance;
        }

        static::$instance = new static(
            Client::instance(['token' => $config['token'], 'uri' => $config['uri']])
        );

        return static::$instance;
    }

    private function __construct($client)
    {
        $this->client = $client;
    }

    public function push(AuditLog $log)
    {
        $this->logs[] = $log;
        return $this;
    }

    public function send() : array
    {
        // Check if we have anything to send
        if (empty($this->logs)) {
            return false;
        }

        $response = $this->client->post('/audit-log', $this->expose());

        return json_decode($response, true);
    }

    public function logs() : array
    {
        return $this->logs;
    }

    public function count() : int
    {
        return count($this->logs);
    }

    public function expose() : array
    {
        return array_map(fn(AuditLog $log): array => $log->expose(), $this->logs);
    }
}
