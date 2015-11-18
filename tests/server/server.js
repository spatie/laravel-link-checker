"use strict";

var app = require('express')();

app.get('/', function (req, res) {
    var html = [200, 300, 400, 500].map(function (statusCode) {
        return '<a href="' + statusCode + '">' + statusCode + '</a><br />';
    }).join('');

    console.log('Visit on /');

    res.writeHead(200, { 'Content-Type': 'text/html' });
    res.end(html);
});

app.get('/:responseCode', function (req, res) {
    var responseCode = req.params.responseCode;

    console.log('Visit on ' + responseCode);

    res.status(responseCode).send('Responsecode ' + responseCode);
});

var server = app.listen(4020, function () {
    var host = 'localhost';
    var port = server.address().port;

    console.log('Testing server listening at http://%s:%s', host, port);
});
