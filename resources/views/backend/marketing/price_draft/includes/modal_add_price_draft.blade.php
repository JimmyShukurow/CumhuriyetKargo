{{-- Standart Modal - New Desi Interval --}}
<div class="modal fade" id="ModalAddPriceDraft" tabindex="-1" role="dialog"
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
            <div id="ModalBodyAddPriceDraft" class="modalEnabledDisabled modal-body ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="insertDraftName">Taslak Adı:</label>
                            <input id="insertDraftName" class="form-control form-control-sm input-price-draft" type="text"
                                   value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertFilePrice">Dosya ücreti:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="insertFilePrice"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertMiPrice">Mi ücreti:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="insertMiPrice"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertDesi1_5">1-5 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="insertDesi1_5"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertDesi6_10">6-10 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="insertDesi6_10"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertDesi11_15">11-15 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="insertDesi11_15"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertDesi16_20">16-20 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="insertDesi16_20"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertDesi21_25">21-25 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="insertDesi21_25"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertDesi26_30">26-30 Desi:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="insertDesi26_30"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertAmountOfIncrease">30 Üstü Artış Fiyatı:</label>
                            <input class="form-control form-control-sm input-price-draft input-mask-trigger" id="insertAmountOfIncrease"
                                   placeholder="₺ 0.00"
                                   type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insertAgencyPermission">Acente İzin:</label>
                            <select class="form-control form-control-sm input-price-draft" id="insertAgencyPermission">
                                <option value="1">Evet</option>
                                <option value="0">Hayır</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button id="btnInsertPriceDraft" type="button" class="btn btn-primary">Fiyat Taslağını Kaydet
                </button>
            </div>
        </div>
    </div>
</div>
