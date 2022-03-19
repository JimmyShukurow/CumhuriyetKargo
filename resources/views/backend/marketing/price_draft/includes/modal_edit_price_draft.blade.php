{{-- Standart Modal - New Desi Interval --}}
<div class="modal fade" id="ModalEditPriceDraft" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yeni Fiyat Taslağı Oluştur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="ModalBodyEditPriceDraft" class="modalEnabledDisabled modal-body ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="editDraftName">Taslak Adı:</label>
                            <input id="editDraftName" class="form-control form-control-sm input-price-draft" type="text"
                                   value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editFilePrice">Dosya ücreti:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="editFilePrice"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editMiPrice">Mi ücreti:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="editMiPrice"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editDesi1_5">1-5 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="editDesi1_5"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editDesi6_10">6-10 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="editDesi6_10"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editDesi11_15">11-15 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="editDesi11_15"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editDesi16_20">16-20 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="editDesi16_20"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editDesi21_25">21-25 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="editDesi21_25"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editDesi26_30">26-30 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="editDesi26_30"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editAmountOfIncrease">30 Üstü Artış Fiyatı:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="editAmountOfIncrease"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editAgencyPermission">Acente İzin:</label>
                            <select class="form-control form-control-sm input-price-draft" id="editAgencyPermission">
                                <option value="1">Evet</option>
                                <option value="0">Hayır</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button id="btnUpdatePriceDraft" type="button" class="btn btn-primary">Fiyat Taslağını Kaydet
                </button>
            </div>
        </div>
    </div>
</div>
