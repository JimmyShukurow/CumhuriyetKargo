var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {
    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 25,
        lengthMenu: dtLengthMenu,
        order: [
            10, 'desc'
        ],
        language: dtLanguage,
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
        buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                title: "CK - Torba & Çuvallarınız"
            },
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                }
            },
        ],
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/CargoBags/Agency/GetCargoBags',
            data: function (d) {
                d.startDate = $('#startDate').val()
                d.endDate = $('#endDate').val()
                d.creatorUser = $('#creatorUser').val()
                d.dateFilterStatus = $('#filter-by-time').prop('checked')
            },
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                }
            }
        },
        columns: [
            {data: 'tracking_no', name: 'tracking_no'},
            {data: 'type', name: 'type'},
            {data: 'included_cargo_count', name: 'included_cargo_count'},
            {data: 'status', name: 'status'},
            {data: 'name_surname', name: 'name_surname'},
            {data: 'departure_branch_name', name: 'departure_branch_name'},
            {data: 'arrival_branch_name', name: 'arrival_branch_name'},
            {data: 'last_opener', name: 'last_opener'},
            {data: 'last_opening_date', name: 'last_opening_date'},
            {data: 'is_opened', name: 'is_opened'},
            {data: 'created_at', name: 'created_at'},
            {data: 'edit', name: 'edit'},
        ],
        scrollY: "400px",
    })
})


$('#search-form').on('submit', function (e) {
    oTable.draw();
    e.preventDefault();
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

// parse a date in yyyy-mm-dd format
function dateFormat(date) {
    date = String(date);
    let text = date.substring(0, 10);
    let time = date.substring(19, 8);
    time = time.substring(3, 11);
    let datetime = text + " " + time;
    return datetime;
}

$('#btnCreateNewBag').click(function () {
    $('#bag_type').val('');
    $('#modalCreateBag').modal();
});

$(document).on('click', '#btnInsertBag', function () {
    $('#modalBodyCreateBag').block({
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

    $('#btnInsertBag').prop('disabled', true);
    $.ajax('/CargoBags/Agency/CreateBag', {
        method: 'POST',
        data: {
            _token: token,
            bag_type: $('#bag_type').val(),
            arrivalBranchType: $('#arrivalBranchType').val(),
            arrivalBranchId: $('#arrivalBranchType').val() == 'Acente' ? $('#arrivalAgency').val() : $('#arrivalTc').val()
        }
    }).done(function (response) {

        if (response.status == 0)
            ToastMessage('error', response.message, 'Hata!');
        else if (response.status == 1) {
            $('#modalCreateBag').modal('hide');
            ToastMessage('success', '', 'İşlem başarılı, olutşruldu!');
            oTable.ajax.reload();
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#modalBodyCreateBag').unblock();
        $('#btnInsertBag').prop('disabled', false);
    });
});

var detail_id = null;

$(document).on('click', '.bag-details', function () {
    let bag_id = $(this).prop('id');
    detail_id = bag_id;
    getBagDetails(bag_id);
});


function getBagDetails(bag_id) {

    $('#modalBagDetails').modal();

    $('#modalBodyBagDetails').block({
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

    $.ajax('/CargoBags/Agency/GetBagInfo', {
        method: 'POST',
        data: {
            _token: token,
            bag_id: bag_id,
        }
    }).done(function (response) {

        if (response.status == 0)
            ToastMessage('error', response.message, 'Hata!');
        else if (response.status == 1) {


            let bag = response.bag;
            let bag_details = response.bag_details;
            let number_of_cargoes = response.number_of_cargoes;

            $('#modalBagDetailHeader').text("#" + bag.tracking_no + " - " + bag.type + " DETAYLARI");

            $('#tbodyBagDetails').html('');

            if (bag_details.length == 0) {
                $('#tbodyBagDetails').html(' <tr><td class="font-weight-bold text-danger text-center" colspan="8">Burda hiç veri yok!</td></tr>');
                $('#numberOfCargoesInBag').css('display', 'none');
            } else {
                $.each(bag_details, function (key, val) {
                    $('#tbodyBagDetails').append('<tr>' +
                        '<td class="font-weight-bold">' + (val['invoice_number']) + '</td>' +
                        '<td>' + (val['part_no']) + '</td>' +
                        '<td>' + (val['cargo_type']) + '</td>' +
                        '<td>' + (val['receiver_name']) + '</td>' +
                        '<td>' + (val['sender_name']) + '</td>' +
                        '<td>' + (val['arrival_city'] + '/' + val['arrival_district']) + '</td>' +
                        '<td>' + (val['name_surname']) + '</td>' +
                        '<td>' + (val['created_at']) + '</td>' +
                        '</tr>');
                });

                $('#numberOfCargoesInBag').css('display', 'block').html('Toplam ' + number_of_cargoes + ' kargo');

            }
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#modalBodyBagDetails').unblock();
    });
}

$(document).on('click', '#btnRefreshBagDetails', function () {
    getBagDetails(detail_id);
});

$(document).on('click', '.print-bag-barcode', function () {

    let bag_id = $(this).attr('id');

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
            '                                <div class="bg-white"></div>\n' +
            '                            </div>\n' +
            '                        </div>')
    });
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/CargoBags/GetBagGeneralInfo', {
        method: 'POST',
        data: {
            _token: token,
            id: bag_id,
        }
    }).done(function (response) {

        if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!');
        } else if (response.status == 1) {

            let bag = response.bag_info;

            $('#barcodeTrackingNo').text(bag.design_tracking_no);
            $('#barcodeCreatedAt').text(bag.created_at);
            $('.barcodeBagType').text(bag.type);
            $('.barcodeDepartureTC').text(bag.departure_point);
            $('.barcodeArrivalTC').text(bag.arrival_point);

            // D@56@HI@ECVHLDEOIIAB5S@
            makeBarcodeCode39('#barcodeCode39', bag.crypted_no, "" + bag.tracking_no + "");
            makeBarcodeQRCode('qrcode', bag.crypted_no);
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBarcodes').unblock();
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


$(document).on('change', '#arrivalBranchType', function () {
    if ($(this).val() == 'Acente') {
        $('#divArrivalTc').hide()
        $('#divArrivalAgency').show()
    } else if ($(this).val() == 'Aktarma') {
        $('#divArrivalTc').show()
        $('#divArrivalAgency').hide()
    }
})
