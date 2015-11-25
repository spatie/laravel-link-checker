<?php

namespace Spatie\LinkChecker;

use Exception;
use Illuminate\Console\Command;
use Spatie\Crawler\Crawler;

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
                        ';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all links';

    public function handle()
    {
        Crawler::create()
            ->setCrawlProfile($this->getProfile())
            ->setCrawlObserver($this->getReporter())
            ->startCrawling($this->getUrlToBeCrawled());

        $this->info('All done!');
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
        if (!is_null($this->option('url'))) {
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
     * Get a the profile.
     *
     * @return \Spatie\Crawler\CrawlProfile
     *
     * @throws \Exception
     */
    protected function getProfile()
    {
        if (!is_null($this->option('profile'))) {
            return app($this->option('profile'));
        }

        if (config('laravel-link-checker.defaultProfile') != '') {
            return app(config('laravel-link-checker.defaultProfile'));
        }

        throw new Exception('Could not determine the profile to be used');
    }

    /**
     * Get the reporter.
     *
     * @return \Spatie\Crawler\CrawlObserver
     *
     * @throws \Exception
     */
    protected function getReporter()
    {
        if (!is_null($this->option('reporter'))) {
            return app($this->option('reporter'));
        }

        if (config('laravel-link-checker.defaultReporter') != '') {
            return app(config('laravel-link-checker.defaultReporter'));
        }

        throw new Exception('Could not reporter the profile to be used');
    }
}
