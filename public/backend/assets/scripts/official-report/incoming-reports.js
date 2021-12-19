let details_id = null;
$(document).on('click', '#btnMakeAnObjection', function () {
    details_id = $(this).attr('data-id');
    $('#modalObjection').modal();

    $('#modalObjection').block({
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
            $('#defenseText').val(report.objection_defense);
        }
        $('#modalObjection').unblock();
        return false;
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#modalObjection').unblock();
    });

});


$(document).on('click', '#btnSaveDefense', function () {


    let defenseText = $('#defenseText').val();

    if (defenseText == '') {
        ToastMessage('error', 'Savunma alanı zorunludur!', 'Hata!');
        return false;
    }

    $('#modalObjection').block({
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

    $.ajax('/OfficialReport/MakeAnObjection', {
        method: 'POST',
        data: {
            _token: token,
            id: details_id,
            defense: defenseText,
        },
        cache: false
    }).done(function (response) {
        if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!');
            return false;
        } else if (response.status == 1) {
            setTimeout(function () {
                $('#modalObjection').modal('hide');
                $('#OfficialReportsTable').DataTable().ajax.reload();
                getReportInfo(details_id);
                ToastMessage('success', response.message, 'İşlem Başarılı!');

            }, 250);
        }
        $('#modalObjection').unblock();
        return false;
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#modalObjection').unblock();
    });

});



