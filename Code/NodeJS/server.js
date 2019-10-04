let WebSocketServer = require('websocket').server;
let http = require('http');

let server = http.createServer(function (request, response) {

});
server.listen(8081, function () {
});

// create the server
wsServer = new WebSocketServer({
    httpServer: server
});

// WebSocket server
wsServer.on('request', function (request) {
    let connection = request.accept(null, request.origin);

    // This is the most important callback for us, we'll handle
    // all messages from users here.
    connection.on('message', function (message) {
        if (message.type === 'utf8') {
            // process WebSocket message
            connection.sendUTF("test")
        }
    });

    connection.on('close', function (connection) {
        // close user connection
    });
});

// kick everyone off the server if docker shuts down
process.on('SIGINT', function () {
    // terminate all clients on close
    wsServer.closeAllConnections();
    console.log('Good bye');
    process.exit();
});
