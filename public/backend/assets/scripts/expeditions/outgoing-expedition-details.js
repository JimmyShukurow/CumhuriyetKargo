$(document).on('dblclick', '.expedition-details', function() {
    let id = $(this).prop('id')
    detailsID = id;
    expeditionInfo(id);
});

function expeditionInfo(expedition) {

    $('#ModalExpeditionDetails').modal();

    $('#ModalExpeditionDetails').block({
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

    let url = '/Expedition/AjaxTransactions/GetExpeditionInfo';


    $.ajax(url, {
        method: 'POST',
        data: {
            _token: token,
            id: expedition
        },
        cache: false
    }).done(function(response) {

        if (response.status == 0) {
            setTimeout(function() {
                ToastMessage('error', response.message, 'Hata!');
                $('#ModalExpeditionDetails').modal('hide');
                $('#CargoesTable').DataTable().ajax.reload();
                return false;
            }, 350);
        } else if (response.status == 1) {
            $('#titleTrackingNo').text(response.expedition.serial_no);
            $('#titleExpeditionCarPlaque').text(response.expedition.car.plaka);
            $('#expeditionSerialNo').text(response.expedition.serial_no);
            $('#expeditionCarPlaque').text(response.expedition.car.plaka);

            var creator = response.expedition.user.name_surname + '(' + response.expedition.user.display_name + ')';

            $('#expeditionCreator').text(creator);
            $('#expeditionBranch').text(response.expedition.branch);
            $('#expeditionDescription').text(response.expedition.description);

            var expedtionDone = response.expedition.done == 0 ? ' <b class="text-success">Devam Ediyor</b> ' : ' <b class="text-success">Bitti</b>';

            $('#expeditionDone').html(expedtionDone);

            $('#expeditionCreatedAt').text(response.expedition.created_at);

            $('#expeditionDepartureBranch').text(response.expedition.departure_branch);
            $('.expoRoutes').remove();

            var length = Object.keys(response.expedition.betweens).length
            var expeditionRoutes = '';

            for (let i = 0; i <length ; i++) {
                expeditionRoutes +=
                    '<tr class="expoRoutes"> <td class="static">'
                    + (i+1)
                    + '. Güzergah'
                    + ' </td> <td class="customer-detail unselectable font-weight-bold text-dark">'
                    + response.expedition.betweens[i]
                    + '</td> </tr>';
            }
            $('#ExpeditionBranchs>tbody>tr:first-child').after(expeditionRoutes);
            $('#expeditionArrivalBranch').text(response.expedition.arrival_branch);

            let cargoes = response.expedition.cargoes;
            var cargoeslength = Object.keys(cargoes).length
            let cargohtml = '';
            for (let i = 0; i < cargoeslength; i++) {
                let arrival = cargoes[i].cargo.arrival_city + '/' + cargoes[i].cargo.arrival_district;
                let loader = cargoes[i].user.name_surname + '(' + cargoes[i].user.role.display_name + ')';
                cargohtml = cargohtml
                    + expeditionCargoes(
                        cargoes[i].cargo.invoice_number,
                        cargoes[i].cargo.number_of_pieces,
                        cargoes[i].cargo.cargo_type,
                        cargoes[i].cargo.receiver_name,
                        cargoes[i].cargo.sender_name,
                        arrival,
                        cargoes[i].cargo.status,
                        loader,
                        cargoes[i].created_at,
                    );
            }
            if (cargoeslength > 0) {
                $('#tbodyExpedtionCargoes').html(cargohtml);
            }else {
                $('#tbodyExpedtionCargoes').html('<td colspan="9" class="text-center">Burda hiç veri yok.</td>');
            }
             let allCargoes = response.expedition.allCargoes;
            var allCargoeslength = Object.keys(allCargoes).length
            let allCargohtml = '';
            for (let i = 0; i < allCargoeslength; i++) {
                let arrival = allCargoes[i].cargo.arrival_city + '/' + allCargoes[i].cargo.arrival_district;
                let loader = allCargoes[i].user.name_surname + '(' + allCargoes[i].user.role.display_name + ')';
                let unloader = allCargoes[i].unloaded_user == null ? ' '  : allCargoes[i].unloaded_user.name_surname + '(' + allCargoes[i].unloaded_user.role.display_name + ')';
                let deleter = allCargoes[i].deleted_user == null ? ' '  : allCargoes[i].deleted_user.name_surname + '(' + allCargoes[i].deleted_user.role.display_name + ')';
                if (allCargoes[i].deleted_at != null) {
                    allCargohtml = allCargohtml
                        + expeditionCargoesDeleted(
                            allCargoes[i].cargo.invoice_number,
                            allCargoes[i].cargo.number_of_pieces,
                            allCargoes[i].cargo.cargo_type,
                            allCargoes[i].cargo.receiver_name,
                            allCargoes[i].cargo.sender_name,
                            arrival,
                            allCargoes[i].cargo.status,
                            loader,
                            allCargoes[i].created_at,
                            unloader,
                            allCargoes[i].unloading_at == null ? ' ' : allCargoes[i].unloading_at,
                            deleter,
                            allCargoes[i].deleted_at == null ? ' ' : allCargoes[i].deleted_at,
                        );
                }else if (allCargoes[i].unloading_at != null) {
                    allCargohtml = allCargohtml
                        + expeditionCargoesUnloaded(
                            allCargoes[i].cargo.invoice_number,
                            allCargoes[i].cargo.number_of_pieces,
                            allCargoes[i].cargo.cargo_type,
                            allCargoes[i].cargo.receiver_name,
                            allCargoes[i].cargo.sender_name,
                            arrival,
                            allCargoes[i].cargo.status,
                            loader,
                            allCargoes[i].created_at,
                            unloader,
                            allCargoes[i].unloading_at == null ? ' ' : allCargoes[i].unloading_at,
                            deleter,
                            allCargoes[i].deleted_at == null ? ' ' : allCargoes[i].deleted_at,
                        );
                } else {
                    allCargohtml = allCargohtml
                        + expeditionAllCargoes(
                            allCargoes[i].cargo.invoice_number,
                            allCargoes[i].cargo.number_of_pieces,
                            allCargoes[i].cargo.cargo_type,
                            allCargoes[i].cargo.receiver_name,
                            allCargoes[i].cargo.sender_name,
                            arrival,
                            allCargoes[i].cargo.status,
                            loader,
                            allCargoes[i].created_at,
                            unloader,
                            allCargoes[i].unloading_at == null ? ' ' : allCargoes[i].unloading_at,
                            deleter,
                            allCargoes[i].deleted_at == null ? ' ' : allCargoes[i].deleted_at,
                        );
                }

            }
            if (cargoeslength > 0) {
                $('#tbodyExpedtionCargoesSecondary').html(allCargohtml);
            }else {
                $('#tbodyExpedtionCargoesSecondary').html('<td colspan="9" class="text-center">Burda hiç veri yok.</td>');
            }


        }

        $('#ModalExpeditionDetails').unblock();
        return false;
    }).error(function(jqXHR, exception) {
        ajaxError(jqXHR.status);
    }).always(function() {
        $('#ModalExpeditionDetails').unblock();
    });

    $('#ModalAgencyDetail').modal();
}
function expeditionCargoes(invoice, count, type, reciever, sender, departure, status, loader, loadedDate){
    let cargoe = '<tr>' + '<td class="text-center">' + invoice + '</td>'
        + '<td class="text-center">' + count + '</td>'
        + '<td class="text-center">' + type + '</td>'
        + '<td class="text-center">' + reciever + '</td>'
        + '<td class="text-center">' + sender + '</td>'
        + '<td class="text-center">' + departure + '</td>'
        + '<td class="text-center">' + status + '</td>'
        + '<td class="text-center">' + loader + '</td>'
        + '<td class="text-center">' + loadedDate + '</td>'
        + '</tr>';
    return cargoe;
}
function expeditionAllCargoes(invoice, count, type, reciever, sender, departure, status, loader,loadedDate, unloadedUser, unloaded_at, deletedUser, deleted_at){
    let cargoe = '<tr>' + '<td class="text-center">' + invoice + '</td>'
        + '<td class="text-center">' + count + '</td>'
        + '<td class="text-center">' + type + '</td>'
        + '<td class="text-center">' + reciever + '</td>'
        + '<td class="text-center">' + sender + '</td>'
        + '<td class="text-center">' + departure + '</td>'
        + '<td class="text-center">' + status + '</td>'
        + '<td class="text-center">' + loader + '</td>'
        + '<td class="text-center">' + loadedDate + '</td>'
        + '<td class="text-center">' + unloadedUser + '</td>'
        + '<td class="text-center">' + unloaded_at + '</td>'
        + '<td class="text-center">' + deletedUser + '</td>'
        + '<td class="text-center">' + deleted_at + '</td>'
        + '</tr>';
    return cargoe;
}

function expeditionCargoesUnloaded(invoice, count, type, reciever, sender, departure, status, loader,loadedDate,  unloadedUser, unloaded_at, deletedUser, deleted_at){
    let cargoe = '<tr class="bg-info">' + '<td class="text-center">' + invoice + '</td>'
        + '<td class="text-center">' + count + '</td>'
        + '<td class="text-center">' + type + '</td>'
        + '<td class="text-center">' + reciever + '</td>'
        + '<td class="text-center">' + sender + '</td>'
        + '<td class="text-center">' + departure + '</td>'
        + '<td class="text-center">' + status + '</td>'
        + '<td class="text-center">' + loader + '</td>'
        + '<td class="text-center">' + loadedDate + '</td>'
        + '<td class="text-center">' + unloadedUser + '</td>'
        + '<td class="text-center">' + unloaded_at + '</td>'
        + '<td class="text-center">' + deletedUser + '</td>'
        + '<td class="text-center">' + deleted_at + '</td>'
        + '</tr>';
    return cargoe;
}
function expeditionCargoesDeleted(invoice, count, type, reciever, sender, departure, status, loader,loadedDate,  unloadedUser, unloaded_at, deletedUser, deleted_at){
    let cargoe = '<tr class="bg-danger">' + '<td class="text-center">' + invoice + '</td>'
        + '<td class="text-center">' + count + '</td>'
        + '<td class="text-center">' + type + '</td>'
        + '<td class="text-center">' + reciever + '</td>'
        + '<td class="text-center">' + sender + '</td>'
        + '<td class="text-center">' + departure + '</td>'
        + '<td class="text-center">' + status + '</td>'
        + '<td class="text-center">' + loader + '</td>'
        + '<td class="text-center">' + loadedDate + '</td>'
        + '<td class="text-center">' + unloadedUser + '</td>'
        + '<td class="text-center">' + unloaded_at + '</td>'
        + '<td class="text-center">' + deletedUser + '</td>'
        + '<td class="text-center">' + deleted_at + '</td>'
        + '</tr>';
    return cargoe;
}
