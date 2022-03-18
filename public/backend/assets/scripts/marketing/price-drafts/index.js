var edit_additional_service_id = 0, edit_desi_interval = 0, edit_file = 0;
let oTablePriceDrafts, priceDraftDetailsID = null;
// # Additional Service Transasction
$(document).ready(function () {
    oTablePriceDrafts = $('.TablePriceDrafts').DataTable({
        pageLength: 25,
        lengthMenu: dtLengthMenu,
        order: [
            10, 'desc'
        ],
        language: dtLanguage,
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">f>rtip',
        buttons: [
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                attr: {
                    class: 'btn btn-success'
                },
            },
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                },
                attr: {
                    class: 'btn btn-info'
                },
            },
            {
                text: 'Yeni Taslak',
                action: function (e, dt, node, config) {
                    $('#ModalAddPriceDraft').modal()
                },
                attr: {
                    class: 'btn btn-primary'
                },
            }
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: '/Marketing/GetPriceDrafts',
            data: function (d) {

            },
            error: function (xhr, error, code) {
                if (code == "Too Many Requests") {
                    ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                }
            }
        },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'agency_permission', name: 'agency_permission'},
            {data: 'file', name: 'file'},
            {data: 'mi', name: 'mi'},
            {data: 'd_1_5', name: 'd_1_5'},
            {data: 'd_6_10', name: 'd_6_10'},
            {data: 'd_11_15', name: 'd_11_15'},
            {data: 'd_16_20', name: 'd_16_20'},
            {data: 'd_21_25', name: 'd_21_25'},
            {data: 'd_26_30', name: 'd_26_30'},
            {data: 'amount_of_increase', name: 'amount_of_increase'},
            {data: 'created_at', name: 'created_at'},
            {data: 'edit', name: 'edit'},
            {data: 'delete', name: 'delete'}
        ],
        scrollX: true,
        scrollY: "500px"
    });
});

$('#btnNewAdditionalService').click(function () {
    $('#modalNewAdditionalService').modal();
});

$(document).on('click', '#btnInsertPriceDraft', function () {
    $('#ModalBodyAddPriceDraft').block(whiteAnimation);
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/Marketing/PriceDraft/', {
        method: 'POST',
        data: {
            _token: token,
            DraftName: $('#insertDraftName').val(),
            FilePrice: $('#insertFilePrice').val(),
            MiPrice: $('#insertMiPrice').val(),
            Desi1_5: $('#insertDesi1_5').val(),
            Desi6_10: $('#insertDesi6_10').val(),
            Desi11_15: $('#insertDesi11_15').val(),
            Desi16_20: $('#insertDesi16_20').val(),
            Desi21_25: $('#insertDesi21_25').val(),
            Desi26_30: $('#insertDesi26_30').val(),
            AmountOfIncrease: $('#insertAmountOfIncrease').val(),
            AgencyPermission: $('#insertAgencyPermission').val(),
        }
    }).done(function (response) {

        if (response.status == 1) {

            ToastMessage('success', response.message, 'İşlem Başarılı!');

            oTablePriceDrafts.draw()
            $('#ModalAddPriceDraft').modal('hide')
            $('.input-price-draft').val('')

        } else if (response.status == -1) {
            ToastMessage('error', response.message, 'İşlem Başarısız!');
        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status, JSON.parse(jqXHR.responseText));
    }).always(function () {
        $('#ModalBodyAddPriceDraft').unblock();
    });
})

$(document).on('click', '.detail-price-draft', function () {

    priceDraftDetailsID = $(this).prop('id')
    $('#ModalEditPriceDraft').modal()

    $('#ModalBodyEditPriceDraft').block(whiteAnimation);
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/Marketing/PriceDraft/' + priceDraftDetailsID, {
        method: 'GET'
    }).done(function (response) {

        if (response.status == 1) {

            data = response.data
            $('#editDraftName').val(data.name)
            $('#editFilePrice').val(data.file)
            $('#editMiPrice').val(data.mi)
            $('#editDesi1_5').val(data.d_1_5)
            $('#editDesi6_10').val(data.d_6_10)
            $('#editDesi11_15').val(data.d_11_15)
            $('#editDesi16_20').val(data.d_16_20)
            $('#editDesi21_25').val(data.d_21_25)
            $('#editDesi26_30').val(data.d_26_30)
            $('#editAmountOfIncrease').val(data.amount_of_increase)
            $('#editAgencyPermission').val(data.agency_permission)

        } else if (response.status == -1) {
            ToastMessage('error', response.message, 'İşlem Başarısız!');
        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        }

    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status, JSON.parse(jqXHR.responseText));
    }).always(function () {
        $('#ModalBodyEditPriceDraft').unblock();
    });
})

$(document).on('click', '#btnUpdatePriceDraft', function () {
    $('#ModalBodyEditPriceDraft').block(whiteAnimation);
    $('.blockUI.blockMsg.blockElement').css('width', '100%');
    $('.blockUI.blockMsg.blockElement').css('border', '0px');
    $('.blockUI.blockMsg.blockElement').css('background-color', '');

    $.ajax('/Marketing/PriceDraft/' + priceDraftDetailsID, {
        method: 'PUT',
        data: {
            _token: token,
            DraftName: $('#editDraftName').val(),
            FilePrice: $('#editFilePrice').val(),
            MiPrice: $('#editMiPrice').val(),
            Desi1_5: $('#editDesi1_5').val(),
            Desi6_10: $('#editDesi6_10').val(),
            Desi11_15: $('#editDesi11_15').val(),
            Desi16_20: $('#editDesi16_20').val(),
            Desi21_25: $('#editDesi21_25').val(),
            Desi26_30: $('#editDesi26_30').val(),
            AmountOfIncrease: $('#editAmountOfIncrease').val(),
            AgencyPermission: $('#editAgencyPermission').val(),
        }
    }).done(function (response) {
        if (response.status == 1) {
            ToastMessage('success', response.message, 'İşlem Başarılı!');
            oTablePriceDrafts.draw()
            $('#ModalEditPriceDraft').modal('hide')
            $('.input-price-draft').val('')
        } else if (response.status == -1) {
            ToastMessage('error', response.message, 'İşlem Başarısız!');
        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
        }
    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status, JSON.parse(jqXHR.responseText));
    }).always(function () {
        $('#ModalBodyEditPriceDraft').unblock();
    });
})
