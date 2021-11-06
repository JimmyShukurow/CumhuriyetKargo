$(document).on('change', '#receiverCity', function () {
    getDistricts('#receiverCity', '#receiverDistrict');
});

$(document).on('change', '#senderCity', function () {
    getDistricts('#senderCity', '#senderDistrict');
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
        order: [20, 'desc'],
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
            url: '/Reports/GetOutcomingCargoes',
            data: function (d) {
                d.trackingNo = $('#trackingNo').val();
                d.invoiceNumber = $('#invoice_number').val();
                d.cargoType = $('#cargoType').val();
                d.receiverCurrentCode = $('#receiverCurrentCode').val();
                d.senderCurrentCode = $('#senderCurrentCode').val();
                d.startDate = $('#startDate').val();
                d.finishDate = $('#finishDate').val();
                d.receiverName = $('#receiverName').val();
                d.receiverCity = $('option:selected', '#receiverCity').attr('data') == undefined ? '' : $('option:selected', '#receiverCity').attr('data');
                d.receiverDistrict = $('#receiverDistrict').val();
                d.receiverPhone = $('#receiverPhone').val();
                d.senderName = $('#senderName').val();
                d.senderCity = $('option:selected', '#senderCity').attr('data') == undefined ? '' : $('option:selected', '#senderCity').attr('data');
                d.senderDistrict = $('#senderDistrict').val();
                d.senderPhone = $('#senderPhone').val();
                d.filterByDAte = $('#filterByDate').prop('checked');
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
            {data: 'invoice_number', name: 'invoice_number'},
            {data: 'tracking_no', name: 'tracking_no'},
            {data: 'agency_name', name: 'agency_name'},
            {data: 'sender_name', name: 'sender_name'},
            {data: 'sender_city', name: 'sender_city'},
            {data: 'receiver_name', name: 'receiver_name'},
            {data: 'receiver_city', name: 'receiver_city'},
            {data: 'receiver_district', name: 'receiver_district'},
            {data: 'receiver_address', name: 'receiver_address'},
            {data: 'cargo_type', name: 'cargo_type'},
            {data: 'payment_type', name: 'payment_type'},
            {data: 'total_price', name: 'total_price'},
            {data: 'collectible', name: 'collectible'},
            {data: 'collectible', name: 'collectible'},
            {data: 'collection_fee', name: 'collection_fee'},
            {data: 'status', name: 'status'},
            {data: 'status_for_human', name: 'status_for_human'},
            {data: 'transporter', name: 'transporter'},
            {data: 'system', name: 'system'},
            {data: 'created_at', name: 'created_at'},
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

$(document).on('click', '#btnConfirmCurrent', function () {
    $('#btnConfirmCurrent').prop('disabled', true);

    $.ajax('/SenderCurrents/AjaxTransaction/ConfirmCurrent', {
        method: 'POST',
        data: {
            _token: token,
            currentID: detailsID
        }
    }).done(function (response) {

        if (response.status == -1)
            ToastMessage('error', response.message, '');
        else if (response.status == 1) {
            ToastMessage('success', 'İşlem başarılı, cari hesabı onaylandı!', 'İşlem Başarılı!');
            userInfo(detailsID);
            $('#divConfirmCurrent').hide();
        }


        $('.modalEnabledDisabled.modal-body').unblock();
    }).error(function (jqXHR, response) {
        ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
    }).always(function () {
        $('#btnConfirmCurrent').prop('disabled', false);
    });

});

$(document).on('click', '#btnEnabledDisabled', function () {
    // alert(detailsID);
    $('#modalEnabledDisabled').modal();

    $('#modalBodyEnabledDisabled.modalEnabledDisabled.modal-body').block({
        message: $('<div class="loader mx-auto">\n' +
            '                            <div class="ball-pulse-sync">\n' +
            '                                <div class="bg-warning"></div>\n' +
            '                                <div class="bg-warning"></div>\n' +
            '                                <div class="bg-warning"></div>\n' +
            '                            </div>\n' +
            '                        </div>')
    });
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');


    $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
        method: 'POST',
        data: {
            _token: token,
            currentID: detailsID
        }
    }).done(function (response) {

        $('#userNameSurname').val(response.current.name);
        $('#accountStatus').val(response.current.status);

        $('.modalEnabledDisabled.modal-body').unblock();
    });
});

$(document).on('click', '#btnSaveStatus', function () {

    ToastMessage('warning', 'İstek alındı, lütfen bekleyiniz.', 'Dikkat!');
    $.ajax('/SenderCurrents/AjaxTransaction/ChangeStatus', {
        method: 'POST',
        data: {
            _token: token,
            currentID: detailsID,
            status: $('#accountStatus').val(),
        }
    }).done(function (response) {
        if (response.status == 1) {

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

            userInfo(detailsID);
            ToastMessage('success', 'Değişiklikler başarıyla kaydedildi.', 'İşlem Başarılı!');
            $('#modalEnabledDisabled').modal('toggle');
        } else if (response.status == 0) {
            ToastMessage('error', response.description, 'Hata!');
        } else if (response.status == -1) {
            response.errors.status.forEach(key =>
                ToastMessage('error', key, 'Hata!')
            );
        }

        return false;
    }).error(function (jqXHR, response) {

        ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
    });
});
$(document).on('click', '#btnCurrentPerformanceReport', function () {
    ToastMessage('warning', 'Cari performans raporu çok yakında!', 'Bilgi');
});

$(document).on('click', '#btnPrintModal', function () {
    printWindow('#ModalBodyUserDetail', "CK - " + $('#agencyName').text());
});

$(document).on('dblclick', '.main-cargo-tracking_no', function () {
    let tracking_no = $(this).attr('tracking-no')
    let id = $(this).prop('id')
    detailsID = id;
    copyToClipBoard(tracking_no);
    SnackMessage('Takip numarası kopyalandı!', 'info', 'bl');
    cargoInfo(id);
});

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
            let part_details = response.part_details;

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
            if (sms.length == 0)
                $('#tbodySentMessages').html('<tr><td colspan="5" class="text-center">Burda hiç veri yok.</td></tr>');
            else
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

            let countCargoPart = 0, cargoDesiCount = 0;
            $('#tbodyCargoPartDetails').html('');
            if (part_details.length == 0)
                $('#tbodyCargoPartDetails').html('<tr><td colspan="8" class="text-center">Burda hiç veri yok.</td></tr>');
            else {
                $.each(part_details, function (key, val) {

                    $('#tbodyCargoPartDetails').prepend(
                        '<tr>' +
                        '<td class="font-weight-bold text-success">' + cargo.cargo_type + '</td>' +
                        '<td class="font-weight-bold">' + val['part_no'] + '</td>' +
                        '<td class="">' + val['width'] + '</td>' +
                        '<td class="">' + val['size'] + '</td>' +
                        '<td class="">' + val['height'] + '</td>' +
                        '<td class="">' + val['weight'] + '</td>' +
                        '<td class="font-weight-bold text-primary">' + val['desi'] + '</td>' +
                        '<td class="text-alternate">' + val['cubic_meter_volume'] + '</td>' +
                        +'</tr>'
                    );
                    countCargoPart = countCargoPart + 1;
                    cargoDesiCount = cargoDesiCount + parseInt(val['desi']);
                });

                $('#tbodyCargoPartDetails').prepend(
                    '<tr><td class="font-weight-bold text-center" colspan="8"> Toplam: <b class="text-primary">' + countCargoPart + ' Parça</b>, <b class="text-primary">' + cargoDesiCount + ' Desi</b>. </td></tr>'
                );
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


