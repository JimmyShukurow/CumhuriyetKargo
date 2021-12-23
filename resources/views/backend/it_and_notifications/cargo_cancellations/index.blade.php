@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Kargo İptal Paneli')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-ticket icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Kargo İptal Paneli
                        <div class="page-title-subheading">Bu sayfa üzerinden gelen kargo iptal başvurularını
                            yönetebilirsiniz.
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

                </div>

            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" id="search-form">

                    <div class="form-row">

                        <div class="col-md-3">
                            <label for="receiverCode">Kargo Takip No:</label>
                            <input type="text" data-inputmask="'mask': '99999 99999 99999'"
                                   placeholder="_____ _____ _____" type="text" id="trackingNo"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="receiverCode">Fatura NO:</label>
                            <input type="text" data-inputmask="'mask': 'AA-999999'"
                                   placeholder="__ ______" type="text" id="invoice_number"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name_surname">B. Yapan Ad Soyad</label>
                                <input type="text" class="form-control form-control-sm"
                                       placeholder="Oluşturan Kişi Ad Soyad"
                                       name="name_surname" id="name_surname">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="agency">Acente</label>
                                <input type="text" class="form-control form-control-sm" id="agency">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="confirm">Onay Durumu</label>
                                <select name="confirm" id="confirm" class="form-control form-control-sm">
                                    <option value="">Tümü</option>
                                    <option value="0">Onay Bekliyor</option>
                                    <option value="1">Onaylandı</option>
                                    <option value="-1">Reddedildi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="appointment_reason">Başvuru Nedeni</label>
                                <input type="text" class="form-control form-control-sm"
                                       placeholder="İptal Başvuru Nedeni"
                                       name="title" id="appointment_reason">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="confirming_name_surname">Onaylayan Ad Soyad</label>
                                <input type="text" class="form-control form-control-sm"
                                       placeholder="Onaylayan Kişi Ad Soyad"
                                       name="confirming_name_surname" id="confirming_name_surname">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="x_token"
                           value="{{Crypte4x(\Illuminate\Support\Facades\Auth::id())}}}">

                    <div class="form-row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="creating_start_date">Oluşturma Başlangıç Tarih</label>
                                <input type="date" name="creating_start_date" value="{{date('Y-m-d')}}"
                                       class="form-control form-control-sm"
                                       id="creating_start_date">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="creating_finish_date">Oluşturma Bitiş Tarih</label>
                                <input type="date" name="creating_finish_date" value="{{date('Y-m-d')}}"
                                       class="form-control form-control-sm"
                                       id="creating_finish_date">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="last_proccess_start_date">Son İşlem Başlangıç Tarih</label>
                                <input type="date" name="last_proccess_start_date" value="{{date('Y-m-d')}}"
                                       class="form-control form-control-sm"
                                       id="last_proccess_start_date">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="last_proccess_finish_date">Son İşlem Bitiş Tarih</label>
                                <input type="date" name="last_proccess_finish_date" value="{{date('Y-m-d')}}"
                                       class="form-control form-control-sm"
                                       id="last_proccess_finish_date">
                            </div>
                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-md-12 text-center">
                            <button id="btn-submit" type="submit" class="btn btn-primary ml-5">Ara</button>
                            <input type="reset" class="btn btn-secondary">

                            <div style="display: inline-block; float: right;" class="form-check ml-3">
                                <input style="cursor: pointer;" type="checkbox" id="last_proccess_date_filter"
                                       class="form-check-input">
                                <label style="cursor: pointer;" for="last_proccess_date_filter">Son İşlem Tarih
                                    Filtresi</label>
                            </div>

                            <div style="display: inline-block; float: right;" class="form-check">
                                <input style="cursor: pointer;" type="checkbox" id="creating_date_filter"
                                       class="form-check-input">
                                <label style="cursor: pointer;" for="creating_date_filter">Oluşturma Tarih
                                    Filtresi</label>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>


        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-ticket mr-3 text-muted opacity-6"> </i>Kargo İptalleri

                    <button id="CountConfirmed" type="button" data-title="Onaylanan başvurular."
                            data-toggle="popover-custom-bg"
                            data-bg-class="text-light bg-premium-dark"
                            class="mr-2 ml-2  btn btn-sm btn-success">0
                    </button>

                    <button id="CountRejected" type="button" data-title="Reddedilen başvurular."
                            data-toggle="popover-custom-bg"
                            data-bg-class="text-light bg-premium-dark"
                            class="mr-2 ml-2  btn btn-sm btn-danger">0
                    </button>

                    <button id="CountWaiting" type="button" data-title="Onay bekleyen başvurular."
                            data-toggle="popover-custom-bg"
                            data-bg-class="text-light bg-premium-dark"
                            class="mr-2 ml-2  btn btn-sm btn-info">0
                    </button>

                </div>
            </div>
            <div style="min-height: 70vh;overflow-x: auto;" class="card-body">

                <table style="white-space: nowrap;"
                       class="NikolasDataTable table-hover  table table-bordered Table20Padding">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Fatura No</th>
                        <th>B. Yapan</th>
                        <th>Acente</th>
                        <th>Bölge</th>
                        <th>Onay Durumu</th>
                        <th>Onaylayan</th>
                        <th>Son İşlem Tarihi</th>
                        <th>Oluşturulma Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Fatura No</th>
                        <th>B. Yapan</th>
                        <th>Acente</th>
                        <th>Bölge</th>
                        <th>Onay Durumu</th>
                        <th>Onaylayan</th>
                        <th>Son İşlem Tarihi</th>
                        <th>Oluşturulma Tarihi</th>
                    </tr>
                    </tfoot>
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

            return text;
        }
    </script>


    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>

    <script>

        function pageRowCount() {
            $.post('{{route('cargoCancel.pageRowCount')}}', {
                _token: token,
                ctn: $('#trackingNo').val(),
                name_surname: $('#name_surname').val(),
                agency: $('#agency').val(),
                appointment_reason: $('#appointment_reason').val(),
                confirm: $('#confirm').val(),
                confirming_name_surname: $('#confirming_name_surname').val(),
                creating_start_date: $('#creating_start_date').val(),
                creating_finish_date: $('#creating_finish_date').val(),
                last_proccess_start_date: $('#last_proccess_start_date').val(),
                creating_finish_date: $('#creating_finish_date').val(),
                last_proccess_start_date: $('#last_proccess_start_date').val(),
                last_proccess_finish_date: $('#last_proccess_finish_date').val(),
                creating_date_filter: creatingDateFilter,
                last_proccess_date_filter: lastProccessDateFilter,
                selected_region: getSelectedRegions,
                x_token: $('#x_token').val(),

            }).success(function (response) {
                $('#CountConfirmed').html(response.confirmed);
                $('#CountWaiting').html(response.waiting);
                $('#CountRejected').html(response.rejected);
            }).error(function () {
                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
            });
        }

        var oTable;
        var creatingDateFilter = false;
        var lastProccessDateFilter = false;

        $('#creating_date_filter').click(function () {
            creatingDateFilter = $('#creating_date_filter').prop("checked");
        });

        $('#last_proccess_date_filter').click(function () {
            lastProccessDateFilter = $('#last_proccess_date_filter').prop("checked");
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
                    8, 'desc'
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
                        title: "CK - Kargo İptalleri"
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
                    url: '{!! route('cargoCancel.getCancellations') !!}',
                    data: function (d) {
                        d.invoiceNumber = $('#invoice_number').val();
                        d.name_surname = $('#name_surname').val();
                        d.agency = $('#agency').val();
                        d.appointment_reason = $('#appointment_reason').val();
                        d.confirm = $('#confirm').val();
                        d.confirming_name_surname = $('#confirming_name_surname').val();
                        d.creating_start_date = $('#creating_start_date').val();
                        d.creating_finish_date = $('#creating_finish_date').val();
                        d.last_proccess_start_date = $('#last_proccess_start_date').val();
                        d.creating_finish_date = $('#creating_finish_date').val();
                        d.last_proccess_start_date = $('#last_proccess_start_date').val();
                        d.last_proccess_finish_date = $('#last_proccess_finish_date').val();
                        d.creating_date_filter = creatingDateFilter;
                        d.last_proccess_date_filter = lastProccessDateFilter;
                        d.selected_region = getSelectedRegions();
                        d.x_token = $('#x_token').val();
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'free', name: 'free'},
                    {data: 'invoice_number', name: 'invoice_number'},
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'agency_name', name: 'agency_name'},
                    {data: 'regional_directorates', name: 'regional_directorates'},
                    {data: 'confirm', name: 'confirm'},
                    {data: 'confirming_user_name_surname', name: 'confirming_user_name_surname'},
                    {data: 'approval_at', name: 'approval_at'},
                    {data: 'created_at', name: 'created_at'},
                ],
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

        var appointmentID = null;
        $(document).on('dblclick', '.main-cargo-tracking_no', function () {
            let tracking_no = $(this).attr('tracking-no')
            let id = $(this).prop('id');
            appointmentID = $(this).attr('appointment-id');
            detailsID = id;
            cargoInfo(id);
        });


        $(document).on('click', '#btnSetResult', function () {
            $('#ModalSetAppResult').modal();
        });

        $(document).on('click', '#btnUpdateAppResult', function () {

            $('#ModalSetAppResult').block({
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


            $.ajax('{{route('cargoCancel.setCargoCancellationApplicationResult')}}', {
                method: 'POST',
                data: {
                    _token: token,
                    id: appointmentID,
                    result: $('#cancelAppResult').val(),
                    description: $('#canelAppResultDescription').val()
                },
                cache: false
            }).done(function (response) {

                if (response.status == -1) {
                    ToastMessage('error', response.message, 'Hata!');
                    return false;
                } else if (response.status == 1) {

                    ToastMessage('success', 'Sonuç kaydedildi!', 'İşlem Başarılı!');
                    $('#ModalSetAppResult').modal('hide');

                    cargoInfo(detailsID);
                    oTable.ajax.reload();
                    pageRowCount();

                } else if (response.status == 0) {
                    $.each(response.errors, function (index, value) {
                        ToastMessage('error', value, 'Hata!')
                    });
                }

                $('#ModalSetAppResult').unblock();
                return false;
            }).error(function (jqXHR, exception) {
                ajaxError(jqXHR.status);
            }).always(function () {
                $('#ModalSetAppResult').unblock();
            });

        });

        $(document).on('click', '#btnCargoRestore', function () {

            $('#ModalSetAppResult').block({
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


            $.ajax('{{route('cargoCancel.backupCargo')}}', {
                method: 'POST',
                data: {
                    _token: token,
                    id: appointmentID,
                },
                cache: false
            }).done(function (response) {

                if (response.status == -1) {
                    ToastMessage('error', response.message, 'Hata!');
                    return false;
                } else if (response.status == 1) {

                    ToastMessage('success', 'Kargo geri yüklendi!', 'İşlem Başarılı!');
                    $('#ModalSetAppResult').modal('hide');

                    cargoInfo(detailsID);
                    oTable.ajax.reload();
                    pageRowCount();

                } else if (response.status == 0) {
                    $.each(response.errors, function (index, value) {
                        ToastMessage('error', value, 'Hata!')
                    });
                }

                $('#ModalSetAppResult').unblock();
                return false;
            }).error(function (jqXHR, exception) {
                ajaxError(jqXHR.status);
            }).always(function () {
                $('#ModalSetAppResult').unblock();
            });

        });

    </script>

    <script>var typeOfJs = 'admin_cancel_cargo'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>

    <script src="/backend/assets/scripts/official-report/report-view.js"></script>

@endsection

@section('modals')

    @php $data = ['type' => 'admin_cancel_cargo']; @endphp
    @include('backend.main_cargo.main.modal_cargo_details')

    @include('backend.OfficialReports.report_modal', $data)

    <!-- Large modal => Modal Set App Result -->
    <div class="modal fade bd-example-modal-lg" id="ModalSetAppResult" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">İptal Başvurusu Sonuç Gir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyCargoMovementsDetails" style="overflow-x: hidden; max-height: 75vh;"
                     class="modal-body">

                    <div class="row form-group">
                        <div class="col-md-12">
                            <label for="cancelAppTrackingNo">Kargo Takip No:</label>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" readonly
                                       id="cancelAppTrackingNo">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="cancelAppReason">İptal Başvuru Nedeni:</label>
                            <div class="form-group">
                                <textarea name="" readonly id="cancelAppReason" class="form-control form-control-sm"
                                          cols="30"
                                          rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="cancelAppResult">Sonuç:</label>
                            <div class="form-group">
                                <select name="" id="cancelAppResult" class="form-control form-control-sm">
                                    <option value="0">Onay Bekliyor</option>
                                    <option value="-1">Reddedildi</option>
                                    <option value="1">Onaylandı</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="canelAppResultDescription">Sonuç Açıklaması:</label>
                            <div class="form-group">
                                <textarea name="" id="canelAppResultDescription"
                                          class="form-control form-control-sm" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                    <button id="btnUpdateAppResult" type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

@endsection
