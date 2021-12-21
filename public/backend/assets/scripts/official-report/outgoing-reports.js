let details_id = null;
$(document).on('click', '#btnMakeAnOpinion', function () {
    details_id = $(this).attr('data-id');
    $('#modalOpinion').modal();

    $('#modalOpinion').block({
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
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/OfficialReport/GetReportInfo', {
        method: 'POST',
        data: {
            _token: token,
            id: details_id
        },
        cache: false
    }).done(function (response) {
        if (response.status == 0) {
            setTimeout(function () {
                ToastMessage('error', response.message, 'Hata!');
                $('#ModalReportDetails').modal('hide');
                $('#CargoesTable').DataTable().ajax.reload();
                return false;
            }, 250);
        } else if (response.status == 1) {
            let report = response.report;
            $('#opinionText').val(report.opinion_text);
        }
        $('#modalOpinion').unblock();
        return false;
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#modalOpinion').unblock();
    });

});


$(document).on('click', '#btnSaveOpinion', function () {


    let opinionText = $('#opinionText').val();

    if (opinionText == '') {
        ToastMessage('error', 'Görüş alanı zorunludur!', 'Hata!');
        return false;
    }

    $('#modalOpinion').block({
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
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/OfficialReport/MakeAnOpinion', {
        method: 'POST',
        data: {
            _token: token,
            id: details_id,
            opinion: opinionText,
        },
        cache: false
    }).done(function (response) {
        if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!');
            return false;
        } else if (response.status == 1) {
            setTimeout(function () {
                $('#modalOpinion').modal('hide');
                $('#OfficialReportsTable').DataTable().ajax.reload();
                getReportInfo(details_id);
                ToastMessage('success', response.message, 'İşlem Başarılı!');

            }, 250);
        }
        $('#modalOpinion').unblock();
        return false;
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#modalOpinion').unblock();
    });

});



