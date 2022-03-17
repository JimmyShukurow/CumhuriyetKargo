<!-- Large modal => Modal Cargo Details -->
<div class="modal fade bd-example-modal-lg" id="ModalExpeditionDetails" tabindex="22" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Sefer Detayları</h5>
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
                                        <h6 id="titleExpeditionCarPlaque" class="menu-header-subtitle">###/###</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">

                            <div class="main-card mb-12 card">
                                <div class="card-header"><i
                                            class="header-icon pe-7s-box2 icon-gradient bg-plum-plate"> </i>Sefer
                                    Detayları
                                    <div class="btn-actions-pane-right">
                                        <div class="nav">
                                            <a data-toggle="tab" href="#tabExpeditionInfo"
                                               class="btn-pill btn-wide btn btn-outline-alternate btn-sm show active">Sefer
                                                Bilgileri</a>
                                            <a data-toggle="tab" href="#tabExpeditionCargoes"
                                               class="btn-pill btn-wide mr-1 ml-1 btn btn-outline-alternate btn-sm show ">Kargolar
                                                </a>
                                            <a data-toggle="tab" href="#tabExpeditionSeals"
                                               class="btn-pill btn-wide btn btn-outline-alternate btn-sm show"> Mühürler </a>
                                            <a data-toggle="tab" href="#tabExpeditionMovements"
                                               class="btn-pill ml-1 btn-wide btn btn-outline-alternate btn-sm show">Sefer Hareketleri</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <x-expedition.modal.tabs.sefer-bilgileri/>

                                        <x-expedition.modal.tabs.kargolar/>

                                        <x-expedition.modal.tabs.muhurler/>

                                        <x-expedition.modal.tabs.sefer-hareketleri/>
                                    </div>
                                </div>

                            </div>

                            @if(@$data['type'] == 'main_cargo')
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
                            @endif

                            @if(@$data['type'] == 'admin_cancel_cargo')
                                <li id="SetResult" class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-12">
                                                <div class="p-1">
                                                    <button
                                                            id="btnSetResult"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-info">
                                                        <i class="pe-7s-check text-info opacity-7 btn-icon-wrapper mb-2"> </i>
                                                        Sonuç Gir
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li id="CargoRestore" class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-12">
                                                <div class="p-1">
                                                    <button
                                                            id="btnCargoRestore"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                                        <i class="pe-7s-check text-s opacity-7 btn-icon-wrapper mb-2"> </i>
                                                        Kargoyu Geri Yükle
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

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

@if(@$data['type'] == 'main_cargo')
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
@endif

@if(@$data['type'] == 'main_cargo')
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
@endif
