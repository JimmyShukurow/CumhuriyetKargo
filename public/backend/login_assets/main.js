$(document).ajaxStart(function () {
    loadingDialog("Show");
    //$(document.body).css({ 'cursor': 'wait' });
}).ajaxComplete(function () {
    //$(document.body).css({ 'cursor': 'default' });
});
function compareTime(minTime, maxTime) {
    //return Date.parse('01/01/2018 ' + minTime) <= Date.parse('01/01/2018 ' + maxTime);


    return Date.parse(minTime) <= Date.parse(maxTime);
}


function splitMail(val) {
    return val.split(/;\s*/);
}

function extractLastMail(term) {
    return splitMail(term).pop();
}

$(document).ajaxStop(function () {
    loadingDialog("Hide");
});
function loadingDialog(prm) {
    try {
        if (prm == "Show") {
            $.blockUI({
                message: '<div class="icon-spinner9 icon-spin icon-lg"></div>',
                overlayCSS: {
                    backgroundColor: '#FFF',
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        }
        else if (prm == "Hide") {
            $.unblockUI();
        }
        else {
            $.unblockUI();
        }
    }
    catch (err)
    { }
}


function alert_content() {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", "/component/alert_content", false); // false for synchronous request
    xmlHttp.send(null);

    var content = xmlHttp.responseText;

    if (content != "") {
        $("#_alert_content").empty().append(xmlHttp.responseText);
    }

}

function OnlyNumber(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode

    if (charCode >= 48 && charCode <= 57) return true;
    else return false;

}
function OnlyNumberandComma(evt) {

    var charCode = (evt.which) ? evt.which : event.keyCode
    //alert(String.fromCharCode(charCode));
    if (charCode == 44 || (charCode >= 48 && charCode <= 57)) {
        return true;
    }
    else {
        return false;
    }

}

function SessionControl(handleData)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", "/component/SessionControl", false); // false for synchronous request
    xmlHttp.send(null);

    var result = JSON.parse(xmlHttp.responseText);

    handleData(result.SessionStatus);
    //var _url = "/component/SessionControl";
    //var _data;
    //$.ajax({
    //    cache: false,
    //    url: _url,
    //    data: _data,
    //    type: "post",
    //    success: function (result) {
    //        handleData(result.SessionStatus);
    //    }, error: function (e) {
    //        //MessageBox("Sonuç", e.responseText);
    //    }
    //});
}

function MessageBox(title,message)
{
    var divID = 'modal-popup-messagebox';
    var bg_div = "<div id='" + divID + "-bg" + "' style='position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 1051; background-color: #000; opacity: 0.5;'></div>";
    var _url = "/component/MessageBox";
    var _data = {
        message: message,
        title: title
    };
    $.ajax({
        cache: false,
        url: _url,
        data: _data,
        type: "post",
        success: function (result) {
            try{
                var elem = document.getElementById(divID);
                elem.parentNode.removeChild(elem);

                var elem2 = document.getElementById(divID + "-bg");
                elem2.parentNode.removeChild(elem2);
            }
            catch (err) { }
            $("#modal-popup-content").append(bg_div);
            $("#modal-popup-content").append(result);

            $('#' + divID).on('hide.bs.modal', function () {
                //Popup Kapanmadan önce çalışacak script ler
                try {
                    var elem2 = document.getElementById(divID + "-bg");
                    elem2.parentNode.removeChild(elem2);
                }
                catch (err) { }
            });

            $('#' + divID).on('hidden.bs.modal', function () {
                if ($('.modal:visible').length) { $('.modal-backdrop').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) - 10); $('body').addClass('modal-open'); }//Popup Kapandıktan sonra çalışacak script ler
            });

            $("#" + divID).modal({
                //backdrop: false
            });
        }, error: function (e) {
            //MessageBox("Sonuç", e.responseText);
        }
    });
}



function popup_LoginForm() {
    var divID = 'modal-popup-LoginForm';
    var bg_div = "<div id='" + divID + "-bg" + "' style='position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 1051; background-color: #000; opacity: 0.5;'></div>";
    var _url = "/component/popup_LoginForm";
    var _data;
    $.ajax({
        cache: false,
        url: _url,
        data: _data,
        success: function (result) {
            try {
                var elem = document.getElementById(divID);
                elem.parentNode.removeChild(elem);

                var elem2 = document.getElementById(divID + "-bg");
                elem2.parentNode.removeChild(elem2);
            }
            catch (err) { }
            $("#modal-popup-content").append(bg_div);
            $("#modal-popup-content").append(result);

            $('#' + divID).on('hide.bs.modal', function () {
                //Popup Kapanmadan önce çalışacak script ler
                try {
                    var elem2 = document.getElementById(divID + "-bg");
                    elem2.parentNode.removeChild(elem2);
                }
                catch (err) { }
            });

            $("#" + divID).modal({
                //backdrop: false
            });
        }, error: function (e) {
            //MessageBox("Sonuç", e.responseText);
        }
    });
}


