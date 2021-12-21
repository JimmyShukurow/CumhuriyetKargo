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

function initDatatable(type = null, urlParams = null) {

    let selectVal = null, selectVal2;
    if (type == 'manage') {

        selectVal = 'multi';
        selectVal2 = 'td:nth-child(2)';

        buttonsVal = [
            {
                extend: 'selectAll',
                text: 'Tümünü Seç'
            },
            {
                extend: 'selectNone',
                text: 'Tümünü Bırak'
            },
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
        ];
    } else {

        selectVal = false;
        selectVal2 = false;
        buttonsVal = [
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
        ];
    }

    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 10,
        columnDefs: [{
            orderable: false,
            className: 'select-checkbox',
            targets: 1
        }],
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [10, 'desc'],
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
            style: selectVal, selector: selectVal2
        },
        buttons: [buttonsVal],
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: urlParams,
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
            {data: 'check', name: 'check'},
            {data: 'report_serial_no', name: 'report_serial_no'},
            {data: 'type', name: 'type'},
            {data: 'detecting_unit', name: 'detecting_unit'},
            {data: 'name_surname', name: 'name_surname'},
            {data: 'reported_unit', name: 'reported_unit'},
            {data: 'description', name: 'description'},
            {data: 'confirm', name: 'confirm'},
            {data: 'objection', name: 'objection'},
            {data: 'opinion', name: 'opinion'},
            {data: 'created_at', name: 'created_at'},
            {data: 'detail', name: 'detail'}
        ],

    });
}

var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {
    $('#agency').select2();
    $('#creatorUser').select2();
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


$(document).on('dblclick', '.report-serial-no', function () {
    let id = $(this).prop('id')
    detailsID = id;
    getReportInfo(id);
});

$(document).on('click', '.btn-detail-report', function () {
    let id = $(this).prop('id')
    detailsID = id;
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
            ToastMessage('error', response.message, 'Hata!');
            $('#ModalReportDetails').modal('hide');
            $('#OfficialReportsTable').DataTable().ajax.reload();
            return false;
        } else if (response.status == 1) {

            let report = response.report;
            let piece_details = response.piece_details;
            let movements = response.movements;

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

            $('#btnMakeAnObjection').attr('data-id', report.id);
            $('#btnMakeAnOpinion').attr('data-id', report.id);

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

            $('#reportReportObjection').html(report.objection == '1' ? 'Evet' : 'Hayır');
            $('#reportReportObjecting').html(report.objecting_user != '' ? report.objecting_user : '-');
            $('#reportReportObjectionDate').html(report.objection_datetime != '' ? report.objection_datetime : '-');
            $('#reportReportObjectionDefense').html(report.objection_defense != null ? report.objection_defense : '-');

            $('#reportReportOpinion').html(report.opinion == '1' ? 'Evet' : 'Hayır');
            $('#reportReportOpinionUser').html(report.opinion_user != '' ? report.opinion_user : '-');
            $('#reportReportOpinionDate').html(report.opinion_datetime != '' ? report.opinion_datetime : '-');
            $('#reportReportOpinionText').html(report.opinion_text != null ? report.opinion_text : '-');



            let confirm = "";
            if (report.confirm == '0')
                confirm = '<b class="text-primary">Onay Bekliyor</b>';
            else if (report.confirm == '-1')
                confirm = '<b style="text-decoration: underline;" class="text-danger">Onaylanmadı</b>';
            else if (report.confirm == '1')
                confirm = '<b class="text-success">Onaylandı</b>';

            $('#reportReportConfirm').html(confirm);

            $('#tBodyOfficialReportMovements').html('');
            if (movements.length == 0) {
                $('#tBodyOfficialReportMovements').html('<tr><td colspan="2" class="text-danger text-center font-weight-bold">Hareket Bulunamadı</td></tr>');
            } else {
                $.each(movements, function (key, val) {
                    $('#tBodyOfficialReportMovements').append('' +
                        '<tr>' +
                        '<td>' + val['movement'] + '</td>' +
                        '<td style="white-space: nowrap;">' + val['created_at'] + '</td>' +
                        '<tr>' +
                        '');
                });
            }

        }

        $('#ModalReportDetails').unblock();
        return false;
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalReportDetails').unblock();
    });
}
