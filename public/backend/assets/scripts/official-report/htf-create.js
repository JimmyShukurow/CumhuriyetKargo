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

    $('#tbodyCargoPartDetails').html('<tr><td colspan="9" class="text-center">Burda hiç veri yok.</td></tr>');

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
        } else if (response.status == 1) {

            general_cargo = response;

            let cargo = response.cargo;
            let arrival = response.arrival;
            let arrival_tc = response.arrival_tc;
            let departure = response.departure;
            let departure_tc = response.departure_tc;
            let part_details = response.part_details;


            $('b#invoice_number').text(cargo.invoice_number);
            $('b#tracking_no').text(cargo.tracking_no);

            $('b#sender_name').text(cargo.sender_name);
            $('b#receiver_name').text(cargo.receiver_name);

            $('b#departure_branch').text(departure.city + "-" + departure.agency_name + " ŞUBE");
            $('b#departure_tc').text(departure_tc.tc_name + " TRM.");
            $('b#arrival_branch').text(arrival.city + "-" + arrival.agency_name + " ŞUBE");
            $('b#arrival_tc').text(arrival_tc.tc_name + " TRM.");

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
            $('#tbodyCargoPartDetails').html('');
            if (part_details.length == 0)
                $('#tbodyCargoPartDetails').html('<tr><td colspan="8" class="text-center">Burda hiç veri yok.</td></tr>');
            else {
                $.each(part_details, function (key, val) {

                    $('#tbodyCargoPartDetails').prepend(
                        '<tr>' +
                        '<th>\n' + '<input style="width: 20px; margin-left: 7px;" type="checkbox" id="' + val['part_no'] + '" class="cb-piece form-control">\n' + '</th>' +
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
                    '<tr><td class="font-weight-bold text-center" colspan="9"> Toplam: <b class="text-primary">' + countCargoPart + ' Parça</b>, <b class="text-primary">' + cargoDesiCount + ' Desi</b>. </td></tr>'
                );
            }

        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#rowCargoInfo').unblock();
    });
}

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

$('#reported_unit_type').change(function () {

    $('#reported_unit').val('');

    if (general_cargo == null) {
        $(this).val('');
        ToastMessage('error', 'Önce kargonun fatura numarasını veya takip numarasını giriniz!', 'HATA!');
        return false;
    }

    switch ($(this).val()) {

        case '':
            hideFakeColumn();
            break;

        case  'Çıkış Şube':
            hideFakeColumn();
            $('#reported_unit').val(general_cargo.departure.agency_name + " ŞUBE");
            $('#reported_unit_id').val(general_cargo.cargo.departure_agency_code);
            break;

        case  'Çıkış TRM.':
            hideFakeColumn();
            $('#reported_unit').val(general_cargo.departure_tc.tc_name + " TRM.");
            $('#reported_unit_id').val(general_cargo.cargo.departure_tc_code);
            break;

        case  'Varış Şube':
            hideFakeColumn();
            $('#reported_unit').val(general_cargo.arrival.agency_name + " ŞUBE");
            $('#reported_unit_id').val(general_cargo.cargo.arrival_agency_code);
            break;

        case  'Varış TRM.':
            hideFakeColumn();
            $('#reported_unit').val(general_cargo.arrival_tc.tc_name + " TRM.");
            $('#reported_unit_id').val(general_cargo.cargo.arrival_tc_code);
            break;

        case 'Diğer Şube':
            $('#reported_unit_id').val($('#select_reported_agency').val());
            $('#column_fake_unit').hide();
            $('#column-agency').show();
            $('#select_reported_agency').prop('disabled', false);

            $('#column-tc').hide();
            $('#select_reported_tc').prop('disabled', true);
            break;

        case 'Diğer TRM.':
            $('#reported_unit_id').val($('#select_reported_tc').val());
            $('#column_fake_unit').hide();
            $('#column-tc').show();
            $('#select_reported_tc').prop('disabled', false);

            $('#column-agency').hide();
            $('#select_reported_agency').prop('disabled', true);
            break;


        default:
            ToastMessage('error', 'Lütfen geçerli bir birim tipi seçin!', 'Hata!');
    }

});

