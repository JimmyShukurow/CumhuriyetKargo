
$('#btnCreateTutorial').on('click',function () {

  $('.app-main__inner').block({
        message: $('<div class="loader mx-auto">\n' +
            '                            <div class="ball-grid-pulse">\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                            </div>\n' +
            '                        </div>')
    });
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');
    
    $.ajax('/Tutorials/tutorial', {
        method: 'POST',
        data: {
            _token: token,
            name: $('#video_name').val(),
            category: $('#category').val(),
            embedded_link: $('#embedded_link').val(),
            tutor: $('#tutor').val(),
            description: $('#description').val(),
        }
    }).done(function (response) {

        if (response.status == 1) {
            ToastMessage('success', response.message, 'İşlem Başarılı!')

            localStorage.setItem('tutorial-success', true);
            setTimeout(function () {
                location.href = "/Tutorials/tutorial"
            }, 100)
        } else if (response.status == -1) {
            ToastMessage('error', response.message, 'Hata!')
            $('.app-main__inner').unblock();
            return false
        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
            $('.app-main__inner').unblock();
        }
    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
        $('.app-main__inner').unblock();
    }).always(function () {
    });
})


