$(document).on('change', '#receiverCity', function () {
    getDistricts('#receiverCity', '#receiverDistrict');
});

$(document).on('change', '#senderCity', function () {
    getDistricts('#senderCity', '#senderDistrict');
});

$(document).ready(function () {
    $('#filterSelectReportedAgency').select2();
    $('#filterSelectReportedTc').select2();

    $('#filterSelectReportedAgency').change(function () {
        $('#filterSelectReportedTc').val('');
        $('#select2-filterSelectReportedTc-container').text('Seçiniz');
    });

    $('#filterSelectReportedTc').change(function () {
        $('#filterSelectReportedAgency').val('');
        $('#select2-filterSelectReportedAgency-container').text('Seçiniz');
    });
});


var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {
    $('#agency').select2();
    $('#creatorUser').select2();

    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [7, 'desc'],
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

        buttons: [
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                }
            },
            {
                extend: 'colvis',
                text: 'Sütun Görünüm'
            },
        ],
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/OfficialReport/GetOutGoingReports',
            data: function (d) {
                d.filterReportSerialNumber = $('#filterReportSerialNumber').val();
                d.filterTrackingNo = $('#filterTrackingNo').val();
                d.filterInvoiceNumber = $('#filterInvoiceNumber').val();
                d.filterReportType = $('#filterReportType').val();
                d.filterStartDate = $('#filterStartDate').val();
                d.filterFinishDate = $('#filterFinishDate').val();
                d.filterSelectReportedAgency = $('#filterSelectReportedAgency').val();
                d.filterSelectReportedTc = $('#filterSelectReportedTc').val();
                d.filterDetectingUser = $('#filterDetectingUser').val();
                d.filterConfirm = $('#filterConfirm').val();
                d.filterDescription = $('#filterDescription').val();
                d.filterByDate = $('#filterByDate').prop('checked');
            },
            error: function (xhr, error, code) {

                if (xhr.status == 429) {
                    ToastMessage('error', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                } else if (xhr.status == 509) {
                    ToastMessage('error', 'Tarih aralığı en fazla 30 gün olabilir!', 'Hata');
                }
            },
            complete: function () {
                SnackMessage('Tamamlandı!', 'info', 'bl');
            }
        },
        columns: [
            {data: 'free', name: 'free'},
            {data: 'report_serial_no', name: 'report_serial_no'},
            {data: 'type', name: 'type'},
            {data: 'name_surname', name: 'name_surname'},
            {data: 'name_surname', name: 'name_surname'},
            {data: 'reported_unit', name: 'reported_unit'},
            {data: 'description', name: 'description'},
            {data: 'confirm', name: 'confirm'},
            {data: 'created_at', name: 'created_at'},
            {data: 'detail', name: 'detail'},
        ],

    });
});

function drawDT() {
    oTable.draw();
}

$('.niko-select-filter').change(delay(function (e) {
    drawDT();
}, 1000));

$('.niko-filter').keyup(delay(function (e) {
    drawDT();
}, 1000));

$('#btnClearFilter').click(function () {
    $('#search-form').trigger("reset");
    $('#select2-creatorUser-container').text('Seçiniz');
    $('#select2-agency-container').text('Seçiniz');
    drawDT();
});

// parse a date in yyyy-mm-dd format
function dateFormat(date) {
    date = String(date);
    let text = date.substring(0, 10);
    let time = date.substring(19, 8);
    time = time.substring(3, 11);
    let datetime = text + " " + time;
    return datetime;
}


