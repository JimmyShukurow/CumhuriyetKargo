let select_role = "";

function getSubModulesOfRole(user_role) {

    $.post('/Module/GetSubModuleOfRole', {
        role: user_role,
        _token: token
    }, function (response) {
        var counter = 0;

        $('#tbodyRolePerm').html('');

        if (response.length == 0) {
            $('#TableRolePermissions').append(
                '<tr> <td class="text-center" colspan="6"> <b class="text-success">Data Yok</b> </td> </tr>'
            );
        } else {

            $.each(response, function (key, value) {

                counter++;

                $('#TableRolePermissions').append(
                    '<tr id="role_permission-item-' + (value['id']) + '">' +
                    '<td>' + (counter) + '</td>' +
                    '<td>' + (value['display_name']) + '</td>' +
                    '<td>' + (value['sub_name']) + '</td>' +

                    '<td>' +
                    '<a href="javascript:void(0)"' +
                    'data-title="' + (value['sub_name']) + '"' +
                    'data-toggle="popover-custom-bg"' +
                    'data-bg-class="text-light bg-premium-dark"' +
                    'title="' + (value['description']) + '">' + (value['description'].substring(0, 35)) +
                    '..' +
                    '</a>' +
                    '</td>' +

                    '<td  width="10"> <i class="pe-2x ' + (value['module_ico']) + '"></i>' + '</td>' +
                    '<td>' + (value['module_name']) + '</td>' +

                    '<td width="10" class="text-center">' +

                    '<button from="role_permission" id="' + (value['id']) + '" class="btn btn-sm btn-danger trash">' +
                    'Alt Modülü Kaldır' +
                    '</button>' +

                    '</td>' +

                    +'</tr>'
                )
                ;


            });
        }
    }, 'json');

}

$('#select-roles').change(function () {
    getSubModulesOfRole($(this).val());
    select_role = $('option:selected', this).attr('role');
});

$('#BtnGiveRolePermission').click(function () {

    let role = $('#select-roles').val();

    if (role == '') {
        ToastMessage('error', 'Önce yetki seçin', 'Hata!');
    } else {

        $.post('/Module/GetNonPermissionsOfRole', {
            role: role,
            _token: token
        }, function (response) {

            console.log(response);

            var counter = 0;

            $('#GiveRolePermissionsTbody').html('');

            if (response.length == 0) {
                $('#GiveRolePermissionsTable').append(
                    '<tr> <td class="text-center" colspan="6"> <b class="text-success">Data Yok</b> </td> </tr>'
                );
            } else {


                $.each(response, function (key, value) {

                    counter++;

                    $('#GiveRolePermissionsTable').append(
                        '<tr class="nikooooo' + (value['id']) + '">' +
                        '<td width="10"> <input style="width: 20px"  for="' + (role) + '" nikoid="' + (value['id']) + '" type="checkbox" ' +
                        (value['role_gived'] != '0' ? 'checked disabled' : '')
                        + ' class="form-control check-give-perm ck-' + (value['id']) + '"></td>' +
                        '<td>' + (value['sub_name']) + '</td>' +
                        '<td>' + (value['description']) + '</td>' +
                        '<td  width="10"> <i class="pe-2x ' + (value['ico']) + '"></i>' + '</td>' +
                        '<td>' + (value['module_name']) + '</td>' +
                        +'</tr>'
                    );


                });
            }
        }, 'json');

        $('#ModalGiveRolePermission').modal();
        $('#ModalGiveRolePermissionLabel').html(select_role);
    }
});

$(document).on('click', '.check-give-perm', function () {
    var from_role_id = $(this).attr('for');
    var given_sub_module_id = $(this).attr('nikoid');
    $('.ck-' + given_sub_module_id).prop('disabled', true);

    $.post('/Module/AddSubModuleToRole', {
        _token: token,
        role_id: from_role_id,
        module_id: given_sub_module_id
    }, function (response) {
        if (response == 1) {
            ToastMessage('success', 'Modül yetkiye  tanımlandı.', 'İşlem Başarılı!');
            $(this).prop('disabled', true);
            $('.ck-' + given_sub_module_id).prop('disabled', true);
            getSubModulesOfRole(from_role_id);
        } else {
            $('.ck-' + given_sub_module_id).prop('disabled', false);
            ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin.', 'Hata!');
        }
    });

});


