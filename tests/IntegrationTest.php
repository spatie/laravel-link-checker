<?php

namespace Spatie\LinkChecker\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Route;
use Spatie\Crawler\Crawler;
use Spatie\LinkChecker\LinkCheckerServiceProvider;
use Symfony\Component\HttpFoundation\Response;

abstract class IntegrationTest extends Orchestra
{
    /**
     * @var \Spatie\Crawler\Crawler
     */
    protected $crawler;

    public function setUp()
    {
        parent::setUp();

        $this->setUpRoutes($this->app);

        $this->setUpConfig($this->app);

        $this->crawler = $this->app->make(Crawler::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LinkCheckerServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpConfig($app)
    {
        $app['config']->set('laravel-link-checker.url', 'http://localhost');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpRoutes($app)
    {
        Route::any('/', function () {
            dd('home visited');

            return collect(['200', '300', '400', '500'])->reduce(function ($carry, $statusCode) {
                return $carry.'<a href="'.$statusCode.'">'.$statusCode.'</a>';
            }, '');

        });

        Route::any('/{responsecode}', function ($statusCode) {
            return (new Response("reponse for statuscode {$statusCode}", $statusCode));
        });
    }
}
