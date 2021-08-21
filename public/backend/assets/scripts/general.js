var CURRENT_URL = window.location.href.split('#')[0].split('?')[0];
var $SIDEBAR_MENU = $('#SideBar');

$(document).ready(function () {

    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').addClass('mm-active');
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent("li").addClass('mm-active');

    $('#datatableRefreshBtn').click(function () {
        $(this).prop('disabled', true);
    });

});

$('.btnReply').click(function () {
    $(this).prop('disabled', 'true');
    $('#ticketForm').submit();
})


// alert($(window).width());
//
// if (parseInt($(window).width()) <= 1249) {
//     alert('gived!');
//     $('#main-logo').addClass('logo-width-type');
// }
