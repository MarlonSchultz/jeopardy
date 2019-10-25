const http = require('http');
const fs = require('fs');

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
    return fs.readFileSync('buzzed', (err, data) => {
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
setBuzzer('none');

//create a server object:
http.createServer(function (request, response) {
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

            case '/setBuzzer/green':
                setBuzzer('green');
                response.write('Buzzed green');
                response.end();
                break;

            case '/setBuzzer/blue':
                setBuzzer('blue');
                response.write('Buzzed blue');
                response.end();
                break;

            case '/setBuzzer/yellow':
                setBuzzer('yellow');
                response.write('Buzzed yellow');
                response.end();
                break;

            case '/setBuzzer/red':
                setBuzzer('red');
                response.write('Buzzed red');
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





