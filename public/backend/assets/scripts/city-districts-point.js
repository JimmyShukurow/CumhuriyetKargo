// #Example Usage

// $(document).on('change', '#currentCity', function () {
//     getDistricts('#currentCity', '#currentDistrict');
// });

// $(document).on('change', '#receiverCity', function () {
//     getDistricts('#receiverCity', '#receiverDistrict', '#receiverNeighborhood');
// });

// $(document).on('change', '#receiverDistrict', function () {
//     getNeigborhoods('#receiverDistrict', '#receiverNeighborhood');
// });

function getDistricts(cityElement, districtElement, neighborhoodElement = 'null') {

    $.post('/Ajax/CityToDistrict', {
        _token: token,
        city_id: $(cityElement).val()
    }, function (response) {
        $(districtElement).html('');
        $(districtElement).append(
            '<option  value="">İlçe Seçin</option>'
        );
        $.each(response, function (key, value) {
            $(districtElement).append(
                '<option id="' + (value['key']) + '"  value="' + (value['key']) + '">' + (value['name']) + '</option>'
            );
        });
        $(districtElement).prop('disabled', false);
    });

    if (neighborhoodElement != 'null') {
        $(neighborhoodElement).prop('disabled', true);
        $(neighborhoodElement).html('');
        $(neighborhoodElement).append('<option value="">Mahalle Seçin</option>');
    }

}

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
