const fs = require('fs');

module.exports = {


    setBuzzer: setBuzzer = (buzzer) => {
        //console.log(getStateOfLastPressedBuzzer());
        //console.log(isQuestionOpen());
        // always allow buzzer reset
        if (buzzer === 'none') {
            writeBuzzerState(buzzer);
            return 'Reset buzzer';
        }
        // if no current buzzer is set and no question is open, log bad condition
        if (getStateOfLastPressedBuzzer() !== 'none' || !isQuestionOpen()) {
            return 'Either someone already buzzed, or no question is open';
        }


        if (getStateOfLastPressedBuzzer() === 'none' && isQuestionOpen()) {
            writeBuzzerState(buzzer);
            return 'Buzzer set to ' + buzzer
        }
    },


    closeQuestion: closeQuestion = () => {
        fs.writeFileSync('questionOpen', 'False');
        return ('Question closed and ' + setBuzzer("none"));
    },

    openQuestion: openQuestion = () => {
        fs.writeFileSync('questionOpen', 'True');
        return 'question Open!';
    },

    getStateOfLastPressedBuzzer: getStateOfLastPressedBuzzer = () => {
        return fs.readFileSync('buzzed', 'utf8').trim();
    },

    isQuestionOpen: isQuestionOpen = () => {
        let dataFromFile = fs.readFileSync('questionOpen', 'utf8');
        return dataFromFile === 'True';
    },


    writeBuzzerState: writeBuzzerState = (buzzer) => {
        fs.writeFileSync('buzzed', buzzer);
    },
};

