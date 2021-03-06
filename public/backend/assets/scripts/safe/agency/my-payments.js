let myPaymentInit = false;

$('#tabMyPayments').click(function () {
    if (myPaymentInit == false) {

        myPaymentInit = true
        tableMyPayments = $('#tableMyPayments').DataTable({
            pageLength: 50,
            lengthMenu: dtLengthMenu,
            order: [8, 'desc'],
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
                url: '/Safe/Agency/AjaxTransactions/GetMyPayments',
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
                {data: 'agency_name', name: 'agency_name'},
                {data: 'row_type', name: 'row_type'},
                {data: 'app_id', name: 'app_id'},
                {data: 'payment', name: 'payment'},
                {data: 'payment_channel', name: 'payment_channel'},
                {data: 'payment_date', name: 'payment_date'},
                {data: 'paying_name_surname', name: 'paying_name_surname'},
                {data: 'description', name: 'description'},
                {data: 'name_surname', name: 'name_surname'},
                {data: 'created_at', name: 'created_at'},
            ],
            scrollY: '500px',
            scrollX: true,
        });

        $('#selectedExcelBtn').hide();


    }
});

