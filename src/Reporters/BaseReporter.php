<?php

namespace Spatie\LinkChecker\Reporters;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;

abstract class BaseReporter extends CrawlObserver
{
    const UNRESPONSIVE_HOST = 'Host did not respond';

    /**
     * @var array
     */
    protected $urlsGroupedByStatusCode = [];

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param \Psr\Http\Message\UriInterface      $url
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param null|\Psr\Http\Message\UriInterface $foundOnUrl
     *
     * @return int|string
     */
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ) {
        $statusCode = $response->getStatusCode();

        if (! $this->isExcludedStatusCode($statusCode)) {
            $this->urlsGroupedByStatusCode[$statusCode][] = $url;
        }

        return $statusCode;
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \GuzzleHttp\Exception\RequestException $requestException
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ) {
        $statusCode = $requestException->getCode();

        if (! $this->isExcludedStatusCode($statusCode)) {
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
        return Str::startsWith($statusCode, ['2', '3']);
    }

    /**
     * Determine if the crawler saw some bad urls.
     */
    protected function crawledBadUrls(): bool
    {
        return collect($this->urlsGroupedByStatusCode)->keys()->filter(function ($statusCode) {
            return ! $this->isSuccessOrRedirect($statusCode);
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
        $excludedStatusCodes = config('laravel-link-checker.reporters.exclude_status_codes', []);

        return in_array($statusCode, $excludedStatusCodes);
    }
}
