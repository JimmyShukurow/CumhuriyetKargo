function fillCargo(tbodyId, cargo) {
    var mytbodyId = "#" + tbodyId;
    $.each(cargo, function (index, value) {
            $(mytbodyId).append(
                '<tr>' +
                '<td class="font-weight-bold">' + value.invoice_number + '</td>' +
                '<td class="font-weight-bold">' + value.sender_name + '</td>' +
                '<td class="font-weight-bold">' + value.receiver_name + '</td>' +
                '<td class="font-weight-bold text-success">' + value.status + '</td>' +
                '<td class="text-primary">' + value.cargo_type + '</td>' +
                '<td class="font-weight-bold text-primary">' + value.total_price + '₺' + '</td>' +
                '</tr>'
            )
        }
    )
}

var detailsID;
$(document).on('click', '.user-detail', function () {
    ToastMessage('warning', '', 'Yükleniyor');
    detailsID = $(this).prop('id');
    getCustomerDetails(detailsID);
});

$(document).on('dblclick', '.customer-detail', function () {
    detailsID = $(this).prop('id');
    getCustomerDetails(detailsID);
});


$(document).on('click', '#deleteCustomer', function () {
    swal({
        title: "Silme İşlemini Onaylayın!",
        text: "Emin misiniz?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'DELETE',
                    url: '/Customers/Delete/' + detailsID,
                    data: {
                        _token: token,
                    },
                    success: function () {
                        $('#ModalCustomerDetails').modal('hide');
                        oTable.draw();
                        ToastMessage('success', 'Müşteri silindi!', 'İşlem başarılı!');
                    },
                    error: function (jqXHR, response) {
                        if (jqXHR.status == 403) {
                            ToastMessage('error', JSON.parse(jqXHR.responseText).message, 'İşlem başarısız');
                        }
                        ajaxError(jqXHR.status);
                    }
                }).always(function () {
                    $('#ModalBodyCustomerDetails').unblock();
                });

            }


        });
});
var array = new Array();

