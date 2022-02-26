let agencySafeStatusDetailsID = null

$(document).ready(function () {

    tableAgencySafeStatus = $('#tableAgencySafeStatus').DataTable({
        pageLength: 250,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [9, 'desc'],
        language: {
            "sDecimal": ",",
            "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
            "sInfo": "_TOTAL_ kayıttan _START_ - _END_ kayıtlar gösteriliyor",
            "sInfoEmpty": "Kayıt yok",
            "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_",
            "sLoadingRecords": "Yükleniyor...",
            "sProcessing": "<div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div>",
            "sSearch": "",
            "sZeroRecords": "Eşleşen kayıt bulunamadı",
            "oPaginate": {
                "sFirst": "İlk",
                "sLast": "Son",
                "sNext": "Sonraki",
                "sPrevious": "Önceki"
            },
            "oAria": {
                "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
            },
            "select": {
                "rows": {
                    "_": "%d kayıt seçildi",
                    "0": "",
                    "1": "1 kayıt seçildi"
                }
            }
        },
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
                    id: 'datatableRefreshBtn',
                    class: 'btn btn-primary',
                }
            },
            {
                extend: 'excelHtml5',
                attr: {class: 'btn btn-success'},
                title: "CKG-Sis Acente Kasa Durumu"
            },
        ],
        responsive: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/Safe/General/AjaxTransactions/GetAgencySafeStatus',
            data: function (d) {
                d.firstDate = $('#agencySafeStatusFirstDate').val()
                d.lastDate = $('#agencySafeStatusLastDate').val()
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
                SnackMessage('Tamamlandı!', 'info', 'bl')

                if ($('#datatableRefreshBtn').prop('disabled') == true)
                    $('#datatableRefreshBtn').prop('disabled', false)

            }
        },
        columns: [
            {data: 'agency_name', name: 'agency_name'},
            {data: 'agency_code', name: 'agency_code'},
            {data: 'regional_directorates', name: 'regional_directorates'},
            {data: 'total_bill_count', name: 'total_bill_count'},
            {data: 'endorsement', name: 'endorsement'},
            {data: 'cash_amount', name: 'cash_amount'},
            {data: 'pos_amount', name: 'pos_amount'},
            {data: 'amount_deposited', name: 'amount_deposited'},
            {data: 'intraday', name: 'intraday'},
            {data: 'debt', name: 'debt'},
            {data: 'safe_status', name: 'safe_status'},
            {data: 'detail', name: 'detail'},
        ],
        scrollY: '500px',
        scrollX: true,
    })
})

$(document).on('click', '.safe-detail', function () {
    agencySafeStatusDetailsID = $(this).prop('id')
    getAgencySafeStatus()
})


function getAgencySafeStatus() {
    $('#ModalAgencySafeStatusDetails').modal();

    $('#ModalBodyAgencySafeStatusDetails').block({
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
    })
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/Safe/General/AjaxTransactions/GetAgencySafeStatusDetails', {
        method: 'POST',
        data: {
            _token: token,
            id: agencySafeStatusDetailsID
        }
    }).done(function (response) {

        if (response.status == 1) {

            let agency = response.data.agency;
            console.log(agency);

            $('h5#agencyName').text("#" + agency.agency_code + "-" + agency.agency_name + " ŞUBE");
            $('h6#agencyRegionName').text(agency.tc_name + " B.M.");

            $('td#agencyName').html("#" + agency.agency_code + "-" + agency.agency_name + " ŞUBE");
            $('td#agencyRegion').html(agency.tc_name + " BÖLGE MÜDÜRLÜĞÜ");
            $('td#appAgencyOfficer').html(agency.agency_officer);

            $('td#agencyPhones').html(agency.phone2 + " / " + agency.phone3);
            $('td#agencyTotalBillCount').html(agency.total_bill_count);
            $('td#agencyTotalEndorsement').html(agency.endorsement + "₺");
            $('td#agencyTotalCashAmount').html(agency.cash_amount + "₺");
            $('td#agencyTotalPosAmount').html(agency.pos_amount + "₺");
            $('td#agencyAmountDeposited').html(agency.amount_deposited + "₺");
            $('td#agencyIntraday').html(agency.intraday + "₺");
            $('td#agencyTotalDebt').html(agency.debt + "₺");
            $('td#agencySafeStatus').html(agency.safe_status == '1' ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Pasif</b>');
            $('td#agencySafeStatusDescription').html(agency.safe_status_description);
            $('textarea#agencySafeStatusDescription').html(agency.safe_status_description);


        } else if (response.status == -1)
            ToastMessage('error', response.message, 'Hata!')

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyAgencySafeStatusDetails').unblock();
    });
}

function changeSafeStatus(status) {

    $('.change-safe-status').prop('disabled', true)
    $('#ModalBodyAgencySafeStatusDetails').block({
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

    $.ajax('/Safe/General/AjaxTransactions/ChangeAgencySafeStatus', {
        method: 'POST',
        data: {
            _token: token,
            id: agencySafeStatusDetailsID,
            status: status,
            description: $('textarea#agencySafeStatusDescription').val()
        }
    }).done(function (response) {

        if (response.status == 1) {
            ToastMessage('success', response.message, 'İşlem Başarılı!')
            getAgencySafeStatus()
            tableAgencySafeStatus.draw()

        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        } else if (response.status == -1)
            ToastMessage('error', response.message, 'Hata!')

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyAgencySafeStatusDetails').unblock();
        $('.change-safe-status').prop('disabled', false)
    });
}

$(document).on('click', '.change-safe-status', delay(function () {
    changeSafeStatus($(this).attr('status'))
}, 500))

