$('#btnExportExcel').click(function () {
    let count = oTable.rows({selected: true}).count();
    if (count > 0)
        $('#selectedExcelBtn').click();
    else
        ToastMessage('error', 'Lütfen excele aktarılacak satırları seçin!', 'Hata!');
});

var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {
    $('#agency').select2();
    $('#creatorUser').select2();

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
        order: [22, 'desc'],
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
            selector: 'td:nth-child(2)'
        },
        buttons: [
            {
                extend: 'selectAll',
                text: 'Tümünü Seç'
            },
            {
                extend: 'selectNone',
                text: 'Tümünü Bırak'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]
                },
                title: "CK - Kesilen Kargolar"
            },
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                },
                attr: {
                    id: 'datatableRefreshBtn'
                }
            },
            {
                extend: 'excel',
                text: 'Ex Akt',
                exportOptions: {
                    modifier: {
                        selected: true
                    },
                    columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]
                },
                attr: {
                    id: 'selectedExcelBtn'
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
            url: '/MainCargo/GetCargoes',
            data: function (d) {
                d.startDate = $('#startDate').val();
                d.finishDate = $('#finishDate').val();
                d.record = $('#record').val();
                d.paymentType = $('#paymentType').val();
                d.collectible = $('#collectible').val();
                d.trackingNo = $('#trackingNo').val();
                d.cargoType = $('#cargoType').val();
                d.status = $('#status').val();
                d.statusForHuman = $('#statusForHuman').val();
                d.transporter = $('#transporter').val();
                d.system = $('#system').val();
                d.invoice_number = $('#invoice_number').val();
                d.creatorUser = $('#creatorUser').val();
                d.cargoContent = $('#cargoContent').val();
                d.receiverCode = $('#receiverCode').val();
                d.receiverName = $('#receiverName').val();
                d.receiverCity = $('#receiverCity').val();
                d.currentCode = $('#currentCode').val();
                d.currentName = $('#currentName').val();
                d.currentCity = $('#currentCity').val();
            },
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    SnackMessage('Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'error', 'bl');
                }
            },
            complete: function () {
                SnackMessage('Tamamlandı!', 'info', 'bl');

                if ($('#datatableRefreshBtn').prop('disabled') == true)
                    $('#datatableRefreshBtn').prop('disabled', false);

            }
        },
        columns: [
            {data: 'free_btn', name: 'free_btn'},
            {data: 'check', name: 'check'},
            {data: 'tracking_no', name: 'tracking_no'},
            {data: 'invoice_number', name: 'invoice_number'},
            {data: 'sender_name', name: 'sender_name'},
            {data: 'sender_city', name: 'sender_city'},
            {data: 'receiver_name', name: 'receiver_name'},
            {data: 'receiver_city', name: 'receiver_city'},
            {data: 'receiver_district', name: 'receiver_district'},
            {data: 'cargo_type', name: 'cargo_type'},
            {data: 'payment_type', name: 'payment_type'},
            {data: 'total_price', name: 'total_price'},
            {data: 'number_of_pieces', name: 'number_of_pieces'},
            {data: 'kg', name: 'kg'},
            {data: 'cubic_meter_volume', name: 'cubic_meter_volume'},
            {data: 'collectible', name: 'collectible'},
            {data: 'collectible', name: 'collectible'},
            {data: 'collection_fee', name: 'collection_fee'},
            {data: 'status', name: 'status'},
            {data: 'status_for_human', name: 'status_for_human'},
            {data: 'transporter', name: 'transporter'},
            {data: 'system', name: 'system'},
            {data: 'name_surname', name: 'name_surname'},
            {data: 'created_at', name: 'created_at'},
            {data: 'edit', name: 'edit'},
        ],
        // scrollY: '450px',
        // scrollX: false,
    });

    $('#selectedExcelBtn').hide();

    // Local Storage Transaction START
    let cargoSuccees = localStorage.getItem('cargo-success');
    if (cargoSuccees) {
        swal('İşlem Başarılı!', 'Kargo Oluşturuldu!', 'success');
        localStorage.clear();
    }
    // Local Storage Transaction END

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

$(document).on('dblclick', '.main-cargo-tracking_no', function () {
    let tracking_no = $(this).attr('tracking-no')
    let id = $(this).prop('id')
    detailsID = id;
    copyToClipBoard(tracking_no);
    SnackMessage('Takip numarası kopyalandı!', 'info', 'bl');
    cargoInfo(id);
});

