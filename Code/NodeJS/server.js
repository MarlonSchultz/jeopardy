const http = require('http');
const fs = require('fs');

module.exports = {
    openQuestion: openQuestion = () => {
        fs.writeFile('questionOpen', 'True', (err) => {
            if (err) throw err;
            console.log('questionOpen!');
        });
    },

    setBuzzer: setBuzzer = (buzzer) => {
        //console.log(getBuzzer());
        //console.log(isQuestionOpen());
        // always allow buzzer reset
        if (buzzer === 'none') {
            writeBuzzer(buzzer);
            console.log('Reset buzzer');
            return;
        }
        // if no current buzzer is set and no question is open, log bad condition
        if (getBuzzer().trim() !== 'none' || !isQuestionOpen()) {
            console.log('Either someone already buzzed, or no question is open');
            return;
        }

        if (getBuzzer().trim() === 'none' && isQuestionOpen()) {
            writeBuzzer(buzzer);
            console.log("Buzzer set to " + buzzer)

        }
    },


    closeQuestion: closeQuestion = () => {
        fs.writeFile('questionOpen', 'False', (err) => {
            if (err) throw err;
            console.log('questionClosed!');
            setBuzzer("none");
        });
    },

    getBuzzer: getBuzzer = () => {
        return fs.readFileSync('buzzed', 'utf8', (err, data) => {
            if (err) throw err;
            return data;
        });
    },

    isQuestionOpen: isQuestionOpen = () => {
        let dataFromFile = fs.readFileSync('questionOpen', 'utf8', (err, data) => {
            if (err) throw err;
            return data;
        });
        return dataFromFile.trim() === 'True';
    },


    writeBuzzer: writeBuzzer = (buzzer) => {
        fs.writeFile('buzzed', buzzer, (err) => {
            if (err) throw err;
        });
    },
};
//Reset questions
closeQuestion();

//create a server object:
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
                response.write(getBuzzer());
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

