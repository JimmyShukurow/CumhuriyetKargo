
$('#btnCreateTutorial').click(function () {

    // if ($('#plaque').val() == '') {
    //     ToastMessage('error', 'Plaka alanı zorunludur!', 'Hata!')
    //     return false
    // }
    //
    // if ($('#arrivalBranchType').val() == '') {
    //     ToastMessage('error', 'Varış birim tipi alanı zorunludur!', 'Hata!')
    //     return false
    // }
    //
    // if (($('#arrivalBranchType').val() == 'Aktarma' && $('#arrivalTc').val() == '') || ($('#arrivalBranchType').val() == 'Acente' && $('#arrivalAgency').val() == '')) {
    //     ToastMessage('error', 'Varış birimi alanı zorunludur!', 'Hata!')
    //     return false
    // }

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

    // let branchCode = null
    // if ($('#arrivalBranchType').val() == 'Aktarma')
    //     branchCode = $('#arrivalTc').val()
    // else if ($('#arrivalBranchType').val() == 'Acente')
    //     branchCode = $('#arrivalAgency').val()

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

            localStorage.setItem('expedition-success', true);
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


function getExpeditionRoutes() {

    let arrayRealRoutes = []
    $('.row-of-routes').each(function () {
        arrayRealRoutes.push([$(this).attr('branch-type'), $(this).attr('branch-id')])
    })

    return arrayRealRoutes
}


