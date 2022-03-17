$('#city').change(function () {
    getDistricts('#city', '#district');
});

$('#dispatchCity').change(function () {
    getDistricts('#dispatchCity', '#dispatchDistrict');
});

$('#district').change(function () {
    getNeigborhoods('#district', '#neighborhood');

});

$(document).ready(function () {
    $('#tax-office').select2({
        ajax: {
            url: "/Marketing/SenderCurrents/AjaxTransaction/GetTaxOffices",
            type: "post",
            delay: 450,
            data: function (params) {
                return {
                    _token: token,
                    SearchTerm: (params.term), // search term,
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.office + " - " + item.city + "/" + item.district,
                            id: item.office
                        }
                    })
                };
            },
        },
        language: "es",
        cache: true,
        placeholder: "Vergi Dairesi Arayın",
    });

});

$('#btnCreateCurrent').click(function () {


});

$("#currentForm").submit(function (e) {

    let goPost = true;

    if ($('#agency').val() == null) {
        ToastMessage('error', 'Bağlanacak acenteyi seçin!', 'Hata!');
        goPost = false;
    }

    if ($('#tax-office').val() == null) {
        ToastMessage('error', 'Vergi dairesi alanı zorunludur!', 'Hata!');
        goPost = false;
    }

    if ($('#street').val().trim() == '' && $('#street2').val().trim() == '') {
        ToastMessage('error', 'Cadde veya Sokak alanlarından en az 1 tanesi zorunludur!');
        goPost = false;
    }

    if (goPost == false)
        e.preventDefault();
});


function getNeigborhoods(districtElement, neighborhoodElement) {

    var district_id = $(districtElement).val();

    $.post('/Ajax/DistrictToNeighborhood', {
        _token: token,
        district_id: district_id
    }, function (response) {

        $(neighborhoodElement).html('');
        $(neighborhoodElement).append(
            '<option  value="">Mahalle Seçin</option>'
        );
        $.each(response, function (key, value) {
            $(neighborhoodElement).append(
                '<option id="' + (value['key']) + '"  value="' + (value['key']) + '">' + (value['name']) + '</option>'
            );
        });
        $(neighborhoodElement).prop('disabled', false);
    });
}

$('#priceDraft').change(function () {
    val = $(this).val()
    if (val != '0')
        $('.price-of-cargo').prop('readonly', true)
    else
        $('.price-of-cargo').prop('readonly', false)

    $('.container-cargo-price').block(whiteAnimation);
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/Marketing/GetPriceDraft', {
        method: 'POST',
        data: {
            _token: token,
            id: $('#priceDraft').val()
        }
    }).done(function (response) {

        if (response.status == 1) {
            data = response.data
            $('#filePrice').val(data.file)
            $('#miPrice').val(data.mi)
            $('#d1_5').val(data.d_1_5)
            $('#d6_10').val(data.d_6_10)
            $('#d11_15').val(data.d_11_15)
            $('#d16_20').val(data.d_16_20)
            $('#d21_25').val(data.d_21_25)
            $('#d26_30').val(data.d_26_30)
            $('#amountOfIncrease').val(data.amount_of_increase)
        } else if (response.status == -1) {
            ToastMessage('error', response.message, 'Hata!')
        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status, JSON.parse(jqXHR.responseText));
    }).always(function () {
        $('.container-cargo-price').unblock();
    });

});
