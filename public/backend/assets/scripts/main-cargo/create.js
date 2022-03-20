var CurrentCity = "", ReceiverCity = "", DistancePrice = 0, PaymentType = 'Gönderici Ödemeli', CargoType = "Dosya";
var MobilBolge = $('#add-service-19');
var AdreseTeslim = $('#add-service-8');
var SubeTeslim = $('#add-service-11');
componentReceiverFrom = "create-cargo"
componentSenderFrom = "create-cargo"

MobilBolge.prop('disabled', true);
MobilBolge.prop('read-only', true);

$(document).ready(function () {
    $('#gondericiAdi').select2({
        ajax: {
            url: "/MainCargo/AjaxTransactions/GetCurrents",
            type: "post",
            delay: 450,
            data: function (params) {
                return {
                    _token: token,
                    currentSearchTerm: (params.term), // search term,
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.name
                        }
                    })
                };
            },
        },
        language: "es",
        cache: true,
        placeholder: "Gönderici Arayın",
    });


    $('#aliciAdi').select2({
        ajax: {
            url: "/MainCargo/AjaxTransactions/GetReceivers",
            type: "post",
            delay: 450,
            data: function (params) {
                return {
                    _token: token,
                    currentSearchTerm: encodeURI(params.term), // search term,
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.name
                        }
                    })
                };
            },
        },
        cache: true,
        placeholder: "Alıcı Arayın",
    });

    // Local Storage Transaction START
    let seriMod = localStorage.getItem('seriMod');
    if (seriMod) {
        $('#seriMod').prop('checked', true);
        getCurrentInfo(localStorage.getItem('currentCode'));
        localStorage.clear();
    }
    // Local Storage Transaction END
});

$(document).on('click', '#btnGondericiOlustur', function () {
    $('#modalNewCurrent').modal();
});

$(document).on('click', '#btnYeniAlici', function () {

    let GondericiAdi = $('#gondericiAdi').val();
    let GondericiCariKodu = $('#gondericiCariKod').val();

    if (GondericiAdi == null || GondericiCariKodu == '') {
        ToastMessage('error', 'Lütfen Önce Göndericiyi Seçiniz.', 'Hata');
        return false;
    }

    $('#modalNewReceiver').modal();
});


function getReceiverInfo(currentCode, tryExist = false) {
    // block div
    $('#divider-alici').block({
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
    $('.blockUI.blockMsg.blockElement').css('left', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/MainCargo/AjaxTransactions/GetCustomer', {
        method: 'POST',
        data: {
            _token: token,
            currentCode: currentCode,
            type: 'receiver'
        }
    }).done(function (response) {

        if (tryExist == true)
            if (response.status == -1)
                ToastMessage('error', 'Cari Bulunamadı!', 'Hata!');

        $('#aliciCariKod').val(response.current_code);


        if (response.category == 'Bireysel') {
            $('#aliciMusteriTipi').val('Bireysel');
            $('#aliciMusteriTipi').addClass('text-primary');
            $('#aliciMusteriTipi').removeClass('text-success');
            $('#aliciMusteriTipi').removeClass('text-alternate');

        } else if (response.category == 'Kurumsal') {
            $('#aliciMusteriTipi').val('Kurumsal');
            $('#aliciMusteriTipi').addClass('text-success');
            $('#aliciMusteriTipi').removeClass('text-primary');
            $('#aliciMusteriTipi').removeClass('text-alternate');
        } else if (response.category == 'Anlaşmalı') {
            $('#aliciMusteriTipi').val('Anlaşmalı');
            $('#aliciMusteriTipi').addClass('text-alternate');
            $('#aliciMusteriTipi').removeClass('text-primary');
            $('#aliciMusteriTipi').removeClass('text-success');
        }



        // console.log(response);
        var newOption = new Option(response.name, response.name, true, true);
        $('#aliciAdi').append(newOption).trigger('change');

        if ($('#gondericiCariKod').val() != '')
            $('.radio-payment-type').trigger('click')

        $('#aliciTelNo').val(response.gsm);
        $('#AliciTelefon').val(response.gsm);
        $('#aliciIl').val(response.city);
        $('#aliciIlce').val(response.district);
        $('#aliciMahalle').val(response.neighborhood);
        $('#aliciCadde').val(response.street);
        $('#aliciSokak').val(response.street2);
        $('#aliciBinaNo').val(response.building_no);
        $('#aliciDaireNo').val(response.door_no);
        $('#aliciKatNo').val(response.floor);
        $('#aliciAdres').val(response.address_note);

        let city = response.city + "/",
            district = response.district + " ",
            neighborhood = response.neighborhood + " ",
            street = response.street != '' && response.street != null ? response.street + " CAD. " : "",
            street2 = response.street2 != '' && response.street2 != null ? response.street2 + " SK. " : "",
            buildingNo = "NO:" + response.building_no + " ",
            door = "D:" + response.door_no + " ",
            floor = "KAT:" + response.floor + " ",
            addressNote = response.address_note != '' ? "(" + response.address_note + ")" : '';

        let fullAddress = city + district + neighborhood + street + street2 + buildingNo + floor + door + addressNote;

        $('#aliciAdres').val(fullAddress);

        ReceiverCity = response.city;
        getDistance(CurrentCity, ReceiverCity);

        getPriceForCustomers();
        DistributionControl();

    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status)
    }).always(function () {
        $('#divider-alici').unblock();
    });

}

function DistributionControl(neighborhood = '') {

    if ($('#aliciCariKod').val() == '')
        return false;


    $('#divider-alici').block({
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
    $('.blockUI.blockMsg.blockElement').css('left', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/MainCargo/AjaxTransactions/DistributionControl', {
        method: 'POST',
        data: {
            _token: token,
            gondericiCariKodu: $('#gondericiCariKod').val(),
            aliciCariKodu: $('#aliciCariKod').val(),
            neighborhood: neighborhood,
        }
    }).done(function (response) {

            // console.log(response);
            if (response.status == 0) {
                ToastMessage('error', response.message, 'Hata!');
                $('#dagitimDurumu').val('AT DIŞI - DAĞITIM YOK');
                $('#dagitimDurumu').removeClass('text-alternate');
                $('#dagitimDurumu').addClass('text-danger');
            } else if (response.status == 1) {

                $('#varisSube').val(response.arrival_agency);
                $('#varisTransferMerkezi').val(response.arrival_tc + " TM");
                $('#dagitimDurumu').val(response.area_type);
                $('#dagitimDurumu').removeClass('text-danger');
                $('#dagitimDurumu').removeClass('text-alternate');
                $('#dagitimDurumu').addClass('text-success');

                if (response.area_type == 'Mobil Bölge') {
                    ToastMessage('warning', "Bölge mobil olarak kayıtlı, teslimat gecikmeli olabilir. Bölge: " + $('#aliciMahalle').val() + " (Mobil olarak işaretlendi!)");

                    if (MobilBolge.prop('checked') == false) {
                        MobilBolge.prop('disabled', false);
                        MobilBolge.click();
                        MobilBolge.prop('disabled', true);
                        calculateTotalPrice();
                        clearAddServices();
                    }

                } else if (response.area_type == 'Ana Bölge') {
                    if (MobilBolge.prop('checked') == true) {
                        MobilBolge.prop('disabled', false);
                        MobilBolge.click();
                        MobilBolge.prop('disabled', true);
                    }


                    $('.partner-service-0').prop('disabled', false);
                    $('.add-service-default-1').prop('checked', true);

                    clearAddServices();
                    surfAddServices();
                    calculateTotalPrice();


                } else if (response.area_type == 'MNG') {

                    if (MobilBolge.prop('checked') == true) {
                        MobilBolge.prop('disabled', false);
                        MobilBolge.click();
                        MobilBolge.prop('disabled', true);
                    }

                    $('.partner-service-0').prop('disabled', true);
                    $('.partner-service-0').prop('checked', false);

                    clearAddServices();
                    surfAddServices();
                    calculateTotalPrice();
                }
            }

        }
    ).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#divider-alici').unblock();
    });
}


