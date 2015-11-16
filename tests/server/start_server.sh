#!/usr/bin/env bash

npm install
node server.js

if [ -z ${TRAVIS_JOB_ID} ]; then
    # not running under travis, stay in foreground until stopped
    node server.js
else
    # running under travis, daemonize
    (node server.js &) || /bin/true
fi
