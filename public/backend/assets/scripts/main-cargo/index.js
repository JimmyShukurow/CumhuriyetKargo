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
            targets: 0
        }],
        lengthMenu: dtLengthMenu,
        order: [22, 'desc'],
        language: dtLanguage,
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
        select: {
            style: 'multi',
            selector: 'td:nth-child(1)'
        },
        buttons: [
            {
                extend: 'selectAll',
                text: 'Tüm. Seç'
            },
            {
                extend: 'selectNone',
                text: 'Tüm. Bırak'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22]
                },
                title: "CK - Kesilen Kargolar",
                attr: {
                    class: 'btn btn-success'
                }
            },
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                },
                attr: {
                    id: 'datatableRefreshBtn'
                },
                attr: {
                    class: 'btn btn-primary'
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
                },
            },
            {
                extend: 'colvis',
                text: 'Sütunlar',
                attr: {
                    class: 'btn btn-alternate'
                }
            },
        ],
        responsive: false,
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
            {data: 'check', name: 'check'},
            {data: 'invoice_number', name: 'invoice_number'},
            {data: 'tracking_no', name: 'tracking_no'},
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
        ],
        // scrollY: '450px',
        scrollX: true,
    });

    $('#selectedExcelBtn').hide();

    // Local Storage Transaction START
    let cargoSuccees = localStorage.getItem('cargo-success');
    if (cargoSuccees) {
        swal('İşlem Başarılı!', 'Kargo Oluşturuldu!', 'success');
        localStorage.clear();
    }
    // Local Storage Transaction END

    $('.dataTables_scrollBody').addClass('mostly-customized-scrollbar');

    $('#tableCheckAll').click(function () {

        if ($(this).prop('checked') == true) {
            $('.buttons-select-all').trigger('click');
        } else {
            $('.buttons-select-none').trigger('click');
        }
    })

    $('.buttons-select-all').hide();
    $('.buttons-select-none').hide();


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


$(document).on('click', '.print-all-barcodes', function () {
    alert('test');
});


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

