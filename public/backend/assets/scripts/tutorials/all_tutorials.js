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
        console.log(name);
        $('#modalVideoCard').attr('src', src);
        $('#exampleModalLabel').text(name);
    })

    $("#exampleModal").on("hidden.bs.modal", function () {
        $('#modalVideoCard')[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
    });

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
                <div class="card-body">' + element.description + '</div><div class="card-footer">' + element.tutor + '</div></div>';
            });

            $('#allVideos').html(text);

        });
}

