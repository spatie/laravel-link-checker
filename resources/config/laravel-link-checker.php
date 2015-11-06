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
    'profile' => Spatie\LinkChecker\CheckAllUrls::class,

    /**
     * The reporter determine what needs to be done when the
     * the crawler has visited an url.
     */
    'reporter' => Spatie\LinkChecker\Reporters\LogBrokenUrls::class,
];