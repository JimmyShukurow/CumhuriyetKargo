$(document).ready(function () {
    $('#filter_City').change(function () {
        getDistricts('#filter_City', '#filter_District');
    });
});

var oTable;
$(document).ready(function () {
    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 10,
        lengthMenu: dtLengthMenu,
        order: [
            13, 'desc'
        ],
        "columnDefs": [
            {"visible": false, "targets": [6]},
            {"visible": false, "targets": [7]},
            {"visible": false, "targets": [10]},
            {"visible": false, "targets": [12]},
        ],
        language: dtLanguage,
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
        buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                },
                title: "CKGSis-Acenteler"
            },
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                }
            },
            {
                extend: 'colvis',
                text: 'Sütunlar'
            },
        ],
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/Agencies/GetAgencies',
            data: function (d) {
                d.city = $('#filter_City').val();
                d.district = $('#filter_District').val();
                d.regionalDirectorate = $('#filter_RegionalDirectorate').val();
                d.transshipmentCenter = $('#filter_TransshipmentCenter').val();
                d.agencyCode = $('#filter_AgencyCode').val();
                d.agencyName = $('#filter_AgencyName').val();
                d.nameSurname = $('#filter_NameSurname').val();
                d.status = $('#filter_Status').val();
                d.phone = $('#filter_phone').val();
                d.phone2 = $('#filter_phone2').val();
                d.phone3 = $('#filter_phone3').val();
                d.maps_link = $('#filter_MapsLink').val();
                d.address = $('#filter_address').val();
                d.ip_address = $('#filter_IpAddress').val();
                d.ip_address_info = $('#filter_IPAddressInfo').val();
                d.permission_of_create_cargo = $('#filter_PermissionOfCreateCargo').val();
                d.operation_status = $('#filter_OperationStatus').val();
                d.safe_status = $('#filter_SafeStatus').val();

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
            {data: 'phone3', name: 'phone3'},
            {data: 'agency_code', name: 'agency_code'},
            {data: 'status', name: 'status'},
            {data: 'permission_of_create_cargo', name: 'permission_of_create_cargo'},
            {data: 'operation_status', name: 'operation_status'},
            {data: 'safe_status', name: 'safe_status'},
            {data: 'maps_link', name: 'maps_link'},
            {data: 'employee_count', name: 'employee_count'},
            {data: 'created_at', name: 'created_at'},
            {data: 'edit', name: 'edit'},
        ],
        scrollY: "500px",
    });

    $('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
});


// parse a date in yyyy-mm-dd format
function dateFormat(date) {
    date = String(date);
    let text = date.substring(0, 10);
    let time = date.substring(19, 8);
    time = time.substring(3, 11);
    let datetime = text + " " + time;
    return datetime;
}


var details_id = null;