$(document).on('click', '.user-detail', function () {
    $('#ModalUserDetail').modal();

    $('#ModalBodyUserDetail.modal-body').block({
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

    detailsID = $(this).prop('id');
    userInfo($(this).prop('id'));
});

var array = new Array();

function userInfo(user) {
    $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
        method: 'POST',
        data: {
            _token: token,
            currentID: user
        },
        cache: false
    }).done(function (response) {
        let currentStatus, currentConfirmed;
        let creatorDisplayName = '<span class="text-primary font-weight-bold">(' + response.current.creator_display_name + ')</span>';

        if (response.current.status == "1")
            currentStatus = '<span class="text-success">(Aktif Hesap)</span>';
        else
            currentStatus = '<span class="text-danger">(Pasif Hesap)</span>';

        if (response.current.confirmed == "1") {
            currentConfirmed = '<span class="text-success font-weight-bold">Onaylandı</span>';
            $('#divConfirmCurrent').hide();
        } else {
            currentConfirmed = '<span class="text-danger font-weight-bold">Onay Bekliyor</span>';
            $('#divConfirmCurrent').show();
        }

        let city = response.current.city + "/",
            district = response.current.district + " ",
            neighborhood = response.current.neighborhood + " ",
            street = response.current.street != '' && response.current.street != null ? response.current.street + " CAD. " : "",
            street2 = response.current.street2 != '' && response.current.street2 != null ? response.current.street2 + " SK. " : "",
            buildingNo = "NO:" + response.current.building_no + " ",
            door = "D:" + response.current.door_no + " ",
            floor = "KAT:" + response.current.floor + " ",
            addressNote = "(" + response.current.address_note + ")";

        let fullAddress = neighborhood + street + street2 + buildingNo + floor + door + addressNote;


        $('#agencyName').html(response.current.name);
        $('#agencyCityDistrict').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
        $('#titleBranch').html(response.current.name + ' - ÖZET ' + currentStatus);

        $('#currentCategory').html(response.current.category);
        $('#modalCurrentCode').html(response.current.current_code);
        $('#nameSurnameCompany').html(response.current.name);
        $('#currentAgency').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
        $('#taxOffice').html(response.current.tax_administration);
        $('#tcknVkn').html(response.current.tckn);
        $('#phone').html(response.current.phone);
        $('#cityDistrict').html(response.current.city + "/" + response.current.district);
        $('#address').html(fullAddress);
        $('#gsm').html(response.current.gsm);
        $('#gsm2').html(response.current.gsm2);
        $('#phone2').html(response.current.phone2);
        $('#email').html(response.current.email);
        $('#website').html(response.current.website);
        $('#regDate').html(response.current.created_at);
        $('#dispatchCityDistrict').html(response.current.dispatch_city + "/" + response.current.dispatch_district);
        $('#dispatchAddress').html(response.current.dispatch_adress);
        $('#iban').html(response.current.iban);
        $('#bankOwner').html(response.current.bank_owner_name);
        $('#contractStartDate').html(response.current.contract_start_date);
        $('#contractEndDate').html(response.current.contract_end_date);
        $('#reference').html(response.current.reference);
        $('#currentCreatorUser').html(response.current.creator_user_name + " " + creatorDisplayName);
        $('#currentFilePrice').html(response.price.file_price + "₺");
        $('#current1_5Desi').html(response.price.d_1_5 + "₺");
        $('#current6_10Desi').html(response.price.d_6_10 + "₺");
        $('#current11_15Desi').html(response.price.d_11_15 + "₺");
        $('#current16_20Desi').html(response.price.d_16_20 + "₺");
        $('#current21_25Desi').html(response.price.d_21_25 + "₺");
        $('#current26_30Desi').html(response.price.d_26_30 + "₺");
        $('#currentAmountOfIncrease').html(response.price.amount_of_increase + "₺");
        $('#currentCollectPrice').html(response.price.collect_price + "₺");
        $('#collectAmountOfIncrease').html("%" + response.price.collect_amount_of_increase);
        $('#currentConfirmed').html(currentConfirmed);

        $('.modal-body').unblock();
        return false;
    });

    $('#ModalAgencyDetail').modal();
}

$(document).on('dblclick', '.report-serial-no', function () {
    let tracking_no = $(this).attr('tracking-no')
    let id = $(this).prop('id')
    detailsID = id;
    copyToClipBoard(tracking_no);
    SnackMessage('Takip numarası kopyalandı!', 'info', 'bl');
    getReportInfo(id);
});

function getReportInfo(detailsID) {

    $('#ModalReportDetails').modal();


    $('#ModalReportDetails').block({
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
            id: detailsID
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
            let piece_details = response.piece_details;

            if (report.type == 'UTF') {
                $('#CartHTF').hide();
                $('#CartUTF').show();
            } else if (report.type == 'HTF') {
                $('#CartHTF').show();
                $('#CartUTF').hide();
            }

            let long_name = report.type == 'UTF' ? "(Uygunsuzluk Tespit Formu)" : '(Hasar Tespit Formu)';

            let piecesString = "";

            $('#titleReportTitleType').html(report.type + " " + long_name);
            $('#titleReportSerialNumber').html(report.report_serial_no);
            $('#reportReportSerialNumber').html(report.report_serial_no);

            $('#titleReportDate').html(report.created_at_date);
            $('#htfInvoiceNumber').html(report.cargo_invoice_number);

            $.each(piece_details, function (key, val) {
                piecesString += val['part_no'] + ",";
            });

            $('#htfCargoPieces').html(piece_details.length + " Adet Parça - (" + piecesString + ")");
            $('#htfDamageDescription').html(report.description);
            $('#htfContentDetection').html(report.content_detection);
            $('#htfDamageDetails').html(report.damage_details);
            $('#htfTransactionDetails').html(report.transaction_details);

            $('#utfImproprietyDescription').html(report.description);
            $('#utfImproprietyDetails').html(report.impropriety_details);

            $('#reportDetectingUnit').html(report.detecting_unit);
            $('#reportDetectingUser').html(report.name_surname + " (" + report.display_name + ")");
            $('#reportRealReportedUnitType').html(report.real_reported_unit_type);
            $('#reportReportedUnit').html(report.reported_unit);
            $('#reportReportConfirmingUser').html(report.confirming_user);
            $('#reportReportConfirmingDate').html(report.confirming_datetime);
            $('#reportReportCreatedAt').html(report.created_at_date);


            let confirm = "";
            if (report.confirm == '0')
                confirm = '<b class="text-primary">Onay Bekliyor</b>';
            else if (report.confirm == '-1')
                confirm = '<b style="text-decoration: underline;" class="text-danger">Onaylanmadı</b>';
            else if (report.confirm == '1')
                confirm = '<b class="text-success">Onaylandı</b>';

            $('#reportReportConfirm').html(confirm);

        }

        $('#ModalReportDetails').unblock();
        return false;
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalReportDetails').unblock();
    });
}
