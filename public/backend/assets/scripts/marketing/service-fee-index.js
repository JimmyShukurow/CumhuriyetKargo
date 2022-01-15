$(document).ready(function () {
    $('.tab-nav-link').click(function () {
        $('.TableAdditionalServices, .TableDesiList, .DistancePriceList').DataTable().ajax.reload();
    });
});

var edit_additional_service_id = 0, edit_desi_interval = 0, edit_file = 0;

// # Additional Service Transasction
$(document).ready(function () {
    $('.TableAdditionalServices').DataTable({
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [
            3, 'desc'
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
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">f>rtip',
        buttons: [
            {
                extend: 'pdf',
                title: 'CK - Ek Hizmet Ücretleri',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                }
            },
            'print',
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                title: "CK - Ek Hizmet Ücretleri"
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
            url: '/AdditionalServices',
            data: function (d) {

            },
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                }
            }
        },
        columns: [
            {data: 'service_name', name: 'service_name'},
            {data: 'price', name: 'price'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'edit', name: 'edit'}
        ],
        scrollY: false,
    });
});

$('#btnNewAdditionalService').click(function () {
    $('#modalNewAdditionalService').modal();
});

$(document).on('click', '#btnInsertAdditionalService', function () {
    $(this).prop('disabled', true);
    ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

    $.ajax('/AdditionalServices', {
        method: 'POST',
        data: {
            _token: token,
            service_name: $('#additionalServiceName').val(),
            price: $('#additionalServicePrice').val()
        }
    }).done(function (response) {
        if (response.status == 1) {
            $('.modal input[type=text]').val('');
            ToastMessage('success', 'Ek hizmet oluşturuldu!', 'İşlem Başarılı!');
            $('#modalNewAdditionalService').modal('toggle');
            $('.TableAdditionalServices').DataTable().ajax.reload();
        } else if (response.status == 0)
            ToastMessage('error', response.message, 'Hata!');
        else if (response.status == -1)
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
    }).error(function () {
        ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
    }).always(function () {
        $('#btnInsertAdditionalService').prop('disabled', false);
    });
});

$(document).on('click', '.edit-additional-service', function () {
    edit_additional_service_id = $(this).prop('id');
    $('#editAdditionalServiceName').val($("#additional-service-" + edit_additional_service_id + " > td:nth-child(1)").text())
    $('#editAdditionalServicePrice').val($("#additional-service-" + edit_additional_service_id + " > td:nth-child(2)").text())
    var status = $("#additional-service-" + edit_additional_service_id + " > td:nth-child(3)").text();
    $('#editAdditionalServiceStatus').val(status == 'Aktif' ? '1' : '0');
    $('#modalEditAdditionalService').modal();
});

$(document).on('click', '#btnUpdateAdditionalService', function () {
    $(this).prop('disabled', true);
    ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

    $.ajax('AdditionalServices/' + edit_additional_service_id, {
        method: 'PUT',
        data: {
            _token: token,
            service_name: $('#editAdditionalServiceName').val(),
            price: $('#editAdditionalServicePrice').val(),
            status: $('#editAdditionalServiceStatus').val()
        }
    }).done(function (response) {
        if (response.status == 1) {
            $('.modal input[type=text]').val('');
            ToastMessage('success', 'Ek hizmet kaydedildi!', 'İşlem Başarılı!');
            $('#modalEditAdditionalService').modal('toggle');
            $('.TableAdditionalServices').DataTable().ajax.reload();
        } else if (response.status == 0)
            ToastMessage('error', response.message, 'Hata!');
        else if (response.status == -1)
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
    }).error(function () {
        ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
    }).always(function () {
        $('#btnUpdateAdditionalService').prop('disabled', false);
    });
});

$(document).on('click', '.trash', function () {
    var from = $(this).attr("from");
    var object;

    destroy_id = $(this).attr('id');

    if (from == "additional-service") {
        url = "AdditionalServices/" + destroy_id;
        object = "Ek hizmet";
    }

    swal({
        title: "Silme İşlemini Onaylayın!",
        text: "Emin misiniz? Bu işlem geri alınamaz!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {
                        destroy_id: destroy_id,
                        _token: token
                    },
                    success: function (msg) {
                        if (msg == 1) {
                            $('#' + from + '-item-' + destroy_id).remove();
                            ToastMessage('success', object + ' Silindi.',
                                'İşlem Başarılı!');
                            $('.TableAdditionalServices').DataTable().ajax.reload();

                        } else
                            ToastMessage('error',
                                'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                                'İşlem Başarısız!');
                    }
                });

            } else {
                ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi');
            }
        });
});

