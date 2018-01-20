<?php

namespace Spatie\LinkChecker\Reporters;

use Spatie\Crawler\CrawlObserver;
use Spatie\Crawler\Url;

abstract class BaseReporter implements CrawlObserver
{
    const UNRESPONSIVE_HOST = 'Host did not respond';

    /**
     * @var array
     */
    protected $urlsGroupedByStatusCode = [];

    /**
     * Called when the crawler will crawl the url.
     *
     * @param \Spatie\Crawler\Url $url
     */
    public function willCrawl(Url $url)
    {
    }

    /**
     * Called when the crawler has crawled the given url.
     *
     * @param \Spatie\Crawler\Url                      $url
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param \Spatie\Crawler\Url $foundOnUrl
     *
     * @return string
     */
    public function hasBeenCrawled(Url $url, $response, Url $foundOnUrl = null)
    {
        $statusCode = $response ? $response->getStatusCode() : static::UNRESPONSIVE_HOST;

        if (!$this->isExcludedStatusCode($statusCode)) {
            $this->urlsGroupedByStatusCode[$statusCode][] = $url;
        }

        return $statusCode;
    }

    /**
     * Determine if the statuscode concerns a successful or
     * redirect response.
     *
     * @param int|string $statusCode
     * @return bool
     */
    protected function isSuccessOrRedirect($statusCode): bool
    {
        return starts_with($statusCode, ['2', '3']);
    }

    /**
     * Determine if the crawler saw some bad urls.
     */
    protected function crawledBadUrls(): bool
    {
        return collect($this->urlsGroupedByStatusCode)->keys()->filter(function ($statusCode) {
            return !$this->isSuccessOrRedirect($statusCode);
        })->count() > 0;
    }

    /**
     * Determine if the statuscode should be excluded'
     * from the reporter.
     *
     * @param int|string $statusCode
     *
     * @return bool
     */
    protected function isExcludedStatusCode($statusCode): bool
    {
        $exludedStatusCodes = config('laravel-link-checker.reporters.exclude_status_codes', []);

        return in_array($statusCode, $exludedStatusCodes);
    }
}
