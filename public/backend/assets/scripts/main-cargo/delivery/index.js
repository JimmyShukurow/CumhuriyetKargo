let general_cargo = null, piecesSelected = false;
$('#invoice_number').keyup(delay(function (e) {

    let invoice_number = $(this).val().replaceAll('_', '').trim();
    if (invoice_number == '')
        return false;

    if (invoice_number.length < 8)
        return false;

    $('#tracking_no').val('');
    getCargo();
}, 1000));

var partNiko = null

function getCargo() {

    $('#tbodyCargoPartDetailsXX').html('<tr><td colspan="9" class="text-center">Burda hiç veri yok.</td></tr>');

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
            $('#piecesBtn').prop('disabled', true);
            general_cargo = null;
            piecesSelected = false;
            $('#cargo_info').prop('disabled', true);
        } else if (response.status == 1) {

            general_cargo = response;

            let cargo = response.cargo;
            let arrival = response.arrival;
            let arrival_tc = response.arrival_tc;
            let departure = response.departure;
            let departure_tc = response.departure_tc;
            let part_details = response.part_details;

            $('#cargo_info').attr('cargo_id', cargo.id);
            $('#cargo_info').prop('disabled', false);

            $('b#invoice_number').text(cargo.invoice_number);
            $('b#tracking_no').text(cargo.tracking_no);

            $('b#sender_name').text(cargo.sender_name);
            $('b#receiver_name').text(cargo.receiver_name);

            $('b#departure_branch').text(departure.city + "-" + departure.agency_name + " ŞUBE");
            $('b#departure_tc').text(departure_tc.tc_name + " TRM.");

            if (cargo.transporter == 'CK') {
                $('b#arrival_branch').text(arrival.city + "-" + arrival.agency_name + " ŞUBE");
                $('b#arrival_tc').text(arrival_tc.tc_name + " TRM.");
            } else {
                $('b#arrival_branch').text(cargo.transporter);
                $('b#arrival_tc').text(cargo.transporter);
            }

            if (cargo.number_of_pieces > 1) {
                $('#pieces').val('Lütfen İlgili Parçaları Seçin!');
                $('#textSelectedPieces').text("");
                $('#pieces').removeClass('text-dark');
                $('#pieces').addClass('text-danger');
                $('#piecesBtn').prop('disabled', false);
                piecesSelected = false;
            } else if (cargo.number_of_pieces == 1) {
                $('#pieces').val('1');
                $('#pieces').removeClass('text-danger');
                $('#pieces').addClass('text-dark');
                $('#piecesBtn').prop('disabled', true);
                $('#textSelectedPieces').text("1 Parça Seçildi.");
                piecesSelected = true;
            }
            $('b#cargo_type').text(cargo.cargo_type);
            $('b#number_of_pieces').text(cargo.number_of_pieces);
            $('b#desi').text(cargo.desi);
            $('b#status').text(cargo.status);
            $('b#total_price').text(cargo.total_price + " ₺");


            let countCargoPart = 0, cargoDesiCount = 0;
            $('#tbodyCargoPartDetailsXX').html('');
            if (part_details.length == 0)
                $('#tbodyCargoPartDetailsXX').html('<tr><td colspan="10" class="text-center">Burda hiç veri yok.</td></tr>');
            else {

                $.each(part_details, function (key, val) {

                    let wasDelivered = val['was_delivered'] == '1' ? '<b class="text-success">Evet</b>' : '<b class="text-dark">Hayır</b>';
                    let bg = val['was_delivered'] == '1' ? 'bg-warning' : '';
                    let disabled = val['was_delivered'] == '1' ? 'disabled' : '';

                    $('#tbodyCargoPartDetailsXX').append(
                        '<tr class="' + bg + '">' +
                        '<th>\n' + '<input ' + disabled + '  style="width: 20px; margin-left: 7px;" type="checkbox" id="' + val['part_no'] + '" class="cb-piece form-control">\n' + '</th>' +
                        '<td class="font-weight-bold text-success">' + cargo.cargo_type + '</td>' +
                        '<td class="font-weight-bold">' + val['part_no'] + '</td>' +
                        '<td class="">' + val['width'] + '</td>' +
                        '<td class="">' + val['size'] + '</td>' +
                        '<td class="">' + val['height'] + '</td>' +
                        '<td class="">' + val['weight'] + '</td>' +
                        '<td class="font-weight-bold text-primary">' + val['desi'] + '</td>' +
                        '<td class="text-alternate">' + val['cubic_meter_volume'] + '</td>' +
                        '<td class="text-alternate">' + wasDelivered + '</td>' +
                        +'</tr>'
                    );
                    countCargoPart = countCargoPart + 1;
                    cargoDesiCount = cargoDesiCount + parseInt(val['desi']);
                });

                $('#tbodyCargoPartDetailsXX').prepend(
                    '<tr><td class="font-weight-bold text-center" colspan="10"> Toplam: <b class="text-primary">' + countCargoPart + ' Parça</b>, <b class="text-primary">' + cargoDesiCount + ' Desi</b>. </td></tr>'
                );
            }

        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
        $('#cargo_info').prop('disabled', true);
    }).always(function () {
        $('#rowCargoInfo').unblock();
    });
}

