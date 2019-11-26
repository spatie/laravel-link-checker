<?php

namespace Spatie\LinkChecker\Test;

use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Crawler\Crawler;
use Spatie\LinkChecker\LinkCheckerServiceProvider;

abstract class IntegrationTest extends Orchestra
{
    /**
     * @var \Spatie\Crawler\Crawler
     */
    protected $crawler;

    /**
     * @var string
     */
    protected $appUrl = 'http://localhost:4020';

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpConfig($this->app);

        $this->crawler = $this->app->make(Crawler::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LinkCheckerServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpConfig($app)
    {
        $app['config']->set('laravel-link-checker.url', $this->appUrl);
        $app['config']->set('laravel-link-checker.url', $this->appUrl);

        $app['config']->set('laravel-link-checker.reporters.mail.from_address', 'technical@spatie.be');
        $app['config']->set('laravel-link-checker.reporters.mail.to_address', 'technical@spatie.be');

        $app['config']->set('app.key', 'SomeRandomString');

        if (defined('OPENSSL_CIPHER_AES_256_CBC')) {
            $app['config']->set('app.cipher', OPENSSL_CIPHER_AES_256_CBC);
        } else {
            $app['config']->set('app.cipher', MCRYPT_RIJNDAEL_128);
        }
    }

    /**
     * Put a marker in the log file.
     *
     * @param $suffix
     */
    protected function placeMarker($suffix = '')
    {
        Log::info("test marker {$suffix}");
    }

    /**
     * Determine if log contains the given text after the last mark.
     *
     * @param $text
     */
    public function assertLogContainsTextAfterLastMarker($text)
    {
        $this->assertStringContainsString($text, $this->getLogContentsAfterLastMarker());
    }

    /**
     * Determine if log does not contains the given text after the last mark.
     *
     * @param $text
     */
    public function assertLogNotContainsTextAfterLastMarker($text)
    {
        $this->assertStringNotContainsString($text, $this->getLogContentsAfterLastMarker());
    }

    /**
     * Get the contents of the log after the last marker.
     *
     * @return string
     */
    protected function getLogContentsAfterLastMarker()
    {
        $startTestMarker = 'test marker';

        $logContents = file_get_contents($this->findNewestLocalLogfile());

        $lastMarkerPosition = strrpos($logContents, $startTestMarker);

        $contentsAfterLastMarker = substr($logContents, $lastMarkerPosition);

        return $contentsAfterLastMarker;
    }

    /**
     * Get the path to the latest local Laravel log file.
     *
     * @return null|string
     */
    protected function findNewestLocalLogfile()
    {
        $files = glob(storage_path('logs').'/*.log');
        $files = array_combine($files, array_map('filemtime', $files));
        arsort($files);

        $newestLogFile = key($files);

        return $newestLogFile;
    }
}
