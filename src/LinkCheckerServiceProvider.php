<?php

namespace Spatie\LinkChecker;

use Illuminate\Support\ServiceProvider;
use Spatie\Crawler\Crawler;

class SkeletonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/config/laravel-link-checker.php' => config_path('laravel-link-checker.php'),
        ], 'config');

        $this->app['command.linkchecker:run'] = $this->app->share(
            function () {

                $this->getConfiguredCrawler();

                return new CheckLinksCommand();
            }
        );

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
            'command.linkchecker:run',
        ];
    }

    /**
     * @return \Spatie\Crawler\Crawler
     */
    protected function getConfiguredCrawler()
    {
        return Crawler::create()
            ->setCrawlObserver()
            ->setCrawlProfile()
            ->startCrawling($url);
    }
}
