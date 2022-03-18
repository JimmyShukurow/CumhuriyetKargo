$(document).on('change', '#selectReciverCategory', function () {

    if ($('#selectReciverCategory').val() == 'Bireysel') {
        $('#containerReciverIndividual').show()
        $('#containerReciverCorporate').hide()
    } else {
        $('#containerReciverIndividual').hide()
        $('#containerReciverCorporate').show()
    }
})

$(document).on('change', '#receiverCity', function () {
    getDistricts('#receiverCity', '#receiverDistrict', '#receiverNeighborhood')
})

$(document).on('change', '#receiverDistrict', function () {
    getNeigborhoods('#receiverDistrict', '#receiverNeighborhood')
})


function receiverConfirmValues(goPost) {

    // console.log(goPost);

    if ($('#selectReciverCategory').val().trim() == '') {
        ToastMessage('error', 'Lütfen alıcı kategorsini seçin!');
        goPost = false;
        return goPost;
    }

    if ($('#selectReciverCategory').val() == 'Bireysel') {

        if ($('#receiverIndividualName').val().trim() == '') {
            ToastMessage('error', 'Alıcı Adı alanı zorunludur!');
            goPost = false;
        }

        if ($('#receiverIndividualSurname').val().trim() == '') {
            ToastMessage('error', 'Alıcı Soyadı alanı zorunludur!');
            goPost = false;
        }

    } else if ($('#selectReciverCategory').val() == 'Kurumsal') {

        if ($('#receiverCompanyName').val().trim() == '') {
            ToastMessage('error', 'Alıcı Firma Ünvanı alanı zorunludur!');
            goPost = false;
        }
    }

    if ($('#receiverGSM').val().trim() == '') {
        ToastMessage('error', 'Alıcı Cep Telefonu alanı zorunludur!');
        goPost = false;
    }

    if ($('#receiverCity').val().trim() == '') {
        ToastMessage('error', 'Alıcı İl alanı zorunludur!');
        goPost = false;
    }

    if ($('#receiverDistrict').val().trim() == '') {
        ToastMessage('error', 'Alıcı İlçe alanı zorunludur!');
        goPost = false;
    }

    if ($('#receiverNeighborhood').val().trim() == '') {
        ToastMessage('error', 'Alıcı Mahalle alanı zorunludur!');
        goPost = false;
    }

    if ($('#receiverStreet').val().trim() == '' && $('#receiverStreet2').val().trim() == '') {
        ToastMessage('error', 'Alıcı Cadde veya Sokak alanlarından en az 1 tanesi zorunludur!');
        goPost = false;
    }

    if ($('#receiverBuildingNo').val().trim() == '') {
        ToastMessage('error', 'Alıcı Bina No alanı zorunludur!');
        goPost = false;
    }

    if ($('#receiverDoorNo').val().trim() == '') {
        ToastMessage('error', 'Alıcı Kapı No alanı zorunludur!');
        goPost = false;
    }

    if ($('#receiverFloor').val().trim() == '') {
        ToastMessage('error', 'Alıcı Kapı No alanı zorunludur!');
        goPost = false;
    }
    // console.log(goPost);

    return goPost;
}


$(document).on('click', '#btnSaveReceiver', function () {

    let btnSaveReceiver = $('#btnSaveReceiver');
    var goPost = true;
    goPost = receiverConfirmValues(goPost);
    // console.log(goPost);
    if (goPost == true) {

        ToastMessage('warning', 'İstek alındı, işleniyor!', 'Bilgi');
        btnSaveReceiver.prop('disabled', true);

        $.ajax('/MainCargo/AjaxTransactions/SaveReceiver', {
            method: 'POST',
            data: {
                _token: token,
                kategori: $('#selectReciverCategory').val(),
                ad: $('#selectReciverCategory').val() != 'Kurumsal' ? $('#receiverIndividualName').val() : $('#receiverAuthorizedName').val(),
                soyad: $('#selectReciverCategory').val() != 'Kurumsal' ? $('#receiverIndividualSurname').val() : $('#receiverAuthorizedSurname').val(),
                tckn: $('#receiverTCKN').val(),
                vkn: $('#receiverVKN').val(),
                firma_unvani: $('#receiverCompanyName').val(),
                telefon: $('#receiverPhone').val(),
                cep_telefonu: $('#receiverGSM').val(),
                email: $('#receiverEmail').val(),
                il: $('#receiverCity').val(),
                ilce: $('#receiverDistrict').val(),
                mahalle_koy: $('#receiverNeighborhood').val(),
                cadde: $('#receiverStreet').val(),
                sokak: $('#receiverStreet2').val(),
                bina_no: $('#receiverBuildingNo').val(),
                daire_no: $('#receiverDoorNo').val(),
                kat_no: $('#receiverFloor').val(),
                adres_notu: $('#receiverAdress').val()
            }
        }).done(function (response) {
            if (response.status == 1) {
                ToastMessage('success', '', response.message);
                $('#btnSaveCurrent').prop('disabled', false);
                tcConfirmed = true;


                if (componentReceiverFrom == 'create-receiver') {
                    setMessageToLS('İşlem Başarılı!', 'Alıcı oluşturuldu!', 'success')
                    location.reload()
                } else if (componentReceiverFrom == 'create-cargo'){
                    getReceiverInfo(response.current_code);
                    $('#modalNewReceiver').modal('hide');
                }

            } else if (response.status == -1) {
                $.each(response.errors, function (index, value) {
                    ToastMessage('error', value, 'Hata!')
                });
            } else if (response.status == 0)
                ToastMessage('error', response.message, 'Hata!');

        }).error(function (jqXHR, exception) {
            ajaxError(jqXHR.status)
        }).always(function () {
            btnSaveReceiver.prop('disabled', false);
        });
    }

});
