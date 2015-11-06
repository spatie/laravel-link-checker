<?php

namespace Spatie\LinkChecker;

use Illuminate\Support\ServiceProvider;
use Spatie\Crawler\Crawler;

class LinkCheckerServiceProvider extends ServiceProvider
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
                return new CheckLinksCommand(
                    $this->getConfiguredCrawler(),
                    $this->getUrlToBeCrawled()
                );
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
            'command.link-checker:run',
        ];
    }

    /**
     * @return \Spatie\Crawler\Crawler
     */
    protected function getConfiguredCrawler()
    {
        $profiler = config('laravel-link-checker.profile');

        $reporter = config('laravel-link-checker.reporter');

        return Crawler::create()
            ->setCrawlProfile(app($profiler))
            ->setCrawlObserver(app($reporter));
    }

    /**
     * Return the url to be crawled.
     *
     * @return string
     */
    protected function getUrlToBeCrawled()
    {
        if (config('link-checker.url') == '') {
            return config('app.url');
        }

        return config('link-checker.url');
    }
}
