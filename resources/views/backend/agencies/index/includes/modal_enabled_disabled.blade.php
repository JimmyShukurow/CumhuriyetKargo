{{-- Standart Modal - Enabled/Disabled User --}}
<div class="modal fade" id="modalEnabledDisabled" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Şubeyi Aktif Pasif Yap</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="enableDisable_agencyName">Şube Adı</label>
                            <input id="enableDisable_agencyName" class="form-control" type="text" readonly
                                   value="">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="accountStatus">Şube Durumu</label>
                            <select class="form-control" name="" id="accountStatus">
                                <option value="0">Pasif</option>
                                <option value="1">Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="statusDesc">Statü Açıklaması</label>
                            <textarea name="" id="statusDesc" cols="30" rows="3" class="form-control"></textarea>
                            <em class="text-danger">Şube hesabı pasif edilmesinden dolayı kargo kesim modülüne
                                erişemeyecektir ve açıklamada girdiğiniz text ile karşılaşacaktır.</em>
                            <em class="text-success"> <br>
                                <b>Default Message: Şubeniz pasif edilmiştir, detaylı bilgi için sistem destek
                                    ekibine ulaşın.</b>
                            </em>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="accountPermissionOfCreateCargo">Kargo Kesim İzni:</label>
                            <select class="form-control" name="" id="accountPermissionOfCreateCargo">
                                <option value="0">Pasif</option>
                                <option value="1">Aktif</option>
                            </select>
                            <em class="text-danger">Kargo kesim iznini pasif yaparsanız acente sadece sistemi yeni
                                fatura
                                modülünü kullanamayacak ve kargo kesimine hiç bir şekilde izin verilmeyecektir.</em>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="accountOperationStatus">Operasyon Statü:</label>
                            <select class="form-control" id="accountOperationStatus">
                                <option value="0">Pasif</option>
                                <option value="1">Aktif</option>
                            </select>
                            <em class="text-danger">Operasyonu pasif yaparsanız acentenin mahalli lokasyonuna çıkan
                                kargolar AT dışı veya varsa entegre partner firma olarak geçecektir.</em>
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
