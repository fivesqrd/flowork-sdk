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

    /**
     * @return mixed null|array
     */
    public function send($maxChunkSize = 50)
    {
        // Check if we have anything to send
        if (empty($this->logs)) {
            return null;
        }

        // Split batch into chunks
        $chunks = array_chunk($this->expose(), $maxChunkSize);

        $responses = [];

        // Send batches in chunks of $maxChunkSize per request
        foreach ($chunks as $chunk) {
            $response = $this->client->post('/audit-log', $chunk);
            $responses[] = json_decode($response, true);
        }

        return $responses;
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
        // Convert log objects to plain array
        return array_map(fn(AuditLog $log): array => $log->expose(), $this->logs);
    }
}
