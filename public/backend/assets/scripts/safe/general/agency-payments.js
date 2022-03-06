let agencyPaymentsInit = false
let agencyPaymentsDetailsID = null

$('#tabAgencyPayments').click(function () {

    if (agencyPaymentsInit == false) {

        agencyPaymentsInit = true

        tableAgencyPayments = $('#tableAgencyPayments').DataTable({
            pageLength: 50,
            lengthMenu: dtLengthMenu,
            order: [10, 'desc'],
            language: dtLanguage,
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
                    d.firstDate = $('#agencyPaymentsFirstDate').val()
                    d.lastDate = $('#agencyPaymentsLastDate').val()
                    d.appNo = $('#agencyPaymentsAppNo').val()
                    d.agency = $('#agencyPaymentsAgency').val()
                    d.paymentNo = $('#agencyPaymentsPaymentNo').val()
                    d.paymentChannel = $('#agencyPaymentsPaymentChannel').val()
                },
                error: function (xhr, error, code) {

                    let response = JSON.parse(xhr.responseText)
                    if (response.status == 0) {
                        ToastMessage('error', response.message, 'HATA!')
                        return false;
                    }
                    ajaxError(code);
                    if (code == "Too Many Requests") {
                        SnackMessage('Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'error', 'bl')
                    } else if (code == 590) {
                        ToastMessage('error', 'Tarih aralığı max. 90 gün olabilir!', 'HATA!')
                    }
                },
                complete: function () {
                    SnackMessage('Tamamlandı!', 'info', 'bl')

                    if ($('#datatableRefreshBtn').prop('disabled') == true)
                        $('#datatableRefreshBtn').prop('disabled', false)

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
        })
    }

})

$(document).ready(function () {
    $('#agencyPaymentsAgency').select2()
    $('#addAgencyPaymentAgency').select2()
    $('#editAgencyPaymentAgency').select2()
})

$(document).on('click', '#btnAddAgencyPayment', function () {
    $('#ModalAddAgencyPayment').modal()
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
    })
    $('.blockUI.blockMsg.blockElement').css('width', '100%')
    $('.blockUI.blockMsg.blockElement').css('border', '0px')
    $('.blockUI.blockMsg.blockElement').css('background-color', '')

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
            tableAgencyPayments.draw()
            $('.add-agency-payment-fields').val('')
            $('#ModalAddAgencyPayment').modal('hide')
            ToastMessage('success', response.message, 'İşlem Başarılı!')
        }


    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyAgencyPaymentAppDetails').unblock()
    })
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
                })

            } else {
                ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi');
            }
        })
})

$(document).on('click', '.payment-details', function () {
    $('#ModalEditAgencyPayment').modal();

    agencyPaymentsDetailsID = $(this).prop('id')

    $('#ModalBodyEditAgencyPayment').block({
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
    })
    $('.blockUI.blockMsg.blockElement').css('width', '100%')
    $('.blockUI.blockMsg.blockElement').css('border', '0px')
    $('.blockUI.blockMsg.blockElement').css('background-color', '')

    $.ajax('/Safe/General/AjaxTransactions/GetPaymentInfo', {
        method: 'POST',
        data: {
            _token: token,
            id: $(this).prop('id'),
        }
    }).done(function (response) {

        if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!')
        } else if (response.status == 1) {

            let payment = response.data;

            $('#editAgencyPaymentAgency').val(payment.agency_id)
            $('#select2-editAgencyPaymentAgency-container').text($('#editAgencyPaymentAgency option:selected').html())
            $('#editAgencyPaymentPayingNameSurname').val(payment.paying_name_surname)
            $('#editAgencyPaymentPaymentChannel').val(payment.payment_channel)
            $('#editAgencyPaymentPayment').val(payment.payment)
            str = payment.payment_date;
            str = str.substr(0, 10) + "T" + str.substr(11, 2) + ":" + str.substr(14, 2);
            $('#editAgencyPaymentPaymentDate').val(str)
            $('#editAgencyPaymentDescription').val(payment.description)
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyEditAgencyPayment').unblock()
    })

})

$(document).on('click', '#btnUpdateAgencyPayment', function () {
    $('#ModalEditAgencyPayment').modal();

    $('#ModalBodyEditAgencyPayment').block({
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
    })
    $('.blockUI.blockMsg.blockElement').css('width', '100%')
    $('.blockUI.blockMsg.blockElement').css('border', '0px')
    $('.blockUI.blockMsg.blockElement').css('background-color', '')

    $.ajax('/Safe/General/AjaxTransactions/UpdateAgencyPayment', {
        method: 'POST',
        data: {
            _token: token,
            id: agencyPaymentsDetailsID,
            agencyID: $('#editAgencyPaymentAgency').val(),
            payingNameSurname: $('#editAgencyPaymentPayingNameSurname').val(),
            paymentChannel: $('#editAgencyPaymentPaymentChannel').val(),
            payment: $('#editAgencyPaymentPayment').val(),
            paymentDate: $('#editAgencyPaymentPaymentDate').val(),
            description: $('#editAgencyPaymentDescription').val(),
        }
    }).done(function (response) {

        if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        } else if (response.status == -1) {
            ToastMessage('error', response.message, 'Hata!')
        } else if (response.status == 1) {
            tableAgencyPayments.draw()
            $('#ModalEditAgencyPayment').modal('hide');
            ToastMessage('success', response.message, 'İşlem Başarılı!')
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyEditAgencyPayment').unblock()
    })

})
