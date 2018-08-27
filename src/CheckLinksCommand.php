<?php

namespace Spatie\LinkChecker;

use Exception;
use Spatie\Crawler\Crawler;
use Illuminate\Console\Command;
use Spatie\Crawler\CrawlProfile;
use Spatie\Crawler\CrawlObserver;

class CheckLinksCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'link-checker:run
                        {--url= : the url to be crawler}
                        {--profile= : The profiler to be used}
                        {--reporter= : The reporter to be used}
                        {--concurrency=10 : The amount of concurrent requests}
                        ';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all links';

    public function handle()
    {
        Crawler::create(config('laravel-link-checker.client_options', []))
            ->setCrawlProfile($this->getProfile())
            ->setCrawlObserver($this->getReporter())
            ->setConcurrency($this->getConcurrency())
            ->startCrawling($this->getUrlToBeCrawled());

        $this->info('All done!');
    }

    /**
     * Returns concurrency. If not found, simply returns a default value like
     * 10 (default from spatie/crawler).
     *
     * @return int
     */
    protected function getConcurrency(): int
    {
        if ($this->option('concurrency') !== null) {
            return $this->option('concurrency');
        }

        if (config('laravel-link-checker.concurrency') != '') {
            return config('laravel-link-checker.concurrency');
        }

        return 10;
    }

    /**
     * Determine the url to be crawled.
     *
     * @return null|string
     *
     * @throws \Exception
     */
    protected function getUrlToBeCrawled()
    {
        if (! is_null($this->option('url'))) {
            return $this->option('url');
        }

        if (config('laravel-link-checker.url') != '') {
            return config('laravel-link-checker.url');
        }

        if (config('app.url') != '') {
            return config('app.url');
        }

        throw new Exception('could not determine which url to be crawled.');
    }

    /**
     * Get the profile.
     */
    protected function getProfile(): CrawlProfile
    {
        if (! is_null($this->option('profile'))) {
            return app($this->option('profile'));
        }

        if (config('laravel-link-checker.default_profile') != '') {
            return app(config('laravel-link-checker.default_profile'));
        }

        throw new Exception('Could not determine the profile to be used');
    }

    /**
     * Get the reporter.
     */
    protected function getReporter(): CrawlObserver
    {
        if (! is_null($this->option('reporter'))) {
            return app($this->option('reporter'));
        }

        if (config('laravel-link-checker.default_reporter') != '') {
            return app(config('laravel-link-checker.default_reporter'));
        }

        throw new Exception('Could not reporter the profile to be used');
    }
}
