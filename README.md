# Check all links in a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-link-checker.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-link-checker)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-link-checker/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-link-checker)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/1c3e45a3-b89a-4339-b0e2-709df055704c.svg?style=flat-square)](https://insight.sensiolabs.com/projects/1c3e45a3-b89a-4339-b0e2-709df055704c)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-link-checker.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-link-checker)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-link-checker.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-link-checker)

This package provides a command that can check all links on your laravel app. By default it will log all
links that do not return a status code in the 200- or 300-range. There's also an option to mail broken links.

If you like this package, take a look at [the other ones we have made](https://spatie.be/opensource/laravel).

## Install

You can install the package via composer:
``` bash
composer require spatie/laravel-link-checker
```

Next, you must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    Spatie\LinkChecker\LinkCheckerServiceProvider::class,
];
```

You must register the `\Spatie\LinkChecker\CheckLinksCommand`:

```php
// app/Console/Kernel.php
protected $commands = [
    ...
    \Spatie\LinkChecker\CheckLinksCommand::class,
];
```

You can optionally publish the config-file with:
```bash
php artisan vendor:publish --provider="Spatie\LinkChecker\LinkCheckerServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [

    /*
     * The base url of your app. Leave this empty to use
     * the url configured in config/app.php
     */
    'url' => '',

    /*
     * The profile determines which links need to be checked.
     */
    'default_profile' => Spatie\LinkChecker\CheckAllLinks::class,

    /*
     * The reporter determines what needs to be done when the
     * the crawler has visited a link.
     */
    'default_reporter' => Spatie\LinkChecker\Reporters\LogBrokenLinks::class,
    
    /*
     * To speed up the checking process we'll fire off requests concurrently.
     * Here you can change the amount of concurrent requests.
     */
    'concurrency' => 10

    /*
     *  Here you can specify configuration regarding the used reporters
     */
    'reporters' => [

        'mail' => [

            /*
             * The `from` address to be used by the mail reporter.
             */
            'from_address' => '',
            
            /*
             * The `to` address to be used by the mail reporter.
             */
            'to_address' => '',

            /*
             * The subject line to be used by the mail reporter.
             */
            'subject' => '',
        ],

        /*
         * If you wish to exclude status codes from the reporters,
         * you can select the status codes that you wish to
         * exclude in the array below like: [200, 302]
         */
        'exclude_status_codes' => [],
    ],
];
```


## Usage

You can start checking all links by issuing this command:

```bash
php artisan link-checker:run
```

Want to run the crawler on a different url? No problem!

```bash
php artisan link-checker:run --url=https://laravel.com
```


### Schedule the command 
To frequently check all links you can schedule the command:

```php
// app/console/Kernel.php

protected function schedule(Schedule $schedule)
{
    ...
    $schedule->command('link-checker:run')->sundays()->daily();
}
``` 

### Mail broken links
By default the package will log all broken links. If you want to have them mailed instead, just specify
`Spatie\LinkChecker\Reporters\MailBrokenLinks` in the `default_reporter` option in the config file.

## Creating your own crawl profile
A crawlprofile determines which links need to be crawled. By default `Spatie\LinkChecker\CheckAllLinks` is used,
which will check all links it finds. This behaviour can be customized by specify a class in the `default_profile`-option in the config file.
The class must implement the `Spatie\Crawler\CrawlProfile`-interface:

```php

interface CrawlProfile
{
    /**
     * Determine if the given url should be crawled.
     *
     * @param \Spatie\Crawler\Url $url
     *
     * @return bool
     */
    public function shouldCrawl(Url $url);
}
```

## Creating your own reporter
A reporter determines what should be done when a link is crawled and when the crawling process is finished.
This package provides two reporters: `Spatie\LinkChecker\Reporters\LogBrokenLinks` and `Spatie\LinkChecker\Reporters\MailBrokenLinks`.
You can create your own behaviour by making a class adhere to the `Spatie\Crawler\CrawlObserver`-interface:

```php
interface CrawlObserver
{
    /**
     * Called when the crawler will crawl the url.
     *
     * @param \Spatie\Crawler\Url $url
     */
    public function willCrawl(Url $url);

    /**
     * Called when the crawler has crawled the given url.
     *
     * @param \Spatie\Crawler\Url                      $url
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function hasBeenCrawled(Url $url, $response);

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling();
}
``` 
  
To make it easier to create a reporter, you can extend `Spatie\LinkChecker\Reporters\BaseReporter` which
provides many useful methods.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing


First start the test server in a seperate terminal session:
``` bash
cd tests/server
./start_server.sh
``` 

With the server running you can execute the tests
``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

All postcards are published [on our website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
