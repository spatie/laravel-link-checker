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
    /**
     * @var string|null
     */
    protected $url = null;

    public function __construct(Crawler $crawler, $url = '')
    {
        parent::__construct();

        $this->crawler = $crawler;

        $this->url = $url;
    }

    public function handle()
    {
        $url = $this->getUrlToBeCrawled();

        $this->setProfiler($this->crawler);

        $this->setReporter($this->crawler);

        $this->crawler->startCrawling($url);

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

        if (!is_null($this->url)) {
            return $this->url;
        }

        throw new Exception('could not determine which url to be crawled.');
    }

    /**
     * Set a custom profiler.
     *
     * @param \Spatie\Crawler\Crawler $crawler
     */
    protected function setProfiler($crawler)
    {
        if (!is_null($this->option('profile'))) {
            $crawler->setCrawlProfile(app($this->argument('profile')));
        }
    }

    /**
     * Set a custom reporter.
     *
     * @param \Spatie\Crawler\Crawler $crawler
     */
    protected function setReporter($crawler)
    {
        if (!is_null($this->option('reporter'))) {
            $crawler->setReporter(app($this->argument('reporter')));
        }
    }
}
