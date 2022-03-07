let agencyPaymenAppstInit = false;
let detailsID = null;

$('#tabAgencyPaymentApps').click(function () {

    if (agencyPaymenAppstInit == false) {

        agencyPaymenAppstInit = true

        tableAgencyPaymentApps = $('#tableAgencyPaymentApps').DataTable({
            pageLength: 50,
            lengthMenu: dtLengthMenu,
            order: [12, 'desc'],
            language: dtLanguage,
            dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
            select: {
                style: 'multi',
                selector: 'td:nth-child(0)'
            },
            buttons: [
                {
                    text: 'Yenile',
                    action: function (e, dt, node, config) {
                        dt.ajax.reload();
                    },
                    attr: {
                        id: 'datatableRefreshBtn'
                    }
                },
            ],
            responsive: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/Safe/General/AjaxTransactions/GetAgencyPaymentApps',
                data: function (d) {
                    d.firstDate = $('#agencyPaymentAppsFirstDate').val()
                    d.lastDate = $('#agencyPaymentAppsLastDate').val()

                    d.appNo = $('#agencyPaymentAppsAppNo').val()
                    d.agency = $('#agencyPaymentAppsAgency').val()
                    d.confirm = $('#agencyPaymentAppsConfirm').val()
                    d.paymentChannel = $('#agencyPaymentAppsPaymentChannel').val()
                },
                error: function (xhr, error, code) {

                    let response = JSON.parse(xhr.responseText);
                    if (response.status == 0) {
                        ToastMessage('error', response.message, 'HATA!');
                        return false;
                    }
                    ajaxError(code);
                    if (code == "Too Many Requests") {
                        SnackMessage('Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'error', 'bl');
                    } else if (code == 590) {
                        ToastMessage('error', 'Tarih aralığı max. 90 gün olabilir!', 'HATA!');
                    }
                },
                complete: function () {
                    SnackMessage('Tamamlandı!', 'info', 'bl');
                    getAppSummery()
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'agency_name', name: 'agency_name'},
                {data: 'name_surname', name: 'name_surname'},
                {data: 'paid', name: 'paid'},
                {data: 'confirm_paid', name: 'confirm_paid'},
                {data: 'payment_channel', name: 'payment_channel'},
                {data: 'add_files', name: 'add_files'},
                {data: 'description', name: 'description'},
                {data: 'currency', name: 'currency'},
                {data: 'confirm', name: 'confirm'},
                {data: 'confirming_user_name_surname', name: 'confirming_user_name_surname'},
                {data: 'confirming_date', name: 'confirming_date'},
                {data: 'created_at', name: 'created_at'},
                {data: 'delete', name: 'delete'},
            ],
            scrollY: '500px',
            scrollX: true,
        });
    }

});

$(document).ready(function () {
    $('#agencyPaymentAppsAgency').select2();
})


