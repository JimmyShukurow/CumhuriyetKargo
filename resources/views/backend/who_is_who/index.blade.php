@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Kim Kimdir?')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas fa-users icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Kim Kimdir?
                        <div class="page-title-subheading">Bu modül üzerinden sistemdeki tüm kullanıcıları
                            listleyebilir, işlem yapabl irsiniz.
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

                        <div class="col-md-4">
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
                        <th>Şube İl</th>
                        <th>Şube İlçe</th>
                        <th>Şube Adı</th>
                        <th>Kullanıcı Tipi</th>
                        <th>Detay</th>
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
                        <th>Şube İl</th>
                        <th>Şube İlçe</th>
                        <th>Şube Adı</th>
                        <th>Kullanıcı Tipi</th>
                        <th>Detay</th>
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
                    7, 'desc'
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
                    },
                    "searchPlaceholder": "Kullanıcı Arayın",
                },
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>frtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: 'Sütun Görünüm'
                    },
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('whois.getUsers') !!}',
                    data: function (d) {
                        d.name_surname = $('#name_surname').val();
                        d.agency = $('#agency').val();
                        d.tc = $('#tc').val();
                        d.role = $('#role').val();
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
                    {data: 'branch_city', name: 'branch_city'},
                    {data: 'branch_district', name: 'branch_district'},
                    {data: 'branch_name', name: 'branch_name'},
                    {data: 'user_type', name: 'user_type'},
                    {data: 'detail', name: 'detail'},
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
            $.ajax('WhoIsWho/GetUserInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    user: user
                },
                cache: false
            }).done(function (response) {
                var user = response.user;
                var director = response.director;
                var region = response.region;


                $('#agencyName').html(user.name_surname);
                $('#agencyCityDistrict').html(user.branch_city + '/' + user.branch_district + ' - ' + user.branch_name + ' ' + user.user_type);
                $('#titleBranch').html(user.name_surname + ' ÖZET');
                $('#phone').html(user.phone);
                $('#email').html(user.email);
                $('td#name_surname').html(user.name_surname);
                $('#authority').html(user.display_name);
                $('td#phone').html(director.phone);
                $('#district').html(region.name + ' BÖLGE MÜDÜRLÜĞÜ');

                let fakeTitle = user.user_type == 'Aktarma' ? 'TRANSFER MERKEZİ' : 'ACENTE';

                $('#dependency').html(director.name_surname + " (" + director.display_name + ")");
                $('#place').html(user.branch_city + '/' + user.branch_district + ' - ' + user.branch_name + ' ' + fakeTitle);

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
                                                    <td class="static">Ad Soyad</td>
                                                    <td id="name_surname">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Telefon</td>
                                                    <td id="phone">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Yetki</td>
                                                    <td id="authority">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">E-Mail</td>
                                                    <td id="email"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>


                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: auto" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="2">BAĞIMLILIKLAR
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">Bağlı olduğu kişi</td>
                                                    <td id="dependency">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Bağlı olduğu yer</td>
                                                    <td id="district"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Çalıştığı yer</td>
                                                    <td id="place"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">İletişim</td>
                                                    <td id="phone"></td>
                                                </tr>
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
@endsection
