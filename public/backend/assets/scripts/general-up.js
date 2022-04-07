let componentReceiverFrom = null, componentSenderFrom = null

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

function ajaxError(status, response = null) {
    var msg = '';
    if (status === 0)
        msg = 'Sunucu ile bağlantı kurulamadı!';
    else if (status == 429)
        msg = 'Aşırı istekte bulundunuz, lütfen bir süre sonra tekrar deneyein! [429]';
    else if (status == 422) {

        $.each(response.errors, function (key, val) {
            ToastMessage('error', val, 'Hata!')
        })

    } else
        msg = 'Bilinmeyen bir hata oluştu! [' + status + ']';

    ToastMessage('error', msg, 'Hata!');
    if (response != null)
        ToastMessage('error', response.message, 'Hata!')

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

function printBarcode(selector) {
    $('.barcode-divider').hide();
    var divContents = $(selector).html();
    var $cssLink = $('link');
    var printWindow = window.open('', '', 'height=' + window.outerHeight * 0.6 + ', width=' + window.outerWidth * 0.6);
    printWindow.document.write('<html><head>');
    for (var i = 0; i < $cssLink.length; i++) {
        printWindow.document.write($cssLink[i].outerHTML);
    }
    printWindow.document.write('</head><body>');
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
    $('.barcode-divider').show();
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
        addressNoteX = address_note != '' && address_note != null ? "(" + address_note + ")" : "";

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


function clicker(selector) {
    $(selector).click();
}

function NikoStylePostMethod() {
    $('#ModalCargoDetails').block({
        message: $('<div class="loader mx-auto">\n' +
            '                            <div class="ball-grid-pulse">\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                            </div>\n' +
            '                        </div>')
    });
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('url', {
        method: 'POST',
        data: {
            _token: token,
        }
    }).done(function (response) {

        if (response.status == 1) {

        } else if (response.status == -1) {

        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status, JSON.parse(jqXHR.responseText));
    }).always(function () {
        $('#ModalCargoDetails').unblock();
    });
}

$(document).on('keyup', '#search-input', delay(function (e) {
    // down
    if (e.keyCode == 40)
        return false;
    // #up
    else if (e.keyCode == 38)
        return false;
    // #enter
    else if (e.keyCode == 13)
        return false;

    searchModule();

}, 650));

var curent_active = 0;

$(document).on('keyup', '#search-input', function (e) {

    var li_count = $('li.li-of-search-module').length;

    var each_counter = 0;
    $('li.li-of-search-module').each(function () {
        if ($(this).hasClass('mm-search-active')) {
            curent_active = each_counter;
        }
        each_counter++;
    });

    if (e.keyCode == 38) { // top
        curent_active -= 1;
    }
    if (e.keyCode == 40) { // bott
        curent_active += 1;
    }

    if (e.keyCode == 13)
        window.location.href = $('li.li-of-search-module a').eq(curent_active).attr('href');


    if (curent_active == li_count) {
        curent_active = 0;
    }

    $('li.li-of-search-module').removeClass('mm-search-active');
    $('li.li-of-search-module').eq(curent_active).addClass('mm-search-active');
});


function searchModule() {
    $('#SearchPanel').block({
        message: $('<div class="loader mx-auto">\n' +
            '                            <div class="ball-grid-pulse">\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                                <div class="bg-white"></div>\n' +
            '                            </div>\n' +
            '                        </div>')
    });

    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/SearchModule', {
        method: 'POST',
        data: {
            _token: token,
            searchTerm: $('#search-input').val()
        }
    }).done(function (response) {

        $('#SearchLinkUl').html('');

        if (response.length == 0) {
            $('#SearchLinkUl').html('<div class="row"><div class="col-md-12 text-center"><b style="color: #fff;">Üzgünüz, aradığınız şeyi bulamadık.</b></div></div>');
        } else
            $.each(response, function (key, val) {
                $('#SearchLinkUl').append('' +
                    '<li class="li-of-search-module"><a class="search-link" href="' + val['url'] + '">' + val['sub_name'] + ' (' + val['module_name'] + ')<i class="metismenu-icon ' + val['ico'] + '"></i></a></li>\n' +
                    '');
            });

        $('li.li-of-search-module').eq(0).addClass('mm-search-active')
        $('li.li-of-search-module').eq(0).focus();

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#SearchPanel').unblock();
    });
}

