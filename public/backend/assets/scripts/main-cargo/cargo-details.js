appointmentID = null;

// parse a date in yyyy-mm-dd format
function dateFormat(date) {
    date = String(date);
    let text = date.substring(0, 10);
    let time = date.substring(19, 8);
    time = time.substring(3, 11);
    let datetime = text + " " + time;
    return datetime;
}


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

    let url = '/MainCargo/AjaxTransactions/GetCargoInfo';
    if (typeOfJs == 'cancelled_cargo')
        url = '/MainCargo/AjaxTransactions/GetCancelledCargoInfo';
    else if (typeOfJs == 'admin_cancel_cargo')
        url = '/MainCargo/AjaxTransactions/GetAllCargoInfo';
    else if (typeOfJs == 'main_cargo')
        url = '/MainCargo/AjaxTransactions/GetCargoInfo';


    $.ajax(url, {
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
            }, 350);
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
            let movementsSecondary = response.movementsSecondary;
            let cancellations = response.cancellation_applications;
            let part_details = response.part_details;
            let official_reports = response.official_reports;

            $('#titleTrackingNo').text(cargo.tracking_no);

            $('#printBarcodeAllPieces').attr('crypted-data', cargo.crypte_invoice_no)

            if (cargo.deleted_at != null) {
                $('#titleTrackingNo').text($('#titleTrackingNo').text() + " ##SİLİNDİ##");
                $('#titleTrackingNo').addClass('text-warning');
                $('li#SetResult').hide();
                $('li#CargoRestore').show();
            } else {
                $('li#SetResult').show();
                $('li#CargoRestore').hide();
                $('#titleTrackingNo').removeClass('text-warning');
            }

            $('#cancelAppTrackingNo').val(cargo.tracking_no);

            $('#titleCargoInvoiceNumber').text(cargo.invoice_number);
            $('#senderTcknVkn').text(sender.tckn);

            $('#senderCurrentCode').text(sender.current_code);
            $('#senderCurrentCode').prop('id', sender.id);


            $('#senderCustomerType').text(sender.category);
            $('#senderNameSurname').text(cargo.sender_name);
            $('td#senderPhone').text(cargo.sender_phone);
            $('#senderCityDistrict').text(cargo.sender_city + "/" + cargo.sender_district);
            $('#senderNeighborhood').text(cargo.sender_neighborhood);
            $('#senderAddress').text(cargo.sender_address);

            $('#receiverTcknVkn').text(receiver.tckn);

            $('#receiverCurrentCode').text(receiver.current_code);
            $('#receiverCurrentCode').prop('id', receiver.id);

            $('#receiverCustomerType').text(receiver.category);
            $('#receiverNameSurname').text(cargo.receiver_name);
            $('td#receiverPhone').text(cargo.receiver_phone);
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


            if (arrival_tc == null)
                $('#arrivalTC').text(cargo.transporter);
            else
                $('#arrivalTC').text(arrival_tc.city + " - " + arrival_tc.tc_name + " TM");

            if (arrival == null)
                $('#arrivalBranch').text(cargo.transporter);
            else
                $('#arrivalBranch').text(arrival.city + "/" + arrival.district + " - " + arrival.agency_name + " (" + arrival.agency_code + ")");


            $('td#postServicesPrice').text(cargo.post_service_price + "₺");
            $('td#heavyLoadCarryingCost').text(cargo.heavy_load_carrying_cost + "₺");
            $('td#distance').text(cargo.distance + " KM");
            $('td#distancePrice').text(cargo.distance_price + "₺");
            $('td#kdv').text(cargo.kdv_price + "₺");
            $('td#addServiceFee').text(cargo.add_service_price + "₺");
            $('td#serviceFee').text(cargo.service_price + "₺");
            $('td#mobileServiceFee').text(cargo.mobile_service_price + "₺");
            $('td#totalFee').text(cargo.total_price + "₺");

            $('#PrintStatementOfResposibility').attr('href', '/MainCargo/StatementOfResponsibility/' + cargo.tracking_no);

            if (response.bag_tracking_no == null)
                $('#inBag').html('<b class="text-danger">HAYIR</b>')
            else
                $('#inBag').html('<b class="text-success">EVET</b> / <b style="color:#000;">REFERANS NO: ' + response.bag_tracking_no + '</b>')


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
            $('#tbodyCargoMovementsSecondary').html('');

            if (movements.length == 0)
                $('#tbodyCargoMovements').html('<tr><td colspan="5" class="text-center">Burda hiç veri yok.</td></tr>');
            else {
                $.each(movements, function (key, val) {

                    let result = val['number_of_pieces'] == val['current_pieces'] ? 'text-success' : 'text-danger';

                    $('#tbodyCargoMovements').append(
                        '<tr>' +
                        '<td>' + val['status'] + '</td>' +
                        '<td style="white-space: initial;">' + val['info'] + '</td>' +
                        '<td class="' + result + ' font-weight-bold">' + val['cargo']['number_of_pieces'] + '/' + val['part_no'] + '</td>' +
                        '<td>' + val['user']['name_surname'] + ' (' + val['user']['role']['display_name'] + ')</td>' +
                        '<td>' + val['created_time'] + '</td>' +
                        '<td><button group_id="' + val['group_id'] + '" class="btn btn-primary btn-xs btnMovementDetail" disabled>Detay</button></td>' +
                        +'</tr>'
                    );

                });

            }
            if (movementsSecondary.length == 0)
                $('#tbodyCargoMovementsSecondary').html('<tr><td colspan="5" class="text-center">Burda hiç veri yok.</td></tr>');
            else {
                $.each(movementsSecondary, function (key, val) {

                    let result = val['number_of_pieces'] == val['current_pieces'] ? 'text-success' : 'text-danger';

                    $('#tbodyCargoMovementsSecondary').append(
                        '<tr>' +
                        '<td>' + val['status'] + '</td>' +
                        '<td style="white-space: initial;">' + val['info'] + '</td>' +
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

                    let background = "";
                    if (val['id'] == appointmentID) {
                        background = "bg-warning";
                        $('#cancelAppReason').val(val['application_reason']);
                        $('#cancelAppResult').val('' + val['confirm'] + '');
                        $('#canelAppResultDescription').val(val['description']);
                    }

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
                        '<tr class="' + background + '">' +
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

            $('#tbodyCargoOfficialReports').html('');

            if (official_reports.length == 0)
                $('#tbodyCargoOfficialReports').html('<tr><td colspan="8" class="font-weight-bold text-center text-danger">Burda hiç veri yok.</td></tr>');
            else {

                $.each(official_reports, function (key, val) {

                    let background = "";
                    if (val['id'] == appointmentID) {
                        background = "bg-warning";
                        $('#cancelAppReason').val(val['application_reason']);
                        $('#cancelAppResult').val('' + val['confirm'] + '');
                        $('#canelAppResultDescription').val(val['description']);
                    }

                    val['approval_at'] = val['approval_at'] == null ? '' : val['approval_at'];
                    val['confirming_user_name_surname'] = val['confirming_user_name_surname'] == null ? '' : val['confirming_user_name_surname'];
                    val['confirming_user_display_name'] = val['confirming_user_display_name'] == null ? '' : ' (' + val['confirming_user_display_name'] + ')';

                    let confirm_status = '';

                    if (val['confirm'] == '0')
                        confirm_status = '<b class="text-info">' + 'Onay Bekliyor' + '</b>';
                    else if (val['confirm'] == '1')
                        confirm_status = '<b class="text-success">' + 'Onaylandı' + '</b>';
                    else if (val['confirm'] == '-1')
                        confirm_status = '<b class="text-danger">' + 'Onaylanmadı' + '</b>';

                    if (val['description'] == null)
                        val['description'] = "";

                    if (val['type'] == 'HTF')
                        report_type = '<b class="text-primary">HTF</b>'
                    else if (val['type'] == 'UTF')
                        report_type = '<b class="text-danger">UTF</b>'

                    $('#tbodyCargoOfficialReports').append(
                        '<tr>' +
                        '<td style="text-decoration: underline; cursor: pointer;" class="font-weight-bold cargo-report-serial-no" report-id="' + val['id'] + '"><b>' + val['report_serial_no'] + '</b></td>' +
                        '<td class="font-weight-bold">' + report_type + '</td>' +
                        '<td title="' + val['detecting_unit'] + '">' + val['detecting_unit'] + '</td>' +
                        '<td>' + val['name_surname'] + '</td>' +
                        '<td title="' + val['reported_unit'] + '">' + val['reported_unit'] + '</td>' +
                        '<td class="font-weight-bold" title="' + val['description'] + '">' + val['description'].substring(0, 20) + '</td>' +
                        '<td class="font-weight-bold text-center">' + confirm_status + '</td>' +
                        '<td class="font-weight-bold text-center">' + val['created_at'] + '</td>' +
                        +'</tr>'
                    )
                });
            }


            $('#btnCargoPrintBarcode').attr('tracking-no', cargo.id);


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

$(document).on('dblclick', '.cargo-report-serial-no', function () {
    getReportInfo($(this).attr('report-id'));
});

$(document).on('click', '#printBarcodeAllPieces', function () {
    window.location = "ckg-barcoder:" + $(this).attr('crypted-data');
})
