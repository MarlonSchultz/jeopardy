const {openQuestion} = require("../buzzerLogic");
const {setBuzzer} = require("../buzzerLogic");
const {closeQuestion} = require("../buzzerLogic");


let spyLog = jest.spyOn(console, 'log');

beforeEach(() => {
    closeQuestion();
    spyLog.mockReset();

});


test('reset buzzer works', () => {
    let response = setBuzzer('none');
    expect(response).toEqual('Reset buzzer')
});


describe('Buzzers are pressed', function () {
    test('close Question', () => {
        let response = closeQuestion();
        expect(response).toEqual('Question closed and Reset buzzer');
    });

    test('try to buzz with closed question should not work', () => {
        let response = setBuzzer('red');
        expect(response).toEqual('Either someone already buzzed, or no question is open');
    });

    test('open a question and buzz should work', () => {
        let openResponse = openQuestion();
        expect(openResponse).toEqual('question Open!');
        let response = setBuzzer('green');
        expect(response).toEqual('Buzzer set to green');
    });

    test('that buzzing two times does not work', () => {
        let openResponse = openQuestion();
        let responseGreen = setBuzzer('green');
        let responseRed =setBuzzer('red');
        expect(openResponse).toEqual('question Open!');
        expect(responseGreen).toEqual('Buzzer set to green');
        expect(responseRed).toEqual('Either someone already buzzed, or no question is open');
    })
});

