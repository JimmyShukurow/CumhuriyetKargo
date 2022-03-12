$(document).ready(function () {
    tableOutGoingExpeditions = $('#OutGoingExpeditionsTable').DataTable({
        pageLength: 250,
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
                    id: 'datatableRefreshBtn',
                    class: 'btn btn-primary',
                }
            },
            {
                extend: 'excelHtml5',
                attr: {class: 'btn btn-success'},
                title: "CKG-Sis Acente Kasa Durumu"
            },
            {
                text: 'Filtreyi Temizle',
                attr: {class: 'btn btn-info'},
                action: function (e, dt, node, config) {
                    clearFilter();
                },
            },
        ],
        responsive: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/Expedition/AjaxTransactions/GetOutGoingExpeditions',
            data: function (d) {
                d.firstDate = $('#filterStartDate').val()
                d.lastDate = $('#filterFinishDate').val()
                d.serialNo = $('#filterExpeditionSerialNo').val()
                d.plaka = $('#filterPlaque').val()
                d.departureBranch = $('#filterDepartureBranch').val()
                d.arrivalBranch = $('#filterArrivalBranch').val()
                d.creator = $('#filterCreatorUser').val()
                d.doneStatus = $('#filterDoneStatus').val()
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
                SnackMessage('Tamamlandı!', 'info', 'bl')

                if ($('#datatableRefreshBtn').prop('disabled') == true)
                    $('#datatableRefreshBtn').prop('disabled', false)

            }
        },
        columns: [
            {data: 'serial_no', name: 'serial_no'},
            {data: 'plaka', name: 'plaka'},
            {data: 'departure_branch', name: 'departure_branch'},
            {data: 'arrival_branch', name: 'arrival_branch'},
            {data: 'route_count', name: 'route_count'},
            {data: 'status', name: 'status'},
            {data: 'name_surname', name: 'name_surname'},
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
        ],
        scrollY: '500px',
        scrollX: true,
    })

    // Local Storage Transaction START
    let cargoSuccees = localStorage.getItem('expedition-success');
    if (cargoSuccees) {
        swal('İşlem Başarılı!', 'Sefer Oluşturuldu!', 'success');
        localStorage.clear();
    }
    // Local Storage Transaction END
})
