var tcConfirmed = false;

$(document).on('click', '#btnTCConfirm', function () {

    let confirmBtn = $('#btnTCConfirm');

    if ($('#currentSelectCategory').val() == 'Bireysel') {

        var goPost = false;
        goPost = currentConfirmTCConfirmValues();

        if (goPost == true) {

            ToastMessage('warning', 'İstek alındı, işleniyor!', 'Bilgi');
            confirmBtn.prop('disabled', true);

            confirmBtn.prop('disabled', true);
            $.ajax('/MainCargo/AjaxTransactions/ConfirmTC', {
                method: 'POST',
                data: {
                    _token: token,
                    ad: $('#currentName').val(),
                    soyad: $('#currentSurName').val(),
                    tc: $('#currentTckn').val(),
                    dogum_tarihi: $('#currentYearOfBirth').val()
                }
            }).done(function (response) {
                if (response.status == 0)
                    ToastMessage('error', response.message, 'Hata!');

                if (response.status == 1) {
                    ToastMessage('success', '', 'İşlem Başarılı, Kişi Doğrulandı!');
                    $('#btnSaveCurrent').prop('disabled', false);
                    tcConfirmed = true;
                }
            }).error(function (jqXHR, exception) {
                ajaxError(jqXHR.status)
            }).always(function () {
                confirmBtn.prop('disabled', false);
            });
        }
    } else if ($('#currentSelectCategory').val() == 'Kurumsal') {

        ToastMessage('warning', 'İstek alındı, işleniyor!', 'Bilgi');
        confirmBtn.prop('disabled', true);

        confirmBtn.prop('disabled', true);
        $.ajax('/Customers/AjaxTransaction/ConfirmCurrentWithVKN', {
            method: 'POST',
            data: {
                _token: token,
                vkn: $('#currentVkn').val(),
                city: $('#currentTaxOfficeCity').val(),
                taxOffice: $('#currentTaxOffice').val()
            }
        }).done(function (response) {
            if (response.status == 0) {
                ToastMessage('error', response.message, 'Hata!');
                $('#currentTitle').val('')
            }
            if (response.status == 1) {
                $('#currentTitle').val(response.data.unvan)
                ToastMessage('success', '', 'Doğrulama işlemi başarılı!');
                $('#btnSaveCurrent').prop('disabled', false);
                tcConfirmed = true;
            }
        }).error(function (jqXHR, exception) {
            ajaxError(jqXHR.status)
        }).always(function () {
            confirmBtn.prop('disabled', false);
        });

    }

});

$(document).on('change', '#currentCity', function () {
    getDistricts('#currentCity', '#currentDistrict');
});

$(document).on('change', '#currentDistrict', function () {
    getNeigborhoods('#currentDistrict', '#currentNeighborhood');
});


