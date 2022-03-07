@extends('backend.layout')

@push('css')
    {{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>--}}
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

                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <button id="btnRefreshMainCargoPage"
                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                        <i class="lnr-sync text-success opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Yenile
                                    </button>
                                </div>

                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <a style="text-decoration: none;" href="{{route('mainCargo.newCargo')}}">
                                        <button id="btnNewCargo"
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary">
                                            <i class="lnr-plus-circle text-primary opacity-7 btn-icon-wrapper mb-2"> </i>
                                            Yeni Fatura
                                        </button>
                                    </a>
                                </div>

                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <button id="btnExportExcel"
                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning">
                                        <i class="fas fa-file-excel text-warning opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Excel'e Aktar
                                    </button>
                                </div>

                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <button id="btnPrintSelectedBarcode"
                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                                        <i class="lnr-printer text-alternate opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Yazdır
                                    </button>
                                </div>
                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <button
                                        aria-haspopup="true" aria-expanded="false"
                                        data-toggle="dropdown"
                                        class="dropdown-toggle  btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-info">
                                        <i class="pe-7s-note2 text-info opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Tutanak
                                    </button>

                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                         class="dropdown-menu-hover-link dropdown-menu">
                                        <h6 tabindex="-1" class="dropdown-header">Tutanak Oluştur</h6>

                                        <a href="{{route('OfficialReport.createHTF')}}"
                                           target="popup"
                                           onclick="window.open('{{route('OfficialReport.createHTF')}}','popup','width=700,height=700'); return false;">
                                            <button type="button"
                                                    tabindex="0" class="dropdown-item">
                                                <i class="dropdown-icon pe-7s-news-paper print-all-barcodes"></i>
                                                <span>HTF (Hasar Tespit Tutanağı)</span>
                                            </button>
                                        </a>

                                        <a href="{{route('OfficialReport.createUTF')}}"
                                           target="popup"
                                           onclick="window.open('{{route('OfficialReport.createUTF')}}','popup','width=700,height=700'); return false;">
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <i class="dropdown-icon lnr-file-empty"></i>
                                                <span>UTF (Uygunsuzluk Tespit Tutanağı)</span>
                                            </button>
                                        </a>

                                        <a href="{{route('OfficialReport.index')}}">
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <i class="dropdown-icon pe-7s-search"></i>
                                                <span>Tutanak Sorgula</span>
                                            </button>
                                        </a>
                                    </div>
                                </div>


                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger"
                                       href="{{route('cargoBags.agencyIndex')}}">
                                        <i class="pe-7s-check text-danger opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Manifesto
                                    </a>
                                </div>

                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success"
                                       href="{{route('customers.index')}}">
                                        <i class="lnr-briefcase text-success opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Müşteriler
                                    </a>
                                </div>

                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary"
                                       href="{{route('safe.agency.index')}}">
                                        <i class="pe-7s-safe text-primary opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Kasa
                                    </a>
                                </div>

                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning"
                                       href="{{route('mainCargo.search')}}">
                                        <i class="pe-7s-search text-warning opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Kargo Sorgula
                                    </a>
                                </div>


                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <button
                                        aria-haspopup="true" aria-expanded="false"
                                        data-toggle="dropdown"
                                        class="dropdown-toggle  btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                                        <i class="fa fa-bus text-alternate opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Sefer
                                    </button>

                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                         class="dropdown-menu-hover-link dropdown-menu">
                                        <h6 tabindex="-1" class="dropdown-header">Sefer İşlemleri</h6>

                                        <a href="{{route('OfficialReport.createHTF')}}"
                                           target="popup"
                                           onclick="window.open('{{route('OfficialReport.createHTF')}}','popup','width=700,height=700'); return false;">
                                            <button type="button"
                                                    tabindex="0" class="dropdown-item">
                                                <i class="dropdown-icon pe-7s-news-paper print-all-barcodes"></i>
                                                <span>Gelen Sefer</span>
                                            </button>
                                        </a>

                                        <a href="{{route('OfficialReport.createUTF')}}"
                                           target="popup"
                                           onclick="window.open('{{route('OfficialReport.createUTF')}}','popup','width=700,height=700'); return false;">
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <i class="dropdown-icon lnr-file-empty"></i>
                                                <span>Giden Sefer</span>
                                            </button>
                                        </a>

                                        <a href="{{route('OfficialReport.index')}}">
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <i class="dropdown-icon pe-7s-search"></i>
                                                <span>Tutanak Sorgula</span>
                                            </button>
                                        </a>

                                        <a href="{{route('OfficialReport.index')}}">
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <i class="dropdown-icon pe-7s-plus"></i>
                                                <span>Sefer Oluştur</span>
                                            </button>
                                        </a>
                                    </div>
                                </div>

                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark"
                                       href="javascript:void(0)">
                                        <i class="pe-7s-timer text-dark opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Module
                                    </a>
                                </div>
                                <div class="p-2 col-lg-1 col-sm-4 col-xs-6">
                                    <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark"
                                       href="javascript:void(0)">
                                        <i class="pe-7s-timer text-dark opacity-7 btn-icon-wrapper mb-2"> </i>
                                        Module
                                    </a>
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
                                    @foreach(allCargoTypes() as $key)
                                        <option value="{{$key}}">{{$key}}</option>
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
                                <input type="text" data-inputmask="'mask': 'AA-999999'"
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
                            <th>Fatura NO</th>
                            <th>KTNO</th>
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
                            <th>Tahsilatlı</th>
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
                            <th>Fatura NO</th>
                            <th>KTNO</th>
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
                            <th>Tahsilatlı</th>
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
    <script>var typeOfJs = 'main_cargo'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>
    <script src="/backend/assets/scripts/official-report/report-view.js"></script>
    <script src="/backend/assets/scripts/customers/customer-details.js"></script>


@endsection

@section('modals')

    @php $data = ['type' => 'main_cargo']; @endphp

    @include('backend.main_cargo.main.modal_cargo_details')

    @include('backend.OfficialReports.report_modal')

    @include('backend/customers/agency/modal_customer_details')
@endsection