$('#cargo_info').click(function () {
    cargoInfo($(this).attr('cargo_id'));
});

$(document).ready(function () {
    $(".select-all-cb").click(function () {
        $('.cb-piece:input:checkbox:not(:disabled)').prop('checked', this.checked);
    });
});

$(document).on('click', '#btnSelectPieces', function () {

    var PieceArray = [];
    $("input.cb-piece[type=checkbox]:checked:not(:disabled)").each(function () {
        PieceArray.push($(this).prop('id'));
    });

    let values = "";
    $.each(PieceArray, function (key, val) {
        // console.log(key + "=>" + val);
        values += val + ",";
    });

    if (PieceArray.length == 0)
        ToastMessage('error', 'Lütfen ilgili parçaları seçiniz', 'HATA!');
    else {
        ToastMessage('success', 'Parçalar seçildi!', 'İşlem Başarılı!');

        let newVal = values.substring(0, values.length - 1);
        $('#pieces').val(newVal);

        $('#textSelectedPieces').text(PieceArray.length + " Parça Seçildi.");
        piecesSelected = true;

        $('#ModalPartDetails').modal('hide');

    }

});


$('#piecesBtn').click(function () {
    $('#ModalPartDetails').modal();
});

function hideFakeColumn() {
    $('#reported_unit').val('');
    $('#column_fake_unit').show();
    $('#column-agency').hide();
    $('#select_reported_agency').prop('disabled', true);
    $('#column-tc').hide();
    $('#select_reported_tc').prop('disabled', true);
}

$('#transaction').change(function () {

    switch ($(this).val()) {
        case "Teslimat":
            $('#descriptionDelivery').val('Teslim edilmiştir.')
            $('#divDelivery').show();
            $('#divTransfer').hide();
            break;

        case "İade":
            $('#descriptionDelivery').val('İade edilmiştir.')
            break;

        case "Devir":
            $('#descriptionDelivery').val('Kargo Devredilmiştir.')
            $('#divDelivery').hide();
            $('#divTransfer').show();
            break;

        case "Yönlendir":
            $('#descriptionDelivery').val('Bu kargo yönlendirilmiştir.')
            break;
    }
})

$('#btnSubmitForm').click(function () {

    let urlForDelivery = "Delivery"

    if (general_cargo == null) {
        ToastMessage('error', 'Lütfen kargo seçiniz!', 'Hata!')
        return false;
    }

    switch ($('#transaction').val()) {
        case "Teslimat":

            urlForDelivery = "Delivery";
            if ($('#receiverNameSurnameCompany').val() == '') {
                ToastMessage('error', 'Teslim alan ad soyad alanı zorunludur!', 'Hata!')
                return false;
            }

            if ($('#receiverTCKN').val() == '') {
                ToastMessage('error', 'Teslim alan TCKN alanı zorunludur!', 'Hata!')
                return false;
            }

            if ($('#receiverProximity').val() == '') {
                ToastMessage('error', 'Teslim alan yakınlık derecesi alanı zorunludur!', 'Hata!')
                return false;
            }

            if ($('#deliveryDate').val() == '') {
                ToastMessage('error', 'Teslimat tarihi alanı zorunludur!', 'Hata!')
                return false;
            }

            if ($('#pieces').val() == 'Lütfen İlgili Parçaları Seçin!' && general_cargo.cargo.number_of_pieces > 1) {
                ToastMessage('error', 'Lütfen teslim edilen parçları seçiniz', 'Hata!')
                return false;
            }
            break;

        case 'Devir':
            urlForDelivery = "Transfer"
            if ($('#transferReason').val() == '') {
                ToastMessage('error', 'Devir nedeni alanı zorunludur!', 'Hata!')
                return false
            }
            break;
    }


    if ($('#descriptionDelivery').val() == '') {
        ToastMessage('error', 'Açıklama alanı zorunludur!', 'Hata!')
        return false;
    }


    $('.main-card').block(whiteAnimation);
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');


    $.ajax('/Delivery/AjaxTransaction/' + urlForDelivery, {
        method: 'POST',
        data: {
            _token: token,
            transaction: $('#transaction').val(),
            teslimAlanAdSoyad: $('#receiverNameSurnameCompany').val(),
            receiverTCKN: $('#receiverTCKN').val(),
            receiverProximity: $('#receiverProximity').val(),
            deliveryDate: $('#deliveryDate').val(),
            cargoId: general_cargo.cargo.id,
            descriptionDelivery: $('#descriptionDelivery').val(),
            selectedPieces: $('input#pieces').val()
        }
    }).done(function (response) {

        if (response.status == 1) {

            setMessageToLS('İşlem Başarılı!', response.message, 'success');
            window.location.reload();

        } else if (response.status == -1) {
            ToastMessage('error', response.message, 'Hata!')
            return false
        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status, JSON.parse(jqXHR.responseText));
    }).always(function () {
        $('.main-card').unblock();
    });


})