$(document).on('click', '#btnSaveCurrent', function () {

    let saveBtn = $('#btnSaveCurrent');
    if (tcConfirmed == false) {
        ToastMessage('error', 'Önce kimlik bilgilerini doğrulayın!', 'Hata!');
        return false;
    }

    var goPost = false;
    goPost = currentConfirmTCConfirmValues();
    goPost = currentSaveConfirmValues(goPost);

    if (goPost == true) {

        ToastMessage('warning', 'İstek alındı, işleniyor!', 'Bilgi');
        saveBtn.prop('disabled', true);

        saveBtn.prop('disabled', true);
        $.ajax('/MainCargo/AjaxTransactions/SaveCurrent', {
            method: 'POST',
            data: {
                _token: token,
                kategori: $('#currentSelectCategory').val(),
                ad: $('#currentName').val(),
                soyad: $('#currentSurName').val(),
                tc: $('#currentTckn').val(),
                dogum_tarihi: $('#currentYearOfBirth').val(),
                vkn: $('#currentVkn').val(),
                vd_sehir: $('#currentTaxOfficeCity').val(),
                vergi_dairesi: $('#currentTaxOffice').val(),
                yetkili_adi: $('#senderCurrentAuthNameSurname').val(),
                telefon: $('#currentPhone').val(),
                cep_telefonu: $('#currentGSM').val(),
                email: $('#currentEmail').val(),
                il: $('#currentCity').val(),
                ilce: $('#currentDistrict').val(),
                mahalle: $('#currentNeighborhood').val(),
                cadde: $('#currentStreet').val(),
                sokak: $('#currentStreet2').val(),
                bina_no: $('#currentBuildingNo').val(),
                daire_no: $('#currentDoorNo').val(),
                kat_no: $('#currentFloor').val(),
                adres_notu: $('#currentAdressNote').val()
            }
        }).done(function (response) {
            if (response.status == 1) {
                ToastMessage('success', '', response.message);

                if (componentSenderFrom == 'create-sender') {
                    setMessageToLS('İşlem Başarılı!', 'Gönderici oluşturuldu!', 'success')
                    location.reload()
                } else if (componentSenderFrom == 'create-cargo') {
                    $('#btnSaveCurrent').prop('disabled', false);
                    tcConfirmed = true;
                    getCurrentInfo(response.current_code)
                    $('#modalNewCurrent').modal('hide');
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
            saveBtn.prop('disabled', false);
        });
    }
});


function currentConfirmTCConfirmValues() {
    var goPost = true;

    currentCategory = $('#currentSelectCategory').val();
    if (currentCategory == 'Bireysel') {

        if ($('#currentTckn').val().trim() == '') {
            ToastMessage('error', 'Gönderici TCKN alanını doldurunuz!');
            goPost = false;
        }

        if ($('#currentName').val().trim() == '') {
            ToastMessage('error', 'Gönderici Adı alanını doldurunuz!');
            goPost = false;
        }

        if ($('#currentSurName').val().trim() == '') {
            ToastMessage('error', 'Gönderici Soyadı alanını doldurunuz!');
            goPost = false;
        }

        if ($('#currentYearOfBirth').val().trim() == '') {
            ToastMessage('error', 'Gönderici Doğum Yılı alanını doldurunuz!');
            goPost = false;
        }
    } else if (currentCategory == 'Kurumsal') {

        if ($('#currentVkn').val().trim() == '') {
            ToastMessage('error', 'Gönderici VKN alanını doldurunuz!');
            goPost = false;
        }

        if ($('#currentTaxOfficeCity').val().trim() == '') {
            ToastMessage('error', 'Gönderici V.D. Şehir alanını doldurunuz!');
            goPost = false;
        }

        if ($('#senderCurrentAuthNameSurname').val().trim() == '') {
            ToastMessage('error', 'Gönderici Yetkili adı soyadı alanını doldurunuz!');
            goPost = false;
        }

    }

    return goPost;
}

function currentSaveConfirmValues(goPost) {

    if ($('#currentGSM').val().trim() == '') {
        ToastMessage('error', 'Cep Telefonu alanı zorunludur!');
        goPost = false;
    }

    if ($('#currentCity').val().trim() == '') {
        ToastMessage('error', 'İl alanı zorunludur!');
        goPost = false;
    }

    if ($('#currentDistrict').val().trim() == '') {
        ToastMessage('error', 'İlçe alanı zorunludur!');
        goPost = false;
    }

    if ($('#currentNeighborhood').val().trim() == '') {
        ToastMessage('error', 'Mahalle alanı zorunludur!');
        goPost = false;
    }

    if ($('#currentStreet').val().trim() == '' && $('#currentStreet2').val().trim() == '') {
        ToastMessage('error', 'Gönderici Cadde veya Sokak alanlarından en az 1 tanesi zorunludur!');
        goPost = false;
    }

    if ($('#currentBuildingNo').val().trim() == '') {
        ToastMessage('error', 'Bina no alanı zorunludur!');
        goPost = false;
    }

    if ($('#currentDoorNo').val().trim() == '') {
        ToastMessage('error', 'Daire No alanı zorunludur!');
        goPost = false;
    }

    if ($('#currentFloor').val().trim() == '') {
        ToastMessage('error', 'Kat no alanı zorunludur!');
        goPost = false;
    }

    return goPost;
}


$(document).on('change', '#currentSelectCategory', function () {
    changeCurrentSelectCategory()
})

$('#currentSelectCategory').change(function () {
    changeCurrentSelectCategory()
})

function changeCurrentSelectCategory() {

    if ($('#currentSelectCategory').val() == 'Bireysel') {
        $('#divIndividualContainer').show()
        $('#divCorporateContainer').hide()
    } else if ($('#currentSelectCategory').val() == 'Kurumsal') {
        $('#divIndividualContainer').hide()
        $('#divCorporateContainer').show()
    }
}

$('#currentTaxOfficeCity').change(function () {
    changeCurrentTaxOfficeCitiy()
})

$(document).on('change', '#currentTaxOfficeCity', function () {
    changeCurrentTaxOfficeCitiy()
})


function changeCurrentTaxOfficeCitiy() {
    if ($('#currentTaxOfficeCity').val() == '') {
        $('#currentTaxOffice').html('');
        $('#currentTaxOffice').append('<option value="">İl Seçiniz</option>');
        $('#currentTaxOffice').prop('disabled', true)
        return false;
    }

    $.ajax('/Customers/AjaxTransaction/TaxOffices', {
        method: 'POST',
        data: {
            _token: token,
            plaque: $('#currentTaxOfficeCity').val()
        }
    }).done(function (response) {

        if (response.status == 1) {

            $('#currentTaxOffice').html('');
            $('#currentTaxOffice').append('<option value="">VD Seçin</option>')
            $.each(response.data, function (key, val) {
                $('#currentTaxOffice').append('<option value="' + val['code'] + '">' + val['tax_office'] + '</option>');
            })
            $('#currentTaxOffice').prop('disabled', false)

        } else if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!')
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status, JSON.parse(jqXHR.responseText));
    }).always(function () {
        $('#ModalCargoDetails').unblock();
    });
}
