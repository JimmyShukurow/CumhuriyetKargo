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
                    <div> Aktarma Araçları
                        <div class="page-title-subheading">Bu modül üzerinden sistemdeki tüm aktarma araçları
                            listleyebilir, işlem yapablirsiniz.
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
                        class="header-icon pe-7s-car mr-3 text-muted opacity-6"> </i>Tüm Aktarma Araçları
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
                            <label for="filter_cikisAktarma">Çıkış Aktarma:</label>
                            <select id="filter_cikisAktarma"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['transshipment_centers'] as $key)
                                    <option value="{{$key->id}}">{{$key->tc_name . ' ('.$key->type.') T.M.'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_varisAktarma">Varış Aktarma:</label>
                            <select id="filter_varisAktarma"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['transshipment_centers'] as $key)
                                    <option value="{{$key->id}}">{{$key->tc_name . ' ('.$key->type.') T.M.'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_soforIletisim">Şoför İletişim:</label>
                            <input type="text" data-inputmask="'mask': '(999) 999 99 99'"
                                   placeholder="___ ___ __ __" type="text" id="filter_soforIletisim"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                    </div>
                </form>
            </div>

            <div class="card-body">
                <table id="AgenciesTable"
                       class="align-middle mb-0 table Table30Padding table-borderless table-striped NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Marka</th>
                        <th>Model</th>
                        <th>Plaka</th>
                        <th>Hat</th>
                        <th>Kapasite (KG)</th>
                        <th>Çıkış Akt.</th>
                        <th>Varış Akt.</th>
                        <th>Muayene Kalan Gün</th>
                        <th>Sigorta Kalan Gün</th>
                        <th>Aylık Kira Bedeli (KDV Dahil)</th>
                        <th>KDV Hariç Hakediş</th>
                        <th>1 Sefer Kira Maliyeti</th>
                        <th>Yakıt Oranı</th>
                        <th>Tur KM</th>
                        <th>Sefer KM</th>
                        <th>1 Sefer Yakıt Maliyeti</th>
                        <th>Sefer Maliyeti (Yakıt+Kira)</th>
                        <th>Aylık Yakıt Maliyeti</th>
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
                        <th>Hat</th>
                        <th>Kapasite (KG)</th>
                        <th>Çıkış Akt.</th>
                        <th>Varış Akt.</th>
                        <th>Muayene Kalan Gün</th>
                        <th>Sigorta Kalan Gün</th>
                        <th>Aylık Kira Bedeli (KDV Dahil)</th>
                        <th>KDV Hariç Hakediş</th>
                        <th>1 Sefer Kira Maliyeti</th>
                        <th>Yakıt Oranı</th>
                        <th>Tur KM</th>
                        <th>Sefer KM</th>
                        <th>1 Sefer Yakıt Maliyeti</th>
                        <th>Sefer Maliyeti (Yakıt+Kira)</th>
                        <th>Aylık Yakıt Maliyeti</th>
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
                    [20, 'desc']
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
                        d.cikisAktarma = $('#filter_cikisAktarma').val();
                        d.varisAktarma = $('#filter_varisAktarma').val();
                        d.soforIletisim = $('#filter_soforIletisim').val();
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
                    {data: 'hat', name: 'hat'},
                    {data: 'arac_kapasitesi', name: 'arac_kapasitesi'},
                    {data: 'cikis_aktarma', name: 'cikis_aktarma'},
                    {data: 'varis_aktarma', name: 'varis_aktarma'},

                    {data: 'muayene_kalan_sure', name: 'muayene_kalan_sure'},
                    {data: 'sigorta_kalan_sure', name: 'sigorta_kalan_sure'},

                    {data: 'aylik_kira_bedeli', name: 'aylik_kira_bedeli'},
                    {data: 'kdv_haric_hakedis', name: 'kdv_haric_hakedis'},
                    {data: 'bir_sefer_kira_maliyeti', name: 'bir_sefer_kira_maliyeti'},
                    {data: 'yakit_orani', name: 'yakit_orani'},
                    {data: 'tur_km', name: 'tur_km'},
                    {data: 'sefer_km', name: 'sefer_km'},
                    {data: 'bir_sefer_yakit_maliyeti', name: 'bir_sefer_yakit_maliyeti'},
                    {data: 'hakedis_arti_mazot', name: 'hakedis_arti_mazot'},
                    {data: 'aylik_yakit', name: 'aylik_yakit'},
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
                $('#marka').html(cars.marka);
                $('#model').html(cars.model);
                $('#modelYili').html(cars.model_yili);
                $('#aracKapasitesi').html(cars.arac_kapasitesi);
                $('#tonaj').html(cars.tonaj);
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

                $('#aylikKiraBedeli').html("₺" + getDotter(cars.aylik_kira_bedeli));
                $('#yakitOrani').html("%" + cars.yakit_orani);
                $('#turKm').html(getDotter(cars.tur_km));
                $('#seferKM').html(cars.sefer_km);
                $('#aylikYakit').html("₺" + getDotter(cars.aylik_yakit));
                $('#kdvHaricHakedis').html("₺" + getDotter(cars.kdv_haric_hakedis));
                $('#birSeferKiraMaliyeti').html("₺" + getDotter(cars.bir_sefer_kira_maliyeti));
                $('#birSeferYakitMaliyeti').html("₺" + getDotter(cars.bir_sefer_yakit_maliyeti));
                $('#hakedisArtiMazot').html("₺" + getDotter(cars.hakedis_arti_mazot));
                $('#seferMaliyeti').html("₺" + getDotter(cars.sefer_maliyeti));

                $('#soforAdi').html(cars.sofor_ad);
                $('#soforIletisim').html(cars.sofor_telefon);
                $('#soforAders').html(cars.sofor_adres);
                $('#aracSahibiAdi').html(cars.arac_sahibi_ad);
                $('#aracSahibiIletisim').html(cars.arac_sahibi_telefon);
                $('#aracSahibiAders').html(cars.arac_sahibi_adres);
                $('#aracSahibiYakiniAdi').html(cars.arac_sahibi_yakini_ad);
                $('#aracSahibiYakiniIletisim').html(cars.arac_sahibi_yakini_telefon);
                $('#aracSahibiYakiniAders').html(cars.arac_sahibi_yakini_adres);

                $('#stepne').html(cars.stepne);
                $('#kriko').html(cars.kriko);
                $('#Zincir').html(cars.zincir);
                $('#bijonAnahtari').html(cars.bijon_anahtari);
                $('#reflektor').html(cars.reflektor);
                $('#yanginTupu').html(cars.yangin_tupu);
                $('#ilkYardimCantasi').html(cars.ilk_yardim_cantasi);
                $('#seyyarLamba').html(cars.seyyar_lamba);
                $('#cekmeHalati').html(cars.cekme_halati);
                $('#giydirme').html(cars.giydirme);
                $('#korNoktaUyarisi').html(cars.kor_nokta_uyarisi);
                $('#hataBildirimHatti').html(cars.hata_bildirim_hatti);

                $('#muayeneEvragi').html(cars.sigorta_belgesi);
                $('#sigortaBelgesi').html(cars.sigorta_belgesi);
                $('#soforEhliyet').html(cars.sofor_ehliyet);
                $('#srcBelgesi').html(cars.src_belgesi);
                $('#ruhsatEkspertizRaporu').html(cars.ruhsat_ekspertiz_raporu);
                $('#tasimaBelgesi').html(cars.tasima_belgesi);
                $('#soforAdliSicilKaydi').html(cars.sofor_adli_sicil_kaydi);
                $('#aracSahibiSicilKaydi').html(cars.arac_sahibi_sicil_kaydi);
                $('#soforYakiniIkametgahBelgesi').html(cars.sofor_yakini_ikametgah_belgesi);

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
                                        <h5 id="plaka" class="menu-header-title text-center">34HV4186</h5>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: auto" class="cont">

                                            {{-- ARAÇ DETAYLARI --}}
                                            <table style="white-space: nowrap;" id="AgencyCard"
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
                                                    <td class="static">Tonaj</td>
                                                    <td class="modal-data" id="tonaj"></td>
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
                                                    <td class="static">Muayene Baş.-Bit. Tarhi</td>
                                                    <td class="modal-data" id="muayeneBaslangicBitisTarihi"></td>
                                                    <td class="static">Muayene Bitimi Kalan Gün</td>
                                                    <td class="modal-data" id="muayeneBitimiKalanGun"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Sigorta Baş.-Bit. Tarhi</td>
                                                    <td class="modal-data" id="sigortaBaslangicBitisTarihi"></td>
                                                    <td class="static">Sigorta Bitimi Kalan Gün</td>
                                                    <td class="modal-data" id="sigortaBitimiKalanGun"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Kayıt Tarihi</td>
                                                    <td class="modal-data" id="kayitTarihi"></td>
                                                    <td class="static"></td>
                                                    <td id=""></td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            {{-- HESAPLAMALAR --}}
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table mt-4 table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="4">Hesaplamalar
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Aylık Kira Bedeli</td>
                                                    <td class="modal-data" id="aylikKiraBedeli"></td>
                                                    <td class="static">Yakıt Oranı</td>
                                                    <td class="modal-data" id="yakitOrani"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Tur KM</td>
                                                    <td class="modal-data" id="turKm"></td>
                                                    <td class="static">Sefer KM</td>
                                                    <td class="modal-data" id="seferKM"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Aylık Yakıt</td>
                                                    <td class="modal-data" id="aylikYakit"></td>
                                                    <td class="static">KDV Hariç Hakediş</td>
                                                    <td class="modal-data" id="kdvHaricHakedis"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">1 Sefer Kira Maliyeti</td>
                                                    <td class="modal-data" id="birSeferKiraMaliyeti"></td>
                                                    <td class="static">1 Sefer Yakıt Maliyeti</td>
                                                    <td class="modal-data" id="birSeferYakitMaliyeti"></td>
                                                </tr>


                                                <tr>
                                                    <td class="static">Sefer Maliyeti</td>
                                                    <td class="modal-data" id="seferMaliyeti"></td>
                                                    <td class="static">Hakediş + Mazot</td>
                                                    <td class="modal-data" id="hakedisArtiMazot"></td>
                                                </tr>

                                                </tbody>
                                            </table>

                                            {{-- ŞOFÖR İLETİŞİM --}}
                                            <table style="white-space: nowrap" id="AgencyCard"
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

                                                <tr>
                                                    <td class="static">Araç Sahibi Adı</td>
                                                    <td class="modal-data" id="aracSahibiAdi"></td>
                                                    <td class="static">Araç Sahibi İletişim</td>
                                                    <td class="modal-data" id="aracSahibiIletisim"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Araç Sahibi Adres</td>
                                                    <td class="modal-data" colspan="3" id="aracSahibiAders"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Araç Sahibi Yakını Adı</td>
                                                    <td class="modal-data" id="aracSahibiYakiniAdi"></td>
                                                    <td class="static">Araç Sahibi Yakını İletişim</td>
                                                    <td class="modal-data" id="aracSahibiYakiniIletisim"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Araç Sahibi Yakını Adres</td>
                                                    <td class="modal-data" colspan="3" id="aracSahibiYakiniAders"></td>
                                                </tr>

                                                </tbody>
                                            </table>

                                            {{-- TRAFİK SETİ --}}
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table mt-4 table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="6">Trafik Seti
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Stepne</td>
                                                    <td class="modal-data" id="stepne"></td>
                                                    <td class="static">Kriko</td>
                                                    <td class="modal-data" id="kriko"></td>
                                                    <td class="static">Zincir</td>
                                                    <td class="modal-data" id="Zincir"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Bijon Anahtarı</td>
                                                    <td class="modal-data" id="bijonAnahtari"></td>
                                                    <td class="static">Reflektör</td>
                                                    <td class="modal-data" id="reflektor"></td>
                                                    <td class="static">Yangın Tüpü</td>
                                                    <td class="modal-data" id="yanginTupu"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">İlk Yardım Çantası</td>
                                                    <td class="modal-data" id="ilkYardimCantasi"></td>
                                                    <td class="static">Seyyar Lamba</td>
                                                    <td class="modal-data" id="seyyarLamba"></td>
                                                    <td class="static">Çekme Halatı</td>
                                                    <td class="modal-data" id="cekmeHalati"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Giydirme</td>
                                                    <td class="modal-data" id="giydirme"></td>
                                                    <td class="static">Kör Nokta Uyarısı</td>
                                                    <td class="modal-data" id="korNoktaUyarisi"></td>
                                                    <td class="static">Hata Bildirim Hattı</td>
                                                    <td class="modal-data" id="hataBildirimHatti"></td>
                                                </tr>

                                                </tbody>
                                            </table>

                                            {{-- EVRAKLAR --}}
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table mt-4 table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="6">Evraklar</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Muayene Evrağı</td>
                                                    <td class="modal-data" id="muayeneEvragi"></td>
                                                    <td class="static">Sigorta Belgesi</td>
                                                    <td class="modal-data" id="sigortaBelgesi"></td>
                                                    <td class="static">Şoför Ehliyet</td>
                                                    <td class="modal-data" id="soforEhliyet"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Src Belgesi</td>
                                                    <td class="modal-data" id="srcBelgesi"></td>
                                                    <td class="static">Ruhsat Ekspertiz Raporu</td>
                                                    <td class="modal-data" id="ruhsatEkspertizRaporu"></td>
                                                    <td class="static">Taşıma Belgesi</td>
                                                    <td class="modal-data" id="tasimaBelgesi"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Şoför Adli Sicil Kaydı</td>
                                                    <td class="modal-data" id="soforAdliSicilKaydi"></td>
                                                    <td class="static">Araç Sahibi Sicil Kaydi</td>
                                                    <td class="modal-data" id="aracSahibiSicilKaydi"></td>
                                                    <td class="static">Şoför Yakını İkametgah Belgesi</td>
                                                    <td class="modal-data" id="soforYakiniIkametgahBelgesi"></td>
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
