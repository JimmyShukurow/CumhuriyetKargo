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
