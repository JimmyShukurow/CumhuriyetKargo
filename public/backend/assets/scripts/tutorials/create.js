$('#arrivalBranchType').change(function () {
    if ($(this).val() == 'Acente') {
        $('#divArrivalTc').hide()
        $('#divArrivalAgency').show()
    } else if ($(this).val() == 'Aktarma') {
        $('#divArrivalTc').show()
        $('#divArrivalAgency').hide()
    }
})

$('#plaque').keyup(function () {
    $(this).val($(this).val().toLocaleUpperCase())
})

$('#AddRoutes').click(function () {
    $('#ModalAddRoutes').modal()
})

$(document).on('change', '#routeBranchType', function () {

    if ($(this).val() == 'Acente') {
        $('#divRouteTc').hide()
        $('#divRouteAgency').show()
    } else if ($(this).val() == 'Aktarma') {
        $('#divRouteTc').show()
        $('#divRouteAgency').hide()
    }
})


$(document).ready(function () {
    $('.agency-select').select2();
})


$(document).on('click', '#btnAddRoute', function () {

    branchType = $('#routeBranchType').val()
    routeAgency = $('#routeAgency').val()
    routeTc = $('#routeTc').val()
    selectedBranch = null

    if (branchType == '') {
        ToastMessage('error', 'Birim tipi seçiniz!', 'Hata!')
        return false
    }

    if (branchType != 'Acente' && branchType != 'Aktarma') {
        ToastMessage('error', 'Birim tipi geçersiz!', 'Hata!')
        return false
    }

    if (branchType == 'Acente' && routeAgency == '') {
        ToastMessage('error', 'Lütfen acente seçiniz!', 'Hata!')
        return false
    }

    if (branchType == 'Aktarma' && routeTc == '') {
        ToastMessage('error', 'Lütfen aktarma seçiniz!', 'Hata!')
        return false
    }

    branchID = null

    if (branchType == 'Aktarma') {
        selectedBranch = $('#routeTc option[value="' + routeTc + '"]').text()
        branchID = routeTc
    } else if (branchType == 'Acente') {
        selectedBranch = $('#routeAgency option[value="' + routeAgency + '"]').text()
        branchID = routeAgency
    }
    addRouteList(branchType, selectedBranch, branchID)
})

let routeRowIndex = 0
let arrRoutes = []

function addRouteList(branchType, branch, branchID) {
    if (arrRoutes.indexOf(branch) != -1) {
        ToastMessage('error', branch + "'yi daha önce eklediğiniz için tekrar ekleyemezsiniz!", 'Hata!')
        return false;
    }

    if (routeRowIndex == 0)
        $('#tbodyExpeditionRoutes').html('')

    routeRowIndex++
    $('#tbodyExpeditionRoutes').append('<tr id="rowRoute-' + routeRowIndex + '" justIndex="' + routeRowIndex + '" branch-type="' + branchType + '" branch-id="' + branchID + '" class="row-of-routes">\n' +
        '                                    <td width="120">' + routeRowIndex + '</td>\n' +
        '                                    <td>' + branch + '</td>\n' +
        '                                    <td width="150">\n' +
        '                                        <button class="btn btn-danger btn-sm btn-delete-route" id="' + routeRowIndex + '">Sil</button>\n' +
        '                                    </td>\n' +
        '                                </tr>')
    arrRoutes.push(branch)
    ToastMessage('success', 'İşlem başarılı, güzergah eklendi!')
}


$(document).on('click', '.btn-delete-route', function () {

    destroyID = $(this).prop('id')
    branch = $('#' + destroyID + ' td:nth-child(2)').text()
    arrRoutes.splice(arrRoutes.indexOf(branch), 1)

    $('#rowRoute-' + destroyID).remove()

    ToastMessage('success', '', 'Güzergah Silindi')
    goAroundRoutes()
    routeRowIndex--
    if (routeRowIndex == 0)
        $('#tbodyExpeditionRoutes').html('' +
            '                            <tr>\n' +
            '                                <td colspan="3" class="text-danger text-center"><b>Ara durak yok.</b></td>\n' +
            '                            </tr>' +
            '')
})


function goAroundRoutes() {
    fakeRowIndex = 0
    $('.row-of-routes').each(function () {
        id = $(this).prop('id')
        index = $(this).attr('justIndex')
        fakeRowIndex++
        $('#' + id + ' td:nth-child(1)').html(fakeRowIndex)
    });
}


$('#DeleteAllRoutes').click(function () {
    swal({
        title: "Silme İşlemini Onaylayın!",
        text: "Güzergahların tamamı silinecek, Emin misiniz?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $('#tbodyExpeditionRoutes').html('' +
                    '                            <tr>\n' +
                    '                                <td colspan="3" class="text-danger text-center"><b>Ara durak yok.</b></td>\n' +
                    '                            </tr>' +
                    '')
                arrRoutes = []
                ToastMessage('success', '', 'Güzergahlar Silindi')
                routeRowIndex = 0
            } else
                ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi')
        })
})

$('#btnCreateTutorial').click(function () {

    // if ($('#plaque').val() == '') {
    //     ToastMessage('error', 'Plaka alanı zorunludur!', 'Hata!')
    //     return false
    // }
    //
    // if ($('#arrivalBranchType').val() == '') {
    //     ToastMessage('error', 'Varış birim tipi alanı zorunludur!', 'Hata!')
    //     return false
    // }
    //
    // if (($('#arrivalBranchType').val() == 'Aktarma' && $('#arrivalTc').val() == '') || ($('#arrivalBranchType').val() == 'Acente' && $('#arrivalAgency').val() == '')) {
    //     ToastMessage('error', 'Varış birimi alanı zorunludur!', 'Hata!')
    //     return false
    // }

    $('.app-main__inner').block({
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

    // let branchCode = null
    // if ($('#arrivalBranchType').val() == 'Aktarma')
    //     branchCode = $('#arrivalTc').val()
    // else if ($('#arrivalBranchType').val() == 'Acente')
    //     branchCode = $('#arrivalAgency').val()

    $.ajax('/Tutorials/tutorial', {
        method: 'POST',
        data: {
            _token: token,
            name: $('#video_name').val(),
            category: $('#category').val(),
            embedded_link: $('#embedded_link').val(),
            tutor: $('#tutor').val(),
            description: $('#description').val(),
        }
    }).done(function (response) {

        if (response.status == 1) {
            ToastMessage('success', response.message, 'İşlem Başarılı!')

            localStorage.setItem('expedition-success', true);
            setTimeout(function () {
                location.href = "/Expedition/OutGoing"
            }, 100)
        } else if (response.status == -1) {
            ToastMessage('error', response.message, 'Hata!')
            $('.app-main__inner').unblock();
            return false
        } else if (response.status == 0) {
            $.each(response.errors, function (index, value) {
                ToastMessage('error', value, 'Hata!')
            });
            $('.app-main__inner').unblock();
        }
    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status);
        $('.app-main__inner').unblock();
    }).always(function () {
    });
})


function getExpeditionRoutes() {

    let arrayRealRoutes = []
    $('.row-of-routes').each(function () {
        arrayRealRoutes.push([$(this).attr('branch-type'), $(this).attr('branch-id')])
    })

    return arrayRealRoutes
}


