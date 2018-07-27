<?php

namespace Spatie\LinkChecker\Test;

use Illuminate\Contracts\Console\Kernel;
use Spatie\LinkChecker\Reporters\MailBrokenLinks;

class MailBrokenUrlsTest extends IntegrationTest
{
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('laravel-link-checker.default_reporter', MailBrokenLinks::class);
        $this->app['config']->set('mail.driver', 'log');
    }

    /**
     * @test
     */
    public function it_can_report_broken_urls_in_the_mail()
    {
        $this->placeMarker();

        $this->app[Kernel::class]->call('link-checker:run', ['--url' => $this->appUrl]);

        $this->assertLogContainsTextAfterLastMarker('Crawled 2 link(s) with status code 200');
        $this->assertLogContainsTextAfterLastMarker('Crawled 1 link(s) with status code 300');
        $this->assertLogContainsTextAfterLastMarker('Crawled 1 link(s) with status code 400');
        $this->assertLogContainsTextAfterLastMarker('Crawled 1 link(s) with status code 500');
    }

    /** @test */
    public function it_does_not_report_excluded_status_codes_in_the_mail()
    {
        $this->placeMarker();

        $this->app['config']->set('laravel-link-checker.reporters.exclude_status_codes', [500]);
        $this->app[Kernel::class]->call('link-checker:run', ['--url' => $this->appUrl]);

        $this->assertLogContainsTextAfterLastMarker('Crawled 2 link(s) with status code 200');
        $this->assertLogContainsTextAfterLastMarker('Crawled 1 link(s) with status code 300');
        $this->assertLogContainsTextAfterLastMarker('Crawled 1 link(s) with status code 400');
        $this->assertLogNotContainsTextAfterLastMarker('Crawled 1 link(s) with status code 500');
    }
}
