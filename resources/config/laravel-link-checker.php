<?php

return [

    /*
     * The base url of your app. Leave this empty to use
     * the url configured in config/app.php.
     */
    'url' => '',

    /*
     * The profile determining which links need to be checked.
     */
    'default_profile' => Spatie\LinkChecker\CheckAllLinks::class,

    /*
     * The reporter determining what needs to be done when the
     * the crawler has visited a link.
     */
    'default_reporter' => Spatie\LinkChecker\Reporters\LogBrokenLinks::class,

    /*
     * To speed up the checking process we'll fire off requests concurrently.
     * Here you can change the amount of concurrent requests.
     */
    'concurrency' => 10,

    /*
     * List of client options to use in Guzzle HTTP.
     */
    'client_options' => [

        \GuzzleHttp\RequestOptions::COOKIES => true,
        \GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => 10,
        \GuzzleHttp\RequestOptions::TIMEOUT => 10,
        \GuzzleHttp\RequestOptions::ALLOW_REDIRECTS => false,

    ],

    /*
     * Configuration regarding the used reporters.
     */
    'reporters' => [

        'mail' => [

            /*
             * The from address to be used by the mail reporter.
             */
            'from_address' => '',

            /*
             * The to address to be used by the mail reporter.
             */
            'to_address' => '',

            /*
             * The subject line to be used by the mail reporter.
             */
            'subject' => '',

        ],

        /*
         * If you wish to exclude certain status codes from the reporters,
         * specify them here.
         * Here's an example: [200, 302]
         */
        'exclude_status_codes' => [],
    ]
];
