$(document).ready(function() {
    senMessageHandler();
});

var senMessageHandler = function() {

    $('#send_message_form').on('submit', function (e) {
        e.preventDefault();
        var data = $(this).serializeArray();
        $.ajax({
            url: '/sendmessage',
            type: 'post',
            data: data
        })
        .done(function(res){
            var message = res.message,
                alertWrap = $('.alert');

            if(alertWrap.length) {

                if(res.error == 'false') {
                    message = '<strong>Success</strong> ' + message;
                    alertWrap.addClass('alert-success');
                } else {
                    message = '<strong>Error</strong> ' + message;
                    alertWrap.addClass('alert-success');
                }

                alertWrap.html(message).fadeIn();
                alertWrap.delay(3000).fadeOut();
            }

        })

    });

}