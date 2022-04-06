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