$(document).on('click', '.cargo-detail', function () {
    cargoInfo($(this).prop('id'));
});

var array = new Array();

$('#btnRefreshMainCargoPage').click(function () {

    SnackMessage('Yenileniyor', 'info', 'bl');

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

    setTimeout(function () {
        $.ajax('/MainCargo/AjaxTransactions/GetMainDailySummery', {
            method: 'POST',
            data: {
                _token: token
            }
        }).done(function (response) {
            $('#file_count').html(response.file_count);
            $('#package_count').html(response.package_count);
            $('#total_cargo_count').html(response.total_cargo_count);
            $('#total_desi').html(response.total_desi);
            $('#total_endorsement').html("₺" + response.total_endorsement);
            $('#total_number_of_pieces').html(response.total_number_of_pieces);
        }).error(function (jqXHR, exception) {
            ajaxError(jqXHR.status);
        }).always(function () {
            $('.app-main__inner').unblock();
            $('#CargoesTable').DataTable().ajax.reload();
        });
    }, 750);

});


function dateFormat(date) {
    date = String(date);
    let text = date.substring(0, 10);
    let time = date.substring(19, 8);
    time = time.substring(3, 11);
    let datetime = text + " " + time;
    return datetime;
}


function cargoInfo(user) {

    $('#ModalCargoDetails').modal();


    $('#ModalCargoDetails').block({
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

    $.ajax('/MainCargo/AjaxTransactions/GetCargoInfo', {
        method: 'POST',
        data: {
            _token: token,
            id: user
        },
        cache: false
    }).done(function (response) {

        if (response.status == 0) {
            setTimeout(function () {
                ToastMessage('error', response.message, 'Hata!');
                $('#ModalCargoDetails').modal('hide');
                $('#CargoesTable').DataTable().ajax.reload();
                return false;
            }, 250);
        } else if (response.status == 1) {

            let cargo = response.cargo;
            let sender = response.sender;
            let receiver = response.receiver;
            let creator = response.creator;
            let departure = response.departure;
            let departure_tc = response.departure_tc;
            let arrival = response.arrival;
            let arrival_tc = response.arrival_tc;
            let sms = response.sms;
            let add_services = response.add_services;
            let movements = response.movements;
            let cancellations = response.cancellation_applications;

            $('#titleTrackingNo').text(cargo.tracking_no);
            $('#titleCargoInvoiceNumber').text(cargo.invoice_number);
            $('#senderTcknVkn').text(sender.tckn);
            $('#senderCurrentCode').text(sender.current_code);
            $('#senderCustomerType').text(sender.category);
            $('#senderNameSurname').text(cargo.sender_name);
            $('#senderPhone').text(cargo.sender_phone);
            $('#senderCityDistrict').text(cargo.sender_city + "/" + cargo.sender_district);
            $('#senderNeighborhood').text(cargo.sender_neighborhood);
            $('#senderAddress').text(cargo.sender_address);

            $('#receiverTcknVkn').text(receiver.tckn);
            $('#receiverCurrentCode').text(receiver.current_code);
            $('#receiverCustomerType').text(receiver.category);
            $('#receiverNameSurname').text(cargo.receiver_name);
            $('#receiverPhone').text(cargo.receiver_phone);
            $('#receiverCityDistrict').text(cargo.receiver_city + "/" + cargo.receiver_district);
            $('#receiverNeighborhood').text(cargo.receiver_neighborhood);
            $('#receiverAddress').text(cargo.receiver_address);

            $('#cargoTrackingNo').text(cargo.tracking_no);
            $('#cargoCreatedAt').text(dateFormat(cargo.created_at));
            $('#numberOfPieces').text(cargo.number_of_pieces);
            $('#cargoKg').text(cargo.kg);
            $('#cubicMeterVolume').text(cargo.cubic_meter_volume);
            $('#desi').text(cargo.desi);
            $('td#cargoType').text(cargo.cargo_type);
            $('td#paymentType').text(cargo.payment_type);
            $('td#transporter').text(cargo.transporter);
            $('td#system').text(cargo.system);
            $('td#creatorUserInfo').text(creator.name_surname + " (" + creator.display_name + ")");
            $('td#customerCode').text(cargo.customer_code == null ? "" : cargo.customer_code);
            $('td#cargoStatus').text(cargo.status);
            $('td#cargoStatusForHumen').text(cargo.status_for_human);
            $('td#cargoContent').text(cargo.cargo_content);
            $('td#cargoContentEx').text(cargo.cargo_content_ex);

            $('td#collectible').text(cargo.collectible);
            $('#collection_fee').text(cargo.collection_fee + "₺");
            $('#exitTransfer').text(departure_tc.city + " - " + departure_tc.tc_name + " TM");
            $('#exitBranch').text(departure.city + "/" + departure.district + " - " + departure.agency_name + " (" + departure.agency_code + ")");
            $('#arrivalTC').text(arrival_tc.city + " - " + arrival_tc.tc_name + " TM");
            $('#arrivalBranch').text(arrival.city + "/" + arrival.district + " - " + arrival.agency_name + " (" + arrival.agency_code + ")");
            $('td#postServicesPrice').text(cargo.post_service_price + "₺");
            $('td#heavyLoadCarryingCost').text(cargo.heavy_load_carrying_cost + "₺");
            $('td#distance').text(cargo.distance + " KM");
            $('td#distancePrice').text(cargo.distance_price + "₺");
            $('td#kdv').text(cargo.kdv_price + "₺");
            $('td#addServiceFee').text(cargo.add_service_price + "₺");
            $('td#serviceFee').text(cargo.service_price + "₺");
            $('td#totalFee').text(cargo.total_price + "₺");

            $('#PrintStatementOfResposibility').attr('href', '/MainCargo/StatementOfResponsibility/' + cargo.tracking_no);

            var addServiceTotalPrice = 0;
            $('#tbodyCargoAddServices').html('');

            if (add_services.length == 0)
                $('#tbodyCargoAddServices').html('<tr><td colspan="2" class="text-center">Burda hiç veri yok.</td></tr>');
            else {
                $.each(add_services, function (key, val) {

                    let result = val['result'] == '1' ? '<b class="text-success">' + 'Başarılı' + '</b>' : '<b class="text-danger">' + 'Başarısız' + '</b>';

                    $('#tbodyCargoAddServices').append(
                        '<tr>' +
                        '<td>' + val['service_name'] + '</td>' +
                        '<td  class="font-weight-bold text-dark">' + val['price'] + "₺" + '</td>' +
                        +'</tr>'
                    );

                    addServiceTotalPrice += parseInt(val['price']);
                });

                $('#tbodyCargoAddServices').append(
                    '<tr>' +
                    '<td class="font-weight-bold text-primary">' + 'Toplam:' + ' </td>' +
                    '<td class="font-weight-bold text-primary">' + cargo.add_service_price + "₺" + '</td>' +
                    +'</tr>'
                );

            }

            $('#tbodyCargoMovements').html('');

            if (movements.length == 0)
                $('#tbodyCargoMovements').html('<tr><td colspan="5" class="text-center">Burda hiç veri yok.</td></tr>');
            else {
                $.each(movements, function (key, val) {

                    let result = val['number_of_pieces'] == val['current_pieces'] ? 'text-success' : 'text-danger';

                    $('#tbodyCargoMovements').append(
                        '<tr>' +
                        '<td>' + val['status'] + '</td>' +
                        '<td>' + val['info'] + '</td>' +
                        '<td class="' + result + ' font-weight-bold">' + val['number_of_pieces'] + '/' + val['current_pieces'] + '</td>' +
                        '<td>' + val['created_at'] + '</td>' +
                        '<td><button group_id="' + val['group_id'] + '" class="btn btn-primary btn-xs btnMovementDetail">Detay</button></td>' +
                        +'</tr>'
                    );

                });

            }

            $('#tbodySentMessages').html('');
            $.each(sms, function (key, val) {

                let result = val['result'] == '1' ? '<b class="text-success">' + 'Başarılı' + '</b>' : '<b class="text-danger">' + 'Başarısız' + '</b>';

                $('#tbodySentMessages').append(
                    '<tr>' +
                    '<td class="font-weight-bold">' + val['heading'] + '</td>' +
                    '<td class="font-weight-bold">' + val['subject'] + '</td>' +
                    '<td style="white-space: initial;">' + val['sms_content'] + '</td>' +
                    '<td>' + val['phone'] + '</td>' +
                    '<td class="font-weight-bold text-center">' + result + '</td>' +
                    +'</tr>'
                )
            });

            $('#tbodyCargoCancellationApplications').html('');

            if (cancellations.length == 0)
                $('#tbodyCargoCancellationApplications').html('<tr><td colspan="8" class="text-center">Burda hiç veri yok.</td></tr>');
            else {

                $.each(cancellations, function (key, val) {


                    val['approval_at'] = val['approval_at'] == null ? '' : val['approval_at'];
                    val['confirming_user_name_surname'] = val['confirming_user_name_surname'] == null ? '' : val['confirming_user_name_surname'];
                    val['confirming_user_display_name'] = val['confirming_user_display_name'] == null ? '' : ' (' + val['confirming_user_display_name'] + ')';

                    let confirm_status = '';

                    if (val['confirm'] == '0')
                        confirm_status = '<b class="text-info">' + 'Sonuç Bekliyor' + '</b>';
                    else if (val['confirm'] == '1')
                        confirm_status = '<b class="text-success">' + 'Onaylandı' + '</b>';
                    else if (val['confirm'] == '-1')
                        confirm_status = '<b class="text-danger">' + 'Reddedildi' + '</b>';

                    if (val['description'] == null)
                        val['description'] = "";

                    $('#tbodyCargoCancellationApplications').append(
                        '<tr>' +
                        '<td class="font-weight-bold">' + cargo.tracking_no + '</td>' +
                        '<td class="font-weight-bold">' + val['name_surname'] + " (" + val['display_name'] + ")" + '</td>' +
                        '<td title="' + val['application_reason'] + '">' + val['application_reason'].substring(0, 35) + '</td>' +
                        '<td>' + confirm_status + '</td>' +
                        '<td title="' + val['description'] + '">' + val['description'].substring(0, 20) + '</td>' +
                        '<td class="font-weight-bold">' + val['confirming_user_name_surname'] + val['confirming_user_display_name'] + '</td>' +
                        '<td class="font-weight-bold text-center">' + val['approval_at'] + '</td>' +
                        '<td class="font-weight-bold text-center">' + val['created_at'] + '</td>' +
                        +'</tr>'
                    )
                });
            }


            $('#btnCargoPrintBarcode').attr('tracking-no', cargo.id);

            // $('#numberOfPieces').text(cargo.number_of_pieces);
            // $('#numberOfPieces').text(cargo.number_of_pieces);
            // $('#numberOfPieces').text(cargo.number_of_pieces);
            // $('#numberOfPieces').text(cargo.number_of_pieces);
            // $('#numberOfPieces').text(cargo.number_of_pieces);
            // $('#numberOfPieces').text(cargo.number_of_pieces);

        }

        $('#ModalCargoDetails').unblock();
        return false;
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalCargoDetails').unblock();
    });

    $('#ModalAgencyDetail').modal();
}

$(document).on('click', '.btnMovementDetail', function () {

    let group_id = $(this).attr('group_id');

    $('#ModalMovementsDetail').modal();

    $('#modalBodyCargoMovementsDetails').block({
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


    $.ajax('/MainCargo/AjaxTransactions/GetCargoMovementDetails', {
        method: 'POST',
        data: {
            _token: token,
            group_id: group_id
        }
    }).done(function (response) {

        $('#tbodyCargoMovementDetails').html('');

        if (response.length == 0)
            $('#tbodyCargoMovementDetails').html('<tr><td colspan="5" class="text-center">Burda hiç veri yok.</td></tr>');
        else {
            $.each(response, function (key, val) {

                let result = val['number_of_pieces'] == val['current_pieces'] ? 'text-success' : 'text-danger';

                $('#tbodyCargoMovementDetails').append(
                    '<tr>' +
                    '<td>' + val['status'] + '</td>' +
                    '<td>' + val['info'] + '</td>' +
                    '<td class="text-dark font-weight-bold">' + val['part_no'] + '</td>' +
                    '<td>' + val['created_at'] + '</td>' +
                    +'</tr>'
                );

            });

        }


    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#modalBodyCargoMovementsDetails').unblock();
    });

});


$('#btnPrintSelectedBarcode').click(function () {
    $('#ModalShowBarcode').modal();
});

$(document).on('click', '.print-all-barcodes', function () {
    alert('test');
});

function sayHello() {
    alert('hello');
}


$(document).on('click', '#btnCargoPrintBarcode', function () {

    let preparedBarcodCount = 0;
    $('#ModalShowBarcode').modal();

    let tracking_no = $(this).attr('tracking-no');

    $('#ModalBarcodes').block({
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
            '                                 <div class="bg-white"></div>\n' +
            '                            </div>\n' +
            '                        </div>')
    });
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $('#modalBarcodeFooterLabel').html('Hazırlanıyor, lütfen bekleyiniz...');

    $.ajax('/MainCargo/AjaxTransactions/GetCargoInfo', {
        method: 'POST',
        data: {
            _token: token,
            id: tracking_no,
        }
    }).done(function (response) {

        if (response.status == 1) {

            let cargo = response.cargo;
            let sender = response.sender;
            let receiver = response.receiver;
            let creator = response.creator;
            let departure = response.departure;
            let departure_tc = response.departure_tc;
            let arrival = response.arrival;
            let arrival_tc = response.arrival_tc;
            let sms = response.sms;
            let add_services = response.add_services;
            let part_details = response.part_details;

            let loop = cargo.number_of_pieces;
            console.log("Loop => " + loop);

            let barcodePaymentType = cargo.invoice_number + " ";
            if (cargo.payment_type == "Alıcı Ödemeli")
                barcodePaymentType += 'AÖ';
            else if (cargo.payment_type == "Gönderici Ödemeli")
                barcodePaymentType += 'GÖ';

            $('#ContainerBarcodes').html('');

            let className = "", elementStyle = "";
            $.each(part_details, function (key, val) {

                preparedBarcodCount++;

                if (loop == 1)
                    className = "barcode-row-last-child";

                else if (loop == preparedBarcodCount)
                    className = "barcode-row-first-child";

                else
                    className = "";

                if (loop > 1) {

                    if (loop != preparedBarcodCount)
                        elementStyle = "margin-top: 90px;";
                    else
                        elementStyle = "";

                }


                $('#ContainerBarcodes').prepend('<div style="' + elementStyle + '" class="row barcode-row ' + className + '">\n' +
                    '                            <div class="col-6">\n' +
                    '                                <h5 class="font-weight-bold barcode-slogan">Cumhuriyet Kargo - Sevgi ve Değer\n' +
                    '                                    Taşıyoruz..</h5>\n' +
                    '                                <h4 class="font-weight-bold  text-dark m-0 barcodeDepartureTC">' + departure_tc.tc_name + " TRM." + '</h4>\n' +
                    '                                <b class="barcodeDepartureAgency">' + departure.agency_name + '</b>\n' +
                    '                            </div>\n' +
                    '\n' +
                    '                            <div class="col-6">\n' +
                    '                                <h3 class="p-0 m-0 barcodePaymentType">' + barcodePaymentType + '</h3>\n' +
                    '                                <h6 class="m-0 labelTrackingNo">GönderiNo: <b class="barcodeTrackingNo">\n' +
                    '                                        ' + cargo.tracking_no + '</b>\n' +
                    '                                </h6>\n' +
                    '                                <b>ÜRÜN BEDELİ: <b class="barcodeCargoTotalPrice"></b></b>\n' +
                    '                            </div>\n' +
                    '\n' +
                    '                            <div class="col-9 p-0">\n' +
                    '                                <table class="shipmentReceiverInfo">\n' +
                    '                                    <tr>\n' +
                    '                                        <td class="barcode-mini-text text-center font-weight-bold vertical-rl">GÖN</td>\n' +
                    '                                        <td>\n' +
                    '                                            <p class="barcode-mini-text p-1 m-0 font-weight-bold barcodeSenderName">\n' +
                    '                                                ' + cargo.sender_name + '</p>\n' +
                    '                                            <p class="barcode-mini-text p-1 m-0 font-weight-bold">\n' +
                    '                                                <span id="barcodeSenderCityDistrict">' + cargo.sender_city + "/" + cargo.sender_district + '</span>\n' +
                    '                                                <span class="text-right barcodeSenderPhone">' + "TEL: " + cargo.sender_phone + '</span>\n' +
                    '                                            </p>\n' +
                    '                                        </td>\n' +
                    '                                        <td class="cargoInfo" rowspan="2">\n' +
                    '                                            <p class="barcodeRegDate font-weight-bold barcode-mini-text m-0">\n' +
                    '                                                ' + dateFormat(cargo.created_at).substring(0, 10) + '</p>\n' +
                    '                                            <p class="barcodeRegDate text-center font-weight-bold barcode-mini-text m-0">\n' +
                    '                                                ' + dateFormat(cargo.created_at).substring(11) + '</p>\n' +
                    '                                            <p class="barcodeCargoType m-0  barcode-mini-text font-weight-bolder">\n' +
                    '                                                ' + cargo.cargo_type + '</p>\n' +
                    '                                            <p class="m-0 font-weight-bold barcode-mini-text">Kg:' + val['weight'] + '</p>\n' +
                    '                                            <p class="m-0 font-weight-bold barcode-mini-text">Ds:' + val['desi'] + '</p>\n' +
                    '                                            <p class="m-0 font-weight-bold barcode-mini-text">Kg/Ds:' + val['desi'] + '</p>\n' +
                    '                                            <p class="m-0 font-weight-bold barcode-mini-text">Toplam:' + cargo.desi + '</p>\n' +
                    '                                            <p class="m-0 text-center font-weight-bold">' + cargo.number_of_pieces + '/' + val['part_no'] + '</p>\n' +
                    '                                        </td>\n' +
                    '                                    </tr>\n' +
                    '                                    <tr>\n' +
                    '                                        <td class="barcode-mini-text text-center font-weight-bold vertical-rl">ALICI\n' +
                    '                                        </td>\n' +
                    '                                        <td>\n' +
                    '                                            <p class="barcodeReceiverName barcode-mini-text p-1 m-0 font-weight-bold">\n' +
                    '                                                ' + cargo.receiver_name + '</p>\n' +
                    '\n' +
                    '                                            <p class="barcodeReceiverAddress barcode-mini-text p-1 m-0 font-weight-bold">\n' +
                    '                                                ' + cargo.receiver_address + '</p>\n' +
                    '                                            <p class="barcode-mini-text p-1 m-0 font-weight-bold">\n' +
                    '                                                <span class="barcodeReceiverCityDistrict">' + cargo.receiver_city + "/" + cargo.receiver_district + '</span>\n' +
                    '                                                <span class="barcodeReceiverPhone"\n' +
                    '                                                      class="text-right">' + "TEL: " + cargo.receiver_phone + '</span>\n' +
                    '                                            </p>\n' +
                    '                                        </td>\n' +
                    '                                    </tr>\n' +
                    '                                </table>\n' +
                    '                            </div>\n' +
                    '\n' +
                    '                            <div class="col-3 qr-barcode-cont">\n' +
                    '                                <div class="qrcodes" id="qrcode-' + val['part_no'] + '"></div>\n' +
                    '                            </div>\n' +
                    '\n' +
                    '                            <div class="col-12 text-right">\n' +
                    '                                <h3 class="font-weight-bold text-dark barcodeArrivalTC">\n' +
                    '                                    ' + arrival_tc.tc_name + " TRM." + '</h3>\n' +
                    '                            </div>\n' +
                    '\n' +
                    '                            <div class="col-12 code39-container">\n' +
                    '                                <svg id="barcodeCode39-' + val['part_no'] + '" class="barcode"></svg>\n' +
                    '                            </div>\n' +
                    '\n' +
                    '                            <div class="col-12 text-right">\n' +
                    '                                <b class="barcodeArrivalAgency color-black">' + arrival.agency_name + '</b>\n' +
                    '                            </div>\n' +
                    '                                <b style="position: relative; left: -130px; top: -214px; height: 2px;" class="vertical-rl">' + 'CUMHURİYET STANDART KARGO' + '</b>\n' +
                    '\n' +
                    '                        </div>\n' +
                    '<div style="clear: both;" class="barcode-divider"></div>'
                );

                // $('#barcodeDepartureTC').text(departure_tc.tc_name + " TRM.");
                // $('#barcodeDepartureAgency').text(departure.agency_name);
                // $('#barcodeTrackingNo').text(cargo.tracking_no);
                // $('#barcodeCargoTotalPrice').text(cargo.total_price + "₺");
                //
                // $('#barcodeArrivalTC').text(arrival_tc.tc_name + " TRM.");
                // $('#barcodeArrivalAgency').text(arrival.agency_name);
                //
                // $('#barcodeSenderName').text(cargo.sender_name);
                // $('#barcodeSenderCityDistrict').text(cargo.sender_city + "/" + cargo.sender_district);
                // $('#barcodeSenderPhone').text("TEL: " + cargo.sender_phone);
                //
                // $('#barcodeReceiverName').text(cargo.receiver_name);
                // $('#barcodeReceiverAddress').text(cargo.receiver_address);
                // $('#barcodeReceiverCityDistrict').text(cargo.receiver_city + "/" + cargo.receiver_district);
                // $('#barcodeReceiverPhone').text("TEL: " + cargo.receiver_phone);
                //
                // $('#barcodeRegDate').text(dateFormat(cargo.created_at).substring(0, 10));
                // $('#barcodeCargoType').text(cargo.cargo_type);
                //

                // $('#barcodePaymentType').text(barcodePaymentType);

                // D@56@HI@ECVHLDEOIIAB5S@
                makeBarcodeCode39('#barcodeCode39-' + val['part_no'], val['barcode_no'], cargo.tracking_no + " " + val['part_no']);
                makeBarcodeQRCode('qrcode-' + val['part_no'], val['barcode_no']);

                // makeBarcodeCode39('.barcode', "D@56@HI@ECVHLDEOIIAB5S@", cargo.tracking_no);
                // makeBarcodeQRCode('qrcode', "D@56@HI@ECVHLDEOIIAB5S@");
            });

        } else
            ToastMessage('error', response.message, 'Hata!');

    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBarcodes').unblock();
        $('#modalBarcodeFooterLabel').html("<b>" + preparedBarcodCount + "</b> adet barkod hazırlandı.");

    });

});

function makeBarcodeCode39(selector, val, text) {
    JsBarcode(selector, val, {
        textPosition: "none",
        text: "Referans No: " + text.replace(' ', '').replace(' ', ''),
    });
}

function makeBarcodeQRCode(selector, val) {

    $('#' + selector).html('');

    let qrcode = new QRCode(document.getElementById(selector), {
        width: 100,
        height: 100
    });

    function makeCode() {
        qrcode.makeCode(val);
    }

    makeCode();

    $("#text").on("blur", function () {
        makeCode();
    }).on("keydown", function (e) {
        if (e.keyCode == 13) {
            makeCode();
        }
    });
}

$(document).on('click', '#btnPrintBarcode', function () {
    printBarcode('#ModalBarcodes');
});

$(document).on('click', '#btnCargoCancel', function () {
    $('#ModalCargoCancelForm').modal();
});

$(document).on('change', '#reason', function () {
    $('#appointmentReason').val($(this).val());
});

$(document).on('click', '#btnMakeCargoCancelAppointment', function () {

    if ($('#appointmentReason').val() == '') {
        ToastMessage('error', 'Lütfen kargo iptal nedeni giriniz!', 'Hata!');
        return false;
    }

    if (detailsID == '' || detailsID == null) {
        ToastMessage('error', 'Kargo Seçilmedi!', 'Hata!');
        return false;
    }

    $('#btnMakeCargoCancelAppointment').prop('disabled', true);

    $('#modalBodyCargoCancelForm').block({
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
    $('.blockUI.blockMsg.blockElement').css('background-color', '');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');

    $.ajax('/MainCargo/AjaxTransactions/MakeCargoCancellationApplication', {
        method: 'POST',
        data: {
            _token: token,
            iptal_nedeni: $('#appointmentReason').val(),
            id: detailsID,
        }
    }).done(function (response) {

        if (response.status == -1) {
            ToastMessage('error', response.message, 'Hata!');
            return false;
        } else if (response.status == 1) {

            ToastMessage('success', 'Kargo iptal başvurusu oluşturuldu!', 'İşlem Başarılı!');
            $('#ModalCargoCancelForm').modal('hide');

            cargoInfo(detailsID);

        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        }

    }).error(function (jqXHR, exception) {

        ajaxError(jqXHR.status)

    }).always(function () {
        $('#btnMakeCargoCancelAppointment').prop('disabled', false);
        $('#modalBodyCargoCancelForm').unblock();
    });

});



