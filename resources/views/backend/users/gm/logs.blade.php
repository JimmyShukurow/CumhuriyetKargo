@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Kullanıcı Harekteleri (General Managment)')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-users icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div> Kullanıcı Harekteleri (User Log)
                        <div class="page-title-subheading">Kullanıcı logları 365 gün sonra otomatikmen silinir.
                        </div>
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
                            <select name="tc" id="tc" class="form-control form-control-sm">
                                <option value="">Seçiniz</option>
                                @foreach($data['tc'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->tc_name . ' TRM.'}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="agency">Acente</label>
                            <select name="agency" id="agency" class="form-control form-control-sm">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option
                                        value="{{$key->id}}">{{$key->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="name_surname">Ad Soyad</label>
                            <input type="text" class="form-control form-control-sm" name="name_surname"
                                   id="name_surname">
                        </div>
                        <div class="col-md-2">
                            <label for="log_type">Log Tipi</label>
                            <select name="log_type" id="log_type" class="form-control form-control-sm">
                                <option value="">Seçiniz</option>
                                @foreach($data['log_names'] as $key)
                                    <option
                                        value="{{$key->log_name}}">{{ $key->log_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="start_date">Başlangıç Tarih</label>
                            <input type="date" name="start_date" value="{{date('Y-m-d')}}"
                                   class="form-control form-control-sm"
                                   id="start_date">
                        </div>
                        <div class="col-md-2">
                            <label for="finish_date">Bitiş Tarih</label>
                            <input type="date" name="finish_date" value="{{date('Y-m-d')}}"
                                   class="form-control form-control-sm"
                                   id="finish_date">
                        </div>

                    </div>

                    <div style="display: none;" class="row text-center mt-3">
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

                <table style="white-space: nowrap;" width="100%" id="AgenciesTable"
                       class="align-middle mb-0 table table-borderless table-striped table-hover Table20Padding NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Şube</th>
                        <th>Kull. Tipi</th>
                        <th>Kullanıcı</th>
                        <th>Yetki</th>
                        <th>Hareket Tipi</th>
                        <th>Açıklama</th>
                        <th>Özellikler</th>
                        <th>Kayıt Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Şube</th>
                        <th>Kull. Tipi</th>
                        <th>Kullanıcı</th>
                        <th>Yetki</th>
                        <th>Hareket Tipi</th>
                        <th>Açıklama</th>
                        <th>Özellikler</th>
                        <th>Kayıt Tarihi</th>
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
    <script>
        let oTable;
        $(document).ready(function () {
            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 25,
                lengthMenu: dtLengthMenu,
                order: [7, 'desc'],
                language: dtLanguage,
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 8, 7]
                        },
                        title: "CKG-Sis User Logs",
                        attr: {
                            class: 'btn btn-success'
                        }
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        },
                        attr: {
                            class: 'btn btn-primary'
                        }
                    },
                    {
                        text: 'Filtreyi Temizle',
                        action: function (e, dt, node, config) {
                            $('#search-form').trigger("reset");
                        },
                        attr: {
                            class: 'btn btn-alternate'
                        }
                    }
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('user.gm.GetLogs') !!}',
                    data: function (d) {
                        d.name_surname = $('#name_surname').val();
                        d.agency = $('#agency').val();
                        d.tc = $('#tc').val();
                        d.log_name = $('#log_type').val();
                        d.start_date = $('#start_date').val();
                        d.finish_date = $('#finish_date').val();
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'agency', name: 'agency'},
                    {data: 'user_type', name: 'user_type'},
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'display_name', name: 'display_name'},
                    {data: 'log_name', name: 'log_name'},
                    {data: 'description', name: 'description'},
                    {data: 'properties', name: 'properties'},
                    {data: 'created_at', name: 'created_at'}
                ],
                scrollY: "400px",
                scrollX: true,
            });
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });

    </script>
    <script>
        // parse a date in yyyy-mm-dd format
        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }

        $(document).on('click', '.properties-log', function () {

            let log_id = $(this).attr('id');

            $('#ModalLogProperties').modal()

            $('#ModalLogBodyProperties').block(whiteAnimation);
            $('.blockUI.blockMsg.blockElement').css('width', '100%');
            $('.blockUI.blockMsg.blockElement').css('border', '0px');
            $('.blockUI.blockMsg.blockElement').css('background-color', '');

            $.ajax('/Users/GetUserLogInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    id: log_id
                }
            }).done(function (response) {

                if (response.status == 1) {

                    user = response.user
                    branch = response.branch
                    log = response.log

                    $('#json-renderer').text(log.properties);
                    $('#json-renderer').jsonViewer(JSON.parse(log.properties), {
                        collapsed: false,
                        rootCollapsable: false,
                        withQuotes: false,
                        withLinks: true
                    });

                    $('h5#userName').html(user.name_surname)
                    $('td#nameSurname').html(user.name_surname)
                    $('td#userMail').html(user.email)
                    $('td#userPhone').html(user.phone)
                    $('td#userType').html(user.user_type)
                    $('td#userRole').html(user.role.display_name)
                    $('td#userBranch').html(branch.name + ' ' + branch.type)

                    $('td#log-time').html(log.created_at)
                    $('td#log-type').html(log.log_name)
                    $('td#description').html(log.description)


                } else if (response.status == 0) {
                    ToastMessage('error', response.message, 'Hata!');
                }

            }).error(function (jqXHR, response) {
                ajaxError(jqXHR.status, JSON.parse(jqXHR.responseText));
            }).always(function () {
                $('#ModalLogBodyProperties').unblock();
            });


        });

        $('#tc').change(function () {
            $('#agency').val('');
            $('#user_type').val('');
        });
        $('#agency').change(function () {
            $('#tc').val('');
            $('#user_type').val('');
        });


    </script>
