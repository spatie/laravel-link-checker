"use strict"

var app = require('express')()

app.get('/', (req, res) => {
    let html = [200, 300, 400, 500]
        .map((statusCode) => `<a href="${statusCode}">${statusCode}</a><br />`)
        .join('')

    console.log(`Visit on /`)

    res.writeHead(200, {'Content-Type': 'text/html'})
    res.end(html)
});

app.get('/:responseCode', (req, res) => {
    let responseCode = req.params.responseCode

    console.log(`Visit on ${responseCode}`)

    res.status(responseCode).send(`Responsecode ${responseCode}`)
});

let server = app.listen(4020,  () => {
    let host = 'localhost'
    let port = server.address().port

    console.log('Testing server listening at http://%s:%s', host, port)
});
