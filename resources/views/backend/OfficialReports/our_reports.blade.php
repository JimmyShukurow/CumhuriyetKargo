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

@section('title', 'Tutanaklarım')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-note2 icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Tutanaklarım <b>[{{$unit}}]</b>
                        <div class="page-title-subheading">Bu sayfa üzerinden birminize ait oluşturulan, ve biriminize
                            oluşturulan tutanakları görünyüleyebilir işlem yapabilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-note2 mr-3 text-muted opacity-6"> </i> Tutanaklarınız
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
                        <div class="col-md-4">
                            <label for="receiverCode">Kargo Takip No:</label>
                            <input type="text" data-inputmask="'mask': '99999 99999 99999'"
                                   placeholder="_____ _____ _____" type="text" id="trackingNo"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                        <div class="col-md-4">
                            <label for="receiverCode">Fatura NO:</label>
                            <input type="text" data-inputmask="'mask': 'AA-999999'"
                                   placeholder="__ ______" type="text" id="invoice_number"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                        <div class="col-md-4">
                            <label for="cargoType">Kargo Tipi:</label>
                            <select id="cargoType"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach(allCargoTypes() as $key)
                                    <option value="{{$key}}">{{$key}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="startDate">Başlangıç Tarih:</label>
                            <input type="datetime-local" id="startDate" value="{{ date('Y-m-d') }}T00:00"
                                   class="form-control form-control-sm  niko-select-filter">
                        </div>

                        <div class="col-md-6">
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
                        <th></th>
                        <th>Tutanak No</th>
                        <th>Tutanak Tipi</th>
                        <th>Oluşturan</th>
                        <th>Tutanak Tutulan Birim</th>
                        <th>Onay</th>
                        <th>Kayıt Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Tutanak No</th>
                        <th>Tutanak Tipi</th>
                        <th>Oluşturan</th>
                        <th>Tutanak Tutulan Birim</th>
                        <th>Onay</th>
                        <th>Kayıt Tarihi</th>
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
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/official-report/our-reports.js"></script>
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

                                                <hr>

                                                <h3 class="text-dark text-center mb-4">Parça Detayları</h3>

                                                <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                                     class="cont">
                                                    <table style="white-space: nowrap;" id="TableEmployees"
                                                           class="Table30Padding table-bordered table-hover table table-striped mt-3">
                                                        <thead>
                                                        <tr>
                                                            <th>Kargo Tipi</th>
                                                            <th>Parça No</th>
                                                            <th>En</th>
                                                            <th>Boy</th>
                                                            <th>Yükseklik</th>
                                                            <th>KG</th>
                                                            <th>Desi</th>
                                                            <th>Hacim m<sup>3</sup></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbodyCargoPartDetails">
                                                        <tr>
                                                            <td class="text-center" colspan="8">Burda hiç veri yok.</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
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
