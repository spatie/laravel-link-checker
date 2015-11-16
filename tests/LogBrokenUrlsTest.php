<?php

namespace Spatie\LinkChecker\Test;

use Illuminate\Support\Facades\Artisan;

class LogBrokenUrlsTest extends IntegrationTest
{
    /**
     * @test
     */
    public function it_can_crawl_a_site()
    {
        Artisan::call('link-checker:run');

        $this->assertTrue(true);
    }
}