$(document).on('click', '#btnCargoPrintBarcode', function () {

    // window.location = "ckg-barcoder:TestData";
    //
    // return false;

    let tracking_no = $(this).attr('tracking-no');

    let preparedBarcodCount = 0;
    $('#ModalShowBarcode').modal();

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

            let invoiceNumber = cargo.invoice_number;
            let barcodePaymentType = "";

            let delivery = "";

            if (cargo.home_delivery == 1)
                delivery = 'AT';
            else
                delivery = 'ŞT';

            if (cargo.payment_type == "Alıcı Ödemeli")
                barcodePaymentType = 'AÖ';
            else if (cargo.payment_type == "Gönderici Ödemeli")
                barcodePaymentType = 'GÖ';
            else if (cargo.payment_type == "PÖCH")
                barcodePaymentType = 'PÖCH';

            barcodePaymentType += " / " + delivery;

            $('#ContainerBarcodes').html('');

            let className = "", elementStyle = "", cumhuriyetCargoType = "";
            $.each(part_details, function (key, val) {

                preparedBarcodCount++;

                if (loop == 1) {
                    className = "barcode-row-last-child";
                    elementStyle = "margin-top: -2px";
                } else if (loop == preparedBarcodCount) {
                    elementStyle = "";
                    className = "barcode-row-first-child";
                } else
                    className = "";

                if (loop > 1)
                    if (loop != preparedBarcodCount)
                        elementStyle = "margin-top: 90px;";
                    else
                        elementStyle = "margin-top: -2px";

                if (cargo.collectible == 1)
                    cumhuriyetCargoType = "TAHSİLATLI";
                else
                    cumhuriyetCargoType = "STANDART"

                designBarcodes(elementStyle, className, departure, departure_tc, barcodePaymentType, invoiceNumber, cargo, val, arrival_tc, arrival, cumhuriyetCargoType);

                // D@56@HI@ECVHLDEOIIAB5S@
                makeBarcodeCode39('#barcodeCode39-' + val['part_no'], val['barcode_no'], cargo.tracking_no + "-" + val['part_no']);
                makeBarcodeQRCode('qrcode-' + val['part_no'], val['barcode_no']);
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

function designBarcodes(elementStyle, className, departure, departure_tc, barcodePaymentType, invoiceNumber, cargo, val, arrival_tc, arrival, cumhuriyetCargoType) {

    let slogan = "Cumhuriyet Kargo - Sevgi ve Değer Taşıyoruz..";


    let departureAgencyName = departure.agency_name;
    let departureTcName = departure_tc.tc_name + " TRM.";

    let arrivalTcName = arrival_tc == null ? '' : arrival_tc.tc_name + " TRM.";
    let arrivalAgencyName = arrival == null ? '' : arrival.agency_name;
    cumhuriyetCargoType = "CUMHURİYET " + cumhuriyetCargoType + " KARGO";


    if (cargo.transporter != 'CK') {
        slogan = "";
        departureAgencyName = "";
        departureTcName = "";
        cumhuriyetCargoType = "";
    }


    $('#ContainerBarcodes').prepend('<div style="' + elementStyle + '" class="row barcode-row ' + className + '">\n' +
        '                            <div class="col-6">\n' +
        '                                <h5 class="font-weight-bold barcode-slogan">' + slogan + '\n' +
        '                                    </h5>\n' +
        '                                <h4 class="font-weight-bold  text-dark m-0 barcodeDepartureTC">' + departureTcName + '</h4>\n' +
        '                                <b class="barcodeDepartureAgency">' + departureAgencyName + '</b>\n' +
        '                            </div>\n' +
        '\n' +
        '                            <div class="col-6">\n' +
        '                                <h3 class="p-0 m-0 invoiceNumber font-weight-bold">' + invoiceNumber + '</h3>\n' +
        '                                <h6 class="m-0 labelTrackingNo">Gönderi No: <b class="barcodeTrackingNo">\n' +
        '                                        ' + cargo.tracking_no + '</b>\n' +
        '                                </h6>\n' +
        '                            </div>\n' +
        '\n' +
        '                            <div class="col-9 p-0">\n' +
        '                                <table class="shipmentReceiverInfo">\n' +
        '                                    <tr>\n' +
        '                                        <td class="barcode-mini-text text-center font-weight-bold vertical-rl">GÖN</td>\n' +
        '                                        <td>\n' +
        '                                            <p class="barcode-mini-text p-1 m-0 font-weight-bold barcodeSenderName">\n' +
        '                                                ' + cargo.sender_name + '</p>\n' +
        '                                            <p class="barcodeReceiverAddress barcode-mini-text p-1 m-0 font-weight-bold">\n' +
        '                                                ' + cargo.sender_address + '</p>\n' +
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
        '                                    ' + arrivalTcName + '</h3>\n' +
        '                            </div>\n' +
        '\n' +
        '                            <div style="height: 100px;" class="col-12 code39-container">\n' +
        '                                <svg id="barcodeCode39-' + val['part_no'] + '" class="barcode"></svg>\n' +
        '                            </div>\n' +
        '\n' +
        '                            <div class="col-12 text-right">\n' +
        '                                <b class="barcodeArrivalAgency color-black">' + arrivalAgencyName + '</b>\n' +
        '                                            <p class="barcode-mini-text p-1 m-0 font-weight-bold barcodeSenderName barcodePaymentType">\n' +
        '                                                ' + barcodePaymentType + '</p>\n' +
        '                            </div>\n' +
        '                                <b style="position: relative; left: -130px; top: -200px; height: 2px;" class="vertical-rl">' + cumhuriyetCargoType + '</b>\n' +
        '\n' +
        '                        </div>\n' +
        '<div style="clear: both;" class="barcode-divider"></div>'
    );
}


$('#btnPrintSelectedBarcodeWCKGBarcoder').click(function () {
    let cargoesDataTable = $('#CargoesTable').DataTable();
    let selectedItems = cargoesDataTable.rows({selected: true}).data();

    if (selectedItems.length == 0) {
        ToastMessage('error', 'Lütfen barkodu basılacak kargoları seçin!', 'Hata!')
        return false;
    }

    let ids = "";
    $.each(selectedItems, function (key, val) {
        ids += val['id'] + ",";
    });

    console.log("ckg-barcoder:v=CreateMultipleBarcode&key=" + $(this).attr('crypted-data') + "[TESLA]" + ids)
    window.location = "ckg-barcoder:v=CreateMultipleBarcode&key=" + $(this).attr('crypted-data') + "[TESLA]" + ids;

});

$('#btnPrintSelectedBarcode').click(function () {

    let cargoesDataTable = $('#CargoesTable').DataTable();
    let selectedItems = cargoesDataTable.rows({selected: true}).data();

    if (selectedItems.length == 0) {
        ToastMessage('error', 'Lütfen barkodu basılacak kargoları seçin!', 'Hata!')
        return false;
    }

    let ids = "";
    $.each(selectedItems, function (key, val) {
        ids += val['id'] + ",";
    });

    let preparedBarcodCount = 0;
    $('#ModalShowBarcode').modal();

    $('#ContainerBarcodes').html('');

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

    $.ajax('/MainCargo/AjaxTransactions/GetMultipleCargoInfo', {
        method: 'POST',
        data: {
            _token: token,
            id: ids,
        }
    }).done(function (response) {


        let total_part_count = response.total_count;

        $.each(response.cargoes, function (key, cargoX) {

            let cargo = cargoX.cargo;
            let sender = cargoX.sender;
            let receiver = cargoX.receiver;
            let creator = cargoX.creator;
            let departure = cargoX.departure;
            let departure_tc = cargoX.departure_tc;
            let arrival = cargoX.arrival;
            let arrival_tc = cargoX.arrival_tc;
            let sms = cargoX.sms;
            let add_services = cargoX.add_services;
            let part_details = cargoX.part_details;

            let loop = cargo.number_of_pieces;
            console.log("Loop => " + loop);


            let invoiceNumber = cargo.invoice_number;
            let barcodePaymentType = "";

            let delivery = "";

            if (cargo.home_delivery == 1)
                delivery = 'AT';
            else
                delivery = 'ŞT';

            if (cargo.payment_type == "Alıcı Ödemeli")
                barcodePaymentType = 'AÖ';
            else if (cargo.payment_type == "Gönderici Ödemeli")
                barcodePaymentType = 'GÖ';
            else if (cargo.payment_type == "PÖCH")
                barcodePaymentType = 'PÖCH';

            barcodePaymentType += " / " + delivery;

            let className = "", elementStyle = "", cumhuriyetCargoType = "";
            $.each(part_details, function (key, val) {

                preparedBarcodCount++;

                if (preparedBarcodCount != total_part_count)
                    elementStyle = "margin-top: 90px;";
                else
                    elementStyle = "";


                if (loop == 1) {
                    className = "barcode-row-last-child";
                    elementStyle = "margin-top: -2px";
                } else if (loop == preparedBarcodCount) {
                    elementStyle = "";
                    className = "barcode-row-first-child";
                } else
                    className = "";


                if (cargo.collectible == 1)
                    cumhuriyetCargoType = "TAHSİLATLI";
                else
                    cumhuriyetCargoType = "STANDART"

                designBarcodes(elementStyle, className, departure, departure_tc, barcodePaymentType, invoiceNumber, cargo, val, arrival_tc, arrival, cumhuriyetCargoType);

                makeBarcodeCode39('#barcodeCode39-' + val['part_no'], val['barcode_no'], cargo.tracking_no + "-" + val['part_no']);
                makeBarcodeQRCode('qrcode-' + val['part_no'], val['barcode_no']);
            });

        });


    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBarcodes').unblock();
        $('#modalBarcodeFooterLabel').html("<b>" + preparedBarcodCount + "</b> adet barkod hazırlandı.");
    });

});