function formatLa(deger, ayrac, hedef) {
    var retVal = ""; var T = "";


    if (deger.search(',') == -1) {
        var x = deger.split(ayrac)
        for (i = 0; i < x.length ; i++) { T += String(x[i]); }
        for (i = T.length; i > 0 ; i--) {
            if (((T.length - i) % 3 == 0) && (i < T.length)) { retVal = T.substring(i - 1, i) + ayrac + retVal; }
            else { retVal = T.substring(i - 1, i) + retVal }
        }
        if (hedef == null)
            event.srcElement.value = retVal;
        else
            hedef.value = retVal;

    }
}


function checkPointInsideOutside(latitude, longitude, latlong) {

    var i, j, polygon_border_count = latlong.length;
    var c = false;
    for (i = 0, j = polygon_border_count - 1; i < polygon_border_count; j = i++) {

        if (((latlong[i].lng > longitude) != (latlong[j].lng > longitude)) &&
            (latitude < (latlong[j].lat - latlong[i].lat) * (longitude - latlong[i].lng) / (latlong[j].lng - latlong[i].lng) + latlong[i].lat))
            c = !c;
    }
    return c;
}


function DakikadanAyrintiliSureBilgisi(dakika)
{
    var result = "";

    if (dakika > 0)
    {

        var bir_gun = 24 * 60;
        if (parseInt(dakika / bir_gun) > 0) { result = result + parseInt(dakika / bir_gun) + " gün "; dakika = dakika % bir_gun; }
        if (parseInt(dakika / 60) > 0) { result = result + parseInt(dakika / 60) + " sa "; dakika = dakika % 60; }
        if (dakika > 0) { result = result + dakika + " dk "; }
    }

    if (result == "") result = "-";

    return result;
}

const popupCenter = ({ url, title, w, h }) => {
    // Fixes dual-screen position                             Most browsers      Firefox
    const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
    const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

    const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    const systemZoom = width / window.screen.availWidth;
    const left = (width - w) / 2 / systemZoom + dualScreenLeft
    const top = (height - h) / 2 / systemZoom + dualScreenTop
    const newWindow = window.open(url, title,
        `
      scrollbars=yes,
      width=${w / systemZoom}, 
      height=${h / systemZoom}, 
      top=${top}, 
      left=${left}
      `
    )

    if (window.focus) newWindow.focus();
}


function UyariKontrol(IslemID, ID) {

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", "/uyarilar/UyariKontrol?IslemID=" + IslemID + "&ID=" + ID, false); // false for synchronous request
    xmlHttp.send(null);

    var result = JSON.parse(xmlHttp.responseText);

    if (result.open_popup == 1) {

        SessionControl(function (output) {
            if (output) {
                var _url = "/uyarilar/popup_Uyari";
                var divID = 'modal-popup_Uyari';
                var _data = {
                    popup_content_id: result.popup_content_id,
                    popup_content_id_1: result.popup_content_id_1,
                    id: result.id


                };
                $.ajax({
                    cache: false,
                    url: _url,
                    data: _data,
                    success: function (result) {
                        var bg_div = "<div id='" + divID + "-bg" + "' style='position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 1051; background-color: #000; opacity: 0.5;'></div>";
                        try {
                            var elem = document.getElementById(divID);
                            elem.parentNode.removeChild(elem);

                            var elem2 = document.getElementById(divID + "-bg");
                            elem2.parentNode.removeChild(elem2);
                        }
                        catch (err) { }
                        $("#modal-popup-content").append(bg_div);
                        $("#modal-popup-content").append(result);

                        $('#' + divID).on('show.bs.modal', function () {
                            //Popup yüklenirken çalışacak script ler

                            if ($('#modal_chk-KaraListe').length) {
                                $('#modal_chk-KaraListe').iCheck({
                                    checkboxClass: 'icheckbox_square-blue',
                                    radioClass: 'iradio_square-blue',
                                });
                            }
                        });
                        $('#' + divID).on('shown.bs.modal', function () {
                            //Popup yüklendikten sonra çalışacak script ler
                            $('.modal-backdrop').remove();
                        });
                        $('#' + divID).on('hide.bs.modal', function () {
                            //Popup Kapanmadan önce çalışacak script ler
                            try {
                                var elem2 = document.getElementById(divID + "-bg");
                                elem2.parentNode.removeChild(elem2);
                            }
                            catch (err) { }
                        });
                        $('#' + divID).on('hidden.bs.modal', function () {
                            if ($('.modal:visible').length) { $('.modal-backdrop').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) - 10); $('body').addClass('modal-open'); }//Popup Kapandıktan sonra çalışacak script ler
                        });

                        $("#" + divID).modal({
                            backdrop: 'static'
                        });
                    }, error: function (e) {
                        alert(e.responseText);//MessageBox("Hata!", e);
                    }
                });
            }
            else {
                popup_LoginForm();
            }
        });
    }
    
}
