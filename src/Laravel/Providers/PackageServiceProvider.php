<?php

namespace Flowork\Laravel\Providers;

use Atlas;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Flowork\Factory;
use Flowork\AuditLog;
use Flowork\Document;

/**
 * Atlas service provider
 */
class PackageServiceProvider extends ServiceProvider
{
    const VERSION = '1.0.0';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Bootstrap the configuration
     *
     * @return void
     */
    public function boot()
    {
        /* Path to default config file */
        $this->publishes([
            dirname(__DIR__) . '/config.php' => config_path('flowork.php')
        ]);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/_config.php', 'flowork');

        $this->app->register(EventServiceProvider::class);

        $this->app->bind('flowork', function ($app) {
            return new Factory($app->make('config')->get('flowork'));
        });

        $this->app->bind('document', function ($app) {
            return new Document($app->make('config')->get('flowork'));
        });

        $this->app->bind('auditlog', function ($app) {
            $config = $app->make('config');
            return AuditLog::instance($config->get('flowork'), ['environment' => $config->get('app.env')]);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['flowork'];
    }
}
