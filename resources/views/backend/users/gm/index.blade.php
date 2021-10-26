@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Tüm Kullanıcılar (General Managment)')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Tüm Kullanıcılar (General Managment)
                        <div class="page-title-subheading">Bu modül üzerinden sistemdeki tüm kullanıcıları
                            listleyebilir, işlem yapablirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('user.gm.AddUser') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Kullanıcı Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">

                        <div class="col-md-2">
                            <label for="tc">Aktarma</label>
                            <select name="tc" id="tc" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($data['tc'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->city . '-' . $key->tc_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="agency">Acente</label>
                            <select name="agency" id="agency" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->city . '/' . $key->district . '-' . $key->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="name_surname">Ad Soyad</label>
                            <input type="text" class="form-control" name="name_surname" id="name_surname">
                        </div>

                        <div class="col-md-2">
                            <label for="user_type">Kullanıcı Tipi</label>
                            <select name="user_type" id="user_type" class="form-control">
                                <option value="">Seçiniz</option>
                                <option value="Acente">Acente</option>
                                <option value="Aktarma">Aktarma</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="status">Statü</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Seçiniz</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Pasif">Pasif</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="role">Yetki</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($data['roles'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row text-center mt-3">
                        <div class="col-md-12 text-center">
                            <button id="btn-submit" type="submit" class="btn btn-primary ">Ara</button>
                            <input type="reset" class="btn btn-secondary">
                        </div>
                    </div>

                </form>


            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Tüm Kullanıcılar
                </div>

            </div>
            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table table-borderless table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Ad Soyad</th>
                        <th>Yetki</th>
                        <th>Eposta</th>
                        <th>Telefon</th>
                        <th>Statü</th>
                        <th>Şube İl</th>
                        <th>Şube İlçe</th>
                        <th>Şube Adı</th>
                        <th>Kullanıcı Tipi</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Ad Soyad</th>
                        <th>Yetki</th>
                        <th>Eposta</th>
                        <th>Telefon</th>
                        <th>Statü</th>
                        <th>Şube İl</th>
                        <th>Şube İlçe</th>
                        <th>Şube Adı</th>
                        <th>Kullanıcı Tipi</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <style type="text/css">
        pre#json-renderer {
            border: 1px solid #aaa;
        }
    </style>

    <script>
        var oTable;
        var detailsID = null;
        // and The Last Part: NikoStyle
        $(document).ready(function () {
            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [
                    9, 'desc'
                ],
                language: {
                    "sDecimal": ",",
                    "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
                    "sInfo": "_TOTAL_ kayıttan _START_ - _END_ kayıtlar gösteriliyor",
                    "sInfoEmpty": "Kayıt yok",
                    "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_",
                    "sLoadingRecords": "Yükleniyor...",
                    "sProcessing": "<div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div>",
                    "sSearch": "",
                    "sZeroRecords": "Eşleşen kayıt bulunamadı",
                    "oPaginate": {
                        "sFirst": "İlk",
                        "sLast": "Son",
                        "sNext": "Sonraki",
                        "sPrevious": "Önceki"
                    },
                    "oAria": {
                        "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                        "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
                    },
                    "select": {
                        "rows": {
                            "_": "%d kayıt seçildi",
                            "0": "",
                            "1": "1 kayıt seçildi"
                        }
                    }
                },
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
                buttons: [
                    'pdf',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        },
                        title: "CK - Sistem Kullanıcıları"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Sütun Görünüm'
                    },
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('user.gm.getAllUsers') !!}',
                    data: function (d) {
                        d.name_surname = $('#name_surname').val();
                        d.agency = $('#agency').val();
                        d.tc = $('#tc').val();
                        d.role = $('#role').val();
                        d.status = $('#status').val();
                        d.user_type = $('#user_type').val();
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'display_name', name: 'display_name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'status', name: 'status'},
                    {data: 'branch_city', name: 'branch_city'},
                    {data: 'branch_district', name: 'branch_district'},
                    {data: 'branch_name', name: 'branch_name'},
                    {data: 'user_type', name: 'user_type'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'edit', name: 'edit'}
                ],
                scrollY: "400px",
            });
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });

        // parse a date in yyyy-mm-dd format
        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }

        $('#tc').change(function () {
            $('#agency').val('');
            $('#user_type').val('');
        });
        $('#agency').change(function () {
            $('#tc').val('');
            $('#user_type').val('');
        });

        $('#user_type').change(function () {
            $('#agency').val('');
            $('#tc').val('');
        });

        $(document).on('click', '.user-detail', function () {
            $('#ModalUserDetail').modal();

            $('#ModalBodyUserDetail.modal-body').block({
                message: $('<div class="loader mx-auto">\n' +
                    '                            <div class="ball-pulse-sync">\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                            </div>\n' +
                    '                        </div>')
            });
            $('.blockUI.blockMsg.blockElement').css('width', '100%');
            $('.blockUI.blockMsg.blockElement').css('border', '0px');
            $('.blockUI.blockMsg.blockElement').css('background-color', '');

            detailsID = $(this).prop('id');
            userInfo($(this).prop('id'));
        });

        var array = new Array();

        function userInfo(user) {
            $.ajax('/Users/GetUserInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    user: user
                },
                cache: false
            }).done(function (response) {
                var user = response.user;
                var creator = response.creator;

                $('#agencyName').html(user.name_surname + " (" + user.display_name + ")");
                $('#agencyCityDistrict').html(user.branch_city + '/' + user.branch_district + ' - ' + user.branch_name + ' ' + user.user_type);
                $('#titleBranch').html(user.name_surname + ' ÖZET');
                $('#phone').html(user.phone);
                $('#email').html(user.email);
                $('#general-status').html(user.status == 0 ? 'Pasif' : 'Aktif');
                $('#statusDescription').html(user.status_description);
                $('#regDate').html(user.created_at);
                $('#creatorUserInfo').html('<b class="text-alternate">#' + creator.id + '</b> ' + creator.name_surname + ' (<b class="text-primary">' + creator.display_name + '</b>)');

                var counter = 0;
                array = [];
                $.each(response.user_log, function (key, value) {
                    array[counter] = value['properties'];
                    counter++;
                });

                $('#tbodyUseLog').html('');
                var counter = 0;
                $.each(response.user_log, function (key, value) {
                    $('#tbodyUseLog').append(
                        '<tr>' +
                        '<td>' + (value['created_at']) + '</td>' +
                        '<td>' + (value['log_name']) + '</td>' +
                        '<td>' + (value['properties'] != '[]' ? '<button id="' + (value['id']) + '" array-no="' + (counter) + '" class="btn  btn-xs btn-danger properties-log">Detay</button>' : '') + '</td>' +
                        '<td>' + (value['description']) + '</td>' +
                        '</tr>'
                    );
                    counter++;
                });

                $('.modal-body').unblock();
                return false;
            });

            $('#ModalAgencyDetail').modal();
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

        $(document).on('click', '#passwordResetBtn', function () {
            swal({
                title: 'Parola Sıfırlama İşlemini Onaylayın.',
                text: 'Kullanıcının şifresi sıfırlanacak ve sisteme kayıtlı telefon numarasına sms olarak gönderilecektir, bunu onaylıyor musunuz?',
                icon: 'warning',
                buttons: ['İptal Et', 'Onayla'],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    ToastMessage('warning', 'İstek alındı, lütfen bekleyiniz.', 'Dikkat!');
                    $.ajax('{{route('user.gm.passwordReset')}}', {
                        method: 'POST',
                        data: {
                            _token: token,
                            user: detailsID
                        }
                    }).done(function (response) {
                        if (response.status == 1) {
                            ToastMessage('success', 'Kullanıcnın şifresi sıfırlandı ve cep numarasına SMS gönderildi.', 'İşlem Başarılı!');
                        } else if (response.status == 0) {
                            ToastMessage('error', response.description, 'Hata!');
                        }
                    });
                } else {
                    ToastMessage('info', 'İşlem iptal edilidi.', 'Bilgi');
                }
            });
        });

        $(document).on('click', '#btnEnabledDisabled', function () {
            // alert(detailsID);
            $('#modalEnabledDisabled').modal();

            $('#modalBodyEnabledDisabled.modalEnabledDisabled.modal-body').block({
                message: $('<div class="loader mx-auto">\n' +
                    '                            <div class="ball-pulse-sync">\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                            </div>\n' +
                    '                        </div>')
            });
            $('.blockUI.blockMsg.blockElement').css('width', '100%');
            $('.blockUI.blockMsg.blockElement').css('border', '0px');
            $('.blockUI.blockMsg.blockElement').css('background-color', '');


            $.ajax('/Users/GetUserInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    user: detailsID
                }
            }).done(function (response) {
                console.log(response);

                $('#userNameSurname').val(response.user.name_surname);
                $('#accountStatus').val(response.user.status);
                $('#statusDesc').val(response.user.status_description);

                $('.modalEnabledDisabled.modal-body').unblock();
            });
        });

        $(document).on('click', '#btnSaveStatus', function () {
            ToastMessage('warning', 'İstek alındı, lütfen bekleyiniz.', 'Dikkat!');
            $.ajax('/Users/ChangeStatus', {
                method: 'POST',
                data: {
                    _token: token,
                    user: detailsID,
                    status: $('#accountStatus').val(),
                    status_description: $('#statusDesc').val()
                }
            }).done(function (response) {
                if (response.status == 1) {
                    userInfo(detailsID);
                    ToastMessage('success', 'Değişiklikler başarıyla kaydedildi.', 'İşlem Başarılı!');
                    $('#modalEnabledDisabled').modal('toggle');
                } else if (response.status == 0) {
                    ToastMessage('error', response.description, 'Hata!');
                } else if (response.status == -1) {
                    response.errors.status.forEach(key =>
                        ToastMessage('error', key, 'Hata!')
                    );
                }

                return false;
            }).error(function (jqXHR, response) {

                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
            });
        });
        $(document).on('click', '#btnVirtualLogin', function () {
            $('#ModalVirtualLogin').modal();
        });

        function kelimeSayisi(icerik) {
            var alfabem = "[a-zA-ZÂâÎîİıÇçŞşÜüÖöĞğ]";
            var kelimeSayisi = 0;

            for (var i = 0; i < icerik.length - 1; i++) {
                if (icerik[i].match(alfabem) && !icerik[i + 1].match(alfabem))
                    kelimeSayisi++;
            }


            if (icerik[icerik.length - 1].match(alfabem))
                kelimeSayisi++;

            return kelimeSayisi;
        }

        $(document).on('click', '#btnGoToVirtualLogin', function () {

            if ($('#reason').val().trim() == '') {
                ToastMessage('error', 'Gerekçe belirtmelisiniz!', 'Hata!');
                return false;
            } else if (kelimeSayisi($('#reason').val()) < 3) {
                ToastMessage('error', 'Gerekçe en az 3 kelimeden oluşmalıdır!', 'Hata!');
                return false;
            }

            window.location.href = "/Users/VirtualLogin/" + detailsID + "/" + $('#reason').val();

        });

    </script>