function clearAddServices() {
    AdreseTeslim.prop('disabled', false);
    SubeTeslim.prop('disabled', false);
}

function getCurrentInfo(currentCode, tryExist = false) {


    // block div
    $('#divider-gonderici').block({
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
    $('.blockUI.blockMsg.blockElement').css('left', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/MainCargo/AjaxTransactions/GetCustomer', {
        method: 'POST',
        data: {
            _token: token,
            currentCode: currentCode,
            type: 'current'
        }
    }).done(function (response) {
        if (tryExist == true)
            if (response.status == -1)
                ToastMessage('error', 'Cari Bulunamadı!', 'Hata!');

        $('#gondericiCariKod').val(response.current_code);

        if (response.category == 'Bireysel') {
            $('#gondericiMusteriTipi').val('Bireysel');
            $('#gondericiMusteriTipi').addClass('text-primary');
            $('#gondericiMusteriTipi').removeClass('text-success');
            $('#gondericiMusteriTipi').removeClass('text-alternate');

            $('#radioPaymentTypePOCH').prop('disabled', true);
            $('#radioPaymentTypeGondericiOdemeli').prop('disabled', false);
            $('#radioPaymentTypeGondericiOdemeli').prop('checked', true);

        } else if (response.category == 'Kurumsal' && response.current_type == 'Gönderici') {
            $('#gondericiMusteriTipi').val('Kurumsal');
            $('#gondericiMusteriTipi').addClass('text-success');
            $('#gondericiMusteriTipi').removeClass('text-primary');
            $('#gondericiMusteriTipi').removeClass('text-alternate');

            $('#radioPaymentTypePOCH').prop('disabled', true);
            $('#radioPaymentTypeGondericiOdemeli').prop('disabled', false);
            $('#radioPaymentTypeGondericiOdemeli').prop('checked', true);

        } else if (response.category == 'Anlaşmalı' && response.current_type == 'Gönderici') {
            $('#gondericiMusteriTipi').val('Anlaşmalı');
            $('#gondericiMusteriTipi').addClass('text-alternate');
            $('#gondericiMusteriTipi').removeClass('text-primary');
            $('#gondericiMusteriTipi').removeClass('text-success');

            $('#radioPaymentTypePOCH').prop('disabled', false);
            $('#radioPaymentTypePOCH').prop('checked', true);
            $('#radioPaymentTypeGondericiOdemeli').prop('disabled', true);
        }

        if ($('#aliciCariKod').val() != '')
            $('.radio-payment-type').trigger('click')

        let legal_number = response.tckn != '' ? response.tckn : response.vkn;
        $('#gondericiTCKN').val(legal_number);

        var newOption = new Option(response.name, response.name, true, true);
        $('#gondericiAdi').append(newOption).trigger('change');

        $('#gondericiTelNo').val(response.gsm)
        $('#GondericiTelefon').val(response.gsm)


        let city = response.city + "/",
            district = response.district + " ",
            neighborhood = response.neighborhood + " ",
            street = response.street != '' && response.street != null ? response.street + " CAD. " : "",
            street2 = response.street2 != '' && response.street2 != null ? response.street2 + " SK. " : "",
            buildingNo = "NO:" + response.building_no + " ",
            door = "D:" + response.door_no + " ",
            floor = "KAT:" + response.floor + " ",
            addressNote = response.address_note != '' ? "(" + response.address_note + ")" : '';

        let fullAddress = city + district + neighborhood + street + street2 + buildingNo + floor + door + addressNote;

        if (response.status != -1)
            $('#gondericiAdres').val(fullAddress);

        CurrentCity = response.city;
        getDistance(CurrentCity, ReceiverCity);

        $('#TahsilatFaturaTutari').trigger('keyup');

        getPriceForCustomers();
        DistributionControl();

    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status)
    }).always(function () {
        $('#divider-gonderici').unblock();
    });

}


