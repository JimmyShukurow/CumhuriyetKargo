{{-- Standart Modal - Enabled/Disabled User --}}
<div class="modal fade" id="modalEnabledDisabled" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hesabı Aktif Pasif Yap</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="userNameSurname">Ad Soyad/Firma</label>
                            <input id="userNameSurname" class="form-control" type="text" readonly
                                   value="Nurullah Güç">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="accountStatus">Hesap Durumu</label>
                            <select class="form-control" name="" id="accountStatus">
                                <option value="0">Pasif</option>
                                <option value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button id="btnSaveStatus" type="button" class="btn btn-primary">Kaydet</button>
            </div>
        </div>
    </div>
</div>