function agencyPost(agency_id) {

    $('#ModalModyAgencyDetail').block({
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


    $.post('/Agencies/Info', {
        _token: token,
        agency_id: agency_id
    }, function (response) {

        details_id = response.agency[0].id;

        $('#agencyName').html(response.agency[0].agency_name + " ŞUBE");
        $('#thMainTitle').html(response.agency[0].agency_name + " ŞUBE");
        $('#agencyCityDistrict').html(response.agency[0].city + "/" + response.agency[0].district);

        $('td#cityDistrict').html(response.agency[0].city + "/" + response.agency[0].district);
        $('td#neighborhood').html(response.agency[0].neighborhood);
        $('td#adress').html(response.agency[0].adress);
        $('td#phone').html(response.agency[0].phone);
        $('td#phone2').html(response.agency[0].phone2);
        $('td#phone3').html(response.agency[0].phone3);
        $('td#transshipmentCenter').html(response.agency[0].tc_name + " TRM.");
        $('td#regionalDirectorate').html(response.agency[0].regional_directorates != null ? response.agency[0].regional_directorates + " BÖLGE MÜDÜRLÜĞÜ" : '');
        $('td#status').html(response.agency[0].status == '1' ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Pasif</b>');
        $('td#statusDescription').html(response.agency[0].status_description);
        $('td#agencyDevelopmentOfficer').html(response.agency[0].agency_development_officer);
        if (response.agency[0].maps_link != null)
            $('#agencyMapsLink').html('<a target="_blank" href="http://www.google.com/maps?q=' + response.agency[0].maps_link + '">' + response.agency[0].maps_link + '</a>')
        else
            $('#agencyMapsLink').html('');

        $('td#agencyPermissionOfCreateCargo').html(response.agency[0].permission_of_create_cargo == '1' ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Pasif</b>');

        $('td#agencySafeStatus').html(response.agency[0].safe_status == '1' ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Pasif</b>');
        $('td#agencySafeStatusDescription').html(response.agency[0].safe_status_description);

        $('td#agencyOperationStatus').html(response.agency[0].operation_status == '1' ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Pasif</b>');


        $('td#agencyIpAddress').html(response.agency[0].ip_address);

        $('#linkOfEditAgency').attr('onclick', "window.open('/Agencies/EditAgency/" + details_id + "','popup','width=800,height=750'); return false;");


        $('#agencyCode').html(response.agency[0].agency_code);
        $('#regDate').html(dateFormat(response.agency[0].created_at));
        $('#updatedDate').html(dateFormat(response.agency[0].updated_at));

        $('#tbodyEmployees').html('');

        if (response.employees.length == 0) {
            $('#tbodyEmployees').append(
                '<tr>' +
                '<td class="text-center" colspan="4">Kullanıcı Yok.</td>' +
                +'</tr>'
            );
        } else {
            $.each(response.employees, function (key, value) {
                $('#tbodyEmployees').append(
                    '<tr>' +
                    '<td>' + (value['name_surname']) + '</td>' +
                    '<td>' + (value['email']) + '</td>' +
                    '<td>' + (value['display_name']) + '</td>' +
                    '<td>' + (value['phone']) + '</td>' +
                    +'</tr>'
                );
            });
        }
    }).done(function () {
        $('#ModalModyAgencyDetail').unblock();
    }).always(function () {
        $('#ModalModyAgencyDetail').unblock();
    });

    $('#ModalAgencyDetail').modal();
}

$('button.agency-detail').click(function () {
    agency_id = $(this).attr('agency_id');
    agencyPost(agency_id);
});

$(document).on('click', '.agency-detail', function () {
    let agency_id = $(this).attr('agency_id');
    agencyPost(agency_id);
});


$(document).on('click', '#btnDisableEnableAgency', function () {
    // alert(detailsID);
    $('#modalEnabledDisabled').modal();

    $('#modalBodyEnabledDisabled.modalEnabledDisabled.modal-body').block({
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


    $.ajax('/Agencies/Info', {
        method: 'POST',
        data: {
            _token: token,
            agency_id: details_id
        }
    }).done(function (response) {


        $('#enableDisable_agencyName').val("#" + response.agency[0].agency_code + "-" + response.agency[0].agency_name);

        $('#accountStatus').val(response.agency[0].status);
        $('#accountPermissionOfCreateCargo').val(response.agency[0].permission_of_create_cargo);
        $('#statusDesc').val(response.agency[0].status_description);
        $('#accountOperationStatus').val(response.agency[0].operation_status);

        $('#modalBodyEnabledDisabled.modalEnabledDisabled.modal-body').unblock();
    });
});

$(document).on('click', '#btnSaveStatus', function () {
    ToastMessage('warning', 'İstek alındı, lütfen bekleyiniz.', 'Dikkat!');
    $.ajax('/Agencies/ChangeStatus', {
        method: 'POST',
        data: {
            _token: token,
            agency: details_id,
            status: $('#accountStatus').val(),
            permission_of_create_cargo: $('#accountPermissionOfCreateCargo').val(),
            status_description: $('textarea#statusDesc').val(),
            operation_status: $('#accountOperationStatus').val()
        }
    }).done(function (response) {
        if (response.status == 1) {
            agencyPost(details_id);
            ToastMessage('success', 'Değişiklikler başarıyla kaydedildi.', 'İşlem Başarılı!');
            oTable.draw();
            $('#modalEnabledDisabled').modal('toggle');
        } else if (response.status == 0) {
            ToastMessage('error', response.description, 'Hata!');
        } else if (response.status == -1) {
            response.errors.status.forEach(key =>
                ToastMessage('error', key, 'Hata!')
            );
        }

        return false;
    }).error(function (jqXHR, response) {

        ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
    });
});

$(document).on('click', '#BtnRefreshAgencyDetail', function () {
    agencyPost(details_id);
});

