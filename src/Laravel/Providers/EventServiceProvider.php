<?php

namespace Flowork\Laravel\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Console\Events\CommandFinished;
use Flowork\Laravel\Listeners\SendBatchedAuditLogs;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CommandFinished::class => [
            SendBatchedAuditLogs::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}