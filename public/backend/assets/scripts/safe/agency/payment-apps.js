let paymentAppsInit = false;

$('#tabPaymentApps').click(function () {
    if (paymentAppsInit == false) {

        paymentAppsInit = true
        tablePaymentApp = $('#tablePaymentApps').DataTable({
            pageLength: 50,
            lengthMenu: dtLengthMenu,
            order: [12, 'desc'],
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
                        id: 'datatableRefreshBtn'
                    }
                },
            ],
            responsive: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/Safe/Agency/AjaxTransactions/GetPaymentApps',
                data: function (d) {
                    d.firstDate = $('#paymentAppFirstDate').val()
                    d.lastDate = $('#paymentAppLastDate').val()
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
                {data: 'agency_name', name: 'agency_name'},
                {data: 'name_surname', name: 'name_surname'},
                {data: 'paid', name: 'paid'},
                {data: 'confirm_paid', name: 'confirm_paid'},
                {data: 'payment_channel', name: 'payment_channel'},
                {data: 'add_files', name: 'add_files'},
                {data: 'description', name: 'description'},
                {data: 'currency', name: 'currency'},
                {data: 'confirm', name: 'confirm'},
                {data: 'confirming_user_name_surname', name: 'confirming_user_name_surname'},
                {data: 'confirming_date', name: 'confirming_date'},
                {data: 'created_at', name: 'created_at'},
                {data: 'delete', name: 'delete'},
            ],
            scrollY: '500px',
            scrollX: true,
        });

        $('#selectedExcelBtn').hide();


    }
});

$(document).on('click', '.delete-app', function () {
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
                    type: "GET",
                    url: '/Safe/Agency/AjaxTransactions/DeletePaymentApp',
                    data: {destroy_id: $(this).prop('id')},
                    success: function (response) {
                        if (response.status == 1) {
                            ToastMessage('success', response.message, 'İşlem Başarılı!');
                            tablePaymentApp.ajax.reload();
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
