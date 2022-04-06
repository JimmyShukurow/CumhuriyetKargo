<!-- Large modal => Modal Part Details -->
<div class="modal fade bd-example-modal-lg" id="ModalPartDetails" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Parça Detayları</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="overflow-x: hidden;min-height: 30vh; max-height: 60vh;" id="ModalBarcodes"
                 class="modal-body">
                <div id="ContainerBarcodes"
                     class="container">


                    <h3 class="text-dark text-center mb-4">İlgili Parçaları Seçin</h3>

                    <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                         class="cont">
                        <table style="white-space: nowrap;" id="TableEmployees"
                               class="Table30Padding table-striped table-bordered table-hover table mt-3">
                            <thead>
                            <tr>
                                <th>
                                    <input style="width: 20px;margin-left: 7px;" type="checkbox"
                                           class="select-all-cb form-control">
                                </th>
                                <th>Kargo Tipi</th>
                                <th>Parça No</th>
                                <th>En</th>
                                <th>Boy</th>
                                <th>Yükseklik</th>
                                <th>KG</th>
                                <th>Desi</th>
                                <th>Hacim m<sup>3</sup></th>
                                <th>Teslim Edildi</th>
                            </tr>
                            </thead>
                            <tbody id="tbodyCargoPartDetailsXX">
                            <tr>
                                <td class="text-center" colspan="10">Burda hiç veri yok.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <label id="modalBarcodeFooterLabel" style="float: left;width: 100%;"><b id="barcodeCount">Lütfen HTF
                        için ilgili parçaları seçin.</b></label>
                <button style="white-space: nowrap;" type="button" id="btnSelectPieces" class="btn btn-primary">
                    Parçaları Seç
                </button>
            </div>

        </div>
    </div>
</div>
