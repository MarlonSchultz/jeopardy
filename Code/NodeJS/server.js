const http = require('http');
const {setBuzzer, closeQuestion, openQuestion} = require("./buzzerLogic");

//create a server object:
if (!process.env.CI) {
    http.createServer(function (request, response) {
        try {
            switch (request.url.toLowerCase()) {
                case '/openquestion':
                    response.write(openQuestion());
                    response.end();
                    break;

                case '/closequestion':
                    response.write(closeQuestion());
                    response.end();
                    break;

                case '/getbuzzer':
                    response.write(getStateOfLastPressedBuzzer());
                    response.end();
                    break;

                case '/setbuzzer/green':
                    response.write(setBuzzer('green'));
                    response.end();
                    break;

                case '/setbuzzer/blue':
                    response.write(setBuzzer('blue'));
                    response.end();
                    break;

                case '/setbuzzer/yellow':

                    response.write(setBuzzer('yellow'));
                    response.end();
                    break;

                case '/setbuzzer/red':
                    response.write(setBuzzer('red'));
                    response.end();
                    break;

                case '/setbuzzer/none':
                    response.write( setBuzzer('none'));
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



