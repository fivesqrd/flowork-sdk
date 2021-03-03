<?php

namespace Flowork\Laravel;

use Atlas;
use Illuminate\Support;
use Illuminate\Foundation\Application as LaravelApplication;

/**
 * Atlas service provider
 */
class ServiceProvider extends Support\ServiceProvider
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
            dirname(__DIR__) . '/Laravel/_config.php' => config_path('flowork.php')
        ]);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Laravel/_config.php', 'flowork'
        );

        $this->app->singleton('Flowork', function ($app) {
            $config = $app->make('config')->get('flowork');
            return new Factory($config);
        });

        $this->app->alias('flowork', 'Flowork');
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
