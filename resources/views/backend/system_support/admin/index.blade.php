@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', $data['title']->sub_name)


@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-ticket icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>{{$data['title']->sub_name}}
                        <div class="page-title-subheading">Bu sayfa üzerinden gelen ticketları yönetebilirsiniz.
                        </div>
                    </div>
                </div>

                <div class="page-title-actions">

                    <div class="d-inline-block pr-1">
                        <select style="display: none;" id="multi-select-areas" multiple="multiple">
                            <optgroup label="Bölge Müdürlükleri">
                                @foreach($data['regional_directorates'] as $rd)
                                    <option selected value="{{$rd->id}}">{{$rd->name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="d-inline-block pr-1">
                        <select id="department" type="select" class="custom-select">
                            @if(count($data['departments']) > 1)
                                <option value="">Tüm Departmanlar</option>
                            @endif
                            @foreach($data['departments'] as $department)
                                <option {{$department->department_name == 'SİSTEM DESTEK' ? 'selected' : ''}}
                                        value="{{$department->id}}">{{$department->department_name}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" id="search-form">

                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Durum</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Seçiniz</option>
                                    <option value="AÇIK">Açık</option>
                                    <option value="BEKLEMEDE">Beklemede</option>
                                    <option value="KAPALI">Kapalı</option>
                                    <option value="CEVAPLANDI">Cevaplandı</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="priority">Öncelik</label>
                                <select name="priority" id="priority" class="form-control">
                                    <option value="">Seçiniz</option>
                                    <option value="Düşük">Düşük</option>
                                    <option value="Normal">Normal</option>
                                    <option value="Yüksek">Yüksek</option>
                                    <option value="Çok Yüksek">Çok Yüksek</option>
                                    <option value="Acil">Acil</option>
                                    <option value="Kritik">Kritik</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name_surname">Ad Soyad</label>
                                <input type="text" class="form-control" placeholder="Oluşturan Kişi Ad Soyad"
                                       name="name_surname" id="name_surname">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title">Başlık</label>
                                <input type="text" class="form-control" placeholder="Ticket Başlığı Girin"
                                       name="title" id="title">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="x_token"
                           value="{{Crypte4x(\Illuminate\Support\Facades\Auth::id())}}}">

                    <div class="form-row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ticket_no">Ticket No</label>
                                <input type="text" class="form-control" placeholder="Ticket numarası ile sorgulayın"
                                       name="ticket_no" id="ticket_no">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="redirected">Yönlendirildi</label>
                                <select name="redirected" id="redirected" class="form-control">
                                    <option value="">Seçiniz</option>
                                    <option value="1">Evet</option>
                                    <option value="0">Hayır</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Başlangıç Tarih</label>
                                <input type="date" name="start_date" value="{{date('Y-m-d')}}" class="form-control"
                                       id="start_date">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="finish_date">Bitiş Tarih</label>
                                <input type="date" name="finish_date" value="{{date('Y-m-d')}}" class="form-control"
                                       id="finish_date">
                            </div>
                        </div>

                    </div>

                    <div class="row mt-3">


                        <div class="col-md-12 text-center">
                            <button id="btn-submit" type="submit" class="btn btn-primary ml-5">Ara</button>
                            <input type="reset" class="btn btn-secondary">

                            <div style="display: inline-block; float: right;" class="form-check">
                                <input style="cursor: pointer;" type="checkbox" id="date_filter"
                                       class="form-check-input">
                                <label style="cursor: pointer;" for="date_filter">Tarih Filtresi</label>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>


        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-ticket mr-3 text-muted opacity-6"> </i>Tickets

                    <button id="CountAcik" type="button" data-title="Durumu açık olan ticketlar."
                            data-toggle="popover-custom-bg"
                            data-bg-class="text-light bg-premium-dark"
                            class="mr-2 ml-2  btn btn-sm btn-success">0
                    </button>

                    <button id="CountBeklemede" type="button" data-title="Durumu beklemede olan ticketlar."
                            data-toggle="popover-custom-bg"
                            data-bg-class="text-light bg-premium-dark"
                            class="mr-2 ml-2  btn btn-sm btn-warning">0
                    </button>

                    <button id="CountKapali" type="button" data-title="Durumu kapalı olan ticketlar."
                            data-toggle="popover-custom-bg"
                            data-bg-class="text-light bg-premium-dark"
                            class="mr-2 ml-2  btn btn-sm btn-dark">0
                    </button>

                    <button id="CountCevaplandi" type="button" data-title="Durumu cevaplandı olan ticketlar."
                            data-toggle="popover-custom-bg"
                            data-bg-class="text-light bg-premium-dark"
                            class="mr-2 ml-2  btn btn-sm btn-alternate">0
                    </button>

                    <button id="CountTotal" type="button" data-title="Total ticket adeti."
                            data-toggle="popover-custom-bg"
                            data-bg-class="text-light bg-premium-dark"
                            class="mr-2 ml-2  btn btn-sm btn-primary">0
                    </button>

                </div>
            </div>
            <div style="min-height: 70vh;overflow-x: auto;" class="card-body">

                <table
                    class="NikolasDataTable align-middle mb-0 table table-bordered table-striped ">
                    <thead>
                    <tr>
                        <th>Durum</th>
                        <th>Departman</th>
                        <th>Oluşturan</th>
                        <th>Başlık</th>
                        <th>Öncelik</th>
                        <th>Oluşt. Tarihi</th>
                        <th>Son Güncelleme</th>
                        <th>Yönlendirildi</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection


@section('js')


    <!-- Include the plugin's CSS and JS: -->
    <script type="text/javascript" src="/backend/assets/scripts/bootstrap-multiselect.js"></script>

    <!-- Initialize the plugin: -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#multi-select-areas').multiselect({
                includeSelectAllOption: true,
                selectAllName: 'select-all-name',
                allSettled: true,
                selectAllText: 'Tümünü Seç',
                onSelectAll: function () {
                    oTable.draw();
                    pageRowCount();
                },
                onDeselectAll: function () {
                    oTable.draw();
                    pageRowCount();
                },
                widthSynchronizationMode: 'always',
                buttonWidth: '250px',
                onChange: function (option, checked, select) {
                    // alert('Changed option ' + $(option).val() + '. Checked : ' + checked);
                    oTable.draw();
                    pageRowCount();
                }

            });

        });

        function getSelectedRegions() {
            var selected = [];
            $('#multi-select-areas option:selected').each(function () {
                selected.push([$(this).val(), $(this).data('order')]);
            });

            selected.sort(function (a, b) {
                return a[1] - b[1];
            });

            var text = '';
            for (var i = 0; i < selected.length; i++) {
                text += selected[i][0] + ', ';
            }
            text = text.substring(0, text.length - 2);

            console.log(text);
            return text;
        }
    </script>



    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>

    <script>

        function pageRowCount() {
            $.post('{{route('admin.systemSupport.pageRowCount')}}', {
                _token: token,
                department: $('#department').val(),
                status: $('#status').val(),
                priority: $('#priority').val(),
                name_surname: $('#name_surname').val(),
                title: $('#title').val(),
                start_date: $('#start_date').val(),
                finish_date: $('#finish_date').val(),
                date_filter: dateFilter,
                selected_regions: getSelectedRegions(),
                x_token: $('#x_token').val(),
                ticket_no: $('#ticket_no').val(),
                redirected: $('#redirected').val(),
            }).success(function (response) {
                $('#CountAcik').html(response.acik);
                $('#CountBeklemede').html(response.beklemede);
                $('#CountKapali').html(response.kapali);
                $('#CountCevaplandi').html(response.cevaplandi);
                $('#CountTotal').html(response.all);
            }).error(function () {
                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
            });
        }

        var oTable;

        var dateFilter = $('#date_filter').prop("checked");

        $('#date_filter').change(function () {
            dateFilter = $('#date_filter').prop("checked");
        });

        // and The Last Part: NikoStyle
        $(document).ready(function () {

            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [
                    6, 'desc'
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
                // columnDefs: [
                //     {
                //         targets: [7],
                //         visible: false,
                //         searchable: false
                //     }
                // ],
                buttons: [
                    'copy',
                    'pdf',
                    'csv',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },
                        title: "CK - Admin Tickets"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                            pageRowCount();
                        }
                    }
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('admin.systemSupport.getTickets') !!}',
                    data: function (d) {
                        d.department = $('#department').val();
                        d.status = $('#status').val();
                        d.priority = $('#priority').val();
                        d.name_surname = $('#name_surname').val();
                        d.title = $('#title').val();
                        d.start_date = $('#start_date').val();
                        d.finish_date = $('#finish_date').val();
                        d.date_filter = dateFilter;
                        d.selected_region = getSelectedRegions();
                        d.x_token = $('#x_token').val();
                        d.ticket_no = $('#ticket_no').val();
                        d.redirected = $('#redirected').val();
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'status', name: 'status'},
                    {data: 'department_name', name: 'department_name'},
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'title', name: 'title'},
                    {data: 'priority', name: 'priority'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'redirected', name: 'redirected'},
                ],
                scrollY: false,
            });
        });

        $('#department').change(function (e) {
            oTable.draw();
            e.preventDefault();
            pageRowCount();
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
            pageRowCount();
        });

        pageRowCount();
    </script>

@endsection