function getPriceForCustomers() {


    let currentCode = $('#gondericiCariKod').val();
    let receiverCode = $('#aliciCariKod').val();

    if (currentCode != '' && receiverCode != '' && (CargoType != 'Koli' || parseInt($('#labelDesi').val()) != 0)) {

        $('#tableSummery').block({
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
        $('.blockUI.blockMsg.blockElement').css('left', '0px');
        $('.blockUI.blockMsg.blockElement').css('background-color', '');


        // PaymentType = $('#paymentType').prop('checked') ? 'Gönderici Ödemeli' : 'Alıcı Ödemeli';


        $.ajax('/MainCargo/AjaxTransactions/GetPriceForCustomers', {
            method: 'POST',
            data: {
                _token: token,
                gondericiCariKodu: currentCode,
                aliciCariKodu: receiverCode,
                odemeTipi: $('input[name="radioPaymentType"]:checked').val(),
                cargoType: $('#selectCargoType').val(),
                desi: parseFloat($('#labelDesi').text()),
                desiData: getFormData($('#formPartDesiContainer')),
                parcaSayisi: $('#partQuantity').text(),

            }
        }).done(function (response) {

            $('#serviceFee').text(roundLikePHP(response.service_fee, 2))
            $('#postServicePrice').text(response.post_service_price)
            $('#heavyLoadCarryingCost').text(response.heavy_load_carrying_cost)
            $('#mobileServiceFee').text(response.mobile_service_fee)

            calculateTotalPrice();

            $('#tableSummery').unblock();

        }).error(function (jqXHR, exception) {
            ajaxError(jqXHR.status)
        }).always(function () {
            $('#modalBodySelectCustomer').unblock();
        });


    }
    // else
    //     ToastMessage('info', 'No-Code', 'bitch!');
}


$('#searchCurrent').click(function () {

    let selectedCurrent = $('#gondericiAdi').select2('data');
    let senderNameText = null;
    if (selectedCurrent.length == 0 && $('#GondericiTelefon').val() == '') {
        ToastMessage('error', 'Önce bir gönderici adı seçin veya telefon numarası girin!', 'Bilgi');
        return false;
    }

    if (selectedCurrent.length != 0)
        senderNameText = selectedCurrent[0].text;

    getCustomer(senderNameText, $('#GondericiTelefon').val(), 'Gönderici');
});

$('#searchReceiver').click(function () {
    let selectedCurrent = $('#aliciAdi').select2('data');
    let receiverNameText = null;
    if (selectedCurrent.length == 0 && $('#AliciTelefon').val() == '') {
        ToastMessage('error', 'Önce bir alıcı adı veya telefon numarası girmelisiniz!', 'Bilgi');
        return false;
    }

    if (selectedCurrent.length != 0)
        receiverNameText = selectedCurrent[0].text;

    getCustomer(receiverNameText, $('#AliciTelefon').val(), 'Alıcı');
});

function getCustomer(name, phone, from) {

    $('#modalSelectCustomerHead').text(from + " Seçin");

    $('#tbodyCustomers').html('');
    $('#modalBodySelectCustomer').block({
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
    $('.blockUI.blockMsg.blockElement').css('left', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/MainCargo/AjaxTransactions/GetCustomers', {
        method: 'POST',
        data: {
            _token: token,
            from: from,
            name: name,
            phone: phone
        }
    }).done(function (response) {

        $.each(response, function (key, val) {

            let city = val['city'] + "/",
                district = val['district'] + " ",
                neighborhood = val['neighborhood'] + " ",
                street = val['street'] != '' ? val['street'] + " CAD. " : "",
                street2 = val['street2'] != '' ? val['street2'] + " SK. " : "",
                buildingNo = "NO:" + val['building_no'] + " ",
                door = "D:" + val['door_no'] + " ",
                floor = "KAT:" + val['floor'] + " ",
                addressNote = "(" + val['address_note'] + ")";

            let fullAddress = city + district + neighborhood + street + street2 + buildingNo + floor + door + addressNote;

            let bgType = val['category'] == 'Anlaşmalı' ? 'bg-warning' : '';
            let rowTitle = val['category'] == 'Anlaşmalı' ? 'Anlaşmalı Cari' : '';

            if (from == 'Gönderici') {

                $('#tbodyCustomers').append(
                    '<tr class="trCustomer ' + bgType + '" title="' + rowTitle + '" id="' + val['id'] + '" type="current">' +
                    '<td class="unselectable cursor-pointer" title="' + val['name'] + '">' + val['name'].substr(0, 25) + '..' + '</td>' +
                    '<td class="unselectable cursor-pointer">' + val['gsm'] + '</td>' +
                    '<td class="unselectable cursor-pointer">' + val['city'] + '/' + val['district'] + '</td>' +
                    '<td class="unselectable cursor-pointer" title="' + fullAddress + '">' + fullAddress.substr(0, 25) + '....' + '</td>' +
                    '<td class="unselectable cursor-pointer">' + val['category'] + '</td>' +
                    '<td class="unselectable cursor-pointer">' + val['reg_date'] + '</td>' +
                    // '<td><button id="' + val['current_code'] + '" class="btnSelectCustomer btn btn-primary btn-xs">Seç</button></td>' +
                    '</tr>'
                );
            } else if (from == 'Alıcı') {

                $('#tbodyCustomers').append(
                    '<tr class="trCustomer ' + bgType + '" title="' + rowTitle + '" id="' + val['id'] + '" type="receiver">' +
                    '<td class="unselectable cursor-pointer" title="' + val['name'] + '">' + val['name'].substr(0, 25) + '..' + '</td>' +
                    '<td class="unselectable cursor-pointer ">' + val['gsm'] + '</td>' +
                    '<td class="unselectable cursor-pointer ">' + val['city'] + '/' + val['district'] + '</td>' +
                    '<td class="unselectable cursor-pointer" title="' + fullAddress + '">' + fullAddress.substr(0, 25) + '..' + '</td>' +
                    '<td class="unselectable cursor-pointer">' + val['category'] + '</td>' +
                    '<td class="unselectable cursor-pointer">' + val['reg_date'] + '</td>' +
                    '</tr>'
                );

            }
        });

    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status)
    }).always(function () {
        $('#modalBodySelectCustomer').unblock();
    });

    $('#modalSelectCustomer').modal();
}

$(document).on('dblclick', '.trCustomer', function () {
    // alert($(this).attr('type') + ' => ' + $(this).attr('id'));

    let type = $(this).attr('type');
    let id = $(this).attr('id');

    if (type == 'receiver')
        getReceiverInfo(id);
    else if (type == 'current')
        getCurrentInfo(id);

    $('#modalSelectCustomer').modal('hide');
})

$('.add-fee').click(function () {
    var addFeePrice = parseFloat($('#addFeePrice').text());

    if ($(this).prop('checked') == true) {
        addFeePrice += parseFloat($(this).val());
    } else {
        addFeePrice -= parseFloat($(this).val());
    }

    $('#addFeePrice').text(roundLikePHP(addFeePrice, 2));
    calculateTotalPrice();
});

function calculateTotalPrice() {
    let total = 0, wKDV = 0, kdvPercent = parseFloat($('#kdvPercent').text());
    let addFee = parseFloat($('#addFeePrice').text());
    let serviceFee = parseFloat($('#serviceFee').text());
    let postServiceFee = parseFloat($('#postServicePrice').text());
    let heavyLoadCarryingCost = parseFloat($('#heavyLoadCarryingCost').text());
    let mobileServiceFee = parseFloat($('#mobileServiceFee').text());

    total += addFee + serviceFee + DistancePrice + postServiceFee + mobileServiceFee;
    wKDV = total + (kdvPercent * (total) / 100) + heavyLoadCarryingCost;
    $('#totalPrice').text(roundLikePHP(wKDV, 2));
    // No - KDV
    $('#kdvExcluding').text(roundLikePHP(total, 2));
}

var CustomerOK = false;

$('#divCargoType').click(function () {

});

$('#selectCargoType').change(function () {

    let receiverSelect = $('#aliciAdi').select2('data');
    let currentSelect = $('#gondericiAdi').select2('data');

    if (receiverSelect.length == 0 || currentSelect.length == 0) {
        ToastMessage('error', 'Önce Göndericiyi ve Alıcıyı Seçmelisiniz!', 'Hata!');
        $('#selectCargoType').val('');
        return false;
    }
    if ($('#gondericiTCKN').val().trim() == '' || $('#gondericiTelNo').val().trim() == '' || $('#gondericiCariKod').val().trim() == '' ||
        $('#aliciCariKod').val().trim() == '' || $('#aliciTelNo').val().trim() == '' || $('#aliciAdres').val().trim() == '') {
        ToastMessage('error', 'Önce Göndericiyi ve Alıcıyı Seçmelisiniz!', 'Hata!');
        $('#checkCargoType').prop('disabled', true);
    } else {
        $('#checkCargoType').prop('disabled', false);


        if ($('#selectCargoType').val() != 'Dosya' && $('#selectCargoType').val() != 'Mi') {

            $('#modalCalcDesi').modal();
            CargoType = 'Koli';

        } else {

            CargoType = 'Dosya';
            $('#labelDesi').text('0');
            $('#partQuantity').text('1');
            calculateTotalPrice();
            // getFilePrice();
            getPriceForCustomers();
            DistributionControl();

        }

        //
        // if ($('#checkCargoType').prop('checked') == true) {
        //     $('#modalCalcDesi').modal();
        //     CargoType = 'Koli';
        // } else {
        //     CargoType = 'Dosya';
        //     $('#labelDesi').text('0');
        //     $('#partQuantity').text('1');
        //     calculateTotalPrice();
        //     // getFilePrice();
        //     getPriceForCustomers();
        // }
    }


});

$('#selectCollectionType').on('change', function () {
    if ($(this).val() == 'POS') {

        $('#tahsilatOnayKodu').val('').prop('disabled', false);
        $('#tahsilatKartSahibi').val('').prop('disabled', false);

        $('#tahsilatAciklama').val('Kargo pos tahsilat:')

    } else if ($(this).val() == 'NAKİT') {

        $('#tahsilatOnayKodu').val('').prop('disabled', true);
        $('#tahsilatKartSahibi').val('').prop('disabled', true);

        $('#tahsilatAciklama').val('Kargo nakit tahsilat:')
    }
});

$('.radio-payment-type').click(function () {
    let receiverSelect = $('#aliciAdi').select2('data');
    let currentSelect = $('#gondericiAdi').select2('data');

    PaymentType = $('input[name="radioPaymentType"]:checked').val();


    if (receiverSelect.length == 0 || currentSelect.length == 0) {
        ToastMessage('error', 'Önce Göndericiyi ve Alıcıyı Seçmelisiniz!', 'Hata!');
        return false;
    }
    if ($('#gondericiTCKN').val().trim() == '' || $('#gondericiTelNo').val().trim() == '' || $('#gondericiCariKod').val().trim() == '' ||
        $('#aliciCariKod').val().trim() == '' || $('#aliciTelNo').val().trim() == '' || $('#aliciAdres').val().trim() == '') {
        ToastMessage('error', 'Önce Göndericiyi ve Alıcıyı Seçmelisiniz!', 'Hata!');

        $('#paymentType').prop('disabled', true);
    } else {

        if (PaymentType != 'Alıcı Ödemeli' && PaymentType != 'Gönderici Ödemeli' && PaymentType != 'PÖCH') {
            ToastMessage('error', 'Lütfen geçerli bir ödeme tipi seçiniz!');
            return false;
        }

        if ($('#add-service-tahsilatli').prop('checked') == true && PaymentType == 'Alıcı Ödemeli')
            ToastMessage('error', 'Alıcı ödemeli tahsilatlı kargo çıkaramazsınız. Sadece gönderici ödemeli tahsilatlı kargo çıkarılabilir!', 'Hata!');


        $('#fakeButton').trigger('click');
    }


    if (PaymentType == 'Alıcı Ödemeli') {

        $('#selectCollectionType').attr('disabled', 'disabled');
        $('#tahsilatOnayKodu').val('').attr('disabled', 'disabled');
        $('#tahsilatKartSahibi').val('').attr('disabled', 'disabled');

        $('#tahsilatAciklama').val('Kargo ücreti alıcıdan tahsil edilecek:')

    } else if (PaymentType == 'Gönderici Ödemeli') {
        $('#selectCollectionType').removeAttr('disabled');
        $('#tahsilatOnayKodu').val('').prop('disabled', true);
        $('#tahsilatKartSahibi').val('').prop('disabled', true);

        $('#selectCollectionType').val('NAKİT')
        $('#tahsilatAciklama').val('Kargo nakit tahsilat:')
    } else if (PaymentType == 'PÖCH') {

        $('#selectCollectionType').attr('disabled', 'disabled');
        $('#tahsilatOnayKodu').val('').attr('disabled', 'disabled');
        $('#tahsilatKartSahibi').val('').attr('disabled', 'disabled');

        $('#tahsilatAciklama').val('Peşin ödemeli cari hesap:')

    }

});


$('#fakeButton').click(delay(function () {
    getPriceForCustomers();
    // ToastMessage('warning', 'test', '');
}, 1000));

function getFilePrice() {

    $('#tableSummery').block({
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
    $('.blockUI.blockMsg.blockElement').css('left', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/MainCargo/AjaxTransactions/GetFilePrice', {
        method: 'POST',
        data: {
            _token: token,
            startPoint: CurrentCity,
            endPoint: $('#aliciIl').val(),
            currentCode: $('#gondericiCariKod').val(),
            receiverCode: $('#aliciCariKod').val(),
        }
    }).done(function (response) {
        if (response.status == 1) {
            $('#serviceFee').text(response.price);
            DistancePrice = response.distance_price;
            $('#labelDistancePrice').text(response.distance_price);
            calculateTotalPrice();
        } else if (response.status == -1)
            ToastMessage('error', response.message, 'Hata!');

    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status)
    }).always(function () {
        $('#tableSummery').unblock();
    });
}

$('#btnDesi').click(function () {
    if (CargoType == 'Koli')
        $('#modalCalcDesi').modal()
});

$(document).on('change keyup', '#totalPartNumber', function () {
    if (parseInt($(this).val()) < 0)
        $(this).val(0);
    else if (parseInt($(this).val()) > 999)
        $(this).val(999);
});

$('#gondericiCariKod').keyup(delay(function (e) {
    if (e.keyCode == 13) {
        let currentCode = $('#gondericiCariKod').val().trim();
        currentCode = currentCode.match(/\d/g);
        currentCode = currentCode.join("");
        if (currentCode.length == 9)
            getCurrentInfo(currentCode, true);
        else
            ToastMessage('error', 'Cari bulunamadı, cari kodu 9 hane olmalıdır!', 'error');
    }
}, 700));

$('#gondericiCariKod').keyup(function (e) {
    if (e.keyCode == 13)
        ToastMessage('warning', 'İstek alındı lütfen bekleyin!', '');
});


$('#aliciCariKod').keyup(delay(function (e) {
    if (e.keyCode == 13) {
        let currentCode = $('#aliciCariKod').val().trim();
        currentCode = currentCode.match(/\d/g);
        currentCode = currentCode.join("");

        if (currentCode.length == 9)
            getReceiverInfo(currentCode, true);
        else
            ToastMessage('error', 'Alıcı bulunamadı, cari kodu 9 hane olmalıdır!', 'error');
    }
}, 700));

$('#aliciCariKod').keyup(function (e) {
    if (e.keyCode == 13)
        ToastMessage('warning', 'İstek alındı lütfen bekleyin!', '');
});

function getDistance(startPoint, endPoint) {

    if (startPoint == undefined)
        return false;

    if (endPoint == undefined)
        return false;


    if (startPoint != '' && endPoint != '') {

        $.ajax('/MainCargo/AjaxTransactions/GetDistance', {
            method: 'POST',
            data: {
                _token: token,
                startPoint: startPoint,
                endPoint: endPoint
            }
        }).done(function (response) {
            if (response.status == 1) {
                $('#distance').text(response.distance);
                DistancePrice = response.price;
                $('#labelDistancePrice').text(response.price);
                calculateTotalPrice();
            } else
                ToastMessage('error', 'Mesafe hesaplanırken bir hata oluştu!', 'Hata!');

        });
    } else {
        DistancePrice = 0;
        calculateTotalPrice();
        $('#distance').text('0');
    }

}

// ==================> ############### <======================= \\
// ==================> CALC DESI START <======================= \\
// ==================> ############### <======================= \\

$(document).on('keyup', '#enT', function () {
    if ($(this).val() > 999)
        $(this).val(999);
    SendTotalDesiValues();
});
$(document).on('keyup', '#BoyT', function () {
    if ($(this).val() > 999)
        $(this).val(999);
    SendTotalDesiValues();
});
$(document).on('keyup', '#YukseklikT', function () {
    if ($(this).val() > 999)
        $(this).val(999);
    SendTotalDesiValues();
});
$(document).on('keyup', '#AgirlikT', function () {
    if ($(this).val() > 999)
        $(this).val(999);
    SendTotalDesiValues();
});

function SendTotalDesiValues() {
    let En = $('#enT').val() != '' ? $('#enT').val() : 0;
    let Boy = $('#BoyT').val() != '' ? $('#BoyT').val() : 0;
    let Yukseklik = $('#YukseklikT').val() != '' ? $('#YukseklikT').val() : 0;
    let Agirlik = $('#AgirlikT').val() != '' ? $('#AgirlikT').val() : 0;

    let desi = calcTotalDesi(parseFloat(En), parseFloat(Boy), parseFloat(Yukseklik), parseFloat(Agirlik));
    $('#totalDesi').val(roundLikePHP(desi, 2));
    let realDesi = desi > parseFloat(Agirlik) ? desi : parseFloat(Agirlik)
    $('#RealDesi').val(roundLikePHP(realDesi, 2));

    let hacim = (En * Boy * Yukseklik) / 1000000;
    $('#TotalHacim').val(roundLikePHP(hacim, 5));

}

function calcTotalDesi(En, Boy, Yukseklik, Agirlik) {

    let desi = (En * Boy * Yukseklik) / 3000;
    return desi;
}

function calcDesi(En, Boy, Yukseklik, Agirlik) {

    let avg = (En * Boy * Yukseklik) / 3000;
    let desi = avg > Agirlik ? avg : Agirlik;

    return desi;
}

//Part Desi START

$(document).on('keyup', '.partDesiCalc', function () {
    if ($(this).val() > 999)
        $(this).val(999);
    let rowID = $(this).attr('data');

    let En = $('#partDesiEn' + rowID).val() != '' ? $('#partDesiEn' + rowID).val() : 0;
    let Boy = $('#partDesiBoy' + rowID).val() != '' ? $('#partDesiBoy' + rowID).val() : 0;
    let Yukseklik = $('#partDesiYukseklik' + rowID).val() != '' ? $('#partDesiYukseklik' + rowID).val() : 0;
    let Agirlik = $('#partDesiAgirlik' + rowID).val() != '' ? $('#partDesiAgirlik' + rowID).val() : 0;

    // console.log(En + " - " + Yukseklik + " - " + Boy + " - " + Agirlik + " - " + rowID)

    SendPartDesiValues(En, Boy, Yukseklik, Agirlik, rowID);
    setPartRealTotalDesi();
});


function SendPartDesiValues(En, Boy, Yukseklik, Agirlik, RowID) {


    let desi = calcTotalDesi(parseFloat(En), parseFloat(Boy), parseFloat(Yukseklik), parseFloat(Agirlik));
    $('#partTotalDesi' + RowID).val(roundLikePHP(desi, 2));
    // console.log(roundLikePHP(desi, 2));
    let realDesi = desi > parseFloat(Agirlik) ? desi : parseFloat(Agirlik)
    $('#partRealDesi' + RowID).val(roundLikePHP(realDesi, 2));
    // Calc Hacim
    let hacim = (En * Boy * Yukseklik) / 1000000;
    $('#partDesiHacim' + RowID).val(roundLikePHP(hacim, 5));

}

function setPartRealTotalDesi() {
    let $inputs = $('.partRealDesi');
    let $Hinputs = $('.partDesiHacim ');

    // not sure if you wanted this, but I thought I'd add it.
    // get an associative array of just the values.
    let values = 0, HValues = 0;
    $inputs.each(function () {
        values += parseFloat($(this).val());
    });
    $Hinputs.each(function () {
        HValues += parseFloat($(this).val());
    });

    $('#hPartsTotalDesi').html(roundLikePHP(values, 2));
    $('#hPartsTotalM3').html(roundLikePHP(HValues, 5));
}

//Part Desi END


var DesiPartRowCounter = 1;

function createDesiPartRow(count, en = 0, boy = 0, yukseklik = 0, agirlik = 0, desi = 0, uea = 0, hacim = 0) {

    var DesiPartRow = '<div id="partDesiContainer' + DesiPartRowCounter + '"\n' +
        '                                                     class="row partDesiContainer animate__animated animate__fadeInDown">\n' +
        '\n' +
        '                                                    <div class="col-md-12">\n' +
        '                                                        <div style="border-bottom: 1px solid lightslategray;"\n' +
        '                                                             class="form-row">\n' +
        '\n' +
        '\n' +
        '                                                            <div class="col-md-1">\n' +
        '                                                                <div class="position-relative ">\n' +
        '                                                                    <label for=""></label>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="input-group mb-1">\n' +
        '                                                                    <button style="margin: 9px auto;"\n' +
        '                                                                            id="' + DesiPartRowCounter + '"\n' +
        '                                                                            class="destroyDesiRow btn-icon btn-icon-only btn-xs btn btn-danger">\n' +
        '                                                                        <i class="lnr-cross btn-icon-wrapper"> </i>\n' +
        '                                                                    </button>\n' +
        '                                                                </div>\n' +
        '                                                            </div>\n' +
        '\n' +
        '                                                            <div class="col-md-1">\n' +
        '                                                                <div class="position-relative ">\n' +
        '                                                                    <label for="partDesiEn' + DesiPartRowCounter + '">En:</label>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="input-group mb-1">\n' +
        '                                                                    <input id="partDesiEn' + DesiPartRowCounter + '" type="text" data="' + DesiPartRowCounter + '"\n' +
        '                                                                           class="form-control form-control-sm input-mask-trigger partDesiEn partDesiCalc validate-part-desi"\n' +
        '                                                                           placeholder="0" value="' + en + '" name="partDesiEn' + DesiPartRowCounter + '"\n' +
        '                                                                           data-inputmask="\'alias\': \'numeric\', \'autoGroup\': true, \'digits\': 0, \'digitsOptional\': false, \'prefix\': \'\', \'placeholder\': \'0\', \'max\':999, \'min\':0"\n' +
        '                                                                           im-insert="true" style="text-align: right;">\n' +
        '\n' +
        '                                                                </div>\n' +
        '                                                            </div>\n' +
        '\n' +
        '                                                            <div class="col-md-1">\n' +
        '                                                                <div class="position-relative ">\n' +
        '                                                                    <label for="partDesiBoy' + DesiPartRowCounter + '">Boy:</label>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="input-group mb-1">\n' +
        '                                                                    <input id="partDesiBoy' + DesiPartRowCounter + '" type="text" data="' + DesiPartRowCounter + '"\n' +
        '                                                                           class="form-control form-control-sm input-mask-trigger partDesiBoy partDesiCalc validate-part-desi"\n' +
        '                                                                           placeholder="0" value="' + boy + '" name="partDesiBoy' + DesiPartRowCounter + '"\n' +
        '                                                                           data-inputmask="\'alias\': \'numeric\', \'autoGroup\': true, \'digits\': 0, \'digitsOptional\': false, \'prefix\': \'\', \'placeholder\': \'0\', \'max\':999, \'min\':0"\n' +
        '                                                                           im-insert="true" style="text-align: right;">\n' +
        '                                                                </div>\n' +
        '                                                            </div>\n' +
        '\n' +
        '                                                            <div class="col-md-2">\n' +
        '                                                                <div class="position-relative ">\n' +
        '                                                                    <label for="partDesiYukseklik' + DesiPartRowCounter + '">Yükseklik:</label>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="input-group mb-1">\n' +
        '                                                                    <input id="partDesiYukseklik' + DesiPartRowCounter + '" data="' + DesiPartRowCounter + '"\n' +
        '                                                                           class="form-control form-control-sm input-mask-trigger partDesiYukseklik partDesiCalc validate-part-desi"\n' +
        '                                                                           placeholder="0" value="' + yukseklik + '" name="partDesiYukseklik' + DesiPartRowCounter + '"\n' +
        '                                                                           data-inputmask="\'alias\': \'numeric\', \'autoGroup\': true, \'digits\': 0, \'digitsOptional\': false, \'prefix\': \'\', \'placeholder\': \'0\', \'max\':999, \'min\':0"\n' +
        '                                                                           im-insert="true" style="text-align: right;">\n' +
        '                                                                </div>\n' +
        '                                                            </div>\n' +
        '\n' +
        '                                                            <div class="col-md-1">\n' +
        '                                                                <div class="position-relative ">\n' +
        '                                                                    <label for="partDesiAgirlik' + DesiPartRowCounter + '">Ağırlık:</label>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="input-group mb-1">\n' +
        '                                                                    <input id="partDesiAgirlik' + DesiPartRowCounter + '" data="' + DesiPartRowCounter + '"\n' +
        '                                                                           class="form-control form-control-sm input-mask-trigger partDesiAgirlik partDesiCalc validate-part-desi"\n' +
        '                                                                           placeholder="0" value="' + agirlik + '" name="partDesiAgirlik' + DesiPartRowCounter + '"\n' +
        '                                                                           data-inputmask="\'alias\': \'numeric\', \'autoGroup\': true, \'digits\': 0, \'digitsOptional\': false, \'prefix\': \'\', \'placeholder\': \'0\', \'max\':999, \'min\':0"\n' +
        '                                                                           im-insert="true" style="text-align: right;">\n' +
        '                                                                </div>\n' +
        '                                                            </div>\n' +
        '\n' +
        '                                                            <div class="col-md-2">\n' +
        '                                                                <div class="position-relative ">\n' +
        '                                                                    <label for="partTotalDesi' + DesiPartRowCounter + '">Desi:</label>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="input-group mb-1">\n' +
        '                                                                    <input disabled type="number" value="' + desi + '"\n' +
        '                                                                           id="partTotalDesi' + DesiPartRowCounter + '"\n' +
        '                                                                           class="form-control no-spin text-center font-weight-bold text-success form-control-sm">\n' +
        '                                                                </div>\n' +
        '                                                            </div>\n' +
        '\n' +
        '                                                            <div class="col-md-2">\n' +
        '                                                                <div class="position-relative ">\n' +
        '                                                                    <label for="partRealDesi' + DesiPartRowCounter + '">Ü.E.\n' +
        '                                                                        Ağr:</label>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="input-group mb-1">\n' +
        '                                                                    <input disabled type="number" value="' + uea + '"\n' +
        '                                                                           id="partRealDesi' + DesiPartRowCounter + '"\n' +
        '                                                                           class="form-control no-spin text-center font-weight-bold text-danger form-control-sm partRealDesi">\n' +
        '                                                                </div>\n' +
        '                                                            </div>\n' +
        '\n' +
        '                                                            <div class="col-md-2">\n' +
        '                                                                <div class="position-relative">\n' +
        '                                                                    <label\n' +
        '                                                                        for="partDesiHacim' + DesiPartRowCounter + '">Hacim\n' +
        '                                                                        (m<sup>3</sup>):</label>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="input-group mb-1">\n' +
        '                                                                    <input id="partDesiHacim' + DesiPartRowCounter + '" type="text" disabled\n' +
        '                                                                           class="form-control form-control-sm partDesiHacim text-center text-dark font-weight-bold"\n' +
        '                                                                           placeholder="0" value="' + hacim + '"\n' +
        '                                                                           im-insert="true" style="text-align: right;">\n' +
        '                                                                </div>\n' +
        '                                                            </div>\n' +
        '\n' +
        '                                                        </div>\n' +
        '                                                    </div>\n' +
        '                                                </div>';
    $('.cont').prepend(DesiPartRow);
}

$(document).on('click', '#btnAddNewPartDesiRow', function () {
    DesiPartRowCounter++;
    createDesiPartRow(DesiPartRowCounter);
    setRowStatus();

    $('.validate-part-desi').each(function () {
        $(this).rules('add', {
            required: true,
            number: true,
            min: 1,
        });
    });
});

$(document).on('click', '.destroyDesiRow', function () {
    let rowId = $(this).prop('id');
    $('#partDesiContainer' + rowId).remove();
    setRowStatus();
    setPartRealTotalDesi();
});

$(document).on('click', '#btnDeleteAllPartDesiRow', function () {
    $('.cont').html('');
    setRowStatus();
    DesiPartRowCounter = 0;
    setPartRealTotalDesi();
});

function setRowStatus() {
    var numItems = $('.partDesiContainer').length;
    $('#hRowSummery').html(numItems);
}

$(document).on('click', '#btnAddMultiplePartDesiRow', function () {
    $('#modalAddRowQuantity').modal();
});

$(document).on('click', '#btnAddMultipleRow', function () {
    // $('.cont').html('');
    // DesiPartRowCounter = 0;
    for (let i = 0; i < parseInt($('#multipleRowQuantity').val()); i++) {
        DesiPartRowCounter++;

        let en = isNaN(parseInt($('#enT').val())) ? 0 : parseInt($('#enT').val());
        let boy = isNaN(parseInt($('#BoyT').val())) ? 0 : parseInt($('#BoyT').val());
        let yukseklik = isNaN(parseInt($('#YukseklikT').val())) ? 0 : parseInt($('#YukseklikT').val());
        let agirlik = isNaN(parseInt($('#AgirlikT').val())) ? 0 : parseInt($('#AgirlikT').val());
        let totalDesi = isNaN(parseFloat($('#totalDesi').val())) ? 0 : parseFloat($('#totalDesi').val());
        let realDesi = isNaN(parseFloat($('#RealDesi').val())) ? 0 : parseFloat($('#RealDesi').val());
        let totalHacim = isNaN(parseFloat($('#TotalHacim').val())) ? 0 : parseFloat($('#TotalHacim').val());

        createDesiPartRow(DesiPartRowCounter, en, boy, yukseklik, agirlik, totalDesi, realDesi, totalHacim);
    }
    setRowStatus();
    setPartRealTotalDesi();
    $('#modalAddRowQuantity').modal('hide');

    $('.validate-part-desi').each(function () {
        $(this).rules('add', {
            required: true,
            number: true,
            min: 1,
        });
    });
});

$(document).on('click', '#btnCalculateTotalDesi', function () {
    CalculateDesi($('#RealDesi').val(), $('#totalPartNumber').val(), $('#btnCalculateTotalDesi'));
});
$(document).on('click', '#btnCalculatePartDesi', function () {
    // CalculateDesi(parseFloat($('#hPartsTotalDesi').text()), parseInt($('#hRowSummery').text()), $('#btnCalculatePartDesi'));
});

function CalculateDesi(RealDesi, PartNumber, clickButton) {

    if (RealDesi <= 0) {
        ToastMessage('error', 'Ücrete Esas Ağırlık (Desi) 0\'dan büyük olmalıdır!');
        return false;
    }

    if (PartNumber <= 0) {
        ToastMessage('error', 'Parça sayısı en az 1 olmalıdır!');
        return false;
    }

    $('#partQuantity').text(PartNumber);
    $('#labelDesi').text(RealDesi);

    let calcBtn = clickButton;
    calcBtn.prop('disabled', true);

    $('#tableSummery').block({
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
    $('.blockUI.blockMsg.blockElement').css('left', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/MainCargo/AjaxTransactions/GetPriceForCustomers', {
        method: 'POST',
        data: {
            _token: token,
            startPoint: CurrentCity,
            endPoint: $('#aliciIl').val(),
            desi: RealDesi,
            cargoType: CargoType,
            paymentType: $('input[name="radioPaymentType"]:checked').val(),
            gondericiCariKodu: $('#gondericiCariKod').val(),
            aliciCariKodu: $('#aliciCariKod').val(),
            desiData: getFormData($('#formPartDesiContainer')),
            partQuantity: $('#partQuantity').text(),
        }
    }).done(function (response) {

        $('#serviceFee').text(roundLikePHP(response.service_fee, 2));
        $('#postServicePrice').text(response.post_service_price);
        $('#heavyLoadCarryingCost').text(response.heavy_load_carrying_cost);
        $('#mobileServiceFee').text(response.mobile_service_fee);

        // DistancePrice = response.distance_price;
        // $('#labelDistancePrice').text(response.distance_price);
        calculateTotalPrice();
        $('#modalCalcDesi').modal('hide');


        // if ($('#selectCargoType').val() != 'Paket') {
        //     if (RealDesi > 1 && RealDesi < 5) {
        //         ToastMessage('warning', 'Kargo türü paket olarak değiştirildi!', 'Bilgi!');
        //         $('#selectCargoType').val('Paket');
        //     }
        // } else {
        //     if (RealDesi >= 5) {
        //         ToastMessage('warning', 'Kargo türü koli olarak değiştirildi!', 'Bilgi!');
        //         $('#selectCargoType').val('Koli');
        //     }
        // }
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status)
    }).always(function () {
        $('#tableSummery').unblock();
        calcBtn.prop('disabled', false);
    });
}

// ==================> ############### <======================= \\
// ==================> CALC DESI END <======================= \\
// ==================> ############### <======================= \\


$('#add-service-tahsilatli').click(function () {

    if ($('#add-service-tahsilatli').prop('checked') == false) {
        $('#TahsilatFaturaTutari').prop('readonly', true);
    } else {
        $('#TahsilatFaturaTutari').prop('readonly', false);
        ToastMessage('info', 'Tahsilatlı kargo aktif, lütfen tahsilat fatura tutarını giriniz.', 'Bilgi');

        if ($('#add-service-tahsilatli').prop('checked') == true && $('input[name="radioPaymentType"]:checked').val() == 'Alıcı Ödemeli')
            ToastMessage('error', 'Alıcı ödemeli tahsilatlı kargo çıkaramazsınız. Sadece gönderici ödemeli tahsilatlı kargo çıkarılabilir!', 'Hata!');

        $('#TahsilatFaturaTutari').trigger('keyup');
    }
});

$(document).ready(function () {

    $("#formPartDesiContainer").validate({
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        submitHandler: function (form) {
            // your ajax would go here
            // alert('simulated ajax submit');
            CalculateDesi(parseFloat($('#hPartsTotalDesi').text()), parseInt($('#hRowSummery').text()), $('#btnCalculatePartDesi'));
            return false;  // blocks regular submit since you have ajax
        }
    });

    $('.validate-part-desi').each(function () {
        $(this).rules('add', {
            required: true,
            number: true,
            min: 1,
        });
    });

})

function checkCurrent() {

    $.ajax('/MainCargo/AjaxTransactions/GetCustomer', {
        method: 'POST',
        data: {
            _token: token,
            type: 'current',
            currentCode: $('#gondericiCariKod').val(),
        }
    }).done(function (response) {
        if (response.name != $('#gondericiAdi').val()) {
            ToastMessage('error', 'Gönderici cari kodu ile gönderici bilgileri eşleşmiyor, lütfen gönderici bilgilerini doğru seçtiğinizden emin olun!', 'Hata!');
            clearPage();
            return false;
        } else {

            if ($('#add-service-tahsilatli').prop('checked') == true && (response.category != 'Kurumsal' || response.current_type != 'Gönderici')) {
                ToastMessage('error', 'Yalnızca Kurumsal-Anlaşmalı cariler tahsilatlı kargo çıkartabilir.', 'Hata!');
                clearPage();
                $('.app-main__inner').unblock();
                return false;
            } else
                checkReceiver();
        }

    }).error(function (jqXHR, exception) {
        clearPage();
        ajaxError(jqXHR.status)
    }).always(function () {

    });
}

function checkReceiver() {

    $('#btnCargoComplate').prop('disabled', true);
    $.ajax('/MainCargo/AjaxTransactions/GetCustomer', {
        method: 'POST',
        data: {
            _token: token,
            type: 'receiver',
            currentCode: $('#aliciCariKod').val(),
        }
    }).done(function (response) {

        if (response.name != $('#aliciAdi').val()) {
            ToastMessage('error', 'Alıcı cari kodu ile alıcı bilgileri eşleşmiyor, lütfen alıcı bilgilerini doğru seçtiğinizden emin olun!', 'Hata!');
            clearPage();
            return false;
        } else
            createCargo();

    }).error(function (jqXHR, exception) {
        clearPage();
        ajaxError(jqXHR.status)
    }).always(function () {

    });
}


function getFormData($form) {

    // $('.add-fee').prop('disabled', false);

    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    let disableElements = $(':disabled:not(:radio)');

    let disableElementsArray = [];
    for (let i = 0; i < disableElements.length; i++) {
        disableElementsArray.push(i);
        $('#' + disableElements[i].id).prop('disabled', false);
    }

    $.map(unindexed_array, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });

    setTimeout(function () {
        for (let i = 0; i < disableElementsArray.length; i++) {
            $('#' + disableElements[i].id).prop('disabled', true);
        }
    }, 150)

    return indexed_array;
}


function createCargo() {

    // Check Parts Of Cargo Start
    if (CargoType == 'Koli') {
        let ParcaSayisi = parseInt($('#partQuantity').text());
        let tabloParcaSayisi = parseInt($('#hRowSummery').text());

        if (ParcaSayisi != tabloParcaSayisi) {
            ToastMessage('error', 'Hesaplanan parça sayısı (' + ParcaSayisi + ') ile girilen parça sayısı (' + tabloParcaSayisi + ') eşleşmiyor, lütfen desiyi tekrar hesaplayınız.', 'Hata!');
            clearPage();
            return false;
        }
    }

    // Check Parts Of Cargo End

    let desiData = getFormData($('#formPartDesiContainer'));
    let addServicesData = getFormData($('#formAdditionalServices'));

    //# Create Order Start
    $('#btnCargoComplate').prop('disabled', true);
    $.ajax('/MainCargo/AjaxTransactions/CreateCargo', {
        method: 'POST',
        data: {
            _token: token,
            gondericiCariKodu: $('#gondericiCariKod').val(),
            aliciCariKodu: $('#aliciCariKod').val(),
            gonderiTuru: $('#selectCargoType').val(),
            cargoType: CargoType,
            odemeTipi: $('input[name="radioPaymentType"]:checked').val(),
            desi: $('#labelDesi').text(),
            parcaSayisi: $('#partQuantity').text(),
            tahsilatliKargo: $('#add-service-tahsilatli').prop('checked'),
            faturaTutari: $('#TahsilatFaturaTutari').val(),
            musteriKodu: $('#musteriKodu').val(),
            desiData: desiData,
            addServicesData: addServicesData,
            mesafe: $('#distance').text(),
            mesafeUcreti: $('#labelDistancePrice').text(),
            ekHizmetFiyat: $('#addFeePrice').text(),
            hizmetUcreti: $('#serviceFee').text(),
            postaHizmetleriUcreti: $('#postServicePrice').text(),
            agirYukTasimaBedeli: $('#heavyLoadCarryingCost').text(),
            genelToplam: $('#totalPrice').text(),
            totalHacim: $('#hPartsTotalM3').text(),
            // kargoIcerigi: $('#cargoIcerigi').val(),
            kargoIcerigiAciklama: $('#explantion').val(),
            collectionDetails: {
                collectionType: $('#selectCollectionType').val(),
                collectionConfirmationCode: $('#tahsilatOnayKodu').val(),
                collectionCardOwner: $('#tahsilatKartSahibi').val(),
                collectionDescription: $('#tahsilatAciklama').val(),
            }
        }
    }).done(function (response) {

        if (response.status == -1) {
            ToastMessage('error', response.message, 'Hata!');
            clearPage();
            return false;
        } else if (response.status == 1) {
            ToastMessage('success', response.message, 'İşlem Başarılı!');

            window.onbeforeunload = null;
            if ($('#seriMod').prop('checked') == true) {
                localStorage.setItem('seriMod', true);
                localStorage.setItem('currentCode', $('#gondericiCariKod').val());
                window.location.reload();
            } else {
                localStorage.setItem('cargo-success', true);
                location.href = "/";
            }

            $('#btnCargoComplate').prop('disabled', true);
            return false;
        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        }

    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status)
        clearPage();
        $('.app-main__inner').unblock();
    }).always(function () {

    });
    //# Create Order End
}

