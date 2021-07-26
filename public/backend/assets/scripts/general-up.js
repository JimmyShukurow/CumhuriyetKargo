function ToastMessage(type, message, title) {
    toastr[type](message, title)

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "rtl": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
}

let token = $('meta[name="csrf-token"]').attr('content');

function getTime() {
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth();
    var day = date.getDay();
    var hour = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();
    return day + "/" + month + "/" + year + " - " + hour + ":" + minute + ":" + second;
}

function confirmBox(title, text, href, icon = 'warning') {

    swal({
        title: title,
        text: text,
        icon: icon,
        buttons: ['İptal Et', 'Onayla'],
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = href;
            } else {
                ToastMessage('info', 'İşlem iptal edilidi.', 'Bilgi');
            }
        });
}

function ajaxError(status) {
    var msg = '';
    if (status === 0)
        msg = 'Sunucu ile bağlantı kurulamadı!';
    else if (status == 429)
        msg = 'Aşırı istekte bulundunuz, lütfen bir süre sonra tekrar deneyein! [429]';
    else
        msg = 'Bilinmeyen bir hata oluştu! [' + status + ']';

    ToastMessage('error', msg, 'Hata!');
}

function delay(callback, ms) {
    var timer = 0;
    return function () {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

function printWindow(selector, title) {
    var divContents = $(selector).html();
    var $cssLink = $('link');
    var printWindow = window.open('', '', 'height=' + window.outerHeight * 0.6 + ', width=' + window.outerWidth * 0.6);
    printWindow.document.write('<html><head><h2><b><title>' + title + '</title></b></h2>');
    for (var i = 0; i < $cssLink.length; i++) {
        printWindow.document.write($cssLink[i].outerHTML);
    }
    printWindow.document.write('</head><body >');
    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.onload = function () {
        printWindow.focus();
        setTimeout(function () {
            printWindow.print();
            printWindow.close();
        }, 100);
    }
}

function roundLikePHP(num, dec){
    var num_sign = num >= 0 ? 1 : -1;
    return parseFloat((Math.round((num * Math.pow(10, dec)) + (num_sign * 0.0001)) / Math.pow(10, dec)).toFixed(dec));
}
