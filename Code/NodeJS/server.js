let WebSocketServer = require('websocket').server;
let http = require('http');
const fs = require("fs");
let server = http.createServer(function (request, response) {

});
server.listen(process.env.PORT, function () {
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
        try {
            if (message.type === 'utf8') {
                // QuestionOpened
                let payload = JSON.parse(message.utf8Data);

                if (payload.message === 'questionOpen') {
                    fs.writeFile('questionOpen', 'True', (err) => {
                        // throws an error, you could also catch it here
                        if (err) throw err;

                        // success case, the file was saved
                        console.log('questionOpen!');
                    });
                }

                // QuestionClosed
                if (payload.message === 'questionClosed') {
                    fs.writeFile('questionOpen', 'False', (err) => {
                        // throws an error, you could also catch it here
                        if (err) throw err;

                        // success case, the file was saved
                        console.log('questionClosed!');
                    });
                }
            }
        } catch (e) {
            console.log('Error:', e);
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
