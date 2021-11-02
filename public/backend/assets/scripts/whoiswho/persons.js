var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {
    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 25,
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
            },
            "searchPlaceholder": "Kullanıcı Arayın",
        },
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>frtip',
        buttons: [
            {
                extend: 'colvis',
                text: 'Sütun Görünüm'
            },
        ],
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/WhoIsWho/GetUsers',
            data: function (d) {
                d.name_surname = $('#name_surname').val();
                d.agency = $('#agency').val();
                d.tc = $('#tc').val();
                d.role = $('#role').val();
                d.user_type = $('#user_type').val();
            },
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                }
            }
        },
        columns: [
            {data: 'name_surname', name: 'name_surname'},
            {data: 'display_name', name: 'display_name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'branch_city', name: 'branch_city'},
            {data: 'branch_district', name: 'branch_district'},
            {data: 'branch_name', name: 'branch_name'},
            {data: 'user_type', name: 'user_type'},
            {data: 'detail', name: 'detail'},
        ],
        scrollY: "400px",
    });
});

$('#search-form').on('submit', function (e) {
    oTable.draw();
    e.preventDefault();
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

$('#tc').change(function () {
    $('#agency').val('');
    $('#user_type').val('');
});
$('#agency').change(function () {
    $('#tc').val('');
    $('#user_type').val('');
});

$('#user_type').change(function () {
    $('#agency').val('');
    $('#tc').val('');
});

$(document).on('click', '.user-detail', function () {
    $('#ModalUserDetail').modal();

    $('#ModalBodyUserDetail.modal-body').block({
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

    detailsID = $(this).prop('id');
    userInfo($(this).prop('id'));
});

var array = new Array();

function userInfo(user) {
    $.ajax('WhoIsWho/GetUserInfo', {
        method: 'POST',
        data: {
            _token: token,
            user: user
        },
        cache: false
    }).done(function (response) {
        var user = response.user;
        var director = response.director;
        var region = response.region;


        $('#agencyName').html(user.name_surname);
        $('#agencyCityDistrict').html(user.branch_city + '/' + user.branch_district + ' - ' + user.branch_name + ' ' + user.user_type);
        $('#titleBranch').html(user.name_surname + ' ÖZET');
        $('#phone').html(user.phone);
        $('#email').html(user.email);
        $('td#name_surname').html(user.name_surname);
        $('#authority').html(user.display_name);

        if (director != null) {
            $('td#phone').html(director.phone);
            $('#dependency').html(director.name_surname + " (" + director.display_name + ")");
        }

        if (region != null)
            $('#district').html(region.name + ' BÖLGE MÜDÜRLÜĞÜ');

        let fakeTitle = user.user_type == 'Aktarma' ? 'TRANSFER MERKEZİ' : 'ACENTE';

        $('#place').html(user.branch_city + '/' + user.branch_district + ' - ' + user.branch_name + ' ' + fakeTitle);

        var counter = 0;
        array = [];
        $.each(response.user_log, function (key, value) {
            array[counter] = value['properties'];
            counter++;
        });

        $('#tbodyUseLog').html('');
        var counter = 0;
        $.each(response.user_log, function (key, value) {
            $('#tbodyUseLog').append(
                '<tr>' +
                '<td>' + (value['created_at']) + '</td>' +
                '<td>' + (value['log_name']) + '</td>' +
                '<td>' + (value['properties'] != '[]' ? '<button id="' + (value['id']) + '" array-no="' + (counter) + '" class="btn  btn-xs btn-danger properties-log">Detay</button>' : '') + '</td>' +
                '<td>' + (value['description']) + '</td>' +
                '</tr>'
            );
            counter++;
        });

        $('.modal-body').unblock();
        return false;
    });

    $('#ModalAgencyDetail').modal();
}
