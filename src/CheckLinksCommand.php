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
                        {url? : the url to be crawler}
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
        if (!is_null($this->argument('url'))) {
            return $this->argument('url');
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
     */
    protected function getProfile()
    {
        if (!is_null($this->option('profile'))) {
            return app($this->argument('profile'));
        }
        
        if (config('laravel-link-checker.profile') != '') {
            return app(config('laravel-link-checker.profile'));
        }
        
        return new Exception('Could not determine the profile to be used');
            
    }

    /**
     * Get the reporter.
     * 
     * @return \Spatie\Crawler\CrawlObserver
     */
    protected function getReporter()
    {
        if (!is_null($this->option('reporter'))) {
            return app($this->argument('reporter'));
        }

        if (config('laravel-link-checker.reporter') != '') {
            return app(config('laravel-link-checker.reporter'));
        }

        return new Exception('Could not reporter the profile to be used');
        
        
    }
}
