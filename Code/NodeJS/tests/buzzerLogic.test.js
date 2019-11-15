const {openQuestion} = require("../buzzerLogic");
const {setBuzzer} = require("../buzzerLogic");
const {closeQuestion} = require("../buzzerLogic");


let spyLog = jest.spyOn(console, 'log');

beforeEach(() => {
    closeQuestion();
    spyLog.mockReset();

});


test('reset buzzer works', () => {
    setBuzzer('none');
    expect(spyLog).toHaveBeenCalledWith('Reset buzzer');
});


describe('Buzzers are pressed', function () {
    test('close Question', () => {
        closeQuestion();
        expect(spyLog).toHaveBeenCalledWith('questionClosed!');
        expect(spyLog).toHaveBeenCalledWith('Reset buzzer');
    });

    test('try to buzz with closed question should not work', () => {
        setBuzzer('red');
        expect(spyLog).toHaveBeenCalledWith('Either someone already buzzed, or no question is open');
    });

    test('open a question and buzz should work', () => {
        openQuestion();
        expect(spyLog).toHaveBeenCalledWith('questionOpen!');
        setBuzzer('green');
        expect(spyLog).toHaveBeenCalledWith('Buzzer set to green');
    });

    test('that buzzing two times does not work', () => {
        openQuestion();
        setBuzzer('green');
        setBuzzer('red');
        expect(spyLog).toHaveBeenCalledWith('questionOpen!');
        expect(spyLog).toHaveBeenCalledWith('Buzzer set to green');
        expect(spyLog).toHaveBeenCalledWith('Either someone already buzzed, or no question is open');
    })
});