function getCustomerDetails(user) {

    $('#ModalCustomerDetails').modal();

    $('#ModalBodyCustomerDetails').block({
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


    $.ajax('/Customers/GetCustomerInfo', {
        method: 'POST',
        data: {
            _token: token,
            user: user
        },
        cache: false
    }).done(function (response) {


        if (response.current.created_at_time > 86400)
            $('#deleteButton').css("display", "none");
        else
            $('#deleteButton').css("display", "block");


        let currentStatus, currentConfirmed;
        let creatorDisplayName = '<span class="text-primary font-weight-bold">(' + response.current.creator_display_name + ')</span>';

        if (response.current.status == "1")
            currentStatus = '<span class="text-success">(Aktif Hesap)</span>';
        else
            currentStatus = '<span class="text-danger">(Pasif Hesap)</span>';

        if (response.current.confirmed == "1") {
            currentConfirmed = '<span class="text-success font-weight-bold">Onaylandı</span>';
            $('#divConfirmCurrent').hide();
        } else {
            currentConfirmed = '<span class="text-danger font-weight-bold">Onay Bekliyor</span>';
            $('#divConfirmCurrent').show();
        }

        let city = response.current.city + "/",
            district = response.current.district + " ",
            neighborhood = response.current.neighborhood + " ",
            street = response.current.street != '' && response.current.street != null ? response.current.street + " CAD. " : "",
            street2 = response.current.street2 != '' && response.current.street2 != null ? response.current.street2 + " SK. " : "",
            buildingNo = "NO:" + response.current.building_no + " ",
            door = "D:" + response.current.door_no + " ",
            floor = "KAT:" + response.current.floor + " ",
            addressNote = "(" + response.current.address_note + ")";

        let fullAddress = neighborhood + street + street2 + buildingNo + floor + door + addressNote;


        $('#customerName').html(response.current.name)
        $('#customerType').html(response.current.current_code);
        $('#agencyCityDistrict').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
        $('#titleCustomerName').html(response.current.name + ' - ÖZET ' + currentStatus)

        $('#currentCategory').html(response.current.category)
        $('#modalCurrentCode').html(response.current.current_code)
        $('#nameSurnameCompany').html(response.current.name)
        $('#currentAgency').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
        $('#taxOffice').html(response.current.tax_administration)
        $('#tcknVkn').html((response.current.tckn ?? '') + "" + (response.current.vkn ?? ''))
        $('#phone').html(response.current.phone)
        $('#cityDistrict').html(response.current.city + "/" + response.current.district)
        $('#address').html(fullAddress)
        $('#gsm').html(response.current.gsm)
        $('#gsm2').html(response.current.gsm2)
        $('#phone2').html(response.current.phone2)
        $('#email').html(response.current.email)
        $('#website').html(response.current.website)
        $('#regDate').html(response.current.created_at)
        $('#dispatchCityDistrict').html((response.current.dispatch_city ?? '') + "/" + (response.current.dispatch_district ?? ''))
        $('#dispatchAddress').html(response.current.dispatch_adress);
        $('#iban').html(response.current.iban)
        $('#bankOwner').html(response.current.bank_owner_name)
        $('#contractStartDate').html(response.current.contract_start_date)
        $('#contractEndDate').html(response.current.contract_end_date)
        $('#reference').html(response.current.reference)
        $('#currentCreatorUser').html(response.current.creator_user_name + " " + creatorDisplayName)
        $('#mbStatus').html(response.current.mb_status == '0' ? '<b class="text-danger">Hayır</b>' : '<b class="text-success">Evet</b>')


        if (response.current.category == 'Anlaşmalı') {
            $('td#currentFilePrice').html(response.price.file + "₺");
            $('td#currentMiPrice').html(response.price.mi + "₺");
            $('td#current1_5Desi').html(response.price.d_1_5 + "₺");
            $('td#current6_10Desi').html(response.price.d_6_10 + "₺");
            $('td#current11_15Desi').html(response.price.d_11_15 + "₺");
            $('td#current16_20Desi').html(response.price.d_16_20 + "₺");
            $('td#current21_25Desi').html(response.price.d_21_25 + "₺");
            $('td#current26_30Desi').html(response.price.d_26_30 + "₺");
            $('#currentAmountOfIncrease').html(response.price.amount_of_increase + "₺");
            $('#currentCollectPrice').html(response.price.collect_price + "₺");
            $('#collectAmountOfIncrease').html("%" + response.price.collect_amount_of_increase);
        } else {
            $('td#currentFilePrice').html('');
            $('td#currentMiPrice').html('');
            $('td#current1_5Desi').html('');
            $('td#current6_10Desi').html('');
            $('td#current11_15Desi').html('');
            $('td#current16_20Desi').html('');
            $('td#current21_25Desi').html('');
            $('td#current26_30Desi').html('');
            $('#currentAmountOfIncrease').html('');
            $('#currentCollectPrice').html('');
            $('#collectAmountOfIncrease').html('');
        }

        $('#currentConfirmed').html(currentConfirmed);
        $('#PrintCurrentContract').attr('href', '/Marketing/SenderCurrents/CurrentContract/' + response.current.current_code);


    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
    }).always(function () {
        $('#ModalBodyCustomerDetails').unblock();
    });
}


$(document).on('click', '.properties-log', function () {
    var properties = $(this).attr('properties');
    var log_id = $(this).attr('id');
    var array_no = $(this).attr('array-no');
    $('#json-renderer').text(JSON.parse(array[array_no]));
    $('#json-renderer').jsonViewer(JSON.parse(array[array_no]), {
        collapsed: false,
        rootCollapsable: false,
        withQuotes: false,
        withLinks: true
    });
    $('#ModalLogProperties').modal();
});

