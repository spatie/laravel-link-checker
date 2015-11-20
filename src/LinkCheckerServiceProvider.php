<?php

namespace Spatie\LinkChecker;

use Illuminate\Support\ServiceProvider;

class LinkCheckerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
     echo 'boot';
        $this->publishes([
            __DIR__.'/../resources/config/laravel-link-checker.php' => config_path('laravel-link-checker.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-link-checker');
        
        $this->commands(['command.linkchecker:run']);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../resources/config/laravel-link-checker.php',
            'laravel-link-checker'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'command.link-checker:run',
        ];
    }
    
}
