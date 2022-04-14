$(document).ready(function () {
    tableOutGoingExpeditions = $('#AllTutorials').DataTable({
        pageLength: 250,
        lengthMenu: dtLengthMenu,
        order: [0, 'desc'],
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
            url: '/Tutorials/Admin/Ajax/GetAllTutorials',
            data: function (d) {
                d.start_date = $('#start_date').val()
                d.end_date = $('#end_date').val()
                d.videoName = $('#videoName').val()
                d.category = $('#category').val()
                d.description = $('#description').val()
                d.tutor = $('#tutor').val()

               
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
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'category', name: 'category'},
            {data: 'link', name: 'link'},
            {data: 'tutor', name: 'tutor'},
            {data: 'created_at', name: 'created_at'},
            {data: 'edit', name: 'edit'},

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