$(document).on('click', '.trash', function () {


    var from = $(this).attr("from");
    var object;


    if (from == "role_permission") {
        url = 'Module/DestroyModuleOfRole'
        object = "Yetkiye ait Alt Modül";
    } else if (from == "agency") {
        url = "Agencies/DestroyAgency";
        object = "Acente";
    } else if (from == "regional_directorates") {
        url = "RegionalDirectorates/DestroyRD";
        object = "Bölge müdürlüğü";
    } else if (from == "region-district") {
        url = "/RegionalDirectorates/DestroyRDDistrict";
        object = "Bölgeye bağlı ilçe";
    } else if (from == "transshipment_center") {
        url = "/RegionalDirectorates/DestroyRDDistrict";
        object = "Transfer merkezi";
    } else if (from == "tc-agency") {
        url = "/TransshipmentCenters/DestroyTCDistricts";
        object = "Transfer merkezine bağlı ilçe";
    } else if (from == "department-of-role") {
        url = "/Departments/DestroyRoleDepartment";
        object = "Departmana bağlı yetki";
    } else if (from == "user") {
        url = "/Users/Destroy";
        object = "Kullanıcı";
    } else if (from == "cargo_bag") {
        url = "/CargoBags/Agency/DeleteBag";
        object = "Torba & Çuval"
    } else if (from == "desi-list") {
        url = "/DesiListDelete";
        object = "Desi Aralığı"
    }

    var destroy_id = $(this).attr('id');
    swal({
        title: "Silme İşlemini Onaylayın!",
        text: "Emin misiniz? Bu işlem geri alınamaz!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        destroy_id: destroy_id,
                        _token: token
                    },
                    success: function (msg) {
                        if (msg == 1 || msg.status == 1) {
                            $('#' + from + '-item-' + destroy_id).remove();
                            ToastMessage('success', object + ' Silindi.',
                                'İşlem Başarılı!');
                        } else if (msg == 0) {
                            ToastMessage('error',
                                'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                                'İşlem Başarısız!');
                        } else if (msg.status == 0) {
                            ToastMessage('error',
                                msg.message, 'İşlem Başarısız!');
                        }

                    }
                });

            } else {
                ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi');
            }
        });
});


$('.trash').click(function () {

    var from = $(this).attr("from");
    var object;

    if (from == "role") {
        url = 'Module/DestroyRole'
        object = "Yetki";
    } else if (from == "mg") {
        url = 'Module/DestroyModuleGroup'
        object = "Modül Grubu";
    } else if (from == "module") {
        url = 'Module/DestroyModule'
        object = "Modül";
    } else if (from == "sub") {
        url = 'Module/DestroySubModule'
        object = "Alt Modül";
    } else if (from == "user") {
        url = "Users/Destroy";
        object = "Kullanıcı";
    } else if (from == "agency") {
        url = "Agencies/DestroyAgency";
        object = "Acente";
    } else if (from == "regional_directorates") {
        url = "RegionalDirectorates/DestroyRD";
        object = "Bölge müdürlüğü";
    }

    destroy_id = $(this).attr('id');
    swal({
        title: "Silme İşlemini Onaylayın!",
        text: "Emin misiniz? Bu işlem geri alınamaz!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        destroy_id: destroy_id,
                        _token: token
                    },
                    success: function (msg) {
                        if (msg) {
                            $('#' + from + '-item-' + destroy_id).remove();
                            ToastMessage('success', object + ' Silindi.',
                                'İşlem Başarılı!');
                        } else
                            ToastMessage('error',
                                'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                                'İşlem Başarısız!');
                    }
                });

            } else {
                ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi');
            }
        });
});
