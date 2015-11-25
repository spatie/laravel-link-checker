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

        $this->mail->send('laravel-link-checker::crawlReport', compact('urlsGroupedByStatusCode'), function ($message) {
            $message->from(config('laravel-link-checker.reporters.mail.fromAddress'));
            $message->to(config('laravel-link-checker.reporters.mail.toAddress'));
        });
    }
}
