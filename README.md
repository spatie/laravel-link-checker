## Work in progress, do not use

# Check all links in a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-link-checker.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-link-checker)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-link-checker/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-link-checker)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/1c3e45a3-b89a-4339-b0e2-709df055704c.svg?style=flat-square)](https://insight.sensiolabs.com/projects/1c3e45a3-b89a-4339-b0e2-709df055704c)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-link-checker.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-link-checker)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-link-checker.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-link-checker)

This package provides a command that can check all links on your laravel app. By default it will log all
urls that do not return a status code in de 200- or 300-range to the log.


## Install

You can install the package via composer:
``` bash
$ composer require spatie/laravel-link-checker
```

Next, you must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    Spatie\LinkChecker\LinkCheckerServiceProvider::class,
];
```

You can publish the config-file with:
```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [

    /**
     * The base url of your app.  Leave this empty to use
     * the url configured in config/app.php
     */
    'url' => '',

    /**
     * The profile determines which urls needs to be checked.
     */
    'profile' => Spatie\LinkChecker\CheckAllUrls::class,

    /**
     * The reporter determine what needs to be done when the
     * the crawler has visited an url.
     */
    'reporter' => Spatie\LinkChecker\Reporters\LogBrokenUrls::class,
];
```


## Usage

You can start checking all links by issuing this command:

```bash
php artisan link-checker:run
```

To frequently check all links you can schedule the command:

```php
// app/console/Kernel.php

protected function schedule(Schedule $schedule)
{
    ...
    $schedule->command('link-checker:run')->sundays();
    ...
}
``` 

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
