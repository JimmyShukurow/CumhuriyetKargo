@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Lokasyon Rapor')

@section('content')

    <div class="app-main__inner">

        <div class="app-inner-layout">
            <div class="app-inner-layout__header-boxed p-0">
                <div class="app-inner-layout__header page-title-icon-rounded text-white bg-asteroid mb-4">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="lnr-map icon-gradient bg-slick-carbon"> </i>
                                </div>
                                <div>
                                    Lokasyon Rapor (Mahalli)
                                    <div class="page-title-subheading">Cumhuriyet Kargo Türkiye Geneli Acenteler Mahalli
                                        Lokasyon Raporu. Raporu.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Toplam Acente</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{$data['agency_quantity']}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-agency d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-success card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Mah. Çalışması Tamamlanan Acenteler
                                </div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{$data['local_location_completed_agencies']}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div
                                                class="circle-progress circle-mah-calismasi-tamamlanan-acenteler d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-danger card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Mah. Çalışması Tamamlanmayan
                                    Acenteler
                                </div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{getDotter($data['local_location_not_completed_agencies'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div
                                                class="circle-progress circle-mah-calismasi-tamamlanmayan-acenteler d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">En çok
                                dağıtım alnına sahip olan acenteler
                            </div>
                            <div class="btn-actions-pane-right text-capitalize">
                            </div>
                        </div>
                        <div class="pt-0 card-body">
                            <div id="chart-regions"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                                Türkiye Geneli Dağıtım Yüzdesi (Şehir Bazlı)
                            </div>

                        </div>
                        <div class="p-0 card-body">
                            <div id="chart-sehir-bazli-dagitim"></div>

                            <div class="col-md-12">
                                <div
                                    class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-danger card">
                                    <div class="widget-chat-wrapper-outer ">
                                        <div class="widget-chart-content">
                                            <div class="widget-title opacity-5 ">Toplam Şehir/AT Yapılan Şehir</div>
                                            <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                                <div class="widget-chart-flex align-items-center">
                                                    <div>
                                                    <span class="opacity-10 text-danger pr-2">
                                                        <i class="fa fa-flag"></i>
                                                    </span>
                                                        {{$data['cities']. '/' . $data['at_cities'] }}
                                                    </div>
                                                    <div
                                                        class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                                        <div
                                                            class="circle-progress circle-sehir-bazli-dagitim d-inline-block">
                                                            <small></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                                Türkiye Geneli Dağıtım Yüzdesi (İlçe Bazlı)
                            </div>

                        </div>
                        <div class="p-0 card-body">
                            <div id="chart-ilce-bazli-dagitim"></div>

                            <div class="col-md-12">
                                <div
                                    class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-info border-info card">
                                    <div class="widget-chat-wrapper-outer">
                                        <div class="widget-chart-content">
                                            <div class="widget-title opacity-5 ">Toplam İlçe/AT Yapılan İlçe</div>
                                            <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                                <div class="widget-chart-flex align-items-center">
                                                    <div>
                                        <span class="opacity-10 text-info pr-2">
                                                        <i class="fa fa-flag"></i>
                                                    </span>
                                                        {{$data['total_districts']. '/' . $data['at_districts'] }}
                                                    </div>
                                                    <div
                                                        class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                                        <div
                                                            class="circle-progress circle-ilce-bazli-dagitim d-inline-block">
                                                            <small></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                                Türkiye Geneli Dağıtım Yüzdesi (Mah. Bazlı)
                            </div>

                        </div>
                        <div class="p-0 card-body">
                            <div id="chart-mahalle-bazli-dagitim"></div>

                            <div class="col-md-12">
                                <div
                                    class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-alternate border-alternate card">
                                    <div class="widget-chat-wrapper-outer">
                                        <div class="widget-chart-content">
                                            <div class="widget-title opacity-5 ">Toplam Mahalle/AT Yapılan Mahalle</div>
                                            <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                                <div class="widget-chart-flex align-items-center">
                                                    <div>
                                                    <span class="opacity-10 text-alternate pr-2">
                                                        <i class="fa fa-flag"></i>
                                                    </span>
                                                        {{getWithK($data['total_neighborhood']). '/' . $data['total_local_locations'] }}
                                                    </div>
                                                    <div
                                                        class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                                        <div
                                                            class="circle-progress circle-mahalle-bazli-dagitim d-inline-block">
                                                            <small></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 col-lg-6">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-warning card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Toplam Şehirler</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{ getDotter($data['cities'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-tc d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-danger card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">AT Dışı Şehirler</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{ getDotter($data['at_out_cities'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-ad-disi-sehirler d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6 col-lg-3">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-warning card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Toplam İlçe</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{getDotter($data['total_districts'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-tc d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-danger card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">AT Dışı İlçeler</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{ getDotter($data['at_out_districts'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-at-disi-district d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-warning card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Toplam Mahalle</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{getDotter($data['total_neighborhood'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-tc d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-danger card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">AT Dışı Mahalleler</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{ getDotter($data['total_not_local_locations'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-at-disi-mahalleler d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-warning card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Dağıtım Yapılan (AB-MB) Mahalleler
                                </div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{ getDotter($data['total_local_locations'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-tc d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-success card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">AB Mahalleler</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{ getDotter($data['ab_locations'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-ab-mahalleler d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-info border-info card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">MB Mahalleler</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{ getDotter($data['mb_locations'])}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-mb-mahalleler d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3 card">
                        <div class="tabs-lg-alternate card-header">
                            <ul class="nav nav-justified">

                                <li class="nav-item">
                                    <a data-toggle="tab" href="#idle-agiencies-region" class="nav-link showa active">
                                        <div class="widget-number">Acente Lokasyon Statü</div>
                                        <div class="tab-subheading">Acentelerin mahalli lokasyon çalılma statüleri.
                                        </div>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a data-toggle="tab" href="#idle-districts" class="nav-link show ">
                                        <div class="widget-number">CK Mahalli Lokasyon</div>
                                        <div class="tab-subheading">
                                            Türkiye geneli mahalleler ve AT-DIŞI, Ana Bölge, Mobil Bölge
                                            Durumları.
                                        </div>
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="idle-agiencies-region" role="tabpanel">
                                <div class="card-body">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="a_l_city">İl</label>
                                                    <select name="" id="a_l_city"
                                                            class="form-control niko-select-filter form-control-sm">
                                                        <option value="">Seçiniz</option>
                                                        @foreach($data['city_names'] as $city)
                                                            <option id="{{$city->id}}" data="{{$city->city_name}}"
                                                                    value="{{$city->id}}">{{$city->city_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="a_l_district">İlçe</label>
                                                    <select disabled name="" id="a_l_district"
                                                            class="form-control niko-select-filter form-control-sm">
                                                        <option value="">Seçiniz</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="a_l_agency_name">Acente</label>
                                                    <input type="text" class="form-control change-filter form-control-sm"
                                                           id="a_l_agency_name">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="area_type">Lokasyon Yapıldı</label>
                                                    <select name="" id="a_l_location_done"
                                                            class="form-control niko-select-filter form-control-sm">
                                                        <option value="">Tümü</option>
                                                        <option value="1">Evet</option>
                                                        <option value="0">Hayır</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <table id="AgencyLocationStatus"
                                               style="white-space: nowrap; "
                                               class="table table-hover table-striped  table-bordered TableNoPadding NikolasDataTable IdleAgenciesRegion table-hover">
                                            <thead>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Acente İsmi</th>
                                                <th>Dağıtım Alanı</th>
                                                <th>Şube Kodu</th>
                                                <th>Detay</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Acente İsmi</th>
                                                <th>Şube Kodu</th>
                                                <th>Dağıtım Alanı</th>
                                                <th>Detay</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show" id="idle-districts" role="tabpanel">
                                <div class="card-body">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="city">İl</label>
                                                    <select name="" id="city"
                                                            class="form-control niko-select-filter form-control-sm">
                                                        @foreach($data['city_names'] as $city)
                                                            <option id="{{$city->id}}" data="{{$city->city_name}}"
                                                                    value="{{$city->id}}">{{$city->city_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="district">İlçe</label>
                                                    <select name="" id="district"
                                                            class="form-control niko-select-filter form-control-sm">
                                                        <option value="">Seçiniz</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="agency">Acente</label>
                                                    <select name="" id="agency"
                                                            class="form-control niko-select-filter form-control-sm">
                                                        <option value="">Seçiniz</option>
                                                        @foreach($data['agencies'] as $key)
                                                            <option
                                                                value="{{$key->id}}">{{$key->agency_name . ' ŞUBE'}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="area_type">Bölge Tipi</label>
                                                    <select name="" id="area_type"
                                                            class="form-control niko-select-filter form-control-sm">
                                                        <option value="">Tümü</option>
                                                        <option value="AT-DIŞI">AT-DIŞI</option>
                                                        <option value="AB">AB</option>
                                                        <option value="MB">MB</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <table id="TableRolePermissions"
                                               style="white-space: nowrap; "
                                               class="table table-hover table-striped  table-bordered TableNoPadding NikolasDataTable TrGeneralLocalLocations table-hover">
                                            <thead>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Mahalle</th>
                                                <th>Bölge Tipi</th>
                                                <th>Acente</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Mahalle</th>
                                                <th>Bölge Tipi</th>
                                                <th>Acente</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @endsection


        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script src="/backend/assets/scripts/circle-progress.min.js"></script>
            <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
            <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
            <script src="/backend/assets/scripts/city-districts-point.js"></script>


            <script>
                var options777 = {
                    chart: {
                        height: 397,
                        type: 'line',
                        toolbar: {
                            show: true,
                        }
                    },
                    series: [{
                        name: 'Dağıtım Yaptığı Mahalle',
                        type: 'column',
                        data: [
                            @foreach($data['distributor_agencies'] as $key)
                                {{ $key->covered_neighborhoods  }},
                            @endforeach
                        ]
                    }, {
                        name: 'Dağıtım Yaptığı Mahalle',
                        type: 'line',
                        data: [
                            @foreach($data['distributor_agencies'] as $key)
                                {{ $key->covered_neighborhoods  }},
                            @endforeach
                        ]
                    }],

                    labels: [
                        @foreach($data['distributor_agencies'] as $key)
                            '{{ $key->agency_city . '-' .$key->agency_name  }}',
                        @endforeach
                    ],

                    yaxis:
                        [{
                            title: {
                                text: 'Bölgeye Bağlı İlçe',
                            },

                        }, {
                            opposite: true,
                            title: {
                                text: 'Bölgeye Bağlı Acente'
                            }
                        }]

                }

                var chart = new ApexCharts(document.querySelector("#chart-regions"), options777);
                chart.render();


                chart.addEventListener("dataPointSelection", function (e, opts) {
                    console.log(e, opts)
                })

            </script>

            <script>
                var optionsRadial = {
                    series: [{{ round(($data['total_local_locations']  / $data['total_neighborhood']) * 100, 0) }}],
                    chart: {
                        height: 350,
                        type: 'radialBar',
                        toolbar: {
                            show: true
                        }
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 225,
                            hollow: {
                                margin: 0,
                                size: '70%',
                                background: '#fff',
                                image: undefined,
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                                dropShadow: {
                                    enabled: true,
                                    top: 3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.24
                                }
                            },
                            track: {
                                background: '#fff',
                                strokeWidth: '67%',
                                margin: 0, // margin is in pixels
                                dropShadow: {
                                    enabled: true,
                                    top: -3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.35
                                }
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    offsetY: -10,
                                    show: true,
                                    color: '#888',
                                    fontSize: '17px'
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    color: '#111',
                                    fontSize: '36px',
                                    show: true,
                                }
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#ABE5A1'],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                    labels: ['%'],
                };
                var chart = new ApexCharts(document.querySelector("#chart-mahalle-bazli-dagitim"), optionsRadial);
                chart.render();

                var optionsRadialSehirBazliDagitim = {
                    series: [{{ round(($data['at_cities']  / $data['cities']) * 100, 0) }}],
                    chart: {
                        height: 350,
                        type: 'radialBar',
                        toolbar: {
                            show: true
                        }
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 225,
                            hollow: {
                                margin: 0,
                                size: '70%',
                                background: '#fff',
                                image: undefined,
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                                dropShadow: {
                                    enabled: true,
                                    top: 3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.24
                                }
                            },
                            track: {
                                background: '#fff',
                                strokeWidth: '67%',
                                margin: 0, // margin is in pixels
                                dropShadow: {
                                    enabled: true,
                                    top: -3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.35
                                }
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    offsetY: -10,
                                    show: true,
                                    color: '#888',
                                    fontSize: '17px'
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    color: '#111',
                                    fontSize: '36px',
                                    show: true,
                                }
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#ABE5A1'],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                    labels: ['%'],
                };
                var chartSehirBazliDagitim = new ApexCharts(document.querySelector("#chart-sehir-bazli-dagitim"), optionsRadialSehirBazliDagitim);
                chartSehirBazliDagitim.render();

                var optionsRadialIlceBazliDagitim = {
                    series: [{{ round(($data['at_districts']  / $data['total_districts']) * 100, 0) }}],
                    chart: {
                        height: 350,
                        type: 'radialBar',
                        toolbar: {
                            show: true
                        }
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 225,
                            hollow: {
                                margin: 0,
                                size: '70%',
                                background: '#fff',
                                image: undefined,
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                                dropShadow: {
                                    enabled: true,
                                    top: 3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.24
                                }
                            },
                            track: {
                                background: '#fff',
                                strokeWidth: '67%',
                                margin: 0, // margin is in pixels
                                dropShadow: {
                                    enabled: true,
                                    top: -3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.35
                                }
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    offsetY: -10,
                                    show: true,
                                    color: '#888',
                                    fontSize: '17px'
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    color: '#111',
                                    fontSize: '36px',
                                    show: true,
                                }
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#ABE5A1'],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                    labels: ['%'],
                };
                var chartIlceBazliDagitim = new ApexCharts(document.querySelector("#chart-ilce-bazli-dagitim"), optionsRadialIlceBazliDagitim);
                chartIlceBazliDagitim.render();


                $(document).ready(function () {
                    var chart777 = new ApexCharts(
                        document.querySelector("#chart-regions"),
                        options777
                    );

                    $('.circle-agency').circleProgress({
                        value: 100,
                        size: 46,
                        lineCap: 'round',
                        fill: {gradient: ['#007bff', '#16aaff']}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(0) + '<span>');
                    });

                    $('.circle-region').circleProgress({
                        value: 100,
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#d92550'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(0) + '<span>');
                    });


                    $('.circle-red-100').circleProgress({
                        value: 100,
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#d92550'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(0) + '<span>');
                    });

                    $('.circle-ad-disi-sehirler').circleProgress({
                        value: {{  '.' . round(($data['at_out_cities'] / $data['cities']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#d92550'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + {{round(($data['at_out_cities'] / $data['cities']) * 100, 0)}} + '<span>');
                    });

                    $('.circle-tc').circleProgress({
                        value: 100,
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#fd7e14'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(0) + '<span>');
                    });

                    $('.circle-idle-district').circleProgress({
                        value: {{ '.'.round(($data['idle_districts_quantity'] / $data['total_districts']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#3ac47d'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(2).substring(2) + '<span>');
                    });

                    $('.circle-mahalle-bazli-dagitim').circleProgress({
                        value: {{ '.'.round(($data['total_local_locations']  / $data['total_neighborhood']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#794C8A'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(2).substring(2) + '<span>');
                    });

                    $('.circle-sehir-bazli-dagitim').circleProgress({
                        value: {{ '.0' . round(($data['at_cities']  / $data['cities']) * 100, 0)}},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#d92550'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + {{round(($data['at_cities']  / $data['cities']) * 100, 0)}} + '<span>');
                    });

                    {{--console.log("  {{round(($data['at_cities']  / $data['cities']) * 100, 0)}} ");--}}


                    $('.circle-ilce-bazli-dagitim').circleProgress({
                        value: {{ '.0' . round(($data['at_districts']  / $data['total_districts']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#16aaff'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + {{round(($data['at_districts']  / $data['total_districts']) * 100, 0)}} + '<span>');
                    });


                    $('.circle-mah-calismasi-tamamlanan-acenteler').circleProgress({
                        value: {{ '.' .round(($data['local_location_completed_agencies']  / $data['agency_quantity']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#3ac47d'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + {{ round(($data['local_location_completed_agencies']  / $data['agency_quantity']) * 100, 0) }} + '<span>');
                    });

                    $('.circle-mah-calismasi-tamamlanmayan-acenteler').circleProgress({
                        value: {{ '.' .round(($data['local_location_not_completed_agencies']  / $data['agency_quantity']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#d92550'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + {{ round(($data['local_location_not_completed_agencies']  / $data['agency_quantity']) * 100, 0) }} + '<span>');
                    });


                    $('.circle-at-disi-district').circleProgress({
                        value: {{ '.' .round(($data['at_out_districts']  / $data['total_districts']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#d92550'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + {{ round(($data['at_out_districts']  / $data['total_districts']) * 100, 0) }} + '<span>');
                    });


                    $('.circle-at-disi-mahalleler').circleProgress({
                        value: {{ '.' .round(($data['at_out_districts']  / $data['total_districts']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#d92550'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + {{ round(($data['at_out_districts']  / $data['total_districts']) * 100, 0) }} + '<span>');
                    });


                    $('.circle-ab-mahalleler ').circleProgress({
                        value: {{ '.' .round(($data['ab_locations']  / $data['total_local_locations']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#3ac47d'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + {{ round(($data['ab_locations']  / $data['total_local_locations']) * 100, 0) }} + '<span>');
                    });

                    $('.circle-mb-mahalleler ').circleProgress({
                        value: {{ '.' .round(($data['mb_locations']  / $data['total_local_locations']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#16aaff'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + {{ round(($data['mb_locations']  / $data['total_local_locations']) * 100, 0) }} + '<span>');
                    });


                });
            </script>

            <script>
                $(document).ready(function () {

                    $('#district').prop('disabled', true)
                    $('#selectAgency').val('');

                    $('#city').change(function () {

                        $('#district').prop('disabled', true);
                        $('#agency').prop('disabled', true);

                        let city_id = $(this).children(":selected").attr("id");
                        let city_name = $('#city').children(":selected").attr("data");
                        let district_name = $('#district').children(":selected").attr("data");

                        $.post('{{route('ajax.city.to.district')}}', {
                            _token: token,
                            city_id: city_id
                        }, function (response) {

                            $('#district').html('');
                            $('#district').append(
                                '<option  value="">İlçe Seçin</option>'
                            );
                            $('#neighborhood').prop('disabled', true);
                            $.each(response, function (key, value) {
                                $('#district').append(
                                    '<option data="' + (value['name']) + '" id="' + (value['key']) + '"  value="' + (value['key']) + '">' + (value['name']) + '</option>'
                                );
                            });
                            $('#district').prop('disabled', false);
                            $('#agency').prop('disabled', false);
                        });

                        getAgencies(city_name, district_name);
                    });

                    function getAgencies(city = '', district = '') {

                        $.ajax('/Ajax/GetAgency', {
                            method: 'POST',
                            data: {
                                _token: token,
                                city: city,
                                district: district
                            }
                        }).done(function (response) {

                            $('#agency').html('<option value="">Seçiniz</option>');

                            $.each(response, function (key, value) {
                                $('#agency').append(
                                    '<option id="' + (value['id']) + '"  value="' + (value['id']) + '">' + (value['agency_name']) + ' ŞUBE </option>'
                                );
                            });

                        }).error(function (jqXHR, exception) {
                            ajaxError(jqXHR.status)
                        }).always(function () {

                        });

                    }
                });
            </script>

            <script>
                var oTable = $('.NikolasDataTable.TrGeneralLocalLocations').DataTable({
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100, 250, 500, -1],
                        ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
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
                    dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
                    buttons: [
                        'copy',
                        'pdf',
                        'csv',
                        'print',
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [0, 1, 2]
                            },
                            title: "CK-Bölgesi Olmayan İlçeler - {{date('d/m/Y H:i')}}"
                        },
                        {
                            text: 'Yenile',
                            action: function (e, dt, node, config) {
                                dt.ajax.reload();
                            }
                        }
                    ],
                    responsive: false,
                    processing: false,
                    serverSide: false,
                    ajax: {
                        url: '{!! route('location.GetTrGeneralLocations') !!}',
                        data: function (d) {
                            d.city = $('#city').val();
                            d.district = $('#district').val();
                            d.agency = $('#agency').val();
                            d.area_type = $('#area_type').val();
                        },
                        error: function (xhr, error, code) {
                            ajaxError(xhr.status);
                        },
                        complete: function () {
                            SnackMessage('Tamamlandı!', 'info', 'bl');
                        }
                    },
                    columns: [
                        {data: 'city_name', name: 'city_name'},
                        {data: 'district_name', name: 'district_name'},
                        {data: 'neighborhood_name', name: 'neighborhood_name'},
                        {data: 'area_type', name: 'area_type'},
                        {data: 'agency', name: 'agency'},
                    ],
                    scrollY: false
                });

                var oTable = $('.NikolasDataTable#AgencyLocationStatus').DataTable({
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100, 250, 500, -1],
                        ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
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
                    dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
                    buttons: [
                        'copy',
                        'pdf',
                        'csv',
                        'print',
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            },
                            title: "CK-Acente Lokasyon Statü - {{date('d/m/Y H:i')}}"
                        },
                        {
                            text: 'Yenile',
                            action: function (e, dt, node, config) {
                                dt.ajax.reload();
                            }
                        }
                    ],
                    responsive: false,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('location.getAgencyLocationStatus') !!}',
                        data: function (d) {
                            d.city = $('#a_l_city').val();
                            d.district = $('#a_l_district').val();
                            d.agency = $('#a_l_agency_name').val();
                            d.location_done = $('#a_l_location_done').val();
                        },
                        error: function (xhr, error, code) {
                            ajaxError(xhr.status);
                        },
                        complete: function () {
                            SnackMessage('Tamamlandı!', 'info', 'bl');
                        }
                    },
                    columns: [
                        {data: 'city', name: 'city'},
                        {data: 'district', name: 'district'},
                        {data: 'agency_name', name: 'agency_name'},
                        {data: 'location_count', name: 'location_count'},
                        {data: 'agency_code', name: 'agency_code'},
                        {data: 'details', name: 'details'},
                    ],
                    scrollY: false
                });

                function drawDT() {
                    oTable.draw();
                }

                $('.niko-select-filter').change(delay(function (e) {
                    drawDT();
                }, 1000));


                $('.change-filter').keyup(delay(function (e) {
                    drawDT();
                }, 1000));


                $(document).ready(function () {
                    $('#city').trigger('change');
                });

                $(document).on('change', '#a_l_city', function () {
                    getDistricts('#a_l_city', '#a_l_district');
                });

                $(document).on('click', '.location-detail', function () {
                    let agencyID = $(this).attr('agency-id');
                    $('#ModalAgencyLocation').modal();

                    $('#modalBodyAgencyLocation').block({
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

                    $.ajax('{{route('location.getAgencyLocations')}}', {
                        method: 'POST',
                        data: {
                            _token: token,
                            agency_id: agencyID
                        }
                    }).done(function (response) {

                        if (response.status == 0)
                            ToastMessage('error', response.message, 'HATA!');
                        else if (response.status == 1) {

                            $('#ModalAgencyLocationHeader').text(response.agency);

                            $('#tbodyAgencyLocations').html('');

                            if (response.locations.length == 0)
                                $('#tbodyAgencyLocations').html('<tr class="text-center"><td colspan="4">Burda hiç veri yok!</td></tr>');
                            else
                                $.each(response.locations, function (key, val) {
                                    $('#tbodyAgencyLocations').append(
                                        '<tr>' +
                                        '<td>' + val['city'] + '</td>' +
                                        '<td>' + val['district'] + '</td>' +
                                        '<td>' + val['neighborhood'] + '</td>' +
                                        '<td>' + val['area_type'] + '</td>' +
                                        '</tr>'
                                    );
                                });
                        }

                    }).error(function (jqXHR, exception) {
                        ajaxError(jqXHR.status)
                    }).always(function () {
                        $('#modalBodyAgencyLocation').unblock();
                    });


                });
            </script>
    @endsection


    @section('modals')
        <!-- Large modal -->
            <div class="modal fade" id="ModalAgencyLocation" tabindex="-1" role="dialog"
                 aria-labelledby="myLargeModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalAgencyLocationHeader">Lokasyon Bilgileri</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="modalBodyAgencyLocation" class="modal-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div style="max-height: 400px; overflow-y: auto;" class="cont">
                                        <table class="table table-bordered table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Mahalle</th>
                                                <th>Bölge Tipi</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tbodyAgencyLocations">
                                            <tr class="text-center">
                                                <td colspan="4">Burda hiç veri yok!</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">İptal Et</button>
                            <button type="button" class="btn btn-primary" id="SaveBtn">Kaydet</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
