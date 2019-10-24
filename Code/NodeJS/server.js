const http = require('http');
const fs = require('fs');
const url = require('url');

const openQuestion = () => {
    fs.writeFile('questionOpen', 'True', (err) => {
        if (err) throw err;
        console.log('questionOpen!');
    });
};

const closeQuestion = () => {
    fs.writeFile('questionOpen', 'False', (err) => {
        if (err) throw err;
        console.log('questionClosed!');
    });
};

const getBuzzer = () => {
    fs.readFile('buzzed', (err, data) => {
        if (err) throw err;
        console.log('buzzer' + data);
        return data;
    });
};

const setBuzzer = (buzzer) => {
    fs.writeFile('buzzed', buzzer, (err) => {
        if (err) throw err;
        console.log('Buzzer written');
    });
};

//Reset questions
closeQuestion();

//create a server object:
http.createServer(function (request, response) {
    response.write('You have reached the node server, pls wait for response\n');


    try {
        console.log(request.url);
        let calledUrl = request.url;

        switch (calledUrl) {
            case '/openQuestion':
                openQuestion();
                response.write('Question opened');
                response.end();
                break;
            case '/closeQuestion':
                closeQuestion();
                response.write('Question closed');
                response.end();
                break;
            case '/getBuzzer':
                response.write(getBuzzer());
                response.end();
                break;
            case '/setBuzzer':
                let url_parts = url.parse(request.url, true);
                let query = url_parts.query;
                console.log(query);
                response.end();
                break;
            default:
                console.log('No url pattern matched');
                response.end();
                break;
        }

    } catch (e) {
        console.log('Weird things! Error:', e);
    }

}).listen(process.env.PORT); //the server object listens on port 8080





