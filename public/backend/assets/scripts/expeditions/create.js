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

    if (branchType == 'Aktarma')
        selectedBranch = $('#routeTc option[value="' + routeTc + '"]').text()
    else if (branchType == 'Acente')
        selectedBranch = $('#routeAgency option[value="' + routeAgency + '"]').text()

    addRouteList(branchType, selectedBranch)
})

let routeRowIndex = 0
let arrRoutes = []

function addRouteList(branchType, branch) {
    if (arrRoutes.indexOf(branch) != -1) {
        ToastMessage('error', branch + "'yi daha önce eklediğiniz için tekrar ekleyemezsiniz!", 'Hata!')
        return false;
    }

    if (routeRowIndex == 0)
        $('#tbodyExpeditionRoutes').html('')

    routeRowIndex++
    $('#tbodyExpeditionRoutes').append('<tr id="rowRoute-' + routeRowIndex + '" justIndex="' + routeRowIndex + '" class="row-of-routes">\n' +
        '                                    <td width="120">' + routeRowIndex + '</td>\n' +
        '                                    <td>' + branch + '</td>\n' +
        '                                    <td width="150">\n' +
        '                                        <button class="btn btn-danger btn-sm btn-delete-route" id="' + routeRowIndex + '">Sil</button>\n' +
        '                                    </td>\n' +
        '                                </tr>')

    arrRoutes.push(branch)
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