function getPaymentDetails(id) {
    $('#ModalAgencyPaymentAppDetails').modal();

    $('#ModalBodyAgencyPaymentAppDetails').block({
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

    $.ajax('/Safe/General/AjaxTransactions/GetAgencyPaymentAppDetails', {
        method: 'GET',
        data: {
            id: id,
        }
    }).done(function (response) {

        if (response.status == 1) {

            let data = response.data;
            $('#agencyName').html(data.agency_name + " ŞUBE")
            $('#appUserNameSurname').html(data.name_surname + " (" + data.display_name + ")")


            $('td#appRegDate').html(data.created_at)
            $('td#appNo').html(data.id)
            $('td#appAgencyName').html(data.agency_name + " ŞUBE")
            $('td#appUserNameSurname').html(data.name_surname + " (" + data.display_name + ")")
            $('td#appPayment').html(data.paid + "₺")
            $('td#appConfirmingPayment').html(data.confirm_paid)
            $('td#appPaymentChannel').html(data.payment_channel)

            let addLink = "";

            if (data.file1 != null)
                addLink += '<a target="_blank" href="/files/app_files/' + data.file1 + '">Ek1</a>'
            if (data.file2 != null)
                addLink += ' <a target="_blank" href="/files/app_files/' + data.file2 + '">Ek2</a>'
            if (data.file3 != null)
                addLink += ' <a target="_blank" href="/files/app_files/' + data.file3 + '">Ek3</a>'

            $('td#appAdd').html(addLink)
            $('td#appDescription').html(data.description)

            let confirm = "";
            if (data.confirm == '0')
                confirm = '<b class="text-primary">Onay Bekliyor</b>'
            else if (data.confirm == '1')
                confirm = '<b class="text-success">Onaylandı</b>'
            else if (data.confirm == '-1')
                confirm = '<b class="text-danger">Reddedildi</b>'


            $('#appConfirmPaidAmount').val(data.confirm_paid != null ? data.confirm_paid + "₺" : "");
            $('td#appConfirm').html(confirm)
            $('td#appConfirmingUser').html(data.confirming_user_name_surname != null ? data.confirming_user_name_surname + " (" + data.confirming_user_display_name + ")" : "")
            $('td#appConfirmDate').html(data.confirming_date)
            $('td#appRejectReason').html(data.reject_reason)

            $('#appConfirmPaidAmount').val(data.confirm_paid)
            $('textarea#appRejectReason').val(data.reject_reason)


        } else if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!')
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyAgencyPaymentAppDetails').unblock();
    });

}

$(document).on('click', '.details-app', function () {
    detailsID = $(this).prop('id');
    getPaymentDetails(detailsID)
});

$(document).on('click', '#appSameAmountLink', function () {
    $('#appConfirmPaidAmount').val($('#appPayment').html())
})


$(document).on('click', '#btnAppConfirmWait', delay(function () {

    $('#ModalBodyAgencyPaymentAppDetails').block({
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

    $('.btn-app-transaction').prop('disabled', true);

    $.ajax('/Safe/General/AjaxTransactions/PaymentAppSetConfirmWaiting', {
        method: 'POST',
        data: {
            _token: token,
            id: detailsID,
        }
    }).done(function (response) {

        if (response.status == 1) {
            ToastMessage('success', response.message, 'Hata!')
            tableAgencyPaymentApps.draw()
            getPaymentDetails(detailsID)
        } else if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!')
            return false;
        }


    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyAgencyPaymentAppDetails').unblock();
        $('.btn-app-transaction').prop('disabled', false)
    });

}, 900))


$(document).on('click', '#btnAppConfirmSuccess', delay(function () {

    $('#ModalBodyAgencyPaymentAppDetails').block({
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

    $('.btn-app-transaction').prop('disabled', true);


    $.ajax('/Safe/General/AjaxTransactions/PaymentAppSetConfirmSuccess', {
        method: 'POST',
        data: {
            _token: token,
            id: detailsID,
            paid: $('#appConfirmPaidAmount').val(),
        }
    }).done(function (response) {

        if (response.status == 1) {
            ToastMessage('success', response.message, 'Hata!')
            tableAgencyPaymentApps.draw()
            getPaymentDetails(detailsID)
        } else if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!')
            return false;
        }


    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyAgencyPaymentAppDetails').unblock();
        $('.btn-app-transaction').prop('disabled', false)
    });

}, 900))

$(document).on('click', '#btnAppConfirmReject', delay(function () {

    $('#ModalBodyAgencyPaymentAppDetails').block({
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

    $('.btn-app-transaction').prop('disabled', true);


    $.ajax('/Safe/General/AjaxTransactions/PaymentAppSetConfirmReject', {
        method: 'POST',
        data: {
            _token: token,
            id: detailsID,
            reject_reason: $('textarea#appRejectReason').val(),
        }
    }).done(function (response) {

        if (response.status == 1) {
            ToastMessage('success', response.message, 'Hata!')
            tableAgencyPaymentApps.draw()
            getPaymentDetails(detailsID)
        } else if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!')
            return false;
        }


    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyAgencyPaymentAppDetails').unblock();
        $('.btn-app-transaction').prop('disabled', false)
    });

}, 900))

function getAppSummery() {
    $('#AgencyPaymentAppRow').block({
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


    $.ajax('/Safe/General/AjaxTransactions/GetAgencyPaymentAppsSummery', {
        method: 'POST',
        data: {
            _token: token,
        }
    }).done(function (response) {

        if (response.status == 1) {
            response = response.data
            $('span#WaitingApp').html(response.waiting)
            $('span#SuccessApp').html(response.success)
            $('span#RejectApp').html(response.reject)
            $('span#AllApp').html(response.all)

        } else if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!')
            return false;
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#AgencyPaymentAppRow').unblock();
    });
}













