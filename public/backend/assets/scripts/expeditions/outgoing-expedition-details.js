var detailID;


$(document).on('click','#deleteExpedition', function () {
    detailID = $('#expeditionID').val();
    swal({
        title: "Silme İşlemini Onaylayın!",
        text: "Emin misiniz? Bu işlem geri alınamaz!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then( (willDelete) => {
        if (willDelete) {
            deleteExpedition(detailID);
        } else {
            ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi');
        }
    })
});

$(document).on('click','#finishExpedition', function () {
    detailID = $('#expeditionID').val();
    var cargoCount = $('#totalCargoCount').text();
    console.log(cargoCount);
    if (cargoCount > 0) {
        swal({
            title: "Seferin içersinde " + cargoCount + " tane kargo var.",
            text: ". Bitirmek istediğinizden emin misiniz?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelet) => {
            if (willDelet) {
                proceedEndingExpedition(detailID);
            }
        });
    } else {
        proceedEndingExpedition(detailID);
    }

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

function endExpedition(expedition) {
    let deleteUrl = '/Expedition/Finish';
    $.ajax(deleteUrl, {
        method: 'POST',
        data: {
            _token: token,
            expedition_id: expedition
        },
        cache: false
    }).done(function (response) {
        if (response.status == 0) {
            ToastMessage('error', response.message, 'HATA!');
        }else {
            ToastMessage('success', response.message, 'BİTİRİLDİ!');
            location.reload();
        }
    }).error(function(jqXHR, exception) {
        ToastMessage('error', response.message, 'HATA!');
    })
}

function proceedEndingExpedition(detailID) {
    swal({
        title: "Bitirme İşlemini Onaylayın!",
        text: "Emin misiniz? Bu işlem geri alınamaz!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then( (willDelete) => {
        if (willDelete) {
            endExpedition(detailID);
        } else {
            ToastMessage('info', 'Bitirme işlemi iptal edilidi.', 'Bilgi');
        }
    });
}

