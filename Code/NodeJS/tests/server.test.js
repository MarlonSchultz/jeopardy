const {setBuzzer} = require("../server");

test('reset buzzer works', () => {
    console.log = jest.fn();
    setBuzzer('none');
    expect(console.log).toHaveBeenCalledWith('Reset buzzer');
});

