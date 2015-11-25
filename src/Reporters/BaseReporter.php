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
     *
     * @return string
     */
    public function hasBeenCrawled(Url $url, $response)
    {
        $statusCode = $response ? $response->getStatusCode() : static::UNRESPONSIVE_HOST;

        $this->urlsGroupedByStatusCode[$statusCode][] = $url;

        return $statusCode;
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
        return starts_with($statusCode, ['2', '3']);
    }

    /**
     * Determine if the crawler saw some bad urls.
     *
     * @return bool
     */
    protected function crawledBadUrls()
    {
        return collect($this->urlsGroupedByStatusCode)->keys()->filter(function ($statusCode) {
            return !$this->isSuccessOrRedirect($statusCode);
        })->count() > 0;
    }
}
