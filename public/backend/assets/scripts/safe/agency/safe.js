$(document).ready(function () {

    oTable = $('#tableSafe').DataTable({
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [7, 'desc'],
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
                    id: 'datatableRefreshBtn'
                }
            },
        ],
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/Safe/Agency/AjaxTransactions/GetCollections',
            data: function (d) {
                d.firstDate = $('#collectionFirstDate').val()
                d.lastDate = $('#collectionLastDate').val()
                // d.dateFilter = $('#collectionDateFilter').prop('checked')
                d.dateFilter = 'true'
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
            {data: 'invoice_date', name: 'invoice_date'},
            {data: 'invoice_number', name: 'invoice_number'},
            {data: 'total_price', name: 'total_price'},
            {data: 'collection_type_entered', name: 'collection_type_entered'},
            {data: 'sender_name', name: 'sender_name'},
            {data: 'sender_current_code', name: 'sender_current_code'},
            {data: 'receiver_name', name: 'receiver_name'},
            {data: 'created_at', name: 'created_at'},
            {data: 'invoice_number', name: 'invoice_number'},
        ],
        // scrollY: '450px',
        // scrollX: false,
    });

    $('#selectedExcelBtn').hide();

    // Local Storage Transaction START
    let cargoSuccees = localStorage.getItem('cargo-success');
    if (cargoSuccees) {
        swal('İşlem Başarılı!', 'Kargo Oluşturuldu!', 'success');
        localStorage.clear();
    }
    // Local Storage Transaction END

});