function clearPage() {
    $('#btnCargoComplate').prop('disabled', false);
    $('.app-main__inner').unblock();
}

$('#btnCargoComplate').click(delay(function () {

    var goCreate = true;

    let GondericiAdi = $('#gondericiAdi').val();
    let GondericiCariKodu = $('#gondericiCariKod').val();

    let AliciAdi = $('#aliciAdi').val();
    let AliciCariKodu = $('#aliciCariKod').val();


    if (GondericiAdi == null || GondericiCariKodu == '') {
        ToastMessage('error', 'Lütfen Göndericiyi Seçiniz.', 'Hata');
        return false;
    }

    if (AliciAdi == null || AliciCariKodu == '') {
        ToastMessage('error', 'Lütfen Alıcıyı Seçiniz.', 'Hata');
        return false;
    }

    if ($('#cargoIcerigi').val() == '') {
        ToastMessage('error', 'Lütfen Kargo İçeriğini Giriniz!', 'Hata');
        return false;
    }

    if ($('#add-service-tahsilatli').prop('checked') == true && $('input[name="radioPaymentType"]:checked').val() == 'Alıcı Ödemeli') {
        ToastMessage('error', 'Alıcı ödemeli tahsilatlı kargo çıkaramazsınız. Sadece gönderici ödemeli tahsilatlı kargo çıkarılabilir!', 'Hata!');
        return false;
    }

    let lengthOfCoast = $('#TahsilatFaturaTutari').val().length;
    if ($('#add-service-tahsilatli').prop('checked') == true) {
        if ($('#TahsilatFaturaTutari').val() == '') {
            ToastMessage('error', 'Lütfen tahsilat fatura tutarını giriniz.', 'Hata');
            goCreate = false;
            return false;
        } else if (parseFloat($('#TahsilatFaturaTutari').val().substr(1, lengthOfCoast - 1)) <= 0) {
            ToastMessage('error', 'Tahsilat fatura tutarı 0\'dan büyük olmalıdır!', 'Hata');
            goCreate = false;
            return false;
        }
    }

    if (CargoType == 'Koli' && parseFloat($('#labelDesi').text()) == 0) {
        ToastMessage('error', 'Lütfen kargo için desi bilgisi giriniz!', 'Hata');
        goCreate = false;
        return false;
    }

    if ($('input[name="radioPaymentType"]:checked').val() == 'Gönderici Ödemeli') {
        if ($('#selectCollectionType').val() == 'POS' && $('#tahsilatOnayKodu').val().trim() == '') {
            ToastMessage('error', 'Lütfen postan çıkan fiş üzerindeki onay kodunu giriniz.', 'Hata');
            goCreate = false;
            return false;
        }

        if ($('#selectCollectionType').val() == 'POS' && $('#tahsilatKartSahibi').val().trim() == '') {
            ToastMessage('error', 'Lütfen postan çekim yaptığınız kart sahibinin ad soyad bilgisini girin.', 'Hata');
            goCreate = false;
            return false;
        }
    }

    $('#btnCargoComplate').prop('disabled', true);

    $('.app-main__inner').block({
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
    $('.blockUI.blockMsg.blockElement').css('left', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    // Check name and current codes Start
    // # checkCurrent => checkReceiver => createCargo
    checkCurrent();
    // Check name and current codes End


}, 400));

//#adrese teslim
$('input[name="add-service-8"]').click(function () {
    if ($(this).prop('checked') == true)
        $('input[name="add-service-11"]').prop('checked', false);
    else
        $('input[name="add-service-11"]').prop('checked', true);

    surfAddServices();
});

//#şube teslim
$('input[name="add-service-11"]').click(function () {

    if ($(this).prop('checked') == true)
        $('input[name="add-service-8"]').prop('checked', false);
    else
        $('input[name="add-service-8"]').prop('checked', true);
    surfAddServices();
});

function surfAddServices() {
    let addServicePrice = 0;
    $('input[type="checkbox"].add-fee').each(function () {
        if ($(this).prop('checked') == true) {
            addServicePrice += parseFloat($(this).attr('value'));
        }
    });
    $('#addFeePrice').text(addServicePrice);
    calculateTotalPrice();
}

$('#TahsilatFaturaTutari').keyup(delay(function () {

    if ($('#add-service-tahsilatli').prop('checked') == true) {

        $('.collection-container').block({
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
        $('.blockUI.blockMsg.blockElement').css('left', '0px');
        $('.blockUI.blockMsg.blockElement').css('background-color', '');


        $.ajax('/MainCargo/AjaxTransactions/CalcCollectionPercent', {
            method: 'POST',
            data: {
                _token: token,
                currentCode: $('#gondericiCariKod').val(),
                receiverCode: $('#aliciCariKod').val(),
                collectionPrice: $('#TahsilatFaturaTutari').val()
            }
        }).done(function (response) {
            if (response.status == -1) {
                ToastMessage('error', response.message, 'Hata!');
                $('#tahsilatOdenecekTutar').val(0);
                $('#tahsilatKesintiTutari').val(0);
            } else if (response.status == 1) {
                $('#tahsilatOdenecekTutar').val(response.to_be_paid);
                $('#tahsilatKesintiTutari').val(response.interruption);
            }

        }).error(function (jqXHR, exception) {
            ajaxError(jqXHR.status)
        }).always(function () {
            $('.collection-container').unblock();
        });

    }

}, 1000));


function myConfirmation() {
    return 'Are you sure you want to quit?';
}

window.onbeforeunload = myConfirmation;


$('#btnClearSenderInfo').click(function () {
    $('#gondericiAdi').text('');
    $('#GondericiTelefon').val('');
});

$('#btnClearReceiverInfo').click(function () {
    $('#aliciAdi').text('');
    $('#AliciTelefon').val('');
});

$('#gondericiAdi').change(function () {
    $('#GondericiTelefon').val('');
});

$('#aliciAdi').change(function () {
    $('#AliciTelefon').val('');
});














