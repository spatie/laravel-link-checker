<?php

namespace Spatie\LinkChecker\Reporters;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Mail\Mailer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class MailBrokenLinks extends BaseReporter
{
    /**
     * @var Mailer
     */
    protected $mail;

    /**
     * MailBrokenLinks constructor.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mail
     */
    public function __construct(Mailer $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling()
    {
        if (!$this->crawledBadUrls()) {
            return;
        }

        $urlsGroupedByStatusCode = $this->urlsGroupedByStatusCode;

        $this->mail->send('laravel-link-checker::crawlReport', compact('urlsGroupedByStatusCode'), function ($message) {
            $message->from(config('laravel-link-checker.reporters.mail.from_address'));
            $message->to(config('laravel-link-checker.reporters.mail.to_address'));
            $message->subject(config('laravel-link-checker.reporters.mail.subject'));
        });
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param \Psr\Http\Message\UriInterface      $url
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ) {
        $url->foundOnUrl = $foundOnUrl;

        return parent::crawled($url, $response, $foundOnUrl);
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
        return;
    }
}
