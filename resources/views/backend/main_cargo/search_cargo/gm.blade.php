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
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
    <link href="/backend/assets/css/multiselect-minifier.css" rel="stylesheet"/>
@endpush

@section('title', 'GM Kargo Sorgulama Ekranı')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Kargo Sorgulama (GM)
                        <div class="page-title-subheading">Bu sayfa üzerinden Cumhuriyet Kargo'nun taşıdığı tüm
                            kargoları sorgulayabilirsiniz (Tek seferde en fazla 90 günlük kayıt görüntüleyebilirsiniz).
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-box2 mr-3 text-muted opacity-6"> </i> CKG-Sis (GM) Kargo Sorgulama
                    Ekranı
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
                            <label for="cargoType">Kargo Tipi:</label>
                            <select id="cargoType"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach(allCargoTypes() as $key)
                                    <option value="{{$key}}">{{$key}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="startDate">Başlangıç Tarih:</label>
                            <input type="datetime-local" id="startDate" value="{{ date('Y-m-d') }}T00:00"
                                   class="form-control form-control-sm  niko-select-filter">
                        </div>

                        <div class="col-md-2">
                            <label for="finishDate">Bitiş Tarihi:</label>
                            <input type="datetime-local" id="finishDate" value="{{ date('Y-m-d') }}T23:59"
                                   class="form-control form-control-sm  niko-select-filter">
                        </div>
                        <div class="col-md-2">
                            <label for="filterTransporter">Taşıyan:</label>
                            <select id="filterTransporter"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                <option value="CK">CK</option>
                                <option value="YK">YK</option>
                                <option value="MNG">MNG</option>
                            </select>
                        </div>

                    </div>

                    <div class="row pt-1">

                        <div class="col-md-2">
                            <label for="filterDepartureAgency">Çıkış Şube:</label>
                            <select id="filterDepartureAgency"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option value="{{$key->id}}">{{$key->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="filterDepartureAgencyCode">Çıkış Şube Kodu:</label>
                            <input type="text" data-inputmask="'mask': '9999'"
                                   placeholder="____" type="text" id="filterDepartureAgencyCode"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                        <div class="col-md-2">
                            <label for="filterDepartureRegion">Çıkış Bölge:</label>
                            <select id="filterDepartureRegion"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['tc'] as $key)
                                    <option value="{{$key->id}}">{{$key->tc_name . ' TRM.'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="filterArrivalAgency">Varış Şube:</label>
                            <select id="filterArrivalAgency"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option value="{{$key->id}}">{{$key->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="filterArrivalAgencyCode">Varış Şube Kodu:</label>
                            <input type="text" data-inputmask="'mask': '9999'"
                                   placeholder="____" type="text" id="filterArrivalAgencyCode"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                        <div class="col-md-2">
                            <label for="filterArrivalRegion">Varış Bölge:</label>
                            <select id="filterArrivalRegion"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['tc'] as $key)
                                    <option value="{{$key->id}}">{{$key->tc_name . ' TRM.'}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row pt-1">

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
                            <select disabled id="receiverDistrict"
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
                            <select disabled class="form-control form-control-sm" id="senderDistrict">
                                <option value="">İlçe Seçin</option>
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

                    <div class="row pt-1">
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

                <table style="width: 100%; white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-bordered table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Fatura No</th>
                        <th>Çıkış Şube</th>
                        <th>Ç.Ş. Kodu</th>
                        <th>Gönderici Adı</th>
                        <th>Gönderici İl</th>
                        <th>Alıcı Adı</th>
                        <th>Alıcı İl</th>
                        <th>Alıcı İlçe</th>
                        <th>Kargo Tipi</th>
                        <th>Ödeme Tipi</th>
                        <th>Ücret</th>
                        <th>Statü</th>
                        <th>Kayıt Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Total:</th>
                        <th colspan="3" class="text-left"></th>
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
    <!-- Include the plugin's CSS and JS: -->
    <script type="text/javascript" src="/backend/assets/scripts/bootstrap-multiselect.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/main-cargo/gm-search-cargo.js"></script>
    <script>var typeOfJs = 'search_cargo_gm'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>
    <script src="/backend/assets/scripts/official-report/report-view.js"></script>
    <script src="/backend/assets/scripts/customers/customer-details.js"></script>
@endsection


@section('modals')

    @php $data = ['type' => 'search_cargo_gm']; @endphp
    @include('backend.main_cargo.cargo_details.modal_cargo_details')

    @include('backend.OfficialReports.report_modal')
    @include('backend/customers/agency/modal_customer_details')
@endsection
