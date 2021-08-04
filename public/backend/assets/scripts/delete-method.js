$(document).on('click', '.trash', function () {

    var from = $(this).attr("from");
    var object;

    destroy_id = $(this).attr('id');

    if (from == "transshipment_center") {
        url = "TransshipmentCenters/" + destroy_id;
        object = "Transfer merkezi";
    } else if (from == "current") {
        url = "SenderCurrents/" + destroy_id;
        object = "Gönderici Cari";
    } else if (from == "various_car") {
        url = "VariousCars/" + destroy_id;
        object = "Muhtelif Araç";
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

                            $('.NikolasDataTable').DataTable().ajax.reload();


                        } else
                            ToastMessage('error',
                                'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                                'İşlem Başarısız!');
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


$('.trash').click(function () {


    var from = $(this).attr("from");
    var object;

    destroy_id = $(this).attr('id');

    if (from == "transshipment_center") {
        url = "TransshipmentCenters/" + destroy_id;
        object = "Transfer merkezi";
    } else if (from == "department") {
        url = "Departments/" + destroy_id;
        object = "Departman";
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
