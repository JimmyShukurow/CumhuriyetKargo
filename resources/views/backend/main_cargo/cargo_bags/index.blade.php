@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <link rel="stylesheet" href="/backend/assets/css/ck-bag-barcode.css">
@endpush()

@section('title', 'Acente Torba & Çuval')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Torba & Çuval
                        <div class="page-title-subheading">
                            Bu modül üzerinden oluşturmuş olduğunuz torba ve çuvallarınızı görüntüleyebilirsiniz. <b>
                                Çuval
                                veya Torba silebilmeniz için, içerdiği kargo sayısı alanının 0 olması gerekmektedir.
                            </b>
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <button id="btnCreateNewBag" type="button" aria-haspopup="true" aria-expanded="false"
                                class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                            Yeni Torba & Çuval
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-box2 mr-3 text-muted opacity-6"> </i> Torba & Çuval
                </div>

            </div>
            <form id="search-form">
                <div class="row p-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="startDate">Başlangıç Tarihi:</label>
                            <input type="date" value="{{date('Y-m-d')}}"
                                   class="form-control form-control-sm niko-select-filter niko-filter" id="startDate">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="endDate">Bitiş Tarihi:</label>
                            <input type="date" value="{{date('Y-m-d')}}"
                                   class="form-control form-control-sm niko-select-filter niko-filter" id="endDate">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="creatorUser">Oluşturan Kullanıcı:</label>
                            <input type="text" class="form-control form-control-sm niko-filter" id="creatorUser">
                        </div>
                    </div>
                </div>
            </form>

            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-borderless table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Çuval Takip No</th>
                        <th>Tip</th>
                        <th>İçerdiği Kargo Sayısı</th>
                        <th>Statü</th>
                        <th>Oluşturan</th>
                        <th>Son İndiren Kişi</th>
                        <th>Son İndirme Tarihi</th>
                        <th>İndirme Yapıldı</th>
                        <th>Oluşturulma Zamanı</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Çuval Takip No</th>
                        <th>Tip</th>
                        <th>İçerdiği Kargo Sayısı</th>
                        <th>Statü</th>
                        <th>Oluşturan</th>
                        <th>Son İndiren Kişi</th>
                        <th>Son İndirme Tarihi</th>
                        <th>İndirme Yapıldı</th>
                        <th>Oluşturulma Zamanı</th>
                        <th>İşlem</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/JsBarcode.js"></script>
    <script src="/backend/assets/scripts/QrCode.min.js"></script>
    <script src="/backend/assets/scripts/cargo-bags/index.js"></script>

@endsection

@section('modals')
    <!-- Large modal => Modal Create Bag -->
    <div class="modal fade bd-example-modal-lg" id="modalCreateBag" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Çuval & Torba Oluştur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="modalBodyCreateBag" class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold" for="bag_type">Tip:</label>
                                <select name="" id="bag_type" class="font-weight-bold form-control form-control-sm">
                                    <option value="">Seçiniz</option>
                                    <option value="Torba">Torba</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Çıkış</label>
                                <input type="text" class="form-control form-control-sm text-primary font-weight-bold"
                                       readonly value="{{$departurePoint}}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Varış</label>
                                <input type="text" class="form-control form-control-sm text-primary font-weight-bold"
                                       readonly value="{{$arrivalPoint}}">
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                    <button id="btnInsertBag" type="button" class="btn btn-primary">Oluştur
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Large modal => Modal Bag Details -->
    <div class="modal fade bd-example-modal-lg" id="modalBagDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBagDetailHeader"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="modalBodyBagDetails" class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="overflow-y: auto; max-height: 425px;" class="cont">
                                <table style="white-space: nowrap;" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Fatura No</th>
                                        <th>Parça No</th>
                                        <th>Kargo Tipi</th>
                                        <th>Alıcı</th>
                                        <th>Gönderici</th>
                                        <th>Gönderici İl/İlçe</th>
                                        <th>Yükleyen</th>
                                        <th>Yükleme Zamanı</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyBagDetails"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display: flex;align-items: center; justify-content:center" class="modal-footer">
                    <div id="numberOfCargoesInBag" style="position: absolute; left: 20px"></div>
                    <button id="btnRefreshBagDetails" class="btn btn-warning">Yenile</button>
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
                <div style="overflow-x: hidden;min-height: 30vh; max-height: 60vh;" id="ModalBarcodes"
                     class="modal-body">
                    <div id="ContainerBarcodes"
                         class="container">
                        <div style="margin-top: -2px;" class="row barcode-row  ">
                            <div class="col-12">
                                <h5 class="font-weight-bold text-center barcode-slogan">Cumhuriyet Kargo Daima
                                    Önde..</h5>
                                <h2 class="font-weight-bold text-center  text-dark m-0 barcodeDepartureTC"> İST. AVRUPA
                                    TRM. </h2>
                            </div>

                            <div class="col-12 p-0">
                                <table style="min-height: 200px;" class="shipmentReceiverInfo">
                                    <tr>
                                        <td>
                                            <h2 id="" class="text-dark font-weight-bold barcodeBagType text-center">
                                                ÇUVAL</h2>
                                        </td>
                                        <td rowspan="3">
                                            <div style="position:relative; left: 33px;" class="qrcodes"
                                                 id="qrcode"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 id="barcodeCreatedAt" class="text-dark text-center font-weight-bold">
                                                10.11.2021 15:46</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-weight-bold">
                                            <span class="barcodeBagType">Çuval</span> REFERANS NO: <br> <span
                                                id="barcodeTrackingNo">54568 78894 12357</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-12 text-right">
                                <h3 class="font-weight-bold text-center text-dark barcodeArrivalTC">İST - AVRUPA
                                    TRM.</h3>
                            </div>

                            <div style="height: 105px;" class="col-12 code39-container">
                                <svg id="barcodeCode39" class="barcode"></svg>
                            </div>
                        </div>
                        <div style="clear: both;" class="barcode-divider"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <label id="modalBarcodeFooterLabel" style="float: left;width: 100%;"><b id="barcodeCount">1</b> Adet
                        barkod hazırlandı.</label>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                    <button type="button" id="btnPrintBarcode" class="btn btn-primary">Yazdır</button>
                </div>

            </div>
        </div>
    </div>
@endsection
