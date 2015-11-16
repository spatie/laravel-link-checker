<?php

namespace Spatie\LinkChecker\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Route;
use Spatie\Crawler\Crawler;
use Spatie\LinkChecker\LinkCheckerServiceProvider;

abstract class IntegrationTest extends Orchestra
{
    /**
     * @var \Spatie\Crawler\Crawler
     */
    protected $crawler;

    /**
     * @var string
     */
    protected $host = 'http://localhost:3000';

    public function setUp()
    {
        parent::setUp();

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
        $app['config']->set('laravel-link-checker.url', $this->host);
    }

}
