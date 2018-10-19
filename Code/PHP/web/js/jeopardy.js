var numberOfRequests = 1;
const maxNumberOfRequests = 30;


$(document).ready(function () {
    $('.modal').modal();
});


function showQuestion(el) {
    numberOfRequests = 1;
    var el = $(el);
    var answer = el.attr('data-answer');
    var question = el.attr('data-question');
    var cell = el.attr('id');
    console.log(cell);
    console.log(question);
    $('#answer').html('<h1>' + answer + '</h1>');
    $('#question').html('');
    $('.card-action').attr('data-cell', cell);
    startTimer();
    $('#modal1').modal('open');
    $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/api/setQuestionOpen/" + cell,
        context: document.body
    });
    checkIfBuzzered();
}

function checkIfBuzzered() {

    if (numberOfRequests < maxNumberOfRequests) {
        setTimeout(() => {
            $.ajax({
                url: window.location.protocol + "//" + window.location.host + "/api/getLastBuzzer",
                success: function (data) {
                    if (data.length > 0) {
                        switch (data[0].buzzer_id) {
                            case "1":
                                numberOfRequests = maxNumberOfRequests;
                                $('#buzzerColour').css('background', '#13266b');
                                break;
                            case "2":
                                numberOfRequests = maxNumberOfRequests;
                                $('#buzzerColour').css('background', '#136b1a');
                                break;
                            case "3":
                                numberOfRequests = maxNumberOfRequests;
                                $('#buzzerColour').css('background', '#c1ad1c');
                                break;
                            case "4":
                                numberOfRequests = maxNumberOfRequests;
                                $('#buzzerColour').css('background', '#c12c1d');
                                break;
                        }
                        resetWrongWarning();
                    }
                },
                complete: function () {
                    checkIfBuzzered(++numberOfRequests);
                }
            })
        }, 1000);
    }
}


function startTimer() {
    var countdown = $("#countdown").countdown360({
        radius: 60,
        seconds: 30,
        fontColor: '#FFFFFF',
        autostart: false,
        onComplete: function () {
            numberOfRequests = maxNumberOfRequests;
            $.ajax({
                url: window.location.protocol + "//" + window.location.host + "/api/setQuestionsClosed"
            })
        }
    })
    countdown.stop();
    countdown.start();

}


function handleAnswerCorrect(el) {
    var el = $(el);
    var cell = $('#' + el.parent().parent().attr('data-cell'));
    stopRequests();
    $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/api/setQuestionCorrect"
    }).success(() => {
        $('#close').attr('data-choice', 'correct');
        $('#question').html('<h2 class="z-depth-4 green">' + cell.attr('data-question') + '</h2>');
    }).error(() => {

    });
}

function handleAnswerWrong(el) {
    var el = $(el);
    var cell = $('#' + el.parent().parent().attr('data-cell'));
    $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/api/setQuestionWrong"
    }).success(() => {
        $('#close').attr('data-choice', 'red');
        $('#question').html('<h2 class="z-depth-4 red"> Leider falsch </h2>');
        startTimer();
        numberOfRequests = 1; // reset for checkIfBuzzered
        checkIfBuzzered();
        resetBuzzerColour();
    }).error(() => {

    });

}

function closeAnswer(el) {
    resetBuzzerColour();
    var el = $(el);
    var cell = $('#' + el.parent().attr('data-cell'));
    var cssClass = el.attr('data-choice') === 'correct' ? 'green' : el.attr('data-choice') === 'wrong' ? "red" : "grey";
    cell.removeAttr('onclick');
    var content =
        '<div class="card ' + cssClass + '">' +
        '<div class="card-content white-text">' +
        '<span class="card-title" style="color: transparent">ANSWERED</span>' +
        '</div>' +
        '</div>';
    cell.html(content);
    $('#modal1').modal('close');
    stopRequests();
    $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/api/setQuestionsClosed"
    })
}

function revealAnswer(el) {
    resetBuzzerColour();
    var el = $(el);
    var cell = $('#' + el.parent().parent().attr('data-cell'));
    $('#question').html('<h2 class="z-depth-4 grey">' + cell.attr('data-question') + '</h2>');

    var tableCell = $('#' + el.parent().attr('data-cell'));
    var content =
        '<div class="card grey">' +
        '<div class="card-content white-text">' +
        '<span class="card-title">ANSWERED</span>' +
        '</div>' +
        '</div>';
    tableCell.html(content);

}

function stopRequests() {
    numberOfRequests = maxNumberOfRequests;
}

function resetBuzzerColour() {
    $('#buzzerColour').css('background', 'unset');
}

function resetWrongWarning() {
    $('#question').html('');
}