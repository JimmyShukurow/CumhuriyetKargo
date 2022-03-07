$(document).on('change', '#cityX', function () {
    getDistricts('#cityX', '#districtX');
    $('#TbodyNeighborhoods').html('');
});

$(document).on('change', '.select-all-cb-ab', function () {
    $('input:checkbox:not(:disabled).location-ab').prop('checked', this.checked);

    if ($(this).prop('checked') == true) {
        $('input:checkbox:not(:disabled).location-mb').prop('checked', false);
        $('#cb-select-all-mb').prop('checked', false);
    }

});

$(document).on('change', '.select-all-cb-mb', function () {
    $('input:checkbox:not(:disabled).location-mb').prop('checked', this.checked);

    if ($(this).prop('checked') == true) {
        $('input:checkbox:not(:disabled).location-ab').prop('checked', false);
        $('#cb-select-all-ab').prop('checked', false);
    }

});


$(document).on('click', '.location-mb', function () {
    let cb_id = $(this).prop('value');

    if ($(this).prop('checked') == true)
        $('#ab-' + cb_id).prop('checked', false);
});

$(document).on('click', '.location-ab', function () {
    let cb_id = $(this).prop('value');

    if ($(this).prop('checked') == true)
        $('#mb-' + cb_id).prop('checked', false);
});


$(document).on('click', '#SaveBtn', function () {

    $('#SaveBtn').prop('disabled', true);

    var NeighborhoodArray = [];
    $("input.check-give-district-to-region[type=checkbox]:checked:not(:disabled)").each(function () {
        NeighborhoodArray.push($(this).prop('id'));
    });

    $.ajax('/Operation/LocalLocation', {
            method: 'POST',
            data: {
                _token: token,
                city_id: $('#cityX').val(),
                district_id: $('#districtX').val(),
                agency_code: $('#selectAgency').val(),
                neighborhood_array: NeighborhoodArray,
            }
        }
    ).done(function (response) {

        if (response.status == 1) {
            ToastMessage('success', 'Mahalleler tanımlandı!', 'İşlem Başarılı!');
            $('#ModalCityDistrictNeighborhoods').modal('hide');
            $('#districtX').val('');
            $('#city').val('');
            $('#TbodyNeighborhoods').html('');
            $('#AgenciesTable').DataTable().ajax.reload();
        } else {
            ToastMessage('error', response.message, 'Hata!');
        }

        $('#TbodyRegionalDistricts').html('');
        $('.select-all-cb').prop('checked', false);

        $('#SaveBtn').prop('disabled', false);


    }).always(function () {
        $('#SaveBtn').prop('disabled', false);
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status)
    });

});


$(document).on('change', '#districtX', function () {

    $('#modalBodyTCDistricts.modal-body').block({
        message: $('<div class="loader mx-auto">\n' +
            '                            <div class="ball-pulse-sync">\n' +
            '                                <div class="bg-warning"></div>\n' +
            '                                <div class="bg-warning"></div>\n' +
            '                                <div class="bg-warning"></div>\n' +
            '                            </div>\n' +
            '                        </div>')
    });
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/Operation/GetNeighborhoodsOfAgency', {
        method: 'POST',
        data: {
            _token: token,
            agency: $('#selectAgency').val(),
            city: $('#cityX').val(),
            district: $('#districtX').val(),
        }
    }).done(function (response) {

        $('#TbodyNeighborhoods').html('');

        $.each(response, function (key, value) {

            let stringAB = '', stringMB = '', stringAgencyName = '';

            if (value['count'] == '1') {
                if (value['area_type'] == 'AB') {
                    stringAB = 'checked disabled';
                    stringMB = 'disabled'
                } else if (value['area_type'] == 'MB') {
                    stringMB = 'checked disabled';
                    stringAB = 'disabled';
                }
            }


            if (value['agency_name'] != null) {
                stringAgencyName = ' (' + value['agency_name'] + ')';
            }

            $('#TbodyNeighborhoods').append(
                '<tr>' +

                '<td width="10"> <input style="width: 20px"' +
                ' value="' + (value['neighborhood_id']) + '" type="checkbox" ' +
                (stringAB) + ' id="ab-' + (value['neighborhood_id']) + '"' +
                ' class="form-control check-give-district-to-region location-ab ck-' +
                (value['neighborhood_id']) +
                '">' + '</td>' +

                '<td width="10"> <input style="width: 20px" ' +
                'value="' + (value['neighborhood_id']) + '" type="checkbox" ' + (stringMB) +
                ' id="mb-' + (value['neighborhood_id']) + '"' + ' class="form-control check-give-district-to-region location-mb  ck-' +
                (value['neighborhood_id']) +
                '">' + '</td>' +

                '<td>' + (value['neighborhood_name']) + stringAgencyName + '</td>' +

                '</tr>'
            );
        });

        $('.select-all-cb').prop('checked', false);

        $('#modalBodyTCDistricts.modal-body').unblock();

    });
});

