@extends('backend.layout')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <link rel="stylesheet" href="/backend/assets/css/ck-barcode.css">
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

        <div class="tabs-animation">
            <div class="card no-shadow bg-transparent no-border rm-borders mb-3">
                <div class="card">
                    <div class="no-gutters row">
                        <div class="col-md-12 col-lg-4">
                            <ul class="list-group list-group-flush">
                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Kargo Sayısı</div>
                                                    <div class="widget-subheading">(Bugün) Toplam Kesilen Kargo Adeti
                                                    </div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div id="package_count"
                                                         class="widget-numbers text-success">{{$daily['package_count']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Dosya Sayısı</div>
                                                    <div class="widget-subheading">(Bugün) Toplam Kesilen Dosya</div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div id="file_count"
                                                         class="widget-numbers text-danger">{{$daily['file_count']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                        <div class="col-md-12 col-lg-4">
                            <ul class="list-group list-group-flush">

                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Toplam Kesilen Kargo-Dosya</div>
                                                    <div class="widget-subheading">(Bugün) Kargo-Dosya Adeti</div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div id="total_cargo_count"
                                                         class="widget-numbers text-success">{{$daily['total_cargo_count']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>


                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Desi</div>
                                                    <div class="widget-subheading">(Bugün) Total Desi
                                                    </div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div id="total_desi"
                                                         class="widget-numbers text-primary">{{$daily['total_desi']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12 col-lg-4">
                            <ul class="list-group list-group-flush">

                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Parça Sayısı</div>
                                                    <div class="widget-subheading">(Bugün) Toplam Parça Sayısı
                                                    </div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div id="total_number_of_pieces"
                                                         class="widget-numbers text-warning">{{$daily['total_number_of_pieces']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Ciro</div>
                                                    <div class="widget-subheading">(Bugün) Toplam Cironuz
                                                    </div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <div class="widget-numbers text-primary" id="total_endorsement">
                                                        ₺{{getDotter($daily['total_endorsement'])}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding: 0" class="col-sm-12 col-lg-12">
                <div class="mb-3 card">
                    <div class="p-0 d-block card-footer">
                        <div class="grid-menu grid-menu-3col">
                            <div class="no-gutters row">

                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button id="btnRefreshMainCargoPage"
                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                        <i class="lnr-sync text-success opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Yenile
                                    </button>
                                </div>

                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <a style="text-decoration: none;" href="{{route('mainCargo.newCargo')}}">
                                        <button id="btnNewCargo"
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-info">
                                            <i class="lnr-plus-circle text-info opacity-7 btn-icon-wrapper mb-2"> </i>
                                            Yeni Kargo
                                        </button>
                                    </a>
                                </div>

                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button id="btnExportExcel"
                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning">
                                        <i class="fas fa-file-excel text-warning opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Excele Aktar
                                    </button>
                                </div>

                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button id="btnPrintSelectedBarcode"
                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                                        <i class="lnr-printer text-alternate opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Yazdır
                                    </button>
                                </div>
                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                        <i class="lnr-pencil text-success opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Kargo Düzenle
                                    </button>
                                </div>
                                <div class="p-2 col-lg-2 col-sm-4 col-xs-6">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                        <i class="lnr-cross text-danger opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Kargo İptal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                            <div class="col-md-2">
                                <label for="startDate">Başlangıç Tarih:</label>
                                <input type="datetime-local" id="startDate" value="{{ date('Y-m-d') }}T00:00"
                                       class="form-control niko-filter form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label for="finishDate">Bitiş Tarihi:</label>
                                <input type="datetime-local" id="finishDate" value="{{ date('Y-m-d') }}T23:59"
                                       class="form-control niko-filter form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label for="record">Kayıt:</label>
                                <select name="record" id="record"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="1">Kayıt</option>
                                    <option value="0">Arşiv</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="paymentType">Ödeme Tipi:</label>
                                <select name="paymentType" id="paymentType"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    <option value="Alıcı Ödemeli">Alıcı Ödemeli</option>
                                    <option value="Gönderici Ödemeli">Gönderici Ödemeli</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="collectible">Tahsilatlı:</label>
                                <select name="collectible" id="collectible"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    <option value="Evet">Evet</option>
                                    <option value="Hayır">Hayır</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="cargoType">Kargo Tipi:</label>
                                <select name="cargoType" id="cargoType"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['cargo_types'] as $key)
                                        <option value="{{$key->cargo_type}}">{{$key->cargo_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row pt-2">

                            <div class="col-md-2">
                                <label for="status">Statü:</label>
                                <select name="status" id="status"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['status'] as $key)
                                        <option value="{{$key->status}}">{{$key->status}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="statusForHuman">Durum:</label>
                                <select name="statusForHuman" id="statusForHuman"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['status_for_human'] as $key)
                                        <option value="{{$key->status_for_human}}">{{$key->status_for_human}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="transporter">Taşıyıcı:</label>
                                <select name="transporter" id="transporter"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['transporters'] as $key)
                                        <option value="{{$key->transporter}}">{{$key->transporter}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="system">Sistem:</label>
                                <select name="system" id="system"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['systems'] as $key)
                                        <option value="{{$key->system}}">{{$key->system}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="creatorUser">Oluşturan:</label>
                                <select name="creatorUser" class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['agency_users'] as $key)
                                        <option value="{{$key->id}}">{{$key->name_surname}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="cargoContent">Kargo İçeriği:</label>
                                <select name="cargoContent" id="cargoContent"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['cargo_contents'] as $key)
                                        <option value="{{$key->cargo_content}}">{{$key->cargo_content}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row pt-2">

                            <div class="col-md-2">
                                <label for="receiverCode">Kargo Takip No:</label>
                                <input type="text" data-inputmask="'mask': '99999 99999 99999'"
                                       placeholder="_____ _____ _____" type="text" id="trackingNo"
                                       class="form-control input-mask-trigger form-control-sm niko-filter">
                            </div>

                            <div class="col-md-2">
                                <label for="receiverCode">Fatura NO:</label>
                                <input type="text" data-inputmask="'mask': 'AA 999999'"
                                       placeholder="__ ______" type="text" id="invoice_number"
                                       class="form-control input-mask-trigger form-control-sm niko-filter">
                            </div>

                            <div class="col-md-2">
                                <label for="receiverName">Alıcı Adı:</label>
                                <input type="text" id="receiverName" class="form-control niko-filter form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label for="receiverCity">Alıcı İl:</label>
                                <select id="receiverCity"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['cities'] as $key)
                                        <option value="{{$key->city_name}}">{{$key->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-2">
                                <label for="currentName">Gönderici Adı:</label>
                                <input type="text" id="currentName"
                                       class="form-control niko-filter form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label for="currentCity">Gönderici İl:</label>
                                <select name="cargoContent" id="currentCity"
                                        class="form-control form-control-sm niko-select-filter">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['cities'] as $key)
                                        <option value="{{$key->city_name}}">{{$key->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">

                    <table style="white-space: nowrap;" id="CargoesTable"
                           class="align-middle mb-0 table Table20Padding table-bordered table-striped table-hover NikolasDataTable">
                        <thead>
                        <tr>
                            <th class="free"></th>
                            <th class="check"></th>
                            <th>KTNO</th>
                            <th>Fatura NO</th>
                            <th>Gönderici Adı</th>
                            <th>Gönderici İl</th>
                            <th>Alıcı Adı</th>
                            <th>Alıcı İl</th>
                            <th>Alıcı İlçe</th>
                            <th>Kargo Tipi</th>
                            <th>Ödeme Tipi</th>
                            <th>Ücret</th>
                            <th>Parça Sayısı</th>
                            <th>Ağırlık (KG)</th>
                            <th>Hacim (m<sup>3</sup>)</th>
                            <th>Tahsilat Tipi</th>
                            <th>Tahilatlı</th>
                            <th>Fatura Tutarı</th>
                            <th>Statü</th>
                            <th>Durum</th>
                            <th>Taşıyan</th>
                            <th>Sistem</th>
                            <th>Oluşturan</th>
                            <th>Kayıt Tarihi</th>
                            <th>İşlem</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="free"></th>
                            <th class="check"></th>
                            <th>KTNO</th>
                            <th>Fatura NO</th>
                            <th>Gönderici Adı</th>
                            <th>Gönderici İl</th>
                            <th>Alıcı Adı</th>
                            <th>Alıcı İl</th>
                            <th>Alıcı İlçe</th>
                            <th>Kargo Tipi</th>
                            <th>Ödeme Tipi</th>
                            <th>Ücret</th>
                            <th>Parça Sayısı</th>
                            <th>Ağırlık (KG)</th>
                            <th>Hacim (m<sup>3</sup>)</th>
                            <th>Tahsilat Tipi</th>
                            <th>Tahilatlı</th>
                            <th>Fatura Tutarı</th>
                            <th>Statü</th>
                            <th>Durum</th>
                            <th>Taşıyan</th>
                            <th>Sistem</th>
                            <th>Oluşturan</th>
                            <th>Kayıt Tarihi</th>
                            <th>İşlem</th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
    <script src="/backend/assets/scripts/JsBarcode.js"></script>
    <script src="/backend/assets/scripts/QrCode.min.js"></script>
    <script src="/backend/assets/scripts/main-cargo/index.js"></script>
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
                                            <h6 id="titleCargoInvoiceNumber" class="menu-header-subtitle">###/###</h6>
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

                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button
                                                        id="btnCargoCancel"
                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                        <i class="lnr-cross text-danger opacity-7 btn-icon-wrapper mb-2"> </i>
                                                        Kargo İptal Başvurusu
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <a style="text-decoration: none;" id="PrintStatementOfResposibility"
                                                       target="_blank"
                                                       href="">
                                                        <button id=""
                                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary">
                                                            <i class="pe-7s-ribbon text-primary opacity-7 btn-icon-wrapper mb-2">
                                                            </i>
                                                            Sorumluluk Taahütnamesi
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button style="display: none;" id="btnCargoPrintBarcode"></button>
                                                    <button style="display: none;"
                                                            id="btnCargoPrintPartBarcode"></button>
                                                    <button
                                                        aria-haspopup="true" aria-expanded="false"
                                                        data-toggle="dropdown"
                                                        class="dropdown-toggle btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                                                        <i class="lnr-printer text-alternate opacity-7 btn-icon-wrapper mb-2"></i>
                                                        Barkod Yazdır
                                                    </button>
                                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                                         class="dropdown-menu-hover-link dropdown-menu">
                                                        <h6 tabindex="-1" class="dropdown-header">Barkod Yazdır</h6>
                                                        <button type="button" onclick="clicker('#btnCargoPrintBarcode')"
                                                                tabindex="0" class="dropdown-item">
                                                            <i class="dropdown-icon pe-7s-news-paper print-all-barcodes"></i>
                                                            <span>Tüm Parçaları Yazdır</span>
                                                        </button>
                                                        <button type="button" tabindex="0" class="dropdown-item">
                                                            <i class="dropdown-icon lnr-file-empty"></i>
                                                            <span>Belirli Parçalı Yazdır (Özel)</span>
                                                        </button>
                                                    </div>
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

    <!-- Large modal => Modal Barcode -->
    <div class="modal fade bd-example-modal-lg" id="ModalShowBarcode" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Barkod Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-x: hidden; max-height: 60vh;" id="ModalBarcodes"
                     class="modal-body">
                    <div id="ContainerBarcodes"
                         class="container">

                        <div class="row barcode-row">
                            <div class="col-6">
                                <h5 class="font-weight-bold barcode-slogan">Cumhuriyet Kargo - Sevgi ve Değer
                                    Taşıyoruz..</h5>
                                <h4 class="font-weight-bold  text-dark m-0 barcodeDepartureTC">VAN Gölü</h4>
                                <b class="barcodeDepartureAgency">EVREN</b>
                            </div>

                            <div class="col-6">
                                <h3 class="p-0 m-0 barcodePaymentType">HL 102856 AÖ</h3>
                                <h6 class="m-0 labelTrackingNo">GönderiNo: <b class="barcodeTrackingNo">145646
                                        749879 87968</b>
                                </h6>
                                <b>ÜRÜN BEDELİ: <b class="barcodeCargoTotalPrice">858₺</b></b>
                            </div>


                            <div class="col-9 p-0">
                                <table class="shipmentReceiverInfo">
                                    <tr>
                                        <td class="barcode-mini-text text-center font-weight-bold vertical-rl">GÖN</td>
                                        <td>
                                            <p class="barcode-mini-text p-1 m-0 font-weight-bold barcodeSenderName">
                                                Kitaip yayın evi,
                                                Basım DAĞ. Reklam Tic. LTD ŞTİ</p>
                                            <p class="barcode-mini-text p-1 m-0 font-weight-bold">
                                                <span id="barcodeSenderCityDistrict">BAĞCILAR/İSTANBUL </span>
                                                <span class="text-right barcodeSenderPhone">5354276824</span>
                                            </p>
                                        </td>
                                        <td class="cargoInfo" rowspan="2">
                                            <p class="barcodeRegDate font-weight-bold barcode-mini-text m-0">
                                                28.08.2021</p>
                                            <p class="barcodeCargoType m-0  barcode-mini-text font-weight-bolder">
                                                KOLİ</p>
                                            <p class="m-0  barcode-mini-text">Kg:50</p>
                                            <p class="m-0  barcode-mini-text">Ds:50</p>
                                            <p class="m-0  barcode-mini-text">Kg/Ds:50</p>
                                            <p class="m-0  barcode-mini-text">Toplam:164</p>
                                            <p class="m-0 text-center font-weight-bold">2/2</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="barcode-mini-text text-center font-weight-bold vertical-rl">ALICI
                                        </td>
                                        <td>
                                            <p class="barcodeReceiverName barcode-mini-text p-1 m-0 font-weight-bold">
                                                NURULLAH GÜÇ</p>

                                            <p class="barcodeReceiverAddress barcode-mini-text p-1 m-0 font-weight-bold">
                                                Gülbahar Mah. Cemal
                                                Sururi Sk.
                                                Halim Meriç İş Merkezi No:15/E K:4/22 Şişli/İstanbul</p>
                                            <p class="barcode-mini-text p-1 m-0 font-weight-bold">
                                                <span class="barcodeReceiverCityDistrict">Şişli/İstanbul </span>
                                                <span class="barcodeReceiverPhone"
                                                      class="text-right">TEL: 5354276824</span>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-3 qr-barcode-cont">
                                <div class="qrcodes" id="qrcode"></div>
                            </div>

                            <div class="col-12 text-right">
                                <h3 class="font-weight-bold text-dark barcodeArrivalTC">
                                    VAN HATTI</h3>
                            </div>

                            <div class="col-12 code39-container">
                                <svg class="barcode"></svg>
                            </div>

                            <div class="col-12 text-right">
                                <b class="barcodeArrivalAgency">EVREN</b>
                            </div>

                        </div>
                        <div class="barcode-divider"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <label id="modalBarcodeFooterLabel" style="float: left;width: 100%;"><b id="barcodeCount">5</b> Adet
                        barkod hazırlandı.</label>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                    <button type="button" id="btnPrintBarcode" class="btn btn-primary">Yazdır</button>
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

    <!-- Large modal => Modal Cargo Cancel Form -->
    <div class="modal fade bd-example-modal-lg" id="ModalCargoCancelForm" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Kargo İptal Başvurusu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div id="modalBodyCargoCancelForm" style="overflow-x: hidden; max-height: 75vh;"
                     class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="reason">İptal Nedeni</label>
                                <select name="" id="reason" class="form-control">
                                    <option value="Gönderici Bilgileri Hatalı">Gönderici Bilgileri Hatalı</option>
                                    <option value="Alıcı Bilgileri Hatalı">Alıcı Bilgileri Hatalı</option>
                                    <option value="Kargo Ebat Bilgileri Hatalı">Kargo Ebat Bilgileri Hatalı</option>
                                    <option value="Ödeme Tipi Hatalı">Ödeme Tipi Hatalı</option>
                                    <option value="Müşteri İade">Müşteri İade</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="appointmentReason">
                                    İptal Nedeni <small class="text-danger"><i>(Zorunlu Alan)</i></small>
                                </label>
                                <textarea name="" id="appointmentReason" cols="30" rows="5" class="form-control">Gönderici Bilgileri Hatalı</textarea>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                    <button type="button" id="btnMakeCargoCancelAppointment" class="btn btn-primary">Başvuru Yap
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
