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
            url: "/SenderCurrents/AjaxTransaction/GetTaxOffices",
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

    $('#agency').select2({
        ajax: {
            url: "/SenderCurrents/AjaxTransaction/GetAgencies",
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
                            text: item.agency_name + " - " + item.city + "/" + item.district,
                            id: item.id
                        }
                    })
                };
            },
        },
        language: "es",
        cache: true,
        placeholder: "Acente Arayın",
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
