{{-- Standart modal  =>  Add Row Quantity --}}
<div class="modal fade" id="modalAddRowQuantity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Toplu Parça Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="multipleRowQuantity">Eklenecek Parça Adedi:</label>
                                <input id="multipleRowQuantity" type="text"
                                       class="form-control form-control-sm input-mask-trigger"
                                       placeholder="0"
                                       data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':500, 'min':0"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative ">
                                        <label for="enT">En (cm):</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input id="enT" type="text"
                                               class="form-control form-control-sm input-mask-trigger"
                                               placeholder="0"
                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                               im-insert="true" style="text-align: right;">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative ">
                                        <label for="BoyT">Boy (cm):</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input id="BoyT" type="text"
                                               class="form-control form-control-sm input-mask-trigger"
                                               placeholder="0"
                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                               im-insert="true" style="text-align: right;">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative ">
                                        <label for="YukseklikT">Yükseklik (cm):</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input id="YukseklikT"
                                               class="form-control form-control-sm input-mask-trigger"
                                               placeholder="0"
                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                               im-insert="true" style="text-align: right;">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative ">
                                        <label for="AgirlikT">Ağırlık (kg):</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input id="AgirlikT"
                                               class="form-control form-control-sm input-mask-trigger"
                                               placeholder="0"
                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                               im-insert="true" style="text-align: right;">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3 justify-content-md-center">
                                <div class="col-md-11">
                                    <div class="form-row justify-content-md-center">
                                        <div class="col-md-4">
                                            <div class="position-relative ">
                                                <label for="receiverIndividualName">Desi:</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input readonly type="number" value="0"
                                                       id="totalDesi"
                                                       class="form-control no-spin text-center font-weight-bold text-success form-control-sm">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="position-relative ">
                                                <label for="receiverIndividualName">Ücrete Esas
                                                    Ağırlık:</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input readonly type="number" value="0"
                                                       id="RealDesi"
                                                       class="form-control no-spin text-center font-weight-bold text-danger form-control-sm">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="position-relative ">
                                                <label for="receiverIndividualName">Hacim (m<sup>3</sup>):</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input readonly type="number" value="0"
                                                       id="TotalHacim"
                                                       class="form-control no-spin text-center font-weight-bold text-dark form-control-sm">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div style="display: block; margin-bottom: 2rem;" class="modal-footer">
                    <button type="reset" class="float-left btn btn-danger">Formu Sıfırla</button>
                    <button id="btnAddMultipleRow" type="button" class="float-right btn btn-primary">Kaydet</button>
                    <button type="button" class="float-right btn btn-secondary" data-dismiss="modal">Kapat</button>
                </div>
            </form>
        </div>
    </div>
</div>
