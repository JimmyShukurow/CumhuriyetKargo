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
