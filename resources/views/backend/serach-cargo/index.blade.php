@extends('backend.layout')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <style>
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
            top: 6px;
            left: 5px;
        }
    </style>
@endpush

@section('title', 'Ana Menü')
@section('content')

    <div class="app-main__inner">
        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-box2 mr-3 text-muted opacity-6"> </i>Kestiğiniz Kargolar
                </div>
                <div class="btn-actions-pane-right actions-icon-btn">
                    <div class="btn-group dropdown">
                        <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i
                                class="pe-7s-menu btn-icon-wrapper"></i></button>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">

                            <div class="p-3 text-right">
                                <button id="btnClearFilter" class="mr-2 btn-shadow btn-sm btn btn-link">Filtreyi
                                    Temizle
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="receiverCode">Kargo Takip No:</label>
                            <input type="text" data-inputmask="'mask': '99999 99999 99999'"
                                   placeholder="_____ _____ _____" type="text" id="trackingNo"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>
                        <div class="col-md-3">
                            <label for="receiverCity">Kargo Tipi:</label>
                            <select id="cargoType"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                <option value="Koli">Koli</option>
                                <option value="Dosya">Dosya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="startDate">Başlangıç Tarih:</label>
                            <input type="datetime-local" id="startDate" value="{{ date('Y-m-d') }}T00:00"
                                   class="form-control form-control-sm  niko-select-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="finishDate">Bitiş Tarihi:</label>
                            <input type="datetime-local" id="finishDate" value="{{ date('Y-m-d') }}T23:59"
                                   class="form-control form-control-sm  niko-select-filter">
                        </div>
                    </div>


                    <div class="row pt-2">

                        {{--                        <div class="col-md-2">--}}
                        {{--                            <label for="receiverCurrentCode">Alıcı Cari Kod:</label>--}}
                        {{--                            <input type="text" data-inputmask="'mask': '999 999 999'"--}}
                        {{--                                   placeholder="___ ___ ___" type="text" id="receiverCurrentCode"--}}
                        {{--                                   class="form-control input-mask-trigger form-control-sm niko-filter">--}}
                        {{--                        </div>--}}

                        <div class="col-md-3">
                            <label for="receiverName">Alıcı Adı:</label>
                            <input type="text" id="receiverName" class="form-control niko-filter form-control-sm">
                        </div>

                        <div class="col-md-3">
                            <label for="receiverCity">Alıcı İl:</label>
                            <select id="receiverCity"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['cities'] as $key)
                                    <option data="{{$key->city_name}}" value="{{$key->id}}">{{$key->city_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="receiverDistrict">Alıcı İlçe:</label>
                            <select id="receiverDistrict"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="receiverPhone" class="">Alıcı Telefon (Cep):</label>
                                <input name="receiverPhone" id="receiverPhone" required
                                       data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text"
                                       value="{{ old('phone') }}"
                                       class="form-control form-control-sm input-mask-trigger niko-filter">
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">

                        {{--                        <div class="col-md-2">--}}
                        {{--                            <label for="senderCurrentCode">Gönderici Cari Kod:</label>--}}
                        {{--                            <input type="text" data-inputmask="'mask': '999 999 999'"--}}
                        {{--                                   placeholder="___ ___ ___" type="text" id="senderCurrentCode"--}}
                        {{--                                   class="form-control input-mask-trigger form-control-sm niko-filter">--}}
                        {{--                        </div>--}}

                        <div class="col-md-3">
                            <label for="senderName">Gönderici Adı:</label>
                            <input type="text" id="senderName"
                                   class="form-control niko-filter form-control-sm niko-select-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="senderCity">Gönderici İl:</label>
                            <select id="senderCity"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['cities'] as $key)
                                    <option value="{{$key->id}}" data="{{$key->city_name}}">{{$key->city_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="senderDistrict">Gönderici İlçe:</label>
                            <select id="senderDistrict"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="senderPhone" class="">Gönderici Telefon (Cep):</label>
                                <input name="senderPhone" id="senderPhone" required
                                       data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text"
                                       value="{{ old('phone') }}"
                                       class="form-control form-control-sm input-mask-trigger niko-filter">
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <div class="col">
                            <label for="filterByDate">Tarihe göre ara</label>
                            <input type="checkbox" id="filterByDate" name="filterByDate" class="niko-filter">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-bordered table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>KTNO</th>
                        <th>Gönderici Adı</th>
                        <th>Gönderici İl</th>
                        <th>Alıcı Adı</th>
                        <th>Alıcı İl</th>
                        <th>Alıcı İlçe</th>
                        <th>Alıcı Adres</th>
                        <th>Kargo Tipi</th>
                        <th>Ödeme Tipi</th>
                        <th>Ücret</th>
                        <th>Tahsilat Tipi</th>
                        <th>Tahilatlı</th>
                        <th>Fatura Tutarı</th>
                        <th>Statü</th>
                        <th>Durum</th>
                        <th>Taşıyan</th>
                        <th>Sistem</th>
                        <th>Oluşturan Acente</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>KTNO</th>
                        <th>Gönderici Adı</th>
                        <th>Gönderici İl</th>
                        <th>Alıcı Adı</th>
                        <th>Alıcı İl</th>
                        <th>Alıcı İlçe</th>
                        <th>Alıcı Adres</th>
                        <th>Kargo Tipi</th>
                        <th>Ödeme Tipi</th>
                        <th>Ücret</th>
                        <th>Tahsilat Tipi</th>
                        <th>Tahilatlı</th>
                        <th>Fatura Tutarı</th>
                        <th>Statü</th>
                        <th>Durum</th>
                        <th>Taşıyan</th>
                        <th>Sistem</th>
                        <th>Oluşturan Acente</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>


        {{--Statistics--}}
        <div>
        </div>
    </div>
@endsection

@section('js')

    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <script src="/backend/assets/scripts/city-districts-point.js"></script>

    <script>
        $(document).on('change', '#receiverCity', function () {
            getDistricts('#receiverCity', '#receiverDistrict');
        });

        $(document).on('change', '#senderCity', function () {
            getDistricts('#senderCity', '#senderDistrict');
        });
    </script>


    <script>
        var oTable;
        var detailsID = null;
        // and The Last Part: NikoStyle
        $(document).ready(function () {
            $('#agency').select2();
            $('#creatorUser').select2();

            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [17, 'desc'],
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

                select: {
                    style: 'multi',
                    selector: 'td:nth-child(2)'
                },
                buttons: [
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18]
                        },
                        title: "CK - Kesilen Kargolar"
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
                    url: '{!! route('mainCargo.getGlobalCargoes') !!}',
                    data: function (d) {
                        d.trackingNo = $('#trackingNo').val();
                        d.cargoType = $('#cargoType').val();
                        d.receiverCurrentCode = $('#receiverCurrentCode').val();
                        d.senderCurrentCode = $('#senderCurrentCode').val();
                        d.startDate = $('#startDate').val();
                        d.finishDate = $('#finishDate').val();
                        d.receiverName = $('#receiverName').val();
                        d.receiverCity = $('option:selected', '#receiverCity').attr('data') == undefined ? '' : $('option:selected', '#receiverCity').attr('data');
                        d.receiverDistrict = $('#receiverDistrict').val();
                        d.receiverPhone = $('#receiverPhone').val();
                        d.senderName = $('#senderName').val();
                        d.senderCity = $('option:selected', '#senderCity').attr('data') == undefined ? '' : $('option:selected', '#senderCity').attr('data');
                        d.senderDistrict = $('#senderDistrict').val();
                        d.senderPhone = $('#senderPhone').val();
                        d.filterByDAte = $('#filterByDate').prop('checked');
                    },
                    error: function (xhr, error, code) {

                        if (xhr.status == 429) {
                            ToastMessage('error', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        } else if (xhr.status == 509) {
                            ToastMessage('error', 'Tarih aralığı en fazla 60 gün olabilir!', 'Hata');
                        }
                    },
                    complete: function () {
                        ToastMessage('info', 'Tamamlandı!', 'Bilgi');
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'sender_city', name: 'sender_city'},
                    {data: 'receiver_name', name: 'receiver_name'},
                    {data: 'receiver_city', name: 'receiver_city'},
                    {data: 'receiver_district', name: 'receiver_district'},
                    {data: 'receiver_address', name: 'receiver_address'},
                    {data: 'cargo_type', name: 'cargo_type'},
                    {data: 'payment_type', name: 'payment_type'},
                    {data: 'total_price', name: 'total_price'},
                    {data: 'collectible', name: 'collectible'},
                    {data: 'collectible', name: 'collectible'},
                    {data: 'collection_fee', name: 'collection_fee'},
                    {data: 'status', name: 'status'},
                    {data: 'status_for_human', name: 'status_for_human'},
                    {data: 'transporter', name: 'transporter'},
                    {data: 'system', name: 'system'},
                    {data: 'agency_name', name: 'agency_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'edit', name: 'edit'},
                ],

            });
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

        $('#btnClearFilter').click(function () {
            $('#search-form').trigger("reset");
            $('#select2-creatorUser-container').text('Seçiniz');
            $('#select2-agency-container').text('Seçiniz');
            drawDT();
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


        $(document).on('click', '.user-detail', function () {
            $('#ModalUserDetail').modal();

            $('#ModalBodyUserDetail.modal-body').block({
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

            detailsID = $(this).prop('id');
            userInfo($(this).prop('id'));
        });

        var array = new Array();

        function userInfo(user) {
            $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: user
                },
                cache: false
            }).done(function (response) {
                let currentStatus, currentConfirmed;
                let creatorDisplayName = '<span class="text-primary font-weight-bold">(' + response.current.creator_display_name + ')</span>';

                if (response.current.status == "1")
                    currentStatus = '<span class="text-success">(Aktif Hesap)</span>';
                else
                    currentStatus = '<span class="text-danger">(Pasif Hesap)</span>';

                if (response.current.confirmed == "1") {
                    currentConfirmed = '<span class="text-success font-weight-bold">Onaylandı</span>';
                    $('#divConfirmCurrent').hide();
                } else {
                    currentConfirmed = '<span class="text-danger font-weight-bold">Onay Bekliyor</span>';
                    $('#divConfirmCurrent').show();
                }

                let city = response.current.city + "/",
                    district = response.current.district + " ",
                    neighborhood = response.current.neighborhood + " ",
                    street = response.current.street != '' && response.current.street != null ? response.current.street + " CAD. " : "",
                    street2 = response.current.street2 != '' && response.current.street2 != null ? response.current.street2 + " SK. " : "",
                    buildingNo = "NO:" + response.current.building_no + " ",
                    door = "D:" + response.current.door_no + " ",
                    floor = "KAT:" + response.current.floor + " ",
                    addressNote = "(" + response.current.address_note + ")";

                let fullAddress = neighborhood + street + street2 + buildingNo + floor + door + addressNote;


                $('#agencyName').html(response.current.name);
                $('#agencyCityDistrict').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
                $('#titleBranch').html(response.current.name + ' - ÖZET ' + currentStatus);

                $('#currentCategory').html(response.current.category);
                $('#modalCurrentCode').html(response.current.current_code);
                $('#nameSurnameCompany').html(response.current.name);
                $('#currentAgency').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
                $('#taxOffice').html(response.current.tax_administration);
                $('#tcknVkn').html(response.current.tckn);
                $('#phone').html(response.current.phone);
                $('#cityDistrict').html(response.current.city + "/" + response.current.district);
                $('#address').html(fullAddress);
                $('#gsm').html(response.current.gsm);
                $('#gsm2').html(response.current.gsm2);
                $('#phone2').html(response.current.phone2);
                $('#email').html(response.current.email);
                $('#website').html(response.current.website);
                $('#regDate').html(response.current.created_at);
                $('#dispatchCityDistrict').html(response.current.dispatch_city + "/" + response.current.dispatch_district);
                $('#dispatchAddress').html(response.current.dispatch_adress);
                $('#iban').html(response.current.iban);
                $('#bankOwner').html(response.current.bank_owner_name);
                $('#contractStartDate').html(response.current.contract_start_date);
                $('#contractEndDate').html(response.current.contract_end_date);
                $('#reference').html(response.current.reference);
                $('#currentCreatorUser').html(response.current.creator_user_name + " " + creatorDisplayName);
                $('#currentFilePrice').html(response.price.file_price + "₺");
                $('#current1_5Desi').html(response.price.d_1_5 + "₺");
                $('#current6_10Desi').html(response.price.d_6_10 + "₺");
                $('#current11_15Desi').html(response.price.d_11_15 + "₺");
                $('#current16_20Desi').html(response.price.d_16_20 + "₺");
                $('#current21_25Desi').html(response.price.d_21_25 + "₺");
                $('#current26_30Desi').html(response.price.d_26_30 + "₺");
                $('#currentAmountOfIncrease').html(response.price.amount_of_increase + "₺");
                $('#currentCollectPrice').html(response.price.collect_price + "₺");
                $('#collectAmountOfIncrease').html("%" + response.price.collect_amount_of_increase);
                $('#currentConfirmed').html(currentConfirmed);

                $('.modal-body').unblock();
                return false;
            });

            $('#ModalAgencyDetail').modal();
        }

        $(document).on('click', '#btnConfirmCurrent', function () {
            $('#btnConfirmCurrent').prop('disabled', true);

            $.ajax('/SenderCurrents/AjaxTransaction/ConfirmCurrent', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID
                }
            }).done(function (response) {

                if (response.status == -1)
                    ToastMessage('error', response.message, '');
                else if (response.status == 1) {
                    ToastMessage('success', 'İşlem başarılı, cari hesabı onaylandı!', 'İşlem Başarılı!');
                    userInfo(detailsID);
                    $('#divConfirmCurrent').hide();
                }


                $('.modalEnabledDisabled.modal-body').unblock();
            }).error(function (jqXHR, response) {
                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
            }).always(function () {
                $('#btnConfirmCurrent').prop('disabled', false);
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


            $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID
                }
            }).done(function (response) {

                $('#userNameSurname').val(response.current.name);
                $('#accountStatus').val(response.current.status);

                $('.modalEnabledDisabled.modal-body').unblock();
            });
        });

        $(document).on('click', '#btnSaveStatus', function () {

            ToastMessage('warning', 'İstek alındı, lütfen bekleyiniz.', 'Dikkat!');
            $.ajax('/SenderCurrents/AjaxTransaction/ChangeStatus', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID,
                    status: $('#accountStatus').val(),
                }
            }).done(function (response) {
                if (response.status == 1) {

                    $('#ModalBodyUserDetail.modal-body').block({
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
        $(document).on('click', '#btnCurrentPerformanceReport', function () {
            ToastMessage('warning', 'Cari performans raporu çok yakında!', 'Bilgi');
        });

        $(document).on('click', '#btnPrintModal', function () {
            printWindow('#ModalBodyUserDetail', "CK - " + $('#agencyName').text());
        });

    </script>
@endsection


@section('modals')

@endsection
