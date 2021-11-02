var oTable;

$(document).ready(function () {
    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [
            7, 'desc'
        ],
        language: {
            "sDecimal": ",",
            "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
            "sInfo": "_TOTAL_ kayıttan _START_ - _END_ kayıtlar gösteriliyor",
            "sInfoEmpty": "Kayıt yok",
            "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_",
            "sLoadingRecords": "Yükleniyor...",
            "sProcessing": "<div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div>",
            "sSearch": "",
            "sZeroRecords": "Eşleşen kayıt bulunamadı",
            "oPaginate": {
                "sFirst": "İlk",
                "sLast": "Son",
                "sNext": "Sonraki",
                "sPrevious": "Önceki"
            },
            "oAria": {
                "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
            },
            "select": {
                "rows": {
                    "_": "%d kayıt seçildi",
                    "0": "",
                    "1": "1 kayıt seçildi"
                }
            }
        },
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
        buttons: [
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
            url: '{!! route('whois.getTransshipmentCenters') !!}',
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                }
            }
        },
        columns: [
            {data: 'city', name: 'city'},
            {data: 'tc_name', name: 'tc_name'},
            {data: 'region_name', name: 'region_name'},
            {data: 'agency_count', name: 'agency_count'},
            {data: 'type', name: 'type'},
            {data: 'director_name', name: 'director_name'},
            {data: 'phone', name: 'phone'},
            {data: 'edit', name: 'edit'},
        ],
        scrollY: false
    });
});

$(document).on('click', '.tc-detail', function () {
    $('#ModalUserDetail').modal();

    detailsID = $(this).prop('id');
    TransshipmentPost($(this).prop('id'));
});


function TransshipmentPost(transshipment_id) {
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

    $.ajax('{{ route('whois.Transshipment') }}', {
        method: 'POST',
        data: {
            _token: token,
            transshipment_id: transshipment_id
        }
    }).done(function (response) {
        //console.dir(response)
        var transshipment = response.transshipment;
        var director = response.director;
        var assistantdirector = response.assistant_director
        var agency_worker = response.agency_worker
        //response.agency[0].agency_name + " ACENTE");
        $('h5#transshippingname').html(transshipment.tc_name + " TCM");
        $('#cityDistrict').html(transshipment.city + "/" + transshipment.district);
        $('#transshippingname').html(transshipment.tc_name);
        $('#transshippingtype').html(transshipment.type);
        $('#transshippingphone').html(transshipment.phone);
        $('#trmheader').html(transshipment.tc_name+ ' TRM ÖZET');
        
        if(typeof director?.name_surname !== "undefined"){
            $('#directorname').html(director.name_surname)
            $('#directphone').html(director.phone)
            $('#directemail').html(director.email)
        }
        if(typeof assistantdirector?.name_surname !== "undefined"){
            $('#assistantdirectorname').html(assistantdirector.name_surname)
            $('#assistantdirectorphone').html(assistantdirector.phone)
            $('#assistantdirectoremail').html(assistantdirector.email)
        }

        $('#tbodyEmployees').html('');
        if (agency_worker.length == 0) {
            $('#tbodyEmployees').append(
                '<tr>' +
                '<td class="text-center" colspan="4">Kullanıcı Yok.</td>' +
                +'</tr>'
            );
        } else {
            $.each(agency_worker, function (key, value) {
        
            /* let email = value['email'];
                let character = email.indexOf('@');
                email = email.substring(0, character) + "@cumh...com.tr";
                */

                $('#tbodyEmployees').append(
                    '<tr>' +
                    '<td>' + (value['agency_code']) + '</td>' +
                    '<td>' + (value['city']) + '/'+ (value['district']) +'</td>' +
                   // '<td title="' + (value['email']) + '">' + (email) + '</td>' +
                    '<td>' + (value['agency_name']) +  ' ŞUBE</td>' +
                    '<td>' + (value['phone']) + '</td>' +
                    '<td>' + (value['name_surname']) + '</td>' +
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