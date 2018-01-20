<?php

namespace Spatie\LinkChecker\Reporters;

use Illuminate\Contracts\Logging\Log;
use Spatie\Crawler\Url;

class LogBrokenLinks extends BaseReporter
{
    /**
     * @var \Illuminate\Contracts\Logging\Log
     */
    protected $log;

    /**
     * @param \Illuminate\Contracts\Logging\Log $log
     */
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    /**
     * Called when the crawler has crawled the given url.
     *
     * @param \Spatie\Crawler\Url $url
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param \Spatie\Crawler\Url $foundOnUrl
     *
     * @return string
     */
    public function hasBeenCrawled(Url $url, $response, Url $foundOnUrl = null)
    {
        $statusCode = parent::hasBeenCrawled($url, $response);

        if ($this->isSuccessOrRedirect($statusCode)) {
            return;
        }

        if ($this->isExcludedStatusCode($statusCode)) {
            return;
        }

        $reason = $response ? $response->getReasonPhrase() : '';

        $logMessage = "{$statusCode} {$reason} - {$url}";

        if ($foundOnUrl) {
            $logMessage .= " (found on {$foundOnUrl}";
        }

        $this->log->warning($logMessage);
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling()
    {
        $this->log->info('link checker summary');

        collect($this->urlsGroupedByStatusCode)
            ->each(function ($urls, $statusCode) {
                if ($this->isSuccessOrRedirect($statusCode)) {
                    return;
                }

                $count = count($urls);

                if ($statusCode == static::UNRESPONSIVE_HOST) {
                    $this->log->warning("{$count} url(s) did have unresponsive host(s)");

                    return;
                }

                $this->log->warning("Crawled {$count} url(s) with statuscode {$statusCode}");

            });
    }
}