@endsection

@section('modals')
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <style type="text/css">
        pre#json-renderer {
            border: 1px solid #aaa;
        }
    </style>

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
                <div style="max-height: 75vh; overflow-y: auto;" id="ModalLogBodyProperties" class="modal-body">

                    {{-- CARD START --}}
                    <div class="col-md-12">
                        <div class="mb-3 profile-responsive card">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-dark">
                                    <div class="menu-header-image "
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract2.jpg');">
                                    </div>
                                    <div class="menu-header-content btn-pane-right">
                                        <div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                            <div class="avatar-icon rounded">
                                                <img src="/backend/assets/images/ck-ico-white.png" alt="Avatar 5">
                                            </div>
                                        </div>
                                        <div>
                                            <h5 id="userName" class="menu-header-title">###</h5>
                                            <h6 id="userAgency" class="menu-header-subtitle">###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">

                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div style="overflow-x: auto" class="cont">
                                                    <table id="AgencyCard"
                                                           class="TableNoPadding table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" colspan="2">Kullanıcı Detayları</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td width="100;">Ad Soyad</td>
                                                            <td id="nameSurname"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>E-Posta</td>
                                                            <td id="userMail"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>İletişim</td>
                                                            <td id="userPhone"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kullanıcı Tipi</td>
                                                            <td id="userType"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Yetki</td>
                                                            <td id="userRole"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bağlı Birim</td>
                                                            <td id="userBranch"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div style="overflow-x: auto" class="cont">
                                                    <table id="AgencyCard"
                                                           class="TableNoPadding table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" colspan="2">Log Detayları</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td width="100;">İşlem Zamanı</td>
                                                            <td id="log-time"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>İşlem</td>
                                                            <td id="log-type"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Açıklama</td>
                                                            <td id="description">Adres Satırı</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="text-center text-primary">Özellikler</h3>
                                        <div class="cont">
                                            <pre id="json-renderer"></pre>
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
