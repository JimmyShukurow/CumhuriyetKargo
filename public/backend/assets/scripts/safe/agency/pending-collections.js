let pendingCollectionInit = false;

$('#tabPendingCollection').click(function () {

    if (pendingCollectionInit == false) {
        pendingCollectionInit = true
        oTable = $('#tablePendingCollections').DataTable({
            pageLength: 10,
            lengthMenu: dtLengthMenu,
            order: [6, 'desc'],
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
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/Safe/Agency/AjaxTransactions/GetPendingCollections',
                data: function (d) {
                    d.firstDate = $('#pendingCollectionFirstDate').val()
                    d.lastDate = $('#pendingCollectionLastDate').val()
                    d.dateFilter = $('#pendingCollectionDateFilter').prop('checked')
                },
                error: function (xhr, error, code) {
                    if (code == "Too Many Requests") {
                        SnackMessage('Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'error', 'bl');
                    }
                },
                complete: function () {
                    SnackMessage('Tamamlandı!', 'info', 'bl');

                    if ($('#datatableRefreshBtn').prop('disabled') == true)
                        $('#datatableRefreshBtn').prop('disabled', false);

                }
            },
            columns: [
                {data: 'created_at', name: 'created_at'},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'total_price', name: 'total_price'},
                {data: 'sender_name', name: 'sender_name'},
                {data: 'sender_current_code', name: 'sender_current_code'},
                {data: 'receiver_name', name: 'receiver_name'},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'invoice_number', name: 'invoice_number'},
            ],
            scrollY: '500px',
            scrollX: true,
        });

        $('#selectedExcelBtn').hide();

        // Local Storage Transaction START
        let cargoSuccees = localStorage.getItem('cargo-success');
        if (cargoSuccees) {
            swal('İşlem Başarılı!', 'Kargo Oluşturuldu!', 'success');
            localStorage.clear();
        }
        // Local Storage Transaction END
    }

});
