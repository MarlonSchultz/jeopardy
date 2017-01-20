$(document).ready(function(){
    $('.modal').modal();
});

function showQuestion(el) {
    var el = $(el);
    var answer = el.data('answer');
    var question = el.data('question');
    var cell = el.attr('id');
    $('#answer').html('<h1>'+answer+'</h1>');
    $('.card-action').attr('data-cell', cell);
    $('#modal1').modal('open');
}

function handleAnswer(el, choice) {
    var el = $(el);
    var cell = $('#'+el.parent().parent().data('cell'));
    cell.data('choice', choice);
    var cssClass = choice === 'wrong' ? 'red' : 'green';
    $('#close').attr('data-choice', choice);
    $('#question').html('<h2 class="z-depth-4 '+cssClass+'">'+cell.data('question')+'</h2>');
}

function closeAnswer(el) {
    var el = $(el);
    var cell = $('#'+el.parent().parent().data('cell'));
    var cssClass = el.parent().data('choice') === 'wrong' ? 'red' : 'green';
    var answer = cell.data('answer');
    var question = cell.data('question');
    cell.removeAttr('onclick');
    $(el).prop('onclick',null).off('click');
    var content =
        '<div class="card '+cssClass+'">'+
            '<div class="card-content white-text">'+
                '<div><strong>Answer: </strong>'+answer+'</div>'+
                '<div><strong>Question: </strong>'+question+'</div>'+
            '</div>'+
        '</div>';
    cell.html(content);
    $('#modal1').modal('close');
}