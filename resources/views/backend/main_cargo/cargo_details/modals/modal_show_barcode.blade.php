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
