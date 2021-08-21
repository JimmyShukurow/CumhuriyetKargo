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

function roundLikePHP(num, dec) {
    var num_sign = num >= 0 ? 1 : -1;
    return parseFloat((Math.round((num * Math.pow(10, dec)) + (num_sign * 0.0001)) / Math.pow(10, dec)).toFixed(dec));
}

function getDotter(x) {
    return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
}


function adresMaker(city, district, neighborhood, street, street2, building_no, door_no, floor, address_note) {

    let cityX = city + "/",
        districtX = district + " ",
        neighborhoodX = neighborhood + " ",
        streetX = street != '' && street != null ? street + " CAD. " : "",
        street2X = street2 != '' && street2 != null ? street2 + " SK. " : "",
        buildingNoX = "NO:" + building_no + " ",
        doorX = "D:" + door_no + " ",
        floorX = "KAT:" + floor + " ",
        addressNoteX = "(" + address_note + ")";

    let fullAddress = cityX + districtX + neighborhoodX + streetX + street2X + buildingNoX + floorX + doorX + addressNoteX;
    return fullAddress;
}

function arrangeCargoTrackingNumber(detay) {
    // 34021 23428 95925
    let part1 = detay.substring(0, 5);
    let part2 = detay.substring(5, 10);
    let part3 = detay.substring(10, 15);

    let fullKTNO = part1 + " " + part2 + " " + part3;
    return fullKTNO;
}


function copyToClipBoard(string) {
    let textarea;
    let result;

    try {
        textarea = document.createElement('textarea');
        textarea.setAttribute('readonly', true);
        textarea.setAttribute('contenteditable', true);
        textarea.style.position = 'fixed'; // prevent scroll from jumping to the bottom when focus is set.
        textarea.value = string;

        document.body.appendChild(textarea);

        textarea.focus();
        textarea.select();

        const range = document.createRange();
        range.selectNodeContents(textarea);

        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);

        textarea.setSelectionRange(0, textarea.value.length);
        result = document.execCommand('copy');
    } catch (err) {
        console.error(err);
        result = null;
    } finally {
        document.body.removeChild(textarea);
    }

    // manual copy fallback using prompt
    if (!result) {
        const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
        const copyHotkey = isMac ? 'âŒ˜C' : 'CTRL+C';
        result = prompt(`Press ${copyHotkey}`, string); // eslint-disable-line no-alert
        if (!result) {
            return false;
        }
    }
    return true;
}

var SnackMessage = function (message, type, position = 'tr') {
    SnackBar({
        message: message,
        status: type,
        position: position,
        fixed: true
    });
};