$(document).ready(function () {

    $('#district').prop('disabled', true)
    $('#selectAgency').val('');

    $('#city').change(function () {

        let city_id = $(this).children(":selected").attr("id");
        let city_name = $('#city').children(":selected").attr("data");
        let district_name = $('#district').children(":selected").attr("data");

        $.post('/Ajax/CityToDistrict', {
                _token: token,
                city_id: city_id
            }, function (response) {

                $('#district').html('');
                $('#district').append(
                    '<option  value="">İlçe Seçin</option>'
                );
                $('#neighborhood').prop('disabled', true);
                $.each(response, function (key, value) {
                    $('#district').append(
                        '<option data="' + (value['name']) + '" id="' + (value['key']) + '"  value="' + (value['key']) + '">' + (value['name']) + '</option>'
                    );
                });
                $('#district').prop('disabled', false);
            }
        )
        ;

        getAgencies(city_name, district_name);
    });

    $('#district').change(function () {

        var district_id = $(this).children(":selected").attr("id");
        let city_name = $('#city').children(":selected").attr("data");
        let district_name = $('#district').children(":selected").attr("data");

        $.post('/Ajax/DistrictToNeighborhood', {
                _token: token,
                district_id: district_id
            }, function (response) {

                $('#neighborhood').html('');
                $('#neighborhood').append(
                    '<option  value="">Mahalle Seçin</option>'
                );
                $.each(response, function (key, value) {
                    $('#neighborhood').append(
                        '<option id="' + (value['key']) + '"  value="' + (value['name']) + '">' + (value['name']) + '</option>'
                    );
                });
            }
        )
        ;
        getAgencies(city_name, district_name);
    });

    function getAgencies(city = '', district = '') {

        $.ajax('/Ajax/GetAgency', {
            method: 'POST',
            data: {
                _token: token,
                city: city,
                district: district
            }
        }).done(function (response) {

            $('#selectAgency').html('<option value="">Seçiniz</option>');

            $.each(response, function (key, value) {
                $('#selectAgency').append(
                    '<option id="' + (value['id']) + '"  value="' + (value['id']) + '">' + (value['agency_name']) + ' ACENTE </option>'
                );
            });

        }).error(function (jqXHR, exception) {
            ajaxError(jqXHR.status)
        }).always(function () {
            $('#modalBodySelectCustomer').unblock();
        });


    }
});

var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {


    $('#selectAgency').select2();
    $('#creatorUser').select2();

    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 25,
        lengthMenu: dtLengthMenu,
        language: dtLanguage,
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
        buttons: [
            'print',
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                title: "CK - Mahalli Lokasyon"
            },
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                }
            },
            {
                extend: 'colvis',
                text: 'Sütun Görünüm'
            },
        ],
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/Operation/GetLocations',
            data: function (d) {
                d.city = $('#city').val();
                d.district = $('#district').val();
                d.agency = $('#selectAgency').val();
            },
            error: function (xhr, error, code) {
                ajaxError(xhr.status);
            },
            complete: function () {

            }
        },
        columns: [
            {data: 'city', name: 'city'},
            {data: 'district', name: 'district'},
            {data: 'neighborhood', name: 'neighborhood'},
            {data: 'area_type', name: 'area_type'},
            {data: 'distance', name: 'distance'},
            {data: 'agency_name', name: 'agency_name'},
            {data: 'remove', name: 'remove'},
            {data: 'edit', name: 'edit'}
        ],
        scrollY: "400px",
    });
});

function drawDT() {
    oTable.draw();
}

$('.niko-select-filter').change(delay(function (e) {
    drawDT();
}, 1000));

$('.niko-filter').keyup(delay(function (e) {
    drawDT();
}, 1000));

$('#btnClearFilter').click(function () {
    $('#search-form').trigger("reset");
    $('#select2-creatorUser-container').text('Seçiniz');
    $('#select2-agency-container').text('Seçiniz');
    drawDT();
});

$('#btnGiveNeighborhood').click(delay(function () {
    if ($('#selectAgency').val() == '')
        ToastMessage('warning', '', 'Önce acente seçin!');
    else {
        $('#ModalCityDistrictNeighborhoods').modal();
        $('#districtX').val('');
        $('#TbodyNeighborhoods').html('');
        $('#cb-select-all-mb').prop('checked', false);
        $('#cb-select-all-ab').prop('checked', false);
    }
}, 450));


function getDistanceInfo() {

    $('#ModalEditLocation').modal();


    $('#ModalEditLocation').block({
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
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/Operation/GetLocationInfo', {
        method: 'POST',
        data: {
            _token: token,
            id: detailsID
        }
    }).done(function (response) {

        if (response.status == '1') {

            let location = response.location
            $('#editLocationCity').val(location.city)
            $('#editLocationDistrict').val(location.district)
            $('#editLocationNeighborhood').val(location.neighborhood)
            $('#editLocationAgency').val(location.agency_code)
            $('#editLocationDistance').val(location.distance)
            $('#editLocationAreaType').val(location.area_type)


        } else if (response.status == '0') {
            ToastMessage('error', response.message, 'HATA!')
            detailsID = null;
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalEditLocation').unblock();
    });
}

$(document).on('click', '.edit-location', function () {
    detailsID = $(this).attr('id')
    getDistanceInfo(detailsID)
})


$(document).on('click', '#saveEditLocation', function () {

    $('#ModalEditLocation').block({
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
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/Operation/EditLocalLocation', {
        method: 'POST',
        data: {
            _token: token,
            id: detailsID,
            agency: $('#editLocationAgency').val(),
            distance: $('#editLocationDistance').val(),
            areaType: $('#editLocationAreaType').val(),
        }
    }).done(function (response) {

        if (response.status == '1') {
            ToastMessage('success', response.message, 'İşlem Başarılı!')
            oTable.draw();
        } else if (response.status == '0')
            ToastMessage('error', response.message, 'HATA!')
        else if (response.status == 0)
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalEditLocation').unblock();
    });

})

