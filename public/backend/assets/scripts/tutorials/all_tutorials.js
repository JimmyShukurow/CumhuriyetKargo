$(document).ready(function () {
    tableOutGoingExpeditions = $('#TutorialsTable').DataTable({
        pageLength: 250,
        lengthMenu: dtLengthMenu,
        order: [0,'desc'],
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
            url: '/Tutorials/Ajax/GetAllTutorials',
            data: function (d) {
                d.name = $('#videoName').val()
                d.category = $('#category').val()
                d.tutor = $('#tutor').val()
                d.description = $('#description').val()
                d.created_at = $('#created_at').val()
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
            {data: 'name', name: 'video_name'},
            {data: 'category', name: 'category'},
            {data: 'embedded_link', name: 'embedded_link'},
            {data: 'tutor', name: 'tutor'},
            {data: 'created_at', name: 'created_at'},
        ],
        scrollY: '500px',
        scrollX: true,
    })

    // Local Storage Transaction START
    let cargoSuccees = localStorage.getItem('tutorial-success');
    if (cargoSuccees) {
        swal('İşlem Başarılı!', 'Eğitim Oluşturuldu!', 'success');
        localStorage.clear();
    }
    // Local Storage Transaction END

    $('.videoCard').on('click', function(){
        let src = $(this).children('iframe').attr('src');
        $('#modalVideoCard').attr('src', src);
    })
  
    $("#exampleModal").on("hidden.bs.modal",function(){
        $('#modalVideoCard')[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
    });
})
