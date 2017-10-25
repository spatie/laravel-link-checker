<?php

namespace Spatie\LinkChecker\Reporters;

use Illuminate\Contracts\Mail\Mailer;

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
        $urlFoundOnPages = $this->urlFoundOnPages;

        $this->mail->send('laravel-link-checker::crawlReport', compact('urlsGroupedByStatusCode', 'urlFoundOnPages'), function ($message) {
            $message->from(config('laravel-link-checker.reporters.mail.from_address'));
            $message->to(config('laravel-link-checker.reporters.mail.to_address'));
            $message->subject(config('laravel-link-checker.reporters.mail.subject'));
        });
    }
}
