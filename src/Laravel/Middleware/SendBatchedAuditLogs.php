<?php

namespace Flowork\Laravel\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Closure;

class SendBatchedAuditLogs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Transmit the batched logs after the response has been sent to the browser
     */
    public function terminate(Request $request, $response)
    {
        $batch = resolve('Flowork')->auditlogBatch();
        Log::debug("{$batch->count()} Flowork audit logs in batch");
        $result = $batch->send();
        Log::debug("Flowork audit logs sent via middleware", $result ?: []);
    }
}
