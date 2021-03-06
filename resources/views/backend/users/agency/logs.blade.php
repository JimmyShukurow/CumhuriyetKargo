@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Kullanıcı Hareketleri '.$data['agencies']->agency_name . ' Acente')


@section('content')


    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-users icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div> Kullanıcı Harekteleri
                        ({{$data['agencies']->city . '/' . $data['agencies']->district . ' - ' . $data['agencies']->agency_name . ' - '. $data['agencies']->agency_code}}
                        )
                        <div class="page-title-subheading">{{$data['agencies']->agency_name}} Acentenize bağlı kullanıcı
                            hareketlerini bu modül üzerinden listeleyebilirsiniz. Kullanıcı logları 365 gün sonra
                            otomatikmen silinir.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="user">Kullanıcı</label>
                            <select name="user" id="user" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($data['users'] as $user)
                                    <option value="{{$user->id}}">{{$user->name_surname}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="start_date">Başlangıç Tarih</label>
                            <input type="date" name="start_date" value="{{date('Y-m-d')}}" class="form-control"
                                   id="start_date">
                        </div>
                        <div class="col-md-4">
                            <label for="finish_date">Bitiş Tarih</label>
                            <input type="date" name="finish_date" value="{{date('Y-m-d')}}" class="form-control"
                                   id="finish_date">
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
                        class="header-icon lnr-store mr-3 text-muted opacity-6"> </i>Tüm Kullanıcılar
                </div>
            </div>
            <div class="card-body">

                <table id="AgenciesTable"
                       class="align-middle mb-0 table table-borderless table-striped table-hover TableNoPadding NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Acente</th>
                        <th>Kullanıcı</th>
                        <th>Yetki</th>
                        <th>Hareket Tipi</th>
                        <th>Açıklama</th>
                        <th>Özellikler</th>
                        <th>Properties</th>
                        <th>Kayıt Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Acente</th>
                        <th>Kullanıcı</th>
                        <th>Yetki</th>
                        <th>Hareket Tipi</th>
                        <th>Açıklama</th>
                        <th>Özellikler</th>
                        <th>Properties</th>
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
        var oTable;

        // and The Last Part: NikoStyle
        $(document).ready(function () {
            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 25,
                lengthMenu: dtLengthMenu,
                order: [
                    6, 'desc'
                ],
                language: dtLanguage,
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
                columnDefs: [
                    {
                        targets: [6],
                        visible: false,
                        searchable: false
                    }
                ],
                buttons: [
                    'copy',
                    'pdf',
                    'csv',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 6, 7]
                        },
                        title: "CK-{{$data['agencies']->agency_name}} Acente Kullanıcı Hareketleri"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('AgencyUsers.GetLogs') !!}',
                    data: function (d) {
                        d.user = $('#user').val();
                        d.start_date = $('#start_date').val();
                        d.finish_date = $('#finish_date').val();
                        d.token = '{{ Crypte4x(Auth::id())}}'
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'agency', name: 'agency'},
                    // {data: 'district', name: 'district'},
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'display_name', name: 'display_name'},
                    {data: 'log_name', name: 'log_name'},
                    {data: 'description', name: 'description'},
                    {data: 'properties', name: 'properties'},
                    {data: 'properties_detail', name: 'properties_detail'},
                    {data: 'created_at', name: 'created_at'}
                ],
                scrollY: "400px",
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
            var properties = $(this).attr('properties');
            var log_id = $(this).attr('id');
            $('#json-renderer').text(properties);
            $('#json-renderer').jsonViewer(JSON.parse(properties), {
                collapsed: false,
                rootCollapsable: false,
                withQuotes: false,
                withLinks: false
            });
            $('#userName').text($("#logs-item-" + log_id + " > td:nth-child(2)").text());
            $('#userAgency').text($("#logs-item-" + log_id + " > td:nth-child(1)").text());
            $('#log-time').text($("#logs-item-" + log_id + " > td:nth-child(7)").text());
            $('#log-type').text($("#logs-item-" + log_id + " > td:nth-child(4)").text());
            $('#description').text($("#logs-item-" + log_id + " > td:nth-child(5)").text());
            $('#ModalLogProperties').modal();
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
                <div class="modal-body">

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

                                        <div style="overflow-x: scroll" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" colspan="2">Log Detayları</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">İşlem Zamanı</td>
                                                    <td id="log-time"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">İşlem</td>
                                                    <td id="log-type"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Açıklama</td>
                                                    <td id="description">Adres Satırı</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

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
@endsection