$('.reported-units').change(function () {
    $('#reported_unit_id').val($(this).val());
});

$(document).ready(function () {
    $('#select_reported_agency').select2();
    $('#select_reported_tc').select2();
});

$('#select_reported_agency').change(function () {
    $('#reported_unit').val($("#select_reported_agency option:selected").text());
});

$('#select_reported_tc').change(function () {
    $('#reported_unit').val($("#select_reported_tc option:selected").text());
});


function getDataDamages() {
    let array = [];
    $.each($('.cb-damges'), function (key, val) {
        if (val.checked == true) {
            array.push($('#' + val.id).attr('damage-id'));
        }
    });
    return array;
}

function getDataTransactions() {
    let array = [];
    $.each($('.cb-transactions'), function (key, val) {
        if (val.checked == true) {
            array.push($('#' + val.id).attr('transaction-id'));
        }
    });
    return array;
}


$("#HtfCreateForm").submit(function (e) {
    e.preventDefault();

    if (general_cargo == null) {
        ToastMessage('error', 'Önce kargonun fatura numarasını veya takip numarasını giriniz!');
        return false;
    }

    if (piecesSelected == false) {
        ToastMessage('error', 'Lütfen ilgili parçaları seçiniz!');
        return false;
    }

    if ($('#reported_unit_type').val() == '') {
        ToastMessage('error', 'Tutanak tutulan birim tipi alanı zorunludur!');
        return false;
    }

    if ($('#reported_unit').val() == '') {
        ToastMessage('error', 'Tutanak tutulan birim alanı zorunludur!');
        return false;
    }

    let damageStatus = true;
    $.each($('.cb-damges'), function (key, val) {
        if (val.checked == true) {
            damageStatus = true;
            return false;
        } else
            damageStatus = false;
    });

    if (!damageStatus) {
        ToastMessage('error', 'Lütfen hasar nedeni kutucuklarından en az 1 tanesini seçiniz!');
        return false;
    }

    let transactionStatus = true;
    $.each($('.cb-transactions'), function (key, val) {
        if (val.checked == true) {
            transactionStatus = true;
            return false;
        } else
            transactionStatus = false;
    });

    if (!transactionStatus) {
        ToastMessage('error', 'Lütfen yapılan işlem kutucuklarından en az 1 tanesini seçiniz!');
        return false;
    }

    if ($('#content_detection').val() == '') {
        ToastMessage('error', 'Lütfen İçerik Tespiti alanını doldurunuz!');
        return false;
    }

    let damageArray = getDataDamages();
    let transactionArray = getDataTransactions();

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

    $.ajax('/OfficialReport/CreateHTF', {
        method: 'POST',
        data: {
            _token: token,
            faturaNumarasi: general_cargo.cargo.invoice_number,
            tutanakTutulanBirimTipi: $('#reported_unit_type').val(),
            tutanakTutulanBirim: $('#reported_unit').val(),
            icerikAciklamasi: $('#content_detection').val(),
            hasarAciklamasi: $('#damage_description').val(),
            hasarNedenleri: damageArray,
            yapilanIslemler: transactionArray,
            ilgiliParcalar: $('#pieces').val(),
            tutanakTutulanBirimID: $('#reported_unit_id').val()
        }
    }).done(function (response) {

        if (response.status == -1) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value)
            });
            $('.app-main__inner').unblock();
        } else if (response.status == 0) {
            $('.app-main__inner').unblock();
            ToastMessage('error', response.message)
        } else if (response.status == 1) {
            ToastMessage('success', response.message);

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

            setMessageToLS('İşlem Başarılı!', response.message, 'success');

            window.location.reload();
        }


    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
        $('.app-main__inner').unblock();
    }).always(function () {
        // $('.app-main__inner').unblock();
    });

});








