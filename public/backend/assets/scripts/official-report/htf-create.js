let general_cargo = null;
$('#invoice_number').keyup(delay(function (e) {

    let invoice_number = $(this).val().replaceAll('_', '').trim();
    if (invoice_number == '')
        return false;

    if (invoice_number.length < 8)
        return false;

    $('#tracking_no').val('');
    getCargo();
}, 1000));

$('#tracking_no').keyup(delay(function (e) {

    let tracking_no = $(this).val().replaceAll('_', '').replaceAll(' ', '');

    if (tracking_no == '')
        return false;

    if (tracking_no.length < 15)
        return false;

    $('#invoice_number').val('');
    getCargo();
}, 1000));

function getCargo() {

    $('#rowCargoInfo').block({
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

    $.ajax('/MainCargo/AjaxTransactions/GetCargoInfo', {
        method: 'POST',
        data: {
            _token: token,
            invoice_number: $('#invoice_number').val(),
            tracking_number: $('#tracking_no').val(),
        }
    }).done(function (response) {

        if (response.status == 0) {
            $('.cargo-information').text('-');
            ToastMessage('error', response.message, 'Hata!');
            general_cargo = null;
        } else if (response.status == 1) {

            general_cargo = response;

            let cargo = response.cargo;
            let arrival = response.arrival;
            let arrival_tc = response.arrival_tc;
            let departure = response.departure;
            let departure_tc = response.departure_tc;


            $('b#invoice_number').text(cargo.invoice_number);
            $('b#tracking_no').text(cargo.tracking_no);

            $('b#sender_name').text(cargo.sender_name);
            $('b#receiver_name').text(cargo.receiver_name);

            $('b#departure_branch').text(departure.city + "-" + departure.agency_name + " ŞUBE");
            $('b#departure_tc').text(departure_tc.tc_name + " TRM.");
            $('b#arrival_branch').text(arrival.city + "-" + arrival.agency_name + " ŞUBE");
            $('b#arrival_tc').text(arrival_tc.tc_name + " TRM.");

            $('b#cargo_type').text(cargo.cargo_type);
            $('b#number_of_pieces').text(cargo.number_of_pieces);
            $('b#desi').text(cargo.desi);
            $('b#status').text(cargo.status);
            $('b#total_price').text(cargo.total_price + " ₺");

        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#rowCargoInfo').unblock();
    });
}

$('#reported_unit_type').change(function () {

    if (general_cargo == null) {
        $(this).val('');
        ToastMessage('error', 'Önce fatura numarasını veya takip numarasını giriniz!', 'HATA!');
        return false;
    }

    switch ($(this).val()) {

        case '':
            $('#reported_unit').val('');
            break;

        case  'Çıkış Şube':
            $('#reported_unit').val("#" + general_cargo.departure.agency_code + " " + general_cargo.departure.city + "-" + general_cargo.departure.agency_name + " ŞUBE");
            break;

        case  'Çıkış TRM.':
            $('#reported_unit').val(general_cargo.departure_tc.tc_name + " TRM.");
            break;

        case  'Varış Şube':
            $('#reported_unit').val("#" + general_cargo.arrival.agency_code + " " + general_cargo.arrival.city + "-" + general_cargo.arrival.agency_name + " ŞUBE");
            break;

        case  'Varış TRM.':
            $('#reported_unit').val(general_cargo.arrival_tc.tc_name + " TRM.");
            break;

        default:
            ToastMessage('error', 'Lütfen geçerli bir birim tipi seçin!', 'Hata!');

    }


});








