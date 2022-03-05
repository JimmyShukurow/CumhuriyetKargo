var oTable;

$(document).ready(function () {
    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 10,
        lengthMenu: dtLengthMenu,
        order: [7, 'desc'],
        language: dtLanguage,
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
        buttons: [
            'copy',
            'pdf',
            'csv',
            'print',
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                },
                title: "CK-Acenteler"
            },
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                }
            }
        ],
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/WhoIsWho/GetAgencies',
            data: function (d) {
                d.agency_code = $('#agencyCode').val();
                d.city = $("#city option:selected").text();
                d.district = $("#district option:selected").text();
                d.agency_name = $('#agencyName').val();
                d.dependency_tc = $("#dependency_tc option:selected").text();
                d.phone = $('#phone').val();
            },
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                }
            }
        },
        columns: [
            {data: 'city', name: 'city'},
            // {data: 'district', name: 'district'},
            {data: 'agency_name', name: 'agency_name'},
            {data: 'regional_directorates', name: 'regional_directorates'},
            {data: 'tc_name', name: 'tc_name'},
            {data: 'name_surname', name: 'name_surname'},
            {data: 'phone', name: 'phone'},
            {data: 'phone2', name: 'phone2'},
            {data: 'maps_link', name: 'maps_link'},
            {data: 'agency_code', name: 'agency_code'},
            {data: 'edit', name: 'edit'},
        ],
        scrollY: false
    });
});

$('#search-form').on('submit', function (e) {
    oTable.draw();
    e.preventDefault();
});

$('#city').change(function () {
    getDistricts('#city', '#district');
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


$('#city_district').change(function () {
    getDistricts('#ilSelector', '#ilceSelector');
});

$(document).on('click', '.agency-detail', function () {
    $('#ModalUserDetail').modal();

    detailsID = $(this).prop('id');
    agencyPost($(this).prop('id'));
});


function agencyPost(agency_id) {
    $('#modalBodyAgencyDetail').block({
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
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $('#ModalAgencyDetail').modal();

    $.ajax('/WhoIsWho/GetAgencyInfo', {
        method: 'POST',
        data: {
            _token: token,
            agency_id: agency_id
        }
    }).done(function (response) {
        console.log(response)
        var employee = response.employees;

        $('h5#agencyName').html(response.agency[0].agency_name + " ACENTE");
        $('#agencyCityDistrict').html(response.agency[0].city + "/" + response.agency[0].district);

        $('#cityDistrict').html(response.agency[0].city + "/" + response.agency[0].district);
        $('#neighborhood').html(response.agency[0].neighborhood);
        $('#adress').html(response.agency[0].adress);
        $('#phone').html(response.agency[0].phone);
        $('#phone2').html(response.agency[0].phone2);
        $('#trasfferCenter').html(response.agency[0].tc_agency_name);
        $('#regionalDirectorate').html(response.agency[0].regional_directorates != null ? response.agency[0].regional_directorates + " BÖLGE MÜDÜRLÜĞÜ" : 'Ne var');
        $('#status').html(response.agency.status == "1" ? "Aktif" : "Pasif");
        $('#agencyDevelopmentOfficer').html(response.agency[0].agency_development_officer);
        $('#agencyCode').html(response.agency[0].agency_code);
        $('#updatedDate').html(response.agency[0].updated_at);

        $('#tbodyEmployees').html('');
        if (employee.length == 0) {
            $('#tbodyEmployees').append(
                '<tr>' +
                '<td class="text-center" colspan="4">Kullanıcı Yok.</td>' +
                +'</tr>'
            );
        } else {
            $.each(employee, function (key, value) {

                let email = value['email'];
                let character = email.indexOf('@');
                email = email.substring(0, character) + "@cumh...com.tr";

                $('#tbodyEmployees').append(
                    '<tr>' +
                    '<td>' + (value['name_surname']) + '</td>' +
                    '<td title="' + (value['email']) + '">' + (email) + '</td>' +
                    '<td>' + (value['display_name']) + '</td>' +
                    '<td>' + (value['phone']) + '</td>' +
                    +'</tr>'
                );
            });
        }
    }).error(function (jqXHR, exception) {
        ajaxError(jqXHR.status)
    }).always(function () {
        $('#modalBodyAgencyDetail').unblock();
    });

}

