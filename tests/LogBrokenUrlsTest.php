<?php

namespace Spatie\LinkChecker\Test;

use Illuminate\Contracts\Console\Kernel;

class LogBrokenUrlsTest extends IntegrationTest
{
    /**
     * @test
     */
    public function it_can_crawl_a_site()
    {
        $this->placeMarker();

        $this->app[Kernel::class]->call("link-checker:run", ['url' => $this->appUrl]);

        $this->assertLogContainsTextAfterLastMarker('400 Bad Request - http://localhost:4020/400');
        $this->assertLogContainsTextAfterLastMarker('500 Internal Server Error - http://localhost:4020/500');
        $this->assertLogContainsTextAfterLastMarker('link checker summary');
        $this->assertLogContainsTextAfterLastMarker('Crawled 1 url(s) with statuscode 400');
        $this->assertLogContainsTextAfterLastMarker('Crawled 1 url(s) with statuscode 500');

        $this->assertTrue(true);
    }
}
