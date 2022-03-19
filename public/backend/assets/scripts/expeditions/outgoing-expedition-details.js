$(document).on('dblclick', '.expedition-details', function() {
    let id = $(this).prop('id')
    detailsID = id;
});

$(document).on('click','#deleteExpedition', function () {
    swal({
        title: "Silme İşlemini Onaylayın!",
        text: "Emin misiniz? Bu işlem geri alınamaz!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then( function () {
        deleteExpedition(detailsID)
    })
});

function deleteExpedition (expedition){
    let deleteUrl = '/Expedition/Delete';
    $.ajax(deleteUrl, {
        method: 'DELETE',
        data: {
            _token: token,
            expedition_id: expedition
        },
        cache: false
    }).done(function (response) {
        ToastMessage('success', response.message, 'SİLİNDİ!');
        $('#ModalExpeditionDetails').modal('hide');
        tableOutGoingExpeditions.draw();
    }).error(function(jqXHR, exception) {
        ToastMessage('error', response.message, 'HATA!');
    })
}