@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalUserDetail" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Kullanıcı Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyUserDetail" class="modal-body">

                    {{-- CARD START --}}
                    <div class="col-md-12">
                        <div class="mb-3 profile-responsive card">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-dark">
                                    <div class="menu-header-image "
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract10.jpg');">
                                    </div>
                                    <div class="menu-header-content btn-pane-right">
                                        <div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                            <div class="avatar-icon rounded">
                                                <img src="/backend/assets/images/ck-ico-white.png" alt="Avatar 5">
                                            </div>
                                        </div>
                                        <div>
                                            <h5 id="agencyName" class="menu-header-title">###</h5>
                                            <h6 id="agencyCityDistrict" class="menu-header-subtitle">###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button
                                                        id="passwordResetBtn"
                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                                        <i class="lnr-redo text-dark opacity-7 btn-icon-wrapper mb-2"></i>
                                                        Şifre Sıfırla
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnEnabledDisabled"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                        <i class="lnr-construction text-danger opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Hesabı Aktif/Pasif Yap
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnVirtualLogin"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                                                        <i class="fa fa-paper-plane text-alternate opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Hesaba Sanal Giriş Yap
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: auto" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="2">Genel Merkez
                                                        Acente
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Telefon</td>
                                                    <td id="phone">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">E-Mail</td>
                                                    <td id="email"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Statü</td>
                                                    <td id="general-status">Aktif</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Statü Açıklama</td>
                                                    <td id="statusDescription"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Kayıt Tarihi</td>
                                                    <td id="regDate"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Oluşturan Kullanıcı</td>
                                                    <td class="font-weight-bold" id="creatorUserInfo"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <h4 class="mt-3">Son Hareketler</h4>

                                        <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                             class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees"
                                                   class="Table30Padding table table-striped mt-3">
                                                <thead>
                                                <tr>
                                                    <th>Kayıt Tarihi</th>
                                                    <th>Hareket Tipi</th>
                                                    <th>Detay</th>
                                                    <th>Hareket</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyUseLog">

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </li>


                            </ul>
                        </div>
                    </div>
                    {{-- CARD END --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalLogProperties" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Log Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- CARD START --}}
                    <div class="col-md-12">
                        <div class="mb-3 profile-responsive card">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <h3 class="text-center text-primary">Özellikler</h3>
                                    <pre id="json-renderer"></pre>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- CARD END --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Enabled/Disabled User --}}
    <div class="modal fade" id="modalEnabledDisabled" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hesabı Aktif Pasif Yap</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="userNameSurname">Ad Soyad</label>
                                <input id="userNameSurname" class="form-control" type="text" readonly
                                       value="Nurullah Güç">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="accountStatus">Hesap Durumu</label>
                                <select class="form-control" name="" id="accountStatus">
                                    <option value="0">Pasif</option>
                                    <option value="1">Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="statusDesc">Statü Açıklaması</label>
                                <textarea name="" id="statusDesc" cols="30" rows="10" class="form-control"></textarea>
                                <em class="text-danger">Kullanıcı hesabı pasif edilmesinden dolayı sisteme giriş
                                    yapamadığında karşısına çıkacak uyarıyı belirtin.</em>
                                <em class="text-success"> <br>
                                    <b>Default Message: Hesabınız pasif edilmiştir. Detaylı bilgi için sistem destek
                                        ekibine ulaşın.</b>
                                </em>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnSaveStatus" type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Virtual Login --}}
    <div class="modal fade" id="ModalVirtualLogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sanal Giriş</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modalEnabledDisabled">
                    <em class="text-dark">Kullanıcının hesabına neden sanal giriş yaptığınızı
                        belirtin.</em>
                    <em class="text-dark"><br>
                        Not: Hesap sahibine bununla ilgili gerekçesi ile birlikte bir bildirim
                        göndereceğiz.
                    </em>
                    <em class="text-dark"><br>
                        Not: Yetkisi <b>Genel Yönetici</b> olanlar ve <b>kendi hesabınız</b> hariç tüm hesaplara sanal
                        giriş
                        yapabilirsiniz.
                    </em>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="reason">Gerekçe Belirtin (<label for="reason"
                                                                             class="text-danger">Zorunlu*</label>)</label>
                                <textarea name="" id="reason" cols="30" rows="4" class="form-control"></textarea>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnGoToVirtualLogin" type="button" class="btn btn-primary">Hesaba Sanal Giriş Yap
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
