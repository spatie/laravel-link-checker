<?php

return [

    /**
     * The base url of your app.  Leave this empty to use
     * the url configured in config/app.php
     */
    'url' => '',

    /**
     * The profile determines which urls needs to be checked.
     */
    'defaultProfile' => Spatie\LinkChecker\CheckAllUrls::class,

    /**
     * The reporter determine what needs to be done when the
     * the crawler has visited an url.
     */
    'defaultReporter' => Spatie\LinkChecker\Reporters\MailBrokenUrls::class,


    /**
     *  Here you can specify configuration regarding the used reporters
     */
    'reporters' => [


        'mail' => [

            /**
             * The to address to be used by the mail reporter.
             */
            'toAddress' => '',

            /**
             * The from address to be used by the mail reporter.
             */
            'fromAddress'
        ]
    ]
];
