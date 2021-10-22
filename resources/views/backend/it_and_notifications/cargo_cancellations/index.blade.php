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
                            <input type="text" data-inputmask="'mask': 'AA 999999'"
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

    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>

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

        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }


        function cargoInfo(user) {

            $('#ModalCargoDetails').modal();

            $('#ModalCargoDetails').block({
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


            $.ajax('/MainCargo/AjaxTransactions/GetAllCargoInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    id: user
                },
                cache: false
            }).done(function (response) {

                if (response.status == 0) {
                    setTimeout(function () {
                        ToastMessage('error', response.message, 'Hata!');
                        $('#ModalCargoDetails').modal('hide');
                        $('#CargoesTable').DataTable().ajax.reload();
                        return false;
                    }, 250);
                } else if (response.status == 1) {

                    let cargo = response.cargo;
                    let sender = response.sender;
                    let receiver = response.receiver;
                    let creator = response.creator;
                    let departure = response.departure;
                    let departure_tc = response.departure_tc;
                    let arrival = response.arrival;
                    let arrival_tc = response.arrival_tc;
                    let sms = response.sms;
                    let add_services = response.add_services;
                    let movements = response.movements;
                    let cancellations = response.cancellation_applications;

                    $('#titleTrackingNo').text(cargo.tracking_no);

                    if (cargo.deleted_at != null) {
                        $('#titleTrackingNo').text($('#titleTrackingNo').text() + " ##SİLİNDİ##");
                        $('#titleTrackingNo').addClass('text-warning');
                        $('li#SetResult').hide();
                        $('li#CargoRestore').show();
                    } else {
                        $('li#SetResult').show();
                        $('li#CargoRestore').hide();
                        $('#titleTrackingNo').removeClass('text-warning');
                    }

                    $('#cancelAppTrackingNo').val(cargo.tracking_no);

                    $('#senderTcknVkn').text(sender.tckn);
                    $('#senderCurrentCode').text(sender.current_code);
                    $('#senderCustomerType').text(sender.category);
                    $('#senderNameSurname').text(cargo.sender_name);
                    $('#senderPhone').text(cargo.sender_phone);
                    $('#senderCityDistrict').text(cargo.sender_city + "/" + cargo.sender_district);
                    $('#senderNeighborhood').text(cargo.sender_neighborhood);
                    $('#senderAddress').text(cargo.sender_address);

                    $('#receiverTcknVkn').text(receiver.tckn);
                    $('#receiverCurrentCode').text(receiver.current_code);
                    $('#receiverCustomerType').text(receiver.category);
                    $('#receiverNameSurname').text(cargo.receiver_name);
                    $('#receiverPhone').text(cargo.receiver_phone);
                    $('#receiverCityDistrict').text(cargo.receiver_city + "/" + cargo.receiver_district);
                    $('#receiverNeighborhood').text(cargo.receiver_neighborhood);
                    $('#receiverAddress').text(cargo.receiver_address);

                    $('#cargoTrackingNo').text(cargo.tracking_no);
                    $('#cargoCreatedAt').text(dateFormat(cargo.created_at));
                    $('#numberOfPieces').text(cargo.number_of_pieces);
                    $('#cargoKg').text(cargo.kg);
                    $('#cubicMeterVolume').text(cargo.cubic_meter_volume);
                    $('#desi').text(cargo.desi);
                    $('td#cargoType').text(cargo.cargo_type);
                    $('td#paymentType').text(cargo.payment_type);
                    $('td#transporter').text(cargo.transporter);
                    $('td#system').text(cargo.system);
                    $('td#creatorUserInfo').text(creator.name_surname + " (" + creator.display_name + ")");
                    $('td#customerCode').text(cargo.customer_code == null ? "" : cargo.customer_code);
                    $('td#cargoStatus').text(cargo.status);
                    $('td#cargoStatusForHumen').text(cargo.status_for_human);
                    $('td#cargoContent').text(cargo.cargo_content);
                    $('td#cargoContentEx').text(cargo.cargo_content_ex);

                    $('td#collectible').text(cargo.collectible);
                    $('#collection_fee').text(cargo.collection_fee + "₺");
                    $('#exitTransfer').text(departure_tc.city + " - " + departure_tc.tc_name + " TM");
                    $('#exitBranch').text(departure.city + "/" + departure.district + " - " + departure.agency_name + " (" + departure.agency_code + ")");
                    $('#arrivalTC').text(arrival_tc.city + " - " + arrival_tc.tc_name + " TM");
                    $('#arrivalBranch').text(arrival.city + "/" + arrival.district + " - " + arrival.agency_name + " (" + arrival.agency_code + ")");
                    $('td#postServicesPrice').text(cargo.post_service_price + "₺");
                    $('td#heavyLoadCarryingCost').text(cargo.heavy_load_carrying_cost + "₺");
                    $('td#distance').text(cargo.distance + " KM");
                    $('td#distancePrice').text(cargo.distance_price + "₺");
                    $('td#kdv').text(cargo.kdv_price + "₺");
                    $('td#addServiceFee').text(cargo.add_service_price + "₺");
                    $('td#serviceFee').text(cargo.service_price + "₺");
                    $('td#totalFee').text(cargo.total_price + "₺");

                    $('#PrintStatementOfResposibility').attr('href', '/MainCargo/StatementOfResponsibility/' + cargo.tracking_no);

                    var addServiceTotalPrice = 0;
                    $('#tbodyCargoAddServices').html('');

                    if (add_services.length == 0)
                        $('#tbodyCargoAddServices').html('<tr><td colspan="2" class="text-center">Burda hiç veri yok.</td></tr>');
                    else {
                        $.each(add_services, function (key, val) {

                            let result = val['result'] == '1' ? '<b class="text-success">' + 'Başarılı' + '</b>' : '<b class="text-danger">' + 'Başarısız' + '</b>';

                            $('#tbodyCargoAddServices').append(
                                '<tr>' +
                                '<td>' + val['service_name'] + '</td>' +
                                '<td  class="font-weight-bold text-dark">' + val['price'] + "₺" + '</td>' +
                                +'</tr>'
                            );

                            addServiceTotalPrice += parseInt(val['price']);
                        });

                        $('#tbodyCargoAddServices').append(
                            '<tr>' +
                            '<td class="font-weight-bold text-primary">' + 'Toplam:' + ' </td>' +
                            '<td class="font-weight-bold text-primary">' + cargo.add_service_price + "₺" + '</td>' +
                            +'</tr>'
                        );

                    }

                    $('#tbodyCargoMovements').html('');

                    if (movements.length == 0)
                        $('#tbodyCargoMovements').html('<tr><td colspan="5" class="text-center">Burda hiç veri yok.</td></tr>');
                    else {
                        $.each(movements, function (key, val) {

                            let result = val['number_of_pieces'] == val['current_pieces'] ? 'text-success' : 'text-danger';

                            $('#tbodyCargoMovements').append(
                                '<tr>' +
                                '<td>' + val['status'] + '</td>' +
                                '<td>' + val['info'] + '</td>' +
                                '<td class="' + result + ' font-weight-bold">' + val['number_of_pieces'] + '/' + val['current_pieces'] + '</td>' +
                                '<td>' + val['created_at'] + '</td>' +
                                '<td><button group_id="' + val['group_id'] + '" class="btn btn-primary btn-xs btnMovementDetail">Detay</button></td>' +
                                +'</tr>'
                            );

                        });

                    }

                    $('#tbodySentMessages').html('');
                    $.each(sms, function (key, val) {

                        let result = val['result'] == '1' ? '<b class="text-success">' + 'Başarılı' + '</b>' : '<b class="text-danger">' + 'Başarısız' + '</b>';

                        $('#tbodySentMessages').append(
                            '<tr>' +
                            '<td class="font-weight-bold">' + val['heading'] + '</td>' +
                            '<td class="font-weight-bold">' + val['subject'] + '</td>' +
                            '<td style="white-space: initial;">' + val['sms_content'] + '</td>' +
                            '<td>' + val['phone'] + '</td>' +
                            '<td class="font-weight-bold text-center">' + result + '</td>' +
                            +'</tr>'
                        )
                    });

                    $('#tbodyCargoCancellationApplications').html('');

                    if (movements.length == 0)
                        $('#tbodyCargoCancellationApplications').html('<tr><td colspan="5" class="text-center">Burda hiç veri yok.</td></tr>');
                    else {

                        $.each(cancellations, function (key, val) {

                            let background = "";
                            if (val['id'] == appointmentID) {
                                background = "bg-warning";
                                $('#cancelAppReason').val(val['application_reason']);
                                $('#cancelAppResult').val('' + val['confirm'] + '');
                                $('#canelAppResultDescription').val(val['description']);
                            }
                            val['approval_at'] = val['approval_at'] == null ? '' : val['approval_at'];
                            val['confirming_user_name_surname'] = val['confirming_user_name_surname'] == null ? '' : val['confirming_user_name_surname'];
                            val['confirming_user_display_name'] = val['confirming_user_display_name'] == null ? '' : ' (' + val['confirming_user_display_name'] + ')';

                            let confirm_status = '';

                            if (val['confirm'] == '0')
                                confirm_status = '<b class="text-info">' + 'Sonuç Bekliyor' + '</b>';
                            else if (val['confirm'] == '1')
                                confirm_status = '<b class="text-success">' + 'Onaylandı' + '</b>';
                            else if (val['confirm'] == '-1')
                                confirm_status = '<b class="text-danger">' + 'Reddedildi' + '</b>';

                            if (val['description'] == null)
                                val['description'] = "";

                            $('#tbodyCargoCancellationApplications').append(
                                '<tr class="' + background + '">' +
                                '<td class="font-weight-bold">' + cargo.tracking_no + '</td>' +
                                '<td class="font-weight-bold">' + val['name_surname'] + " (" + val['display_name'] + ")" + '</td>' +
                                '<td title="' + val['application_reason'] + '">' + val['application_reason'].substring(0, 35) + '</td>' +
                                '<td>' + confirm_status + '</td>' +
                                '<td title="' + val['description'] + '">' + val['description'].substring(0, 20) + '</td>' +
                                '<td class="font-weight-bold">' + val['confirming_user_name_surname'] + val['confirming_user_display_name'] + '</td>' +
                                '<td class="font-weight-bold text-center">' + val['approval_at'] + '</td>' +
                                '<td class="font-weight-bold text-center">' + val['created_at'] + '</td>' +
                                +'</tr>'
                            )
                        });
                    }


                    $('#btnCargoPrintBarcode').attr('tracking-no', cargo.id);

                }

                $('#ModalCargoDetails').unblock();
                return false;
            }).error(function (jqXHR, exception) {
                ajaxError(jqXHR.status);
            }).always(function () {
                $('#ModalCargoDetails').unblock();
            });

            $('#ModalAgencyDetail').modal();
        }

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

        $(document).on('click', '.btnMovementDetail', function () {

            let group_id = $(this).attr('group_id');

            $('#ModalMovementsDetail').modal();

            $('#modalBodyCargoMovementsDetails').block({
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


            $.ajax('/MainCargo/AjaxTransactions/GetCargoMovementDetails', {
                method: 'POST',
                data: {
                    _token: token,
                    group_id: group_id
                }
            }).done(function (response) {

                $('#tbodyCargoMovementDetails').html('');

                if (response.length == 0)
                    $('#tbodyCargoMovementDetails').html('<tr><td colspan="5" class="text-center">Burda hiç veri yok.</td></tr>');
                else {
                    $.each(response, function (key, val) {

                        let result = val['number_of_pieces'] == val['current_pieces'] ? 'text-success' : 'text-danger';

                        $('#tbodyCargoMovementDetails').append(
                            '<tr>' +
                            '<td>' + val['status'] + '</td>' +
                            '<td>' + val['info'] + '</td>' +
                            '<td class="text-dark font-weight-bold">' + val['part_no'] + '</td>' +
                            '<td>' + val['created_at'] + '</td>' +
                            +'</tr>'
                        );

                    });

                }


            }).error(function (jqXHR, exception) {
                ajaxError(jqXHR.status);
            }).always(function () {
                $('#modalBodyCargoMovementsDetails').unblock();
            });

        });

    </script>

@endsection

@section('modals')
    <!-- Large modal => Modal Cargo Details -->
    <div class="modal fade bd-example-modal-lg" id="ModalCargoDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Kargo Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto;overflow-x: hidden; max-height: 75vh;" id="ModalBodyUserDetail"
                     class="modal-body">

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
                                            <h5 id="titleTrackingNo" class="menu-header-title">###</h5>
                                            <h6 id="titleCreatorInfo" class="menu-header-subtitle">###/###</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <ul class="list-group list-group-flush">

                                <div class="main-card mb-12 card">
                                    <div class="card-header"><i
                                            class="header-icon pe-7s-box2 icon-gradient bg-plum-plate"> </i>Kargo
                                        Detayları
                                        <div class="btn-actions-pane-right">
                                            <div class="nav">
                                                <a data-toggle="tab" href="#tabCargoInfo"
                                                   class="btn-pill btn-wide btn btn-outline-alternate btn-sm show active">Kargo
                                                    Bilgileri</a>
                                                <a data-toggle="tab" href="#tabCargoMovements"
                                                   class="btn-pill btn-wide mr-1 ml-1 btn btn-outline-alternate btn-sm show ">Kargo
                                                    Hareketleri</a>
                                                <a data-toggle="tab" href="#tabCargoSMS"
                                                   class="btn-pill btn-wide btn btn-outline-alternate btn-sm show">SMS </a>
                                                <a data-toggle="tab" href="#tabCargoDetail"
                                                   class="btn-pill ml-1 btn-wide btn btn-outline-alternate btn-sm show">Detay</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="tabCargoInfo" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <table style="white-space: nowrap" id="AgencyCard"
                                                               class="TableNoPadding table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" id="titleBranch" colspan="2">
                                                                    Gönderici Bilgileri
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="static">Cari Kodu:</td>
                                                                <td id="senderCurrentCode"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Müşteri Tipi:</td>
                                                                <td id="senderCustomerType"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">TCKN/VKN:</td>
                                                                <td id="senderTcknVkn"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Ad Soyad:</td>
                                                                <td id="senderNameSurname"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Telefon:</td>
                                                                <td id="senderPhone"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">İl/İlçe:</td>
                                                                <td id="senderCityDistrict"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Mahalle:</td>
                                                                <td id="senderNeighborhood"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Adres:</td>
                                                                <td style="white-space: initial;"
                                                                    id="senderAddress"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <table style="white-space: nowrap" id="AgencyCard"
                                                               class="TableNoPadding table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" id="titleBranch" colspan="2">
                                                                    Alıcı Bilgileri
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="static">Cari Kodu:</td>
                                                                <td id="receiverCurrentCode"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Müşteri Tipi:</td>
                                                                <td id="receiverCustomerType"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">TCKN/VKN:</td>
                                                                <td id="receiverTcknVkn"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Ad Soyad:</td>
                                                                <td id="receiverNameSurname"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Telefon:</td>
                                                                <td id="receiverPhone"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">İl/İlçe:</td>
                                                                <td id="receiverCityDistrict"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Mahalle:</td>
                                                                <td id="receiverNeighborhood"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Adres:</td>
                                                                <td style="white-space: initial;"
                                                                    id="receiverAddress"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="divider"></div>
                                                <h3 class="text-dark text-center mb-4">Kargo Bilgileri</h3>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <table style="white-space: nowrap" id="AgencyCard"
                                                               class="TableNoPadding table table-bordered table-striped">
                                                            <tbody>
                                                            <tr>
                                                                <td class="static">Kargo Takip No:</td>
                                                                <td class="font-weight-bold text-dark"
                                                                    id="cargoTrackingNo"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Kayıt Tarihi:</td>
                                                                <td id="cargoCreatedAt"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Parça Sayısı:</td>
                                                                <td id="numberOfPieces"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">KG:</td>
                                                                <td id="cargoKg"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Hacim (m<sup>3</sup>):</td>
                                                                <td id="cubicMeterVolume"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Gönderi Türü:
                                                                <td id="cargoType"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Ödeme Tipi:</td>
                                                                <td id="paymentType"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Taşıyan:</td>
                                                                <td id="transporter"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Sistem:</td>
                                                                <td id="system"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Oluşturan:</td>
                                                                <td id="creatorUserInfo"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Müşteri Kodu:</td>
                                                                <td id="customerCode"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Statü:</td>
                                                                <td id="cargoStatus"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">İnsanlar İçin Statü:</td>
                                                                <td id="cargoStatusForHumen"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Kargo İçeriği:</td>
                                                                <td id="cargoContent"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Kargo İçerik Açıklaması:</td>
                                                                <td style="white-space: initial;"
                                                                    id="cargoContentEx"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <table style="white-space: nowrap" id="AgencyCard"
                                                               class="TableNoPadding table table-bordered table-striped">

                                                            <tbody>
                                                            <tr>
                                                                <td class="static">Tahislatlı:</td>
                                                                <td id="collectible"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Fatura Tutarı:</td>
                                                                <td id="collection_fee"
                                                                    class="font-weight-bold text-primary"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Desi:</td>
                                                                <td id="desi"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Çıkış Şube:</td>
                                                                <td class="text-primary" id="exitBranch"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">Çıkış Transfer:</td>
                                                                <td class="text-primary" id="exitTransfer"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">Varış Şube:</td>
                                                                <td class="text-alternate" id="arrivalBranch"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">Varış Transfer:</td>
                                                                <td class="text-alternate" id="arrivalTC"></td>
                                                            </tr>


                                                            <tr>
                                                                <td class="static">Mesafe (KM):</td>
                                                                <td id="distance"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">Mesafe Ücreti:</td>
                                                                <td class="font-weight-bold text-dark"
                                                                    id="distancePrice"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">Posta Hizmetleri Bedeli:</td>
                                                                <td class="font-weight-bold text-dark"
                                                                    id="postServicesPrice"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">Ağır Yük Taşıma Bedeli:</td>
                                                                <td class="font-weight-bold text-dark"
                                                                    id="heavyLoadCarryingCost"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">KDV (%18):</td>
                                                                <td class="font-weight-bold text-dark" id="kdv"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">Ek Hizmet Tutarı:</td>
                                                                <td class="font-weight-bold text-dark"
                                                                    id="addServiceFee"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="static">Hizmet Ücreti:</td>
                                                                <td class="font-weight-bold text-dark"
                                                                    id="serviceFee"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="static">Genel Toplam:</td>
                                                                <td class="font-weight-bold text-primary"
                                                                    id="totalFee"></td>
                                                            </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="divider"></div>
                                                <h3 class="text-dark text-center mb-4">Kargo Ek Hizmetleri</h3>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <table style="white-space: nowrap" id="AgencyCard"
                                                               class="TableNoPadding table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>Ek Hizmet</th>
                                                                <th>Maliyeti</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="tbodyCargoAddServices">
                                                            <tr>
                                                                <td>Adrese Teslim</td>
                                                                <td>8.5₺</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane show" id="tabCargoMovements" role="tabpanel">
                                                <h3 class="text-dark text-center mb-4">Kargo Hareketleri</h3>

                                                <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                                     class="cont">
                                                    <table style="white-space: nowrap" id="TableEmployees"
                                                           class="Table30Padding table table-striped mt-3">
                                                        <thead>
                                                        <tr>
                                                            <th>Durum</th>
                                                            <th>Bilgi</th>
                                                            <th>Parça</th>
                                                            <th>İşlem Zamanı</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbodyCargoMovements">
                                                        <tr>
                                                            <td colspan="4" class="text-center">Burda hiç veri yok.</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane show" id="tabCargoSMS" role="tabpanel">
                                                <h3 class="text-dark text-center mb-4">Gönderilen SMS'ler</h3>

                                                <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                                     class="cont">
                                                    <table id="TableEmployees"
                                                           class="Table30Padding table-bordered table table-striped mt-3">
                                                        <thead>
                                                        <tr>
                                                            <th>Başlık</th>
                                                            <th>Konu</th>
                                                            <th>Mesaj İçeriği</th>
                                                            <th>Numara</th>
                                                            <th>Gönd. Durumu</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbodySentMessages">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane show" id="tabCargoDetail" role="tabpanel">
                                                <h3 class="text-dark text-center mb-4">Kargo İptal Başvurusu</h3>

                                                <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                                     class="cont">
                                                    <table style="white-space: nowrap;" id="TableEmployees"
                                                           class="Table30Padding table-bordered table-hover table table-striped mt-3">
                                                        <thead>
                                                        <tr>
                                                            <th>Kargo Takip Numarası</th>
                                                            <th>Başvuru Yapan</th>
                                                            <th>İptal Nedeni</th>
                                                            <th>Sonuç</th>
                                                            <th>Açıklama</th>
                                                            <th>Sonuç Giren</th>
                                                            <th>Sonuç Giriş Zamanı</th>
                                                            <th>Başvuru Kayıt Zamanı</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbodyCargoCancellationApplications">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <li id="SetResult" class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-12">
                                                <div class="p-1">
                                                    <button
                                                        id="btnSetResult"
                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-info">
                                                        <i class="pe-7s-check text-info opacity-7 btn-icon-wrapper mb-2"> </i>
                                                        Sonuç Gir
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li id="CargoRestore" class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-12">
                                                <div class="p-1">
                                                    <button
                                                        id="btnCargoRestore"
                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                                        <i class="pe-7s-check text-s opacity-7 btn-icon-wrapper mb-2"> </i>
                                                        Kargoyu Geri Yükle
                                                    </button>
                                                </div>
                                            </div>
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

    <!-- Large modal => Modal Movements Detail -->
    <div class="modal fade bd-example-modal-lg" id="ModalMovementsDetail" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Hareket Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyCargoMovementsDetails" style="overflow-x: hidden; max-height: 75vh;"
                     class="modal-body">

                    <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                         class="cont">
                        <table style="white-space: nowrap" id="TableEmployees"
                               class="Table30Padding table table-striped mt-3">
                            <thead>
                            <tr>
                                <th>Durum</th>
                                <th>Bilgi</th>
                                <th>Parça Numarası</th>
                                <th>İşlem Zamanı</th>
                            </tr>
                            </thead>
                            <tbody id="tbodyCargoMovementDetails">
                            <tr>
                                <td colspan="4" class="text-center">Burda hiç veri yok.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
@endsection
