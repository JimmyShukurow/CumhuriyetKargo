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
