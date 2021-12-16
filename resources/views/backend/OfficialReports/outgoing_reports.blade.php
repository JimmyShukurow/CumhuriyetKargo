@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <style>
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
            top: 6px;
            left: 5px;
        }
    </style>
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
@endpush

@section('title', 'Giden Tutanaklar')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-note2 icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Giden Tutanaklar <b>[{{$unit}}]</b>
                        <div class="page-title-subheading">Bu sayfa üzerinden birminize tarafından oluşturulan
                            tutanakları görünyüleyebilir işlem yapabilirsiniz. (Tek seferde max. 500 kayıt
                            veya max 90 günlük kayıt görüntüleyebilirsiniz.)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-note2 mr-3 text-muted opacity-6"> </i> Giden Tutanaklar
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
                            <label for="filterReportSerialNumber">Tutanak NO:</label>
                            <input type="text" data-inputmask="'mask': 'AA-999999'"
                                   placeholder="__ ______" type="text" id="filterReportSerialNumber"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="filterConfirm">Onay:</label>
                            <select id="filterConfirm"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                <option value="0">Onay Bekliyor</option>
                                <option value="1">Onaylandı</option>
                                <option value="-1">Onaylanmadı</option>
                            </select>
                        </div>


                        <div class="col-md-3">
                            <label for="filterInvoiceNumber">Kargo - Fatura NO:</label>
                            <input type="text" data-inputmask="'mask': 'AA-999999'"
                                   placeholder="__ ______" type="text" id="filterInvoiceNumber"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>


                        <div class="col-md-3">
                            <label for="filterReportType">Tutanak Tipi:</label>
                            <select id="filterReportType"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                <option value="HTF">HTF</option>
                                <option value="UTF">UTF</option>
                            </select>
                        </div>


                        <div class="col-md-3 mt-2">
                            <label for="filterStartDate">Başlangıç Tarih:</label>
                            <input type="datetime-local" id="filterStartDate" value="{{ date('Y-m-d') }}T00:00"
                                   class="form-control form-control-sm  niko-select-filter">
                        </div>

                        <div class="col-md-3 mt-2">
                            <label for="filterFinishDate">Bitiş Tarihi:</label>
                            <input type="datetime-local" id="filterFinishDate" value="{{ date('Y-m-d') }}T23:59"
                                   class="form-control form-control-sm  niko-select-filter">
                        </div>

                        <div id="column-agency" class="col-md-3 mt-2">
                            <div class="form-group position-relative">
                                <label for="filterSelectReportedAgency">Şube Seçin:</label>
                                <select style="width:100%;" name="select_reported_agency"
                                        class="form-control form-control-sm reported-units"
                                        id="filterSelectReportedAgency">
                                    <option value="">Seçiniz</option>
                                    @foreach($agencies as $key)
                                        <option
                                            value="{{$key->id}}">{{$key->agency_name . ' ŞUBE'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="column-tc" class="col-md-3 mt-2">
                            <div class="form-group position-relative">
                                <label for="filterSelectReportedTc">Transfer Merkezi Seçin:</label>
                                <select style="width:100%;" name="select_reported_tc"
                                        class="form-control form-control-sm reported-units"
                                        id="filterSelectReportedTc">
                                    <option value="">Seçiniz</option>
                                    @foreach($tc as $key)
                                        <option value="{{$key->id}}">{{$key->tc_name . ' TRM.'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row pt-2">

                        <div class="col-md-3">
                            <label for="filterDetectingUser">Düzenleyen:</label>
                            <input type="text" id="filterDetectingUser"
                                   class="form-control niko-filter form-control-sm">
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="filterDescription" class="">Açıklama:</label>
                                <input type="text" id="filterDescription"
                                       class="form-control niko-filter form-control-sm">
                            </div>
                        </div>

                    </div>

                    <div class="row pt-2">
                        <div class="row pt-2">
                            <div class="col">
                                <label for="filterByDate">Tarihe göre ara</label>
                                <input type="checkbox" id="filterByDate" name="filterByDate" class="niko-filter">
                            </div>
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
                        <th>Tespit Eden Birim</th>
                        <th>Düzenleyen</th>
                        <th>Tutanak Tutulan Birim</th>
                        <th>Açıklama</th>
                        <th>Onay</th>
                        <th>Kayıt Tarihi</th>
                        <th>Detay</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Tutanak No</th>
                        <th>Tutanak Tipi</th>
                        <th>Tespit Eden Birim</th>
                        <th>Düzenleyen</th>
                        <th>Tutanak Tutulan Birim</th>
                        <th>Açıklama</th>
                        <th>Onay</th>
                        <th>Kayıt Tarihi</th>
                        <th>Detay</th>
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
    <script src="/backend/assets/scripts/official-report/outgoing-reports.js"></script>
@endsection


@section('modals')
    <!-- Large modal => Modal Report Details -->
    <div class="modal fade bd-example-modal-lg" id="ModalReportDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Tutanak Detayları</h5>
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
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract8.jpg');">
                                    </div>
                                    <div class="menu-header-content text-center">
                                        <div>
                                            <h5 id="titleReportTitleType" class="menu-header-title">###</h5>
                                            <h5 id="titleReportSerialNumber" class="menu-header-title">###</h5>
                                            <h6 style="color: #fff;" id="titleReportDate"
                                                class="menu-header-subtitle font-weight-bold">###/###</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">

                                <div class="main-card mb-12 card">
                                    <div class="card-header"><i
                                            class="header-icon pe-7s-note2 icon-gradient bg-plum-plate"> </i>Tutanak
                                        Detayları
                                        <div class="btn-actions-pane-right">
                                            <div class="nav">
                                                <a data-toggle="tab" href="#tabCargoInfo"
                                                   class="btn-pill btn-wide btn btn-outline-alternate btn-sm show active">Tutanak
                                                    Bilgileri</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="tabCargoInfo" role="tabpanel">

                                                <div class="row mt-2 mb-2">
                                                    <div class="col-md-3">
                                                        <div class="form-group position-relative">
                                                            <label for="">Tespit Eden Birim:</label>
                                                            <b style="display: block;"
                                                               class="text-primary font-weight-bold"
                                                               id="reportDetectingUnit"></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group position-relative">
                                                            <label for="">Düzenleyen:</label>
                                                            <b style="display: block;"
                                                               class="text-primary font-weight-bold"
                                                               id="reportDetectingUser"></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group position-relative">
                                                            <label for="">Tutanak Tutulan Birim Tipi:</label>
                                                            <b style="display: block;"
                                                               class="text-danger font-weight-bold"
                                                               id="reportRealReportedUnitType"></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group position-relative">
                                                            <label for="">Tutanak Tutulan (Hata Yapan) Birim:</label>
                                                            <b style="display: block;"
                                                               class="text-danger font-weight-bold"
                                                               id="reportReportedUnit"></b>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <table id="CartHTF"
                                                               class="TableNoPadding table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" id="titleBranch" colspan="2">
                                                                    HTF (Hasar Tespit Formu)
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td style="white-space: nowrap;" class="static">Fatura
                                                                    Numarası:
                                                                </td>
                                                                <td style="text-decoration: underline; cursor: pointer;"
                                                                    id="htfInvoiceNumber"
                                                                    class="font-weight-bold cargo-invoice-number text-dark"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="white-space: nowrap;" class="static">İlgili
                                                                    Parçalar:
                                                                </td>
                                                                <td id="htfCargoPieces"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="white-space: nowrap;" class="static">Hasar
                                                                    Açıklaması:
                                                                </td>
                                                                <td id="htfDamageDescription"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="white-space: nowrap;" class="static">İçerik
                                                                    Tespiti:
                                                                </td>
                                                                <td id="htfContentDetection"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="white-space: nowrap;" class="static">Hasar
                                                                    Nedenleri:
                                                                </td>
                                                                <td id="htfDamageDetails"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="white-space: nowrap;" class="static">Yapılan
                                                                    İşlemler:
                                                                </td>
                                                                <td id="htfTransactionDetails"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <table id="CartUTF"
                                                               class="TableNoPadding table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" id="titleBranch" colspan="2">
                                                                    UTF (Uygunsuzluk Tespit Formu)
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td style="white-space: nowrap;" class="static pr-3">
                                                                    Uygunsuzluk Nedenleri:
                                                                </td>
                                                                <td id="utfImproprietyDetails"></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="white-space: nowrap;" class="static pr-3">
                                                                    Uygunsuzluk Açıklaması:
                                                                </td>
                                                                <td id="utfImproprietyDescription"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>

                                                <div class="row mt-2 mb-2">
                                                    <div class="col-md-3">
                                                        <div class="form-group position-relative">
                                                            <label for="">Tutanak Seri No:</label>
                                                            <b style="display: block;"
                                                               class="text-dark font-weight-bold"
                                                               id="reportReportSerialNumber"></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group position-relative">
                                                            <label for="">Onay Durumu:</label>
                                                            <b style="display: block;"
                                                               class="font-weight-bold"
                                                               id="reportReportConfirm"></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group position-relative">
                                                            <label for="">Onaylayan:</label>
                                                            <b style="display: block;"
                                                               class="text-primary font-weight-bold"
                                                               id="reportReportConfirmingUser"></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group position-relative">
                                                            <label for="">Onay Tarihi:</label>
                                                            <b style="display: block;"
                                                               class="text-primary font-weight-bold"
                                                               id="reportReportConfirmingDate"></b>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row justify-content-center">
                                                    <div class="col-md-3">
                                                        <div class="form-group position-relative">
                                                            <label for="">Tutanak Kayıt Tarihi:</label>
                                                            <b style="display: block;"
                                                               class="text-dark font-weight-bold"
                                                               id="reportReportCreatedAt"></b>
                                                        </div>
                                                    </div>
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