// # Desi List
$(document).ready(function () {
    $('.TableDesiList').DataTable({
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        // order: [
        //     2, 'desc'
        // ],
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
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">f>rtip',
        buttons: [
            {
                extend: 'pdf',
                title: 'CK - Desi Ücretleri',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6],
                }
            },
            'print',
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                title: "CK - Desi Ücretleri"
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
            url: '/DesiList',
            data: function (d) {

            },
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                }
            }
        },
        columns: [
            {data: 'start_desi', name: 'start_desi'},
            {data: 'finish_desi', name: 'finish_desi'},
            {data: 'desi_price', name: 'desi_price'},
            {data: 'corporate_unit_price', name: 'corporate_unit_price'},
            {data: 'individual_unit_price', name: 'individual_unit_price'},
            {data: 'mobile_individual_unit_price', name: 'mobile_individual_unit_price'},
            {data: 'created_at', name: 'updated_at'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'edit', name: 'edit'}
        ],
        scrollY: "500px",
    });
});

$(document).on('click', '#addNewDesiInterval', function () {
    $('#modalNewDesiInterval').modal();
});

$(document).on('click', '#btnInsertNewDesiInterval', function () {
    $(this).prop('disabled', true);
    ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

    $.ajax('/DesiList', {
        method: 'POST',
        data: {
            _token: token,
            start_desi: $('#startDesi').val(),
            finish_desi: $('#finishDesi').val(),
            desi_price: $('#desiPrice').val(),
            corporate_unit_price: $('#corporateUnitPrice').val(),
            individual_unit_price: $('#individualUnitPrice').val(),
            mobile_individual_unit_price: $('#mobileIndividualUnitPrice').val(),
        }
    }).done(function (response) {
        if (response.status == 1) {
            $('.modal input[type=text]').val('');
            ToastMessage('success', 'Ek hizmet oluşturuldu!', 'İşlem Başarılı!');
            $('#modalNewDesiInterval').modal('toggle');
            $('.TableDesiList').DataTable().ajax.reload();
        } else if (response.status == 0)
            ToastMessage('error', response.message, 'Hata!');
        else if (response.status == -1)
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
    }).error(function () {
        ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
    }).always(function () {
        $('#btnInsertNewDesiInterval').prop('disabled', false);
    });
});

$(document).on('click', '.edit-desi-list', function () {
    edit_desi_interval = $(this).prop('id');
    $('#editStartDesi').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(1)").text());
    $('#editFinishDesi').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(2)").text());
    $('#editDesiPrice').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(3)").text());
    $('#editCorporateUnitPrice').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(4)").text());
    $('#editIndividualUnitPrice').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(5)").text());
    $('#editMobileIndividualUnitPrice').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(6)").text());

    $('#modelEditDesiInterval').modal();
});

$(document).on('click', '#btnUpdateDesiInterval', function () {
    $(this).prop('disabled', true);
    ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

    $.ajax('DesiList/' + edit_desi_interval, {
        method: 'PUT',
        data: {
            _token: token,
            start_desi: $('#editStartDesi').val(),
            finish_desi: $('#editFinishDesi').val(),
            desi_price: $('#editDesiPrice').val(),
            corporate_unit_price: $('#editCorporateUnitPrice').val(),
            individual_unit_price: $('#editIndividualUnitPrice').val(),
            mobile_individual_unit_price: $('#editMobileIndividualUnitPrice').val(),
        }
    }).done(function (response) {
        if (response.status == 1) {
            $('.modal input[type=text]').val('');
            ToastMessage('success', 'Ek hizmet kaydedildi!', 'İşlem Başarılı!');
            $('#modelEditDesiInterval').modal('toggle');
            $('.TableDesiList').DataTable().ajax.reload();
        } else if (response.status == 0)
            ToastMessage('error', response.message, 'Hata!');
        else if (response.status == -1)
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
    }).error(function () {
        ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
    }).always(function () {
        $('#btnUpdateDesiInterval').prop('disabled', false);
    });
});

// # File Price
$(document).on('click', '.editFilePrice', function () {
    edit_file = $(this).prop('id');
    $('#editCorporteFilePrice').val($("#file-price > td:nth-child(1)").text());
    $('#editIndividualFilePrice').val($("#file-price > td:nth-child(2)").text());
    $('#editMobileFilePrice').val($("#file-price > td:nth-child(3)").text());
    $('#modalEditFilePrice').modal();
});