$(document).on('click', 'button.close', function () {
    $('#SearchPanel').removeClass('animate__bounceIn');
    $('#SearchPanel').addClass('animate__bounceOut');
});

$(document).on('click', 'button.search-icon', function () {
    $('#SearchPanel').show();
    $('input.search-input').focus();
    $('#SearchPanel').removeClass('animate__bounceOut');
    $('#SearchPanel').addClass('animate__bounceIn');
});

$(document).on('click', '#search-input', function () {
    $('#SearchPanel').removeClass('animate__bounceOut');
    $('#SearchPanel').show();
});

$(document).ready(function () {
    searchModule();
});

$(document).mouseup(function (e) {
    var container = $("#SearchPanel");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        $('#SearchPanel').removeClass('animate__bounceIn');
        $('#SearchPanel').addClass('animate__bounceOut');
    }
});

$(window).keydown(function (event) {
    if (event.ctrlKey && (event.which == 66 || event.which == 118)) {
        $('.search-icon').trigger('click');
    }
});

$(document).ready(function () {
    $('#btnShortCutSearch').click(function () {
        $('.search-icon').trigger('click');
    });
});

$(document).ready(function () {

    let successMessage = localStorage.getItem('swal');
    if (successMessage) {
        swal(localStorage.getItem('swal-title'), localStorage.getItem('swal-message'), localStorage.getItem('swal-type'));

        localStorage.removeItem('swal');
        localStorage.removeItem('swal-title');
        localStorage.removeItem('swal-message');
        localStorage.removeItem('swal-type');
    }
})

function setMessageToLS(title, message, type) {
    // # => Set Message To Locale Storage!

    localStorage.setItem('swal', true);
    localStorage.setItem('swal-title', title);
    localStorage.setItem('swal-message', message);
    localStorage.setItem('swal-type', type);
}

$(document).ready(function () {
    $('.alert-not-yet').click(function () {
        ToastMessage('warning', 'Bu modül çok yakında aktif hale gelecektir!', 'Uyarı!');
    });
});

dtLanguage = {
    "sDecimal": ",",
    "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
    "sInfo": "_TOTAL_ kayıttan _START_ - _END_ kayıtlar gösteriliyor",
    "sInfoEmpty": "Kayıt yok",
    "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
    "sInfoPostFix": "",
    "sInfoThousands": ".",
    "sLengthMenu": "_MENU_",
    "sLoadingRecords": "Yükleniyor...",
    "sProcessing": "<div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div>",
    "sSearch": "",
    "sZeroRecords": "Eşleşen kayıt bulunamadı",
    "oPaginate": {
        "sFirst": "İlk",
        "sLast": "Son",
        "sNext": "Sonraki",
        "sPrevious": "Önceki"
    },
    "oAria": {
        "sSortAscending": ": artan sütun sıralamasını aktifleştir",
        "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
    },
    "select": {
        "rows": {
            "_": "%d kayıt seçildi",
            "0": "",
            "1": "1 kayıt seçildi"
        }
    }
};

dtLengthMenu = [
    [10, 25, 50, 100, 250, 500, -1],
    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
];

$(document).on('keyup', '.dataTables_filter input', function () {
    $(this).val($(this).val().toLocaleUpperCase())
});

whiteAnimation = {
    message: $('<div class="loader mx-auto">\n' +
        '                            <div class="ball-grid-pulse">\n' +
        '                                <div class="bg-white"></div>\n' +
        '                                <div class="bg-white"></div>\n' +
        '                                <div class="bg-white"></div>\n' +
        '                                <div class="bg-white"></div>\n' +
        '                                <div class="bg-white"></div>\n' +
        '                                <div class="bg-white"></div>\n' +
        '                                <div class="bg-white"></div>\n' +
        '                                <div class="bg-white"></div>\n' +
        '                                <div class="bg-white"></div>\n' +
        '                            </div>\n' +
        '                        </div>')
}

var currentDate = new Date()
currentDate = currentDate.toLocaleDateString('tr-TR')
detailTrackingNo = null
