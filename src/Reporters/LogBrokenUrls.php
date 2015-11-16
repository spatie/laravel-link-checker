<?php

namespace Spatie\LinkChecker\Reporters;

use Illuminate\Contracts\Logging\Log;
use Spatie\Crawler\CrawlObserver;
use Spatie\Crawler\Url;

class LogBrokenUrls implements CrawlObserver
{
    const UNRESPONSIVE_HOST = 'Host did not respond';

    /**
     * @var array
     */
    protected $crawledUrls = [];
    /**
     * @var \Illuminate\Contracts\Logging\Log
     */
    private $log;

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    /**
     * Called when the crawler will crawl the url.
     *
     * @param Url $url
     */
    public function willCrawl(Url $url)
    {

    }

    /**
     * Called when the crawler has crawled the given url.
     *
     * @param Url $url
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @return string
     */
    public function hasBeenCrawled(Url $url, $response)
    {
        $statusCode = $response ? $response->getStatusCode() : self::UNRESPONSIVE_HOST;

        $this->crawledUrls[$statusCode][] = $url;

        if ($this->isSuccessOrRedirect($statusCode)) {
            return;
        }

        $reason = $response ? $response->getReasonPhrase() : '';

        $this->log->warning("{$statusCode} {$reason} - {$url}");
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling()
    {
        $this->log->info('link checker summary');

        foreach ($this->crawledUrls as $statusCode => $urls) {

            if (!$this->isSuccessOrRedirect($statusCode)) {
                $count = count($urls);

                $this->log->warning("Crawled {$count} url(s) with statuscode {$statusCode}");

                if ($statusCode == static::UNRESPONSIVE_HOST) {
                    $this->log->warning("{$count} url(s) did have unresponsive host(s)");
                }
            }

        }

    }

    /**
     * Determine if the statuscode concerns a successful or
     * redirect response.
     *
     * @param int $statusCode
     *
     * @return bool
     */
    protected function isSuccessOrRedirect($statusCode)
    {
        return (starts_with($statusCode, ['2', '3']));
    }
}
