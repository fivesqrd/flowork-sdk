<?php

namespace Flowork\Laravel\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Flowork\Laravel\Facades\Auditlog;

/**
 * Useful when listening for Illuminate\Console\Events\CommandFinished;
 */
class SendBatchedAuditLogs
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $batch = Auditlog::batch();
        //Log::debug("{$batch->count()} Flowork audit logs in batch");
        $result = $batch->send(30);
        //Log::debug("Flowork audit logs sent via console command listener", $result ?: []);
    }
}
