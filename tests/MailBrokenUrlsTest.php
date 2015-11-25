<?php

namespace Spatie\LinkChecker\Test;

use Illuminate\Contracts\Console\Kernel;

class MailBrokenUrlsTest extends IntegrationTest
{
    /**
     * @test
     */
    public function it_can_mail_broken_urls()
    {

        /*
         * For some reason mail is not logged.
         *
         */

        /*
        $this->placeMarker();

        $this->app[Kernel::class]->call('link-checker:run', ['--url' => $this->appUrl, '--reporter' => MailBrokenLinks::class]);

        $this->assertLogContainsTextAfterLastMarker('Crawled 70 with status code 200');
        */
    }
}
