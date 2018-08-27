<?php

namespace Spatie\LinkChecker\Reporters;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Exception\RequestException;

class LogBrokenLinks extends BaseReporter
{
    protected $log;

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
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

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param \Psr\Http\Message\UriInterface         $url
     * @param \GuzzleHttp\Exception\RequestException $requestException
     * @param \Psr\Http\Message\UriInterface|null    $foundOnUrl
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ) {
        parent::crawlFailed($url, $requestException, $foundOnUrl);

        $statusCode = $requestException->getCode();

        if ($this->isExcludedStatusCode($statusCode)) {
            return;
        }

        $this->log->warning(
            $this->formatLogMessage($url, $requestException, $foundOnUrl)
        );
    }

    protected function formatLogMessage(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ): string {
        $statusCode = $requestException->getCode();

        $reason = $requestException->getMessage();

        $logMessage = "{$statusCode} {$reason} - {$url}";

        if ($foundOnUrl) {
            $logMessage .= " (found on {$foundOnUrl}";
        }

        return $logMessage;
    }
}