$(document).on('click', '#btnUpdateFilePrice', function () {
    $(this).prop('disabled', true);
    ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

    $.ajax('ServiceFees/FilePrice/' + edit_file, {
        method: 'POST',
        data: {
            _token: token,
            corporate_file_price: $('#editCorporteFilePrice').val(),
            individual_file_price: $('#editIndividualFilePrice').val(),
            mobile_file_price: $('#editMobileFilePrice').val(),
        }
    }).done(function (response) {
        if (response.status == 1) {
            $('.modal input[type=text]').val('');
            ToastMessage('success', 'Dosya Fiyatları kaydedildi!', 'İşlem Başarılı!');
            $('#modalEditFilePrice').modal('toggle');
            getFilePrice();
        } else if (response.status == 0)
            ToastMessage('error', response.message, 'Hata!');
        else if (response.status == -1)
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
    }).error(function () {
        ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
    }).always(function () {
        $('#btnUpdateFilePrice').prop('disabled', false);
    });
});

function dateFormat(date) {
    date = String(date);
    let text = date.substring(0, 10);
    let time = date.substring(19, 8);
    time = time.substring(3, 11);
    let datetime = text + " " + time;
    return datetime;
}

function getFilePrice() {
    $.ajax('ServiceFees/GetFilePrice', {
        method: 'POST',
        data: {_token: token}
    }).done(function (response) {
        if (response.status == 1) {

            ToastMessage('success', 'Fiyat Çekildi!', 'İşlem Başarılı!');
            $('#corporateFilePrice').html('₺' + response.price.corporate_file_price);
            $('#individualFilePrice').html('₺' + response.price.individual_file_price);
            $('#mobileFilePrice').html('₺' + response.price.mobile_file_price);
            $('#individualMiPrice').html('₺' + response.price.individual_mi_price);
            $('#corporateMiPrice').html('₺' + response.price.corporate_mi_price);
            $('#mobileMiPrice').html('₺' + response.price.mobile_mi_price);
            $('#filePriceUpdate').html(dateFormat(response.price.updated_at));

        } else if (response.status == 0)
            ToastMessage('error', response.message, 'Hata!');
        else if (response.status == -1)
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
    }).error(function () {
        ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
    }).always(function () {
        $('#btnUpdateFilePrice').prop('disabled', false);
    });
}

// # Mi Price
$(document).on('click', '.editMiPrice', function () {
    edit_file = $(this).prop('id');
    $('#editCorporteMiPrice').val($("#mi-price > td:nth-child(1)").text());
    $('#editIndividualMiPrice').val($("#mi-price > td:nth-child(2)").text());
    $('#editMobileMiPrice').val($("#mi-price > td:nth-child(3)").text());
    $('#modalEditMiPrice').modal();
});

$(document).on('click', '#btnUpdateMiPrice', function () {
    $(this).prop('disabled', true);
    ToastMessage('info', 'İstek alındı lütfen basdasdekleyiniz!', 'Bilgi');

    $.ajax('ServiceFees/MiPrice/' + edit_file, {
        method: 'POST',
        data: {
            _token: token,
            corporate_mi_price: $('#editCorporteMiPrice').val(),
            individual_mi_price: $('#editIndividualMiPrice').val(),
            mobile_mi_price: $('#editMobileMiPrice').val(),
        }
    }).done(function (response) {
        if (response.status == 1) {
            $('.modal input[type=text]').val('');
            ToastMessage('success', 'Mi Fiyatları kaydedildi!', 'İşlem Başarılı!');
            $('#modalEditMiPrice').modal('toggle');
            getFilePrice();
        } else if (response.status == 0)
            ToastMessage('error', response.message, 'Hata!');
        else if (response.status == -1)
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!');
            });
    }).error(function () {
        ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
    }).always(function () {
        $('#btnUpdateMiPrice').prop('disabled', false);
    });
});

// # Distance Price List
$(document).ready(function () {
    $('.DistancePriceList').DataTable({
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [1],
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
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">f>rtip',
        buttons: [
            {
                extend: 'pdf',
                title: 'CKG-Sis - Mesafe Ücretleri',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                }
            },
            'print',
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                },
                title: 'CKG-Sis - Mesafe Ücretleri',
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
            url: '/ServiceFees/GetDistancePrice',
            data: function (d) {

            },
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                }
            }
        },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'distance_start', name: 'distance_start'},
            {data: 'distance_end', name: 'distance_end'},
            {data: 'price', name: 'price'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
        ],
        scrollY: "500px",
    });
});

