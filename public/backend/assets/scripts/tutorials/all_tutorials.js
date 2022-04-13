$(document).ready(function () {

    $('#filterTutorials').on('click', function () {
        filterVideos();
    })

    // Local Storage Transaction START
    let cargoSuccees = localStorage.getItem('tutorial-success');
    if (cargoSuccees) {
        swal('İşlem Başarılı!', 'Eğitim Oluşturuldu!', 'success');
        localStorage.clear();
    }
    // Local Storage Transaction END

    $('.videoCard').on('click', function () {
        let src = $(this).children('iframe').attr('src');
        let name = $(this).data('name')
        $('#modalVideoCard').attr('src', src);
        $('#exampleModalLabel').text(name);
    })

    $("#exampleModal").on("hidden.bs.modal", function () {
        $('#modalVideoCard')[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
    });

    $('.videoCard span:last').on('click', function(e){
        e.stopPropagation();
        var id = $(this).parent().attr('id')
        console.log(id);
        swal({
            title: "Eğitim silinicek!",
            text: ". Silmek istediğinizden emin misiniz?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelet) => {
            if (willDelet) {
                deleteVideo(id);
                filterVideos();
            }
        });
       
    })
    

})

function filterVideos() {
    $.post('/Tutorials/Ajax/GetAllTutorials',
        {
            _token: token,
            name: $('#videoName').val(),
            category: $('#category').val(),
            tutor: $('#tutor').val(),
            description: $('#description').val(),
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val()
        },
        function (responce) {
            let text = '';
            const videos = responce.rows;
            videos.forEach(element => {
                text += '<div class="card col-md-3 videoCard" data-toggle="modal" data-target="#exampleModal" data-name="' + element.name + '"> \
                <div class="card-header" >' + element.name + '</div><iframe style="pointer-events: none;" \
                src="' + element.embedded_link + '" title="YouTube video player" frameborder="0"></iframe> \
                <div class="card-body">' + element.description + '</div><div class="card-footer">' + element.tutor + '</div>\
                <div class="d-flex justify-content-between mb-2"  id=' + element.id +' >\
                <span class="btn btn-success" class="editVideo"> Edit</span>\
                <span class="btn btn-danger" class="deleteVideo"> Delete</span>\
                </div>\
                </div>';
            });

            $('#allVideos').prop('innerHTML', text);

        });
}

function deleteVideo(id){

    $.ajax({
        url: '/Tutorials/tutorial/' +id,
        data: {
            _token: token,
        },
        type: 'DELETE',
        success: function(responce) {
            ToastMessage('success', responce.message, 'SİLİNDİ!');
        }
    });

}

