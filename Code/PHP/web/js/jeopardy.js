$(document).ready(function () {
    $('.modal').modal();


});

function showQuestion(el) {
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
    checkIfBuzzered(1);
}

function checkIfBuzzered(runs) {
    const maxNumsOfRuns = 30;

    if (runs < maxNumsOfRuns) {
        setTimeout(() => {
            $.ajax({
                url: window.location.protocol + "//" + window.location.host + "/api/getLastBuzzer",
                success: function (data) {
                    if (data.length > 0) {
                        switch (data[0].buzzer_id) {
                            case "1":
                                runs = maxNumsOfRuns;
                                $('#buzzerColour').css('background', '#c12c1d');
                                //rot
                                break;
                            case "2":
                                runs = maxNumsOfRuns;
                                $('#buzzerColour').css('background', '#c1ad1c');
                                //gelb
                                break;
                            case "3":
                                runs = maxNumsOfRuns;
                                $('#buzzerColour').css('background', '#13266b');
                                //blau
                                break;
                            case "4":
                                runs = maxNumsOfRuns;
                                $('#buzzerColour').css('background', '#136b1a');
                                //grün
                                break;
                        }
                    }
                },
                complete: function () {
                    checkIfBuzzered(++runs);
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
            console.log('done')
        }
    });
    countdown.start();
}


function handleAnswer(el, choice) {
    var el = $(el);
    var cell = $('#' + el.parent().parent().attr('data-cell'));
    cell.attr('data-choice', choice);
    var cssClass = choice === 'correct' ? 'green' : 'red';
    $('#close').attr('data-choice', choice);
    $('#question').html('<h2 class="z-depth-4 ' + cssClass + '">' + cell.attr('data-question') + '</h2>');
}

function closeAnswer(el) {
    $('#buzzerColour').css('background', 'unset');
    var el = $(el);
    var cell = $('#' + el.parent().attr('data-cell'));
    var cssClass = el.attr('data-choice') === 'correct' ? 'green' : 'red';
    var answer = cell.attr('data-answer');
    var question = cell.attr('data-question');
    cell.removeAttr('onclick');
    var content =
        '<div class="card ' + cssClass + '">' +
        '<div class="card-content white-text">' +
        '<span class="card-title">ANSWERED</span>' +
        '</div>' +
        '</div>';
    cell.html(content);
    $('#modal1').modal('close');
}