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


    switch ($(this).val()) {

        case '':
            hideFakeColumn();
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


function getDataImpropriety() {
    let array = [];
    $.each($('.cb-impropriety'), function (key, val) {
        if (val.checked == true) {
            array.push($('#' + val.id).attr('impropriety-id'));
        }
    });
    return array;
}


$("#HtfCreateForm").submit(function (e) {
    e.preventDefault();

    if ($('#reported_unit_type').val() == '') {
        ToastMessage('error', 'Tutanak tutulan birim tipi alanı zorunludur!');
        return false;
    }

    if ($('#reported_unit').val() == '') {
        ToastMessage('error', 'Tutanak tutulan birim alanı zorunludur!');
        return false;
    }

    if ($('#impropriety_description').val() == '') {
        ToastMessage('error', 'Açıklama alanı zorunludur!');
        return false;
    }

    let improprietyStatus = true;
    $.each($('.cb-impropriety'), function (key, val) {
        if (val.checked == true) {
            improprietyStatus = true;
            return false;
        } else
            improprietyStatus = false;
    });

    if (!improprietyStatus) {
        ToastMessage('error', 'Lütfen uygunsuzluk nedeni kutucuklarından en az 1 tanesini seçiniz!');
        return false;
    }

    let improprietyArray = getDataImpropriety();

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

    $.ajax('/OfficialReport/CreateUTF', {
        method: 'POST',
        data: {
            _token: token,
            tutanakTutulanBirimTipi: $('#reported_unit_type').val(),
            tutanakTutulanBirim: $('#reported_unit').val(),
            uygunsuzlukAciklamasi: $('#impropriety_description').val(),
            uygunsuzlukNedenleri: improprietyArray,
            tutanakTutulanBirimID: $('#reported_unit_id').val()
        }
    }).done(function (response) {

        if (response.status == -1) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value)
            });
        } else if (response.status == 0) {
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
    }).always(function () {
        $('.app-main__inner').unblock();
    });

});


function carContainerControl() {

    $('#contCarInfo').hide();

    $.each($('.not_cargo'), function (key, val) {
        if (val.checked == true) {
            $('#contCarInfo').show();
            return false;
        }
    });
}

$('.not_cargo').click(function () {
    carContainerControl();
    console.log('click car');
});


$('#plaque').keyup(delay(function (e) {

    let plaque = $(this).val();

    if (plaque == '')
        return false;

    getCarInfo();

}, 1000));


function getCarInfo() {

    $('#contCarInfo').block({
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

    $.ajax('/Ajax/GetCarInfo', {
        method: 'POST',
        data: {
            _token: token,
            plaque: $('#plaque').val()
        }
    }).done(function (response) {
        if (response.status == 1) {
            $('#labelPlaque').text(response.car.plaka);
            $('#carType').text(response.car.car_type);
        } else if (response.status == 0) {
            ToastMessage('error', response.message, 'HATA!');
            clearCarVal();
            return false;
        }
    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#contCarInfo').unblock();
    });

}

$('#plaque').keyup(function () {
    this.value = this.value.toUpperCase().replaceAll(' ', '');
});

function clearCarVal() {
    $('#labelPlaque').text('-');
    $('#carType').text('-');
}









