$('#btnRecoverPassword').click(function () {
    if ($('#email').val().trim() == '') {
        ToastMessage('error', 'Lütfen mail adresi girin!', 'Hata!');
        return false;
    }
    if ($('#phone').val().trim() == '') {
        ToastMessage('error', 'Lütfen telefon numaranızı girin!', 'Hata!');
        return false;
    }
    $('#frmRecoverPassword').submit();
});


function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = "Kalan Süre: " + minutes + ":" + seconds;

        if (--timer <= 0) {
            window.location.href = "/ForgetPassword/TimeOut";
        }
    }, 1000);
}

$(document).ready(function () {
    var fiveMinutes = $('#leftseconds').val(),
        display = document.querySelector('#time');
    startTimer(fiveMinutes, display);
});

$(document).ready(function () {

    $('#btnConfirmCode').click(function () {


        if ($('#code').val().trim() == '') {
            ToastMessage('error', 'Lütfen 6 haneli güvenlik kodunu giriniz!');
            return false;
        }

        $(this).prop('disabled', true);

        $.ajax('/ConfirmLoginSecurityCode', {
            method: 'POST',
            data: {
                _token: token,
                token: $('#token').val(),
                code: $('#code').val()
            }
        }).done(function (response) {
            if (response.status == 0)
                ToastMessage('error', response.message, 'Hata!');


            if (response.status == 1) {
                ToastMessage('success', response.message, 'İşlem Başarılı!');
                setMessageToLS('Giriş Doğrulandı!', response.message, 'success');

                setInterval(function () {
                    window.location.href = "/";
                }, 1800);

            }


        }).error(function (jqXHR, exception) {
            if (jqXHR.status == 429) {
                ToastMessage('warning', 'Çok fazla deneme yaptınız, lütfen bir süre sonra tekrar deneyin!');
            }

        }).always(function () {
            $('#btnConfirmCode').prop('disabled', false);
        })

    });


});
