<?php

return [

    /**
     * The url that needs to be crawled. Leave this empty to use
     * the url configured in config/app.php
     */
    'url' => '',

    /**
     * The profile determines which urls needs to be checked.
     */
    'profile' => Spatie\LinkChecker\CheckAllUrls::class,

    /**
     * The reporter determine what needs to be done when the
     * the crawe
     */
    'reporter' => Spatie\LinkChecker\Reporters\LogBrokenUrls::class,
];