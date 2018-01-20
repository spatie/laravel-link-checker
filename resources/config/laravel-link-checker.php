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
         * If you wish to exclude status codes from the reporters,
         * you can select the status codes that you wish to
         * exclude in the array below like: [200, 302]
         */
        'exclude_status_codes' => [],
    ]
];
