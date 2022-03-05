let safeInit = false;

$('#tabSafe').click(function () {
    if (safeInit == false) {

        safeInit = true
        oTable = $('#tableSafe').DataTable({
            pageLength: 50,
            lengthMenu: dtLengthMenu,
            order: [9, 'desc'],
            language: dtLanguage,
            dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
            select: {
                style: 'multi',
                selector: 'td:nth-child(0)'
            },
            buttons: [
                {
                    text: 'Yenile',
                    action: function (e, dt, node, config) {
                        dt.ajax.reload();
                    },
                    attr: {
                        id: 'safeDataTableRefreshBtn'
                    }
                },
            ],
            responsive: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/Safe/Agency/AjaxTransactions/GetSafe',
                data: function (d) {
                    d.firstDate = $('#safeFirstDate').val()
                    d.lastDate = $('#safeLastDate').val()
                },
                error: function (xhr, error, code) {

                    let response = JSON.parse(xhr.responseText);
                    if (response.status == 0) {
                        ToastMessage('error', response.message, 'HATA!');
                        return false;
                    }
                    ajaxError(code);
                    if (code == "Too Many Requests") {
                        SnackMessage('Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'error', 'bl');
                    } else if (code == 590) {
                        ToastMessage('error', 'Tarih aralığı max. 90 gün olabilir!', 'HATA!');
                    }
                },
                complete: function () {
                    SnackMessage('Tamamlandı!', 'info', 'bl');

                    if ($('#datatableRefreshBtn').prop('disabled') == true)
                        $('#datatableRefreshBtn').prop('disabled', false);

                }
            },
            columns: [
                {data: 'invoice_date', name: 'invoice_date'},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'total_price', name: 'total_price'},
                {data: 'collection_type_entered', name: 'collection_type_entered'},
                {data: 'agency_name', name: 'agency_name'},
                {data: 'receiver_city', name: 'receiver_city'},
                {data: 'sender_name', name: 'sender_name'},
                {data: 'sender_current_code', name: 'sender_current_code'},
                {data: 'receiver_name', name: 'receiver_name'},
                {data: 'created_at', name: 'created_at'},
                {data: 'collection_description', name: 'collection_description'},
            ],
            scrollY: '500px',
            scrollX: true,
        });


        // Local Storage Transaction START
        let cargoSuccees = localStorage.getItem('cargo-success');
        if (cargoSuccees) {
            swal('İşlem Başarılı!', 'Kargo Oluşturuldu!', 'success');
            localStorage.clear();
        }
        // Local Storage Transaction END
    }
});

$(document).on('click', '#safeDataTableRefreshBtn', function () {

    $('#SafeDailySummeryRow').block({
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

    $.ajax('/Safe/Agency/AjaxTransactions/GetDailySafeReport', {method: 'GET'})
        .done(function (response) {
            if (response.status == 1) {
                $('#spanDevredenKasa').html(response.data.devreden_kasa);
                $('#spanGunIci').html(response.data.gun_ici);
                $('#spanTotal').html(response.data.total);
            }

        }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#SafeDailySummeryRow').unblock();
    });

});

