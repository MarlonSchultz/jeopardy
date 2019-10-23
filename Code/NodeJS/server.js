let http = require('http');
const fs = require("fs");


//create a server object:
http.createServer(function (request, response) {
    response.write('Hello World!'); //write a response to the client
    response.end(); //end the response

    try {
        console.log(request.url);
        let calledUrl = request.url;

        switch (calledUrl) {
            case '/openQuestion':
                openQuestion();
                break;
            case '/closeQuestion':
                openQuestion();
                break;
            default:
                console.log('No url pattern matched')
                break;
        }

    } catch (e) {
        console.log('Weird things! Error:', e);
    }

}).listen(process.env.PORT); //the server object listens on port 8080


const openQuestion = () => {
    fs.writeFile('questionOpen', 'True', (err) => {
        // throws an error, you could also catch it here
        if (err) throw err;
        // success case, the file was saved
        console.log('questionOpen!');
    });
};

const closeQuestion = () => {
    fs.writeFile('questionOpen', 'False', (err) => {
        // throws an error, you could also catch it here
        if (err) throw err;
        // success case, the file was saved
        console.log('questionClosed!');
    });
};


