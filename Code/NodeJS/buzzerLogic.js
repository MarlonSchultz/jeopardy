const fs = require('fs');

module.exports = {


    setBuzzer: setBuzzer = (buzzer) => {
        //console.log(getStateOfLastPressedBuzzer());
        //console.log(isQuestionOpen());
        // always allow buzzer reset
        if (buzzer === 'none') {
            writeBuzzerState(buzzer);
            console.log('Reset buzzer');
            return;
        }
        // if no current buzzer is set and no question is open, log bad condition
        if (getStateOfLastPressedBuzzer() !== 'none' || !isQuestionOpen()) {
            console.log('Either someone already buzzed, or no question is open');
            return;
        }


        if (getStateOfLastPressedBuzzer() === 'none' && isQuestionOpen()) {
            writeBuzzerState(buzzer);
            console.log("Buzzer set to " + buzzer)
        }
    },


    closeQuestion: closeQuestion = () => {
        fs.writeFileSync('questionOpen', 'False');
        console.log('questionClosed!');
        setBuzzer("none");
    },

    openQuestion: openQuestion = () => {
        fs.writeFileSync('questionOpen', 'True');
        console.log('questionOpen!');
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

