const http = require('http');
const {setBuzzer, closeQuestion, openQuestion} = require("./buzzerLogic");

//create a server object:
if (!process.env.CI) {
    http.createServer(function (request, response) {
        try {
            switch (request.url.toLowerCase()) {
                case '/openquestion':
                    openQuestion();
                    response.write('Question opened');
                    response.end();
                    break;

                case '/closequestion':
                    closeQuestion();
                    response.write('Question closed');
                    response.end();
                    break;

                case '/getbuzzer':
                    response.write(getStateOfLastPressedBuzzer());
                    response.end();
                    break;

                case '/setbuzzer/green':
                    setBuzzer('green');
                    response.write('Buzzed green');
                    response.end();
                    break;

                case '/setbuzzer/blue':
                    setBuzzer('blue');
                    response.write('Buzzed blue');
                    response.end();
                    break;

                case '/setbuzzer/yellow':
                    setBuzzer('yellow');
                    response.write('Buzzed yellow');
                    response.end();
                    break;

                case '/setbuzzer/red':
                    setBuzzer('red');
                    response.write('Buzzed red');
                    response.end();
                    break;

                case '/setbuzzer/none':
                    setBuzzer('none');
                    response.write('Reset buzzer');
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

    //Reset questions
    closeQuestion();
}



