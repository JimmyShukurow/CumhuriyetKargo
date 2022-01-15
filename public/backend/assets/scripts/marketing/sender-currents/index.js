var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {
    $('#agency').select2();
    $('#creatorUser').select2();

    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [
            6, 'desc'
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
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
        buttons: [
            'print',
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                title: "CK - Gönderici Cariler"
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
            url: '/SenderCurrents/GetCurrents',
            data: function (d) {
                d.name = $('#name').val();
                d.agency = $('#agency').val();
                d.currentCode = $('#currentCode').val();
                d.status = $('#status').val();
                d.creatorUser = $('#creatorUser').val();
                d.category = $('#category').val();
                d.record = $('#record').val();
                d.confirmed = $('#confirmed').val();
            },
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                }
            },
            complete: function () {
                ToastMessage('info', 'Tamamlandı!', 'Bilgi');
            }
        },
        columns: [
            {data: 'current_code', name: 'current_code'},
            {data: 'category', name: 'category'},
            {data: 'name', name: 'name'},
            {data: 'city', name: 'city'},
            {data: 'agency_name', name: 'agency_name'},
            {data: 'name_surname', name: 'name_surname'},
            {data: 'created_at', name: 'created_at'},
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

// parse a date in yyyy-mm-dd format
function dateFormat(date) {
    date = String(date);
    let text = date.substring(0, 10);
    let time = date.substring(19, 8);
    time = time.substring(3, 11);
    let datetime = text + " " + time;
    return datetime;
}


$(document).on('click', '.user-detail', function () {
    $('#ModalUserDetail').modal();

    $('#ModalBodyUserDetail.modal-body').block({
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

    detailsID = $(this).prop('id');
    userInfo($(this).prop('id'));
});

var array = new Array();

function userInfo(user) {
    $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
        method: 'POST',
        data: {
            _token: token,
            currentID: user
        },
        cache: false
    }).done(function (response) {
        let currentStatus, currentConfirmed;
        let creatorDisplayName = '<span class="text-primary font-weight-bold">(' + response.current.creator_display_name + ')</span>';

        if (response.current.status == "1")
            currentStatus = '<span class="text-success">(Aktif Hesap)</span>';
        else
            currentStatus = '<span class="text-danger">(Pasif Hesap)</span>';

        if (response.current.confirmed == "1") {
            currentConfirmed = '<span class="text-success font-weight-bold">Onaylandı</span>';
            $('#divConfirmCurrent').hide();
        } else {
            currentConfirmed = '<span class="text-danger font-weight-bold">Onay Bekliyor</span>';
            $('#divConfirmCurrent').show();
        }

        let city = response.current.city + "/",
            district = response.current.district + " ",
            neighborhood = response.current.neighborhood + " ",
            street = response.current.street != '' && response.current.street != null ? response.current.street + " CAD. " : "",
            street2 = response.current.street2 != '' && response.current.street2 != null ? response.current.street2 + " SK. " : "",
            buildingNo = "NO:" + response.current.building_no + " ",
            door = "D:" + response.current.door_no + " ",
            floor = "KAT:" + response.current.floor + " ",
            addressNote = "(" + response.current.address_note + ")";

        let fullAddress = neighborhood + street + street2 + buildingNo + floor + door + addressNote;


        $('#agencyName').html(response.current.name)
        $('#agencyCityDistrict').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
        $('#titleBranch').html(response.current.name + ' - ÖZET ' + currentStatus)

        $('#currentCategory').html(response.current.category)
        $('#modalCurrentCode').html(response.current.current_code)
        $('#nameSurnameCompany').html(response.current.name)
        $('#currentAgency').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
        $('#taxOffice').html(response.current.tax_administration)
        $('#tcknVkn').html(response.current.tckn)
        $('#phone').html(response.current.phone)
        $('#cityDistrict').html(response.current.city + "/" + response.current.district)
        $('#address').html(fullAddress)
        $('#gsm').html(response.current.gsm)
        $('#gsm2').html(response.current.gsm2)
        $('#phone2').html(response.current.phone2)
        $('#email').html(response.current.email)
        $('#website').html(response.current.website)
        $('#regDate').html(response.current.created_at)
        $('#dispatchCityDistrict').html(response.current.dispatch_city + "/" + response.current.dispatch_district)
        $('#dispatchAddress').html(response.current.dispatch_adress);
        $('#iban').html(response.current.iban)
        $('#bankOwner').html(response.current.bank_owner_name)
        $('#contractStartDate').html(response.current.contract_start_date)
        $('#contractEndDate').html(response.current.contract_end_date)
        $('#reference').html(response.current.reference)
        $('#currentCreatorUser').html(response.current.creator_user_name + " " + creatorDisplayName)
        $('#mbStatus').html(response.current.mb_status == '0' ? '<b class="text-danger">Hayır</b>' : '<b class="text-success">Evet</b>')


        if (response.current.category == 'Kurumsal') {

            $('#currentFilePrice').html(response.price.file_price + "₺");
            $('#currentMiPrice').html(response.price.mi_price + "₺");
            $('#current1_5Desi').html(response.price.d_1_5 + "₺");
            $('#current6_10Desi').html(response.price.d_6_10 + "₺");
            $('#current11_15Desi').html(response.price.d_11_15 + "₺");
            $('#current16_20Desi').html(response.price.d_16_20 + "₺");
            $('#current21_25Desi').html(response.price.d_21_25 + "₺");
            $('#current26_30Desi').html(response.price.d_26_30 + "₺");
            $('#current31_35Desi').html(response.price.d_31_35 + "₺");
            $('#current36_40Desi').html(response.price.d_36_40 + "₺");
            $('#current41_45Desi').html(response.price.d_41_45 + "₺");
            $('#current46_50Desi').html(response.price.d_46_50 + "₺");

            // $('#mCurrentFilePrice').html(response.price.m_file_price + "₺");
            // $('#mCurrentMiPrice').html(response.price.m_mi_price + "₺");
            // $('#mCurrent1_5Desi').html(response.price.m_d_1_5 + "₺");
            // $('#mCurrent6_10Desi').html(response.price.m_d_6_10 + "₺");
            // $('#mCurrent11_15Desi').html(response.price.m_d_11_15 + "₺");
            // $('#mCurrent16_20Desi').html(response.price.m_d_16_20 + "₺");
            // $('#mCurrent21_25Desi').html(response.price.m_d_21_25 + "₺");
            // $('#mCurrent26_30Desi').html(response.price.m_d_26_30 + "₺");
            // $('#mCurrent31_35Desi').html(response.price.m_d_31_35 + "₺");
            // $('#mCurrent36_40Desi').html(response.price.m_d_36_40 + "₺");
            // $('#mCurrent41_45Desi').html(response.price.m_d_41_45 + "₺");
            // $('#mCurrent46_50Desi').html(response.price.m_d_46_50 + "₺");

            $('#currentAmountOfIncrease').html(response.price.amount_of_increase + "₺");
            $('#currentCollectPrice').html(response.price.collect_price + "₺");
            $('#collectAmountOfIncrease').html("%" + response.price.collect_amount_of_increase);
            $('#currentConfirmed').html(currentConfirmed);
            $('#PrintCurrentContract').attr('href', '/SenderCurrents/CurrentContract/' + response.current.current_code);

        }

        $('.modal-body').unblock();
        return false;
    });

    $('#ModalAgencyDetail').modal();
}

$(document).on('click', '#btnConfirmCurrent', function () {
    $('#btnConfirmCurrent').prop('disabled', true);

    $.ajax('/SenderCurrents/AjaxTransaction/ConfirmCurrent', {
        method: 'POST',
        data: {
            _token: token,
            currentID: detailsID
        }
    }).done(function (response) {

        if (response.status == -1)
            ToastMessage('error', response.message, '');
        else if (response.status == 1) {
            ToastMessage('success', 'İşlem başarılı, cari hesabı onaylandı!', 'İşlem Başarılı!');
            userInfo(detailsID);
            $('#divConfirmCurrent').hide();
        }


        $('.modalEnabledDisabled.modal-body').unblock();
    }).error(function (jqXHR, response) {
        ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
    }).always(function () {
        $('#btnConfirmCurrent').prop('disabled', false);
    });

});

$(document).on('click', '#btnEnabledDisabled', function () {
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


    $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
        method: 'POST',
        data: {
            _token: token,
            currentID: detailsID
        }
    }).done(function (response) {

        $('#userNameSurname').val(response.current.name);
        $('#accountStatus').val(response.current.status);

        $('.modalEnabledDisabled.modal-body').unblock();
    });
});

$(document).on('click', '#btnSaveStatus', function () {

    ToastMessage('warning', 'İstek alındı, lütfen bekleyiniz.', 'Dikkat!');
    $.ajax('/SenderCurrents/AjaxTransaction/ChangeStatus', {
        method: 'POST',
        data: {
            _token: token,
            currentID: detailsID,
            status: $('#accountStatus').val(),
        }
    }).done(function (response) {
        if (response.status == 1) {

            $('#ModalBodyUserDetail.modal-body').block({
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

            userInfo(detailsID);
            ToastMessage('success', 'Değişiklikler başarıyla kaydedildi.', 'İşlem Başarılı!');
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
$(document).on('click', '#btnCurrentPerformanceReport', function () {
    ToastMessage('warning', 'Cari performans raporu çok yakında!', 'Bilgi');
});

$(document).on('click', '#btnPrintModal', function () {
    printWindow('#ModalBodyUserDetail', "CK - " + $('#agencyName').text());
});

