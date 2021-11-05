@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Acente Torba & Çuval')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Torba & Çuval
                        <div class="page-title-subheading">
                            Bu modül üzerinden oluşturmuş olduğunuz torba ve çuvallarınızı görüntüleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <button id="btnCreateNewBag" type="button" aria-haspopup="true" aria-expanded="false"
                                class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                            Yeni Torba & Çuval
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Tüm Kullanıcılar
                </div>

            </div>
            <form id="search-form">
                <div class="row p-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="startDate">Başlangıç Tarihi:</label>
                            <input type="date" value="{{date('Y-m-d')}}"
                                   class="form-control form-control-sm niko-select-filter niko-filter" id="startDate">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="endDate">Bitiş Tarihi:</label>
                            <input type="date" value="{{date('Y-m-d')}}"
                                   class="form-control form-control-sm niko-select-filter niko-filter" id="endDate">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="creatorUser">Oluşturan Kullanıcı:</label>
                            <input type="text" class="form-control form-control-sm niko-filter" id="creatorUser">
                        </div>
                    </div>
                </div>
            </form>

            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-borderless table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Çuval Takip No</th>
                        <th>Tip</th>
                        <th>İçerdiği Kargo Sayısı</th>
                        <th>Statü</th>
                        <th>Oluşturan</th>
                        <th>Oluşturulma Zamanı</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Çuval Takip No</th>
                        <th>Tip</th>
                        <th>İçerdiği Kargo Sayısı</th>
                        <th>Statü</th>
                        <th>Oluşturan</th>
                        <th>Oluşturulma Zamanı</th>
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
                    5, 'desc'
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
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                        title: "CK - Torba & Çuvallarınız"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    },
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('cargoBags.agencyGetCargoBags') !!}',
                    data: function (d) {
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.creatorUser = $('#creatorUser').val();
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'type', name: 'type'},
                    {data: 'included_cargo_count', name: 'included_cargo_count'},
                    {data: 'status', name: 'status'},
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'edit', name: 'edit'},
                ],
                scrollY: "400px",
            });
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });

        function drawDT() {
            oTable.draw();
        }

        $('.niko-select-filter').change(delay(function (e) {
            drawDT();
        }, 1000));

        $('.niko-filter').keyup(delay(function (e) {
            drawDT();
        }, 1000));

        // parse a date in yyyy-mm-dd format
        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }

        $('#btnCreateNewBag').click(function () {
            $('#bag_type').val('');
            $('#modalCreateBag').modal();
        });

        $(document).on('click', '#btnInsertBag', function () {
            $('#modalBodyCreateBag').block({
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

            $('#btnInsertBag').prop('disabled', true);
            $.ajax('{{route('cargoBags.agencyCreateBag')}}', {
                method: 'POST',
                data: {
                    _token: token,
                    bag_type: $('#bag_type').val(),
                }
            }).done(function (response) {

                if (response.status == 0)
                    ToastMessage('error', response.message, 'Hata!');
                else if (response.status == 1) {
                    $('#modalCreateBag').modal('hide');
                    ToastMessage('success', '', 'İşlem başarılı, olutşruldu!');
                    oTable.ajax.reload();
                }

            }).error(function (jqXHR, response) {
                ajaxError(jqXHR.status);
            }).always(function () {
                $('#modalBodyCreateBag').unblock();
                $('#btnInsertBag').prop('disabled', false);
            });
        });


        var detail_id = null;

        $(document).on('click', '.bag-details', function () {

            let bag_id = $(this).prop('id');
            detail_id = bag_id;
            getBagDetails(bag_id);

        });


        function getBagDetails(bag_id) {

            $('#modalBagDetails').modal();

            $('#modalBodyBagDetails').block({
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

            $.ajax('/CargoBags/Agency/GetBagInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    bag_id: bag_id,
                }
            }).done(function (response) {

                if (response.status == 0)
                    ToastMessage('error', response.message, 'Hata!');
                else if (response.status == 1) {

                    let bag = response.bag;
                    let bag_details = response.bag_details;

                    $('#modalBagDetailHeader').text("#" + bag.tracking_no + " - " + bag.type + " DETAYLARI");

                    $('#tbodyBagDetails').html('');

                    if (bag_details.length == 0) {
                        $('#tbodyBagDetails').html(' <tr><td class="font-weight-bold text-danger text-center" colspan="8">Burda hiç veri yok!</td></tr>');
                    } else {
                        $.each(bag_details, function (key, val) {
                            $('#tbodyBagDetails').append('<tr>' +
                                '<td class="font-weight-bold">' + (val['invoice_number']) + '</td>' +
                                '<td>' + (val['part_no']) + '</td>' +
                                '<td>' + (val['cargo_type']) + '</td>' +
                                '<td>' + (val['receiver_name']) + '</td>' +
                                '<td>' + (val['sender_name']) + '</td>' +
                                '<td>' + (val['arrival_city'] + '/' + val['arrival_district']) + '</td>' +
                                '<td>' + (val['name_surname']) + '</td>' +
                                '<td>' + (val['created_at']) + '</td>' +
                                '</tr>');
                        });
                    }
                }

            }).error(function (jqXHR, response) {
                ajaxError(jqXHR.status);
            }).always(function () {
                $('#modalBodyBagDetails').unblock();
            });
        }

        $(document).on('click', '#btnRefreshBagDetails', function () {
            getBagDetails(detail_id);
        });

    </script>

@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="modalCreateBag" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Çuval & Torba Oluştur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="modalBodyCreateBag" class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold" for="bag_type">Tip:</label>
                                <select name="" id="bag_type" class="font-weight-bold form-control form-control-sm">
                                    <option value="">Seçiniz</option>
                                    <option value="Torba">Torba</option>
                                    <option value="Çuval">Çuval</option>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                    <button id="btnInsertBag" type="button" class="btn btn-primary">Oluştur
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="modalBagDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBagDetailHeader"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="modalBodyBagDetails" class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="overflow-y: auto; max-height: 425px;" class="cont">
                                <table style="white-space: nowrap;" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Fatura No</th>
                                        <th>Parça No</th>
                                        <th>Kargo Tipi</th>
                                        <th>Alıcı</th>
                                        <th>Gönderici</th>
                                        <th>Gönderici İl/İlçe</th>
                                        <th>Yükleyen</th>
                                        <th>Yükleme Zamanı</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyBagDetails"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display: flex;align-items: center; justify-content: center;" class="modal-footer">
                    <button id="btnRefreshBagDetails" class="btn btn-warning">Yenile</button>
                </div>
            </div>
        </div>
    </div>
@endsection
