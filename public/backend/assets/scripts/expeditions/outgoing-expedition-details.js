var detailID;
$(document).on('click','.deleteExpedition', function () {
    detailID = $(this).attr('id');
    swal({
        title: "Silme İşlemini Onaylayın!",
        text: "Emin misiniz? Bu işlem geri alınamaz!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then( function () {
        deleteExpedition(detailID)
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
        if (response.status == 0) {
            ToastMessage('error', response.message, 'HATA!');
        }else {
            ToastMessage('success', response.message, 'SİLİNDİ!');
            window.location.replace('/Expedition/OutGoing');
        }
    }).error(function(jqXHR, exception) {
        ToastMessage('error', response.message, 'HATA!');
    })
}

