$('.btn-confirm-report').click(function () {

    let confirmType = $(this).attr('confirm');

    let ReportsDataTable = $('#OfficialReportsTable').DataTable();
    let selectedItems = ReportsDataTable.rows({selected: true}).data();

    if (selectedItems.length == 0) {
        ToastMessage('error', 'Lütfen işlem yapılacak tutanakları seçin!', 'Hata!')
        return false;
    }

    console.log(selectedItems);

    let ids = "", serialNos = "";
    $.each(selectedItems, function (key, val) {
        ids += val['id'] + ",";
        serialNos += val['report_serial_no_temp'] + ", ";
    });

    let swalText = "";
    if (confirmType == 'yes')
        swalText = serialNos + " seri numaralı raporlar onaylanacak devam edilsin mi?"
    else
        swalText = serialNos + " seri numaralı raporlar onaylanmayacak devam edilsin mi?"

    swal({
        title: "İşlemini Onaylayın!",
        text: swalText,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {

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

                $.ajax('/OfficialReport/EnterConfirmResult', {
                    method: 'POST',
                    data: {
                        _token: token,
                        ids: ids,
                        result: confirmType,
                    }
                }).done(function (response) {

                    if (response.status == -1) {
                        ToastMessage('error', response.message, 'Hata!');
                        return false;
                    } else if (response.status == 1) {

                        ToastMessage('success', 'İşlemler başarılı bir şekilde gerçekleşti!', 'İşlem Başarılı!');
                        oTable.draw();

                    } else if (response.status == 0) {
                        $.each(response.errors, function (index, value) {
                            ToastMessage('error', value, 'Hata!')
                        });
                    }

                }).error(function (jqXHR, response) {
                    ajaxError(jqXHR.status);
                }).always(function () {
                    $('.app-main__inner').unblock();
                });


            } else {
                ToastMessage('warning', 'İşlem iptal edilidi.', 'Bilgi');
            }
        });

});


















