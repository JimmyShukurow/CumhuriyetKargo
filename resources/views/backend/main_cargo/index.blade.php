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
                                                    <div class="widget-heading">Koli Sayısı</div>
                                                    <div class="widget-subheading">(Bugün) Toplam Kesilen Koli Adeti
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
                            </ul>
                        </div>
                        <div class="col-md-12 col-lg-4">
                            <ul class="list-group list-group-flush">
                                <li class="bg-transparent list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">Toplam Kesilen Kargo</div>
                                                    <div class="widget-subheading">(Bugün) Kargo Adeti</div>
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
                                    <button
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


                            {{--                            <div class="col-md-2">--}}
                            {{--                                <label for="receiverCode">Alıcı Cari Kod:</label>--}}
                            {{--                                <input type="text" data-inputmask="'mask': '999 999 999'"--}}
                            {{--                                       placeholder="___ ___ ___" type="text" id="receiverCode"--}}
                            {{--                                       class="form-control input-mask-trigger form-control-sm niko-filter">--}}
                            {{--                            </div>--}}


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
                            <th>Gönderici Adı</th>
                            <th>Gönderici İl</th>
                            <th>Alıcı Adı</th>
                            <th>Alıcı İl</th>
                            <th>Alıcı İlçe</th>
                            <th>Alıcı Adres</th>
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
                            <th>Gönderici Adı</th>
                            <th>Gönderici İl</th>
                            <th>Alıcı Adı</th>
                            <th>Alıcı İl</th>
                            <th>Alıcı İlçe</th>
                            <th>Alıcı Adres</th>
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


            {{--Statistics--}}
            <div>
                {{--            <div class="row">--}}
                {{--                <div class="col-md-6 col-xl-3">--}}
                {{--                    <div--}}
                {{--                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-success border-success">--}}
                {{--                        <div class="widget-chat-wrapper-outer">--}}
                {{--                            <div class="widget-chart-content pt-3 pl-3 pb-1">--}}
                {{--                                <div class="widget-chart-flex">--}}
                {{--                                    <div class="widget-numbers">--}}
                {{--                                        <div class="widget-chart-flex">--}}
                {{--                                            <div class="fsize-4">--}}
                {{--                                                <small class="opacity-5">$</small>--}}
                {{--                                                <span>874</span>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <h6 class="widget-subheading mb-0 opacity-5">sales last month</h6>--}}
                {{--                            </div>--}}
                {{--                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">--}}
                {{--                                <div class="col-md-9">--}}
                {{--                                    <div id="dashboard-sparklines-1"></div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="col-md-6 col-xl-3">--}}
                {{--                    <div--}}
                {{--                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-primary border-primary">--}}
                {{--                        <div class="widget-chat-wrapper-outer">--}}
                {{--                            <div class="widget-chart-content pt-3 pl-3 pb-1">--}}
                {{--                                <div class="widget-chart-flex">--}}
                {{--                                    <div class="widget-numbers">--}}
                {{--                                        <div class="widget-chart-flex">--}}
                {{--                                            <div class="fsize-4">--}}
                {{--                                                <small class="opacity-5">$</small>--}}
                {{--                                                <span>1283</span>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <h6 class="widget-subheading mb-0 opacity-5">sales Income</h6>--}}
                {{--                            </div>--}}
                {{--                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">--}}
                {{--                                <div class="col-md-9">--}}
                {{--                                    <div id="dashboard-sparklines-2"></div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="col-md-6 col-xl-3">--}}
                {{--                    <div--}}
                {{--                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-warning border-warning">--}}
                {{--                        <div class="widget-chat-wrapper-outer">--}}
                {{--                            <div class="widget-chart-content pt-3 pl-3 pb-1">--}}
                {{--                                <div class="widget-chart-flex">--}}
                {{--                                    <div class="widget-numbers">--}}
                {{--                                        <div class="widget-chart-flex">--}}
                {{--                                            <div class="fsize-4">--}}
                {{--                                                <small class="opacity-5">$</small>--}}
                {{--                                                <span>1286</span>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <h6 class="widget-subheading mb-0 opacity-5">last month sales</h6>--}}
                {{--                            </div>--}}
                {{--                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">--}}
                {{--                                <div class="col-md-9">--}}
                {{--                                    <div id="dashboard-sparklines-3"></div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="col-md-6 col-xl-3">--}}
                {{--                    <div--}}
                {{--                        class="card mb-3 widget-chart widget-chart2 text-left card-btm-border card-shadow-danger border-danger">--}}
                {{--                        <div class="widget-chat-wrapper-outer">--}}
                {{--                            <div class="widget-chart-content pt-3 pl-3 pb-1">--}}
                {{--                                <div class="widget-chart-flex">--}}
                {{--                                    <div class="widget-numbers">--}}
                {{--                                        <div class="widget-chart-flex">--}}
                {{--                                            <div class="fsize-4">--}}
                {{--                                                <small class="opacity-5">$</small>--}}
                {{--                                                <span>564</span>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <h6 class="widget-subheading mb-0 opacity-5">total revenue</h6>--}}
                {{--                            </div>--}}
                {{--                            <div class="no-gutters widget-chart-wrapper mt-3 mb-3 pl-2 he-auto row">--}}
                {{--                                <div class="col-md-9">--}}
                {{--                                    <div id="dashboard-sparklines-4"></div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--            </div>--}}
            </div>
            {{-- HERE --}}
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

    <style type="text/css">
        pre#json-renderer {
            border: 1px solid #aaa;
        }
    </style>

    <script>

        $('#btnExportExcel').click(function () {
            let count = oTable.rows({selected: true}).count();
            if (count > 0)
                $('#selectedExcelBtn').click();
            else
                ToastMessage('error', 'Lütfen excele aktarılacak satırları seçin!', 'Hata!');
        });

        var oTable;
        var detailsID = null;
        // and The Last Part: NikoStyle
        $(document).ready(function () {
            $('#agency').select2();
            $('#creatorUser').select2();

            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 10,
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 1
                }],
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [22, 'desc'],
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
                    {
                        extend: 'selectAll',
                        text: 'Tümünü Seç'
                    },
                    {
                        extend: 'selectNone',
                        text: 'Tümünü Bırak'
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]
                        },
                        title: "CK - Kesilen Kargolar"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        },
                        attr: {
                            id: 'datatableRefreshBtn'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Ex Akt',
                        exportOptions: {
                            modifier: {
                                selected: true
                            },
                            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]
                        },
                        attr: {
                            id: 'selectedExcelBtn'
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
                    url: '{!! route('mainCargo.getCargoes') !!}',
                    data: function (d) {
                        d.startDate = $('#startDate').val();
                        d.finishDate = $('#finishDate').val();
                        d.record = $('#record').val();
                        d.paymentType = $('#paymentType').val();
                        d.collectible = $('#collectible').val();
                        d.trackingNo = $('#trackingNo').val();
                        d.cargoType = $('#cargoType').val();
                        d.status = $('#status').val();
                        d.statusForHuman = $('#statusForHuman').val();
                        d.transporter = $('#transporter').val();
                        d.system = $('#system').val();
                        d.creatorUser = $('#creatorUser').val();
                        d.cargoContent = $('#cargoContent').val();
                        d.receiverCode = $('#receiverCode').val();
                        d.receiverName = $('#receiverName').val();
                        d.receiverCity = $('#receiverCity').val();
                        d.currentCode = $('#currentCode').val();
                        d.currentName = $('#currentName').val();
                        d.currentCity = $('#currentCity').val();
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            SnackMessage('Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'error', 'bl');
                        }
                    },
                    complete: function () {
                        SnackMessage('Tamamlandı!', 'info', 'bl');

                        if ($('#datatableRefreshBtn').prop('disabled') == true)
                            $('#datatableRefreshBtn').prop('disabled', false);

                    }
                },
                columns: [
                    {data: 'free_btn', name: 'free_btn'},
                    {data: 'check', name: 'check'},
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'sender_city', name: 'sender_city'},
                    {data: 'receiver_name', name: 'receiver_name'},
                    {data: 'receiver_city', name: 'receiver_city'},
                    {data: 'receiver_district', name: 'receiver_district'},
                    {data: 'receiver_address', name: 'receiver_address'},
                    {data: 'payment_type', name: 'payment_type'},
                    {data: 'total_price', name: 'total_price'},
                    {data: 'number_of_pieces', name: 'number_of_pieces'},
                    {data: 'cubic_meter_volume', name: 'cubic_meter_volume'},
                    {data: 'cubic_meter_volume', name: 'cubic_meter_volume'},
                    {data: 'collectible', name: 'collectible'},
                    {data: 'collectible', name: 'collectible'},
                    {data: 'collection_fee', name: 'collection_fee'},
                    {data: 'status', name: 'status'},
                    {data: 'status_for_human', name: 'status_for_human'},
                    {data: 'transporter', name: 'transporter'},
                    {data: 'system', name: 'system'},
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'edit', name: 'edit'},
                ],
                // scrollY: '450px',
                // scrollX: false,
            });

            $('#selectedExcelBtn').hide();
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

        $(document).on('dblclick', '.main-cargo-tracking_no', function () {
            let tracking_no = $(this).attr('tracking-no')
            let id = $(this).prop('id')
            copyToClipBoard(tracking_no);
            SnackMessage('Takip numarası kopyalandı!', 'info', 'bl');
            cargoInfo(id);
        });

        $(document).on('click', '.cargo-detail', function () {
            cargoInfo($(this).prop('id'));
        });

        var array = new Array();


        $('#btnRefreshMainCargoPage').click(function () {


            SnackMessage('Yenileniyor', 'info', 'bl');

            $('.app-main__inner').block({
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

            setTimeout(function () {
                $.ajax('/MainCargo/AjaxTransactions/GetMainDailySummery', {
                    method: 'POST',
                    data: {
                        _token: token
                    }
                }).done(function (response) {
                    $('#file_count').html(response.file_count);
                    $('#package_count').html(response.package_count);
                    $('#total_cargo_count').html(response.total_cargo_count);
                    $('#total_desi').html(response.total_desi);
                    $('#total_endorsement').html("₺" + response.total_endorsement);
                    $('#total_number_of_pieces').html(response.total_number_of_pieces);
                }).error(function (jqXHR, exception) {
                    ajaxError(jqXHR.status);
                }).always(function () {
                    $('.app-main__inner').unblock();
                    $('#CargoesTable').DataTable().ajax.reload();
                });
            }, 750);

        });


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

            $.ajax('/MainCargo/AjaxTransactions/GetCargoInfo', {
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

                    $('#titleTrackingNo').text(cargo.tracking_no);

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
                    $('#cargoCreatedAt').text(cargo.created_at);
                    $('#numberOfPieces').text(cargo.number_of_pieces);

                    $('#cargoKg').text(cargo.cubic_meter_volume);
                    $('#cubicMeterVolume').text(cargo.cubic_meter_volume);
                    $('#desi').text(cargo.desi);

                    $('#cargoType').text(cargo.cargo_type);
                    $('#paymentType').text(cargo.payment_type);
                    $('#transporter').text(cargo.transporter);
                    $('#system').text(cargo.system);
                    $('#creatorUserInfo').text(cargo.system);
                    $('#customerCode').text(cargo.customer_code);
                    $('#cargoContent').text(cargo.cargo_content);
                    $('#cargoContentEx').text(cargo.cargo_content_ex);



                    //
                    //
                    // $('#numberOfPieces').text(cargo.number_of_pieces);
                    // $('#numberOfPieces').text(cargo.number_of_pieces);
                    // $('#numberOfPieces').text(cargo.number_of_pieces);
                    // $('#numberOfPieces').text(cargo.number_of_pieces);
                    // $('#numberOfPieces').text(cargo.number_of_pieces);
                    // $('#numberOfPieces').text(cargo.number_of_pieces);


                }


                $('#ModalCargoDetails').unblock();
                return false;
            });

            $('#ModalAgencyDetail').modal();
        }


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
                <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyUserDetail" class="modal-body">

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
                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button
                                                        id="passwordResetBtn"
                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                                        <i class="lnr-redo text-dark opacity-7 btn-icon-wrapper mb-2"></i>
                                                        Şifre Sıfırla
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnEnabledDisabled"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                        <i class="lnr-construction text-danger opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Hesabı Aktif/Pasif Yap
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnVirtualLogin"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                                                        <i class="fa fa-paper-plane text-alternate opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Hesaba Sanal Giriş Yap
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4">

                                        <div class="cont">

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
                                                            <td style="white-space: initial;" id="senderAddress"></td>
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
                                                            <td style="white-space: initial;" id="receiverAddress"></td>
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
                                                            <td id="cargoTrackingNo"></td>
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
                                                            <td class="static">Desi:</td>
                                                            <td id="desi"></td>
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
                                                            <td class="static">Kargo İçeriği:</td>
                                                            <td id="cargoContent"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">Kargo İçerik Açıklaması:</td>
                                                            <td style="white-space: initial;" id="cargoContentEx"></td>
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
                                                            <td id="collection_fee"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">Çıkış İl:</td>
                                                            <td id="exitCity"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">Çıkış Şube:</td>
                                                            <td id="exitBranch"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">Varış İl:</td>
                                                            <td id="arrivalCity"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">Varış Şube:</td>
                                                            <td id="arrivalBranch"></td>
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
                                                            <td class="static">Mesafe (KM):</td>
                                                            <td id="distance"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">Mesafe Ücreti:</td>
                                                            <td id="distancePrice"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">KDV:</td>
                                                            <td id="kdv"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">Ek Hizmet Tutarı:</td>
                                                            <td id="addServiceFee"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">Hizmet Ücreti:</td>
                                                            <td id="serviceFee"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="static">Genel Toplam:</td>
                                                            <td id="totalFee"></td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>


                                        </div>
                                        <h4 class="mt-3">Kargo Hareketleri</h4>

                                        <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                             class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees"
                                                   class="Table30Padding table table-striped mt-3">
                                                <thead>
                                                <tr>
                                                    <th>Kayıt Tarihi</th>
                                                    <th>Hareket Tipi</th>
                                                    <th>Detay</th>
                                                    <th>Hareket</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyUseLog">

                                                </tbody>
                                            </table>
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
@endsection
