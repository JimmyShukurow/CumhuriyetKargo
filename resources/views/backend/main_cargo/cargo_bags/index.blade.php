@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
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
                                    <option value="Çuval">Çuval</option>
                                </select>
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
                <div style="display: flex;align-items: center; justify-content: center;" class="modal-footer">
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
@endsection
