<?php

namespace Spatie\LinkChecker\Reporters;

use Illuminate\Contracts\Mail\Mailer;
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
     * Called when the crawler has crawled the given url.
     *
     * @param \Psr\Http\Message\UriInterface           $url
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param \Psr\Http\Message\UriInterface           $foundOnUrl
     *
     * @return string
     */
    public function hasBeenCrawled(UriInterface $url, $response, ?UriInterface $foundOnUrl = null)
    {
        $url->foundOnUrl = $foundOnUrl;

        return parent::hasBeenCrawled($url, $response, $foundOnUrl);
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
}
