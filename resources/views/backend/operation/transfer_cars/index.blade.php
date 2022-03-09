@extends('backend.layout')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <style>
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
            top: 6px;
            left: 5px;
        }

        .modal-data {
            font-weight: bold;
        }
    </style>
@endpush

@section('title', 'Aktarma Araçları')
@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-car icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div> Cumhuriyet Kargo Geneli Tüm Araçlar
                        <div class="page-title-subheading"><b>Cumhuriyet Kargo</b> geneli tüm araçları
                            bu modül üzerinden görüntüleyebilir, işlem yapabilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('TransferCars.create') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Aktarma Aracı Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-car mr-3 text-muted opacity-6"> </i>Tüm Araçları
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
                            <label for="filter_marka">Marka:</label>
                            <input type="text" id="filter_marka"
                                   class="form-control niko-filter form-control-sm niko-select-filter">
                        </div>
                        <div class="col-md-3">
                            <label for="filter_model">Model:</label>
                            <input type="text" id="filter_model"
                                   class="form-control niko-filter form-control-sm niko-select-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="filter_plaka">Plaka:</label>
                            <input type="text" id="filter_plaka"
                                   class="form-control niko-filter form-control-sm niko-select-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="filter_hat">Hat:</label>
                            <select name="" id="filter_hat" class="form-control niko-select-filter form-control-sm">
                                <option value="">Seçiniz</option>
                                <option value="Anahat">Anahat</option>
                                <option value="Arahat">Arahat</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_arac_kapasitesi">Araç Kapasitesi:</label>
                            <select name="filter_arac_kapasitesi" required="" id="filter_arac_kapasitesi"
                                    class="form-control niko-select-filter form-control-sm">
                                <option value=""> Seçiniz</option>
                                <option value="Panelvan">Panelvan</option>
                                <option value="Kamyonet">Kamyonet</option>
                                <option value="6 Teker Kamyonet">6 Teker Kamyonet</option>
                                <option value="10 Teker Kamyon">10 Teker Kamyon</option>
                                <option value="40 Ayak Kamyon">40 Ayak Kamyon</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_agency">Acente:</label>
                            <select id="filter_agency"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option value="{{$key->id}}">{{$key->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_Aktarma">Aktarma:</label>
                            <select id="filter_Aktarma"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['transshipment_centers'] as $key)
                                    <option value="{{$key->id}}">{{$key->tc_name . ' ('.$key->type.') T.M.'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_car_type">Araç Tipi:</label>
                            <input type="text" id="filter_car_type"
                                   class="form-control niko-filter form-control-sm niko-select-filter">
                        </div>

                    </div>
                </form>
            </div>

            <div class="card-body">
                <table style="width:100%" id="AgenciesTable"
                       class="align-middle mb-0 table Table30Padding table-borderless table-striped NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Marka</th>
                        <th>Model</th>
                        <th>Plaka</th>
                        <th>Araç Tipi </th>
                        <th>Ait Oldugu Birimi </th>
                        <th>Onay Durumu </th>
                        <th>Hat</th>
                        <th>Statü</th>
                        <th>Şoför Adı</th>
                        <th>Şoför İletişim</th>
                        <th>Oluşturan</th>
                        <th>Kayıt Tarihi</th>
                        <th class="text-center">İşlem</th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>

                    <tfoot>
                    <tr>
                        <th>Marka</th>
                        <th>Model</th>
                        <th>Plaka</th>
                        <th>Araç Tipi </th>
                        <th>Ait Oldugu Birimi </th>
                        <th>Onay Durumu </th>
                        <th>Hat</th>
                        <th>Statü</th>
                        <th>Şoför Adı</th>
                        <th>Şoför İletişim</th>
                        <th>Oluşturan</th>
                        <th>Kayıt Tarihi</th>
                        <th class="text-center">İşlem</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
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
                lengthMenu: dtLengthMenu,
                order: [
                    [11, 'desc']
                ],
                language: dtLanguage,
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',

                buttons: [
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18]
                        },
                        title: "CK - Aktarma Araçları"
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
                    url: '{!! route('transfer.car.all') !!}',
                    data: function (d) {
                        d.marka = $('#filter_marka').val();
                        d.model = $('#filter_model').val();
                        d.plaka = $('#filter_plaka').val();
                        d.hat = $('#filter_hat').val();
                        d.aracKapasitesi = $('#filter_arac_kapasitesi').val();
                        d.agency = $('#filter_agency').val();
                        d.aktarma = $('#filter_Aktarma').val();
                        d.carType = $('#filter_car_type').val();
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
                    {data: 'marka', name: 'marka'},
                    {data: 'model', name: 'model'},
                    {data: 'plaka', name: 'plaka'},
                    {data: 'car_type', name: 'car_type'},
                    {data: 'ait_oldugu_birimi', name: 'ait_oldugu_birimi'},
                    {data: 'confirmation_status', name: 'confirmation_status'},
                    {data: 'hat', name: 'hat'},
                    {data: 'car_status', name: 'car_status'},
                    {data: 'sofor_ad', name: 'sofor_ad'},
                    {data: 'sofor_telefon', name: 'sofor_telefon'},
                    {data: 'name_surname', name: 'name_surname'},
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


        $(document).on('click', '.btn_car_details', function () {
            detailsID = $(this).prop('id');
            carInfo($(this).prop('id'));
        });

        var array = new Array();

        function carInfo(id) {

            $('#ModalCarDetails').modal();

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

            $.ajax('{{route('getTransferCars')}}', {
                method: 'POST',
                data: {
                    _token: token,
                    carID: id
                },
                cache: false
            }).done(function (response) {

                let cars = response.cars;

                $('#tdPlaka').html(cars.plaka);
                $('#plaka').html(cars.plaka);
                $('#marka').html(cars.marka);
                $('#model').html(cars.model);
                $('#modelYili').html(cars.model_yili);
                $('#aracKapasitesi').html(cars.arac_kapasitesi);
                $('#status').html(cars.status == 1 ? 'Aktif' : 'Pasif');
                $('#aracTakipSistemi').html(cars.arac_takip_sistemi);
                $('#hat').html(cars.hat);
                $('#cikisAktarma').html(cars.cikis_akt + " T.M.");
                $('#varisAktarma').html(cars.varis_akt + " T.M.");
                $('#ugradigiAktarmalar').html(response.aktarmalar);
                $('#muayeneBaslangicBitisTarihi').html(cars.muayene_baslangic_tarihi + " - " + cars.muayene_bitis_tarihi);
                $('#muayeneBitimiKalanGun').html(cars.muayene_kalan_sure);
                $('#sigortaBaslangicBitisTarihi').html(cars.trafik_sigortasi_baslangic_tarihi + " - " + cars.trafik_sigortasi_bitis_tarihi);
                $('#sigortaBitimiKalanGun').html(cars.sigorta_kalan_sure);
                $('#kayitTarihi').html(cars.created_at);
                $('#car_type').html(cars.car_type);

                $('#soforAdi').html(cars.sofor_ad);
                $('#soforIletisim').html(cars.sofor_telefon);
                $('#soforAders').html(cars.sofor_adres);
                $('#aracSahibiAdi').html(cars.arac_sahibi_ad);
                $('#aracSahibiIletisim').html(cars.arac_sahibi_telefon);
                $('#aracSahibiAders').html(cars.arac_sahibi_adres);
                $('#aracSahibiYakiniAdi').html(cars.arac_sahibi_yakini_ad);
                $('#aracSahibiYakiniIletisim').html(cars.arac_sahibi_yakini_telefon);
                $('#aracSahibiYakiniAders').html(cars.arac_sahibi_yakini_adres);

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


        $(document).on('click', '#btnPrintModal', function () {
            printWindow('#ModalBodyUserDetail', "CKG-Sis - Aktarma Araçları");
        });

    </script>
@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalCarDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Araç Detayları</h5>
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
                                    <div class="menu-header-content">
                                        <h5 id="plaka" class="menu-header-title text-center"></h5>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: auto" class="cont">

                                            {{-- ARAÇ DETAYLARI --}}
                                            <table id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="4">Araç
                                                        Detayları
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Plaka:</td>
                                                    <td class="modal-data" id="tdPlaka"></td>
                                                    <td class="static">Marka:</td>
                                                    <td class="modal-data" id="marka"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Model</td>
                                                    <td class="modal-data" id="model"></td>
                                                    <td class="static">Model Yılı</td>
                                                    <td class="modal-data" id="modelYili"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Araç Kapasitesi</td>
                                                    <td class="modal-data" id="aracKapasitesi"></td>
                                                    <td class="static">Status</td>
                                                    <td class="modal-data" id="status"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Araç Takip Sistemi</td>
                                                    <td class="modal-data" id="aracTakipSistemi"></td>
                                                    <td class="static">hat</td>
                                                    <td class="modal-data" id="hat"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Çıkış Aktarma</td>
                                                    <td class="modal-data" id="cikisAktarma"></td>
                                                    <td class="static">Varış Aktarma</td>
                                                    <td class="modal-data" id="varisAktarma"></td>
                                                </tr>
                                                <tr aria-rowspan="2">
                                                    <td class="static">Uğradığı Aktarmalar</td>
                                                    <td class="modal-data" colspan="3" id="ugradigiAktarmalar"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Kayıt Tarihi</td>
                                                    <td class="modal-data" id="kayitTarihi"></td>
                                                    <td class="static">Araç Tipi</td>
                                                    <td id="car_type"></td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            {{-- ŞOFÖR İLETİŞİM --}}
                                            <table id="AgencyCard"
                                                   class="TableNoPadding table mt-4 table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="4">Şoför
                                                        İletişim
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Şoför Adı</td>
                                                    <td class="modal-data" id="soforAdi"></td>
                                                    <td class="static">Şoför İletişim</td>
                                                    <td class="modal-data" id="soforIletisim"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Şoför Adres</td>
                                                    <td class="modal-data" colspan="3" id="soforAders"></td>
                                                </tr>

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </li>

                                <ul class="list-group list-group-flush">
                                    <li class="p-0 list-group-item">
                                        <div class="grid-menu grid-menu-2col">
                                            <div class="no-gutters row">

                                                <div class="col-sm-12">
                                                    <div class="p-1">
                                                        <button id="btnPrintModal"
                                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary">
                                                            <i class="lnr-printer text-primary opacity-7 btn-icon-wrapper mb-2">
                                                            </i>
                                                            Yazdır
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </li>
                                </ul>

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
