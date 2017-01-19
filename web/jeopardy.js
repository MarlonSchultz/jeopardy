function handleItem(el) {
    if ($(el).data('action') === 'answer') {
        var data =$(el).data();
        var answer = '<div class="valign-wrapper"><div class="valign">' + $(el).data('answer') + '</div>' +
            '<div class="btn-floating red"><i class="close material-icons" onclick="handleItem(this)">close</i></div>' +
            '<div class=" btn-floating green"><i class="close material-icons" onclick="handleItem(this)">check</i></div>' +
                '</div>'
            ;
        $(el).html(answer);
        $(el).prop('onclick',null).off('click');
        $(el).addClass('card-panel').addClass('teal').addClass('lighten-2');
        $(el).data('action', 'question');
        $.each(data, function(key, value) {$(el).data(key, value);} );
    } else {
        if ($(el).parent().hasClass('red')) {
            var resultClass = 'red';
        } else {
            var resultClass = 'green';
        }
        var el = $('#'+$(el).parent().parent().parent().attr('id'));
        var question = el.data('question');
        var answer = el.data('answer');
        el.removeData('action');
        var html = 'Answer: ' + answer + '<br>' + 'Question: ' + question;
        el.html(html);
        el.removeClass('teal').removeClass('lighten-2').addClass(resultClass);
    }
}