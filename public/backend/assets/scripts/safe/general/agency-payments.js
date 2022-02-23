let agencyPaymentsInit = false;
let agencyPaymentsInitDetailsID = null;

$('#tabAgencyPayments').click(function () {

    if (agencyPaymentsInit == false) {

        agencyPaymentsInit = true

        tableAgencyPayments = $('#tableAgencyPayments').DataTable({
            pageLength: 50,
            lengthMenu: [
                [10, 25, 50, 100, 250, 500, -1],
                ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
            ],
            order: [10, 'desc'],
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
            select: {
                style: 'multi',
                selector: 'td:nth-child(0)'
            },
            buttons: [
                {
                    text: 'Yenile',
                    action: function (e, dt, node, config) {
                        dt.ajax.reload();
                    },
                    attr: {
                        id: 'datatableRefreshBtn',
                        class: 'btn btn-sm btn-success'
                    }
                },
                {
                    extend: 'excelHtml5',
                    class: 'btn btn-sm btn-info',
                    title: "CKG-Sis - Acente Ödemeleri"
                },
                {
                    text: 'Ödeme Ekle',
                    attr: {
                        id: 'btnAddAgencyPayment',
                        class: 'btn btn-sm btn-primary'
                    }
                },
            ],

            responsive: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/Safe/General/AjaxTransactions/GetAgencyPayments',
                data: function (d) {
                    d.firstDate = $('#agencyPaymentsLastDate').val()
                    d.lastDate = $('#agencyPaymentsLastDate').val()
                    d.appNo = $('#agencyPaymentsAppNo').val()
                    d.agency = $('#agencyPaymentsAgency').val()
                    d.confirm = $('#agencyPaymentsConfirm').val()
                    d.paymentChannel = $('#agencyPaymentsPaymentChannel').val()
                },
                error: function (xhr, error, code) {

                    let response = JSON.parse(xhr.responseText);
                    if (response.status == 0) {
                        ToastMessage('error', response.message, 'HATA!');
                        return false;
                    }
                    ajaxError(code);
                    if (code == "Too Many Requests") {
                        SnackMessage('Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'error', 'bl');
                    } else if (code == 590) {
                        ToastMessage('error', 'Tarih aralığı max. 90 gün olabilir!', 'HATA!');
                    }
                },
                complete: function () {
                    SnackMessage('Tamamlandı!', 'info', 'bl');

                    if ($('#datatableRefreshBtn').prop('disabled') == true)
                        $('#datatableRefreshBtn').prop('disabled', false);

                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'row_type', name: 'row_type'},
                {data: 'app_id', name: 'app_id'},
                {data: 'agency_name', name: 'agency_name'},
                {data: 'payment', name: 'payment'},
                {data: 'payment_date', name: 'payment_date'},
                {data: 'paying_name_surname', name: 'paying_name_surname'},
                {data: 'payment_channel', name: 'payment_channel'},
                {data: 'name_surname', name: 'name_surname'},
                {data: 'description', name: 'description'},
                {data: 'created_at', name: 'created_at'},
                {data: 'edit', name: 'edit'},
                {data: 'delete', name: 'delete'},
            ],
            scrollY: '500px',
            scrollX: true,
        });
    }

});

$(document).ready(function () {
    $('#agencyPaymentsAgency').select2()
    $('#addAgencyPaymentAgency').select2()
})

$(document).on('click', '#btnAddAgencyPayment', function () {
    $('#ModalAddAgencyPayment').modal();
})

$(document).on('click', '#btnSaveAgencyPayment', function () {

    $('#ModalBodyAgencyPaymentAppDetails').block({
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

    $.ajax('/Safe/General/AjaxTransactions/SaveAgencyPayment', {
        method: 'POST',
        data: {
            _token: token,
            agencyID: $('#addAgencyPaymentAgency').val(),
            payingNameSurname: $('#addAgencyPaymentPayingNameSurname').val(),
            paymentChannel: $('#addAgencyPaymentPaymentChannel').val(),
            payment: $('#addAgencyPaymentPayment').val(),
            paymentDate: $('#addAgencyPaymentPaymentDate').val(),
            description: $('#addAgencyPaymentDescription').val(),
        }
    }).done(function (response) {

        if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        } else if (response.status == -1) {
            ToastMessage('error', response.message, 'Hata!')
        } else if (response.status == 1) {
            tableAgencyPayments.draw();
            $('.add-agency-payment-fields').val('')
            $('#ModalAddAgencyPayment').modal('hide')
            ToastMessage('success', response.message, 'İşlem Başarılı!')
        }


    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyAgencyPaymentAppDetails').unblock();
    });
})


$(document).on('click', '.delete-payment', function () {
    swal({
        title: "Silme İşlemini Onaylayın!",
        text: "Emin misiniz? Ödeme başvurusu silme işlemine devam etmek istiyor musunuz?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    type: "POST",
                    url: '/Safe/General/AjaxTransactions/DeleteAgencyPayment',
                    data: {
                        _token: token,
                        destroy_id: $(this).prop('id')
                    },
                    success: function (response) {
                        if (response.status == 1) {
                            ToastMessage('success', response.message, 'İşlem Başarılı!');
                            tableAgencyPayments.ajax.reload();
                        } else
                            ToastMessage('error', response.message, 'İşlem Başarısız!');
                    },
                    error: function (jqXHR, exception) {
                        ajaxError(jqXHR.status)
                    }
                });

            } else {
                ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi');
            }
        });
});






