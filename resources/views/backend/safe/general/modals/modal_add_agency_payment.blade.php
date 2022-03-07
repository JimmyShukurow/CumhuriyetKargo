{{-- Large modal => Modal Payment App Details --}}
<div class="modal fade bd-example-modal-lg" id="ModalAddAgencyPayment" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Acente Ödemesi Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyAgencyPaymentAppDetails"
                 class="modal-body">

                <div class="row">
                    <div class="col-md-6">
                        <label for="addAgencyPaymentAgency">Acente: <span class="text-danger">*</span></label>
                        <select style="width: 100%;" class="form-control-sm form-control add-agency-payment-fields"
                                name=""
                                id="addAgencyPaymentAgency">
                            <option value="">Seçiniz</option>
                            @foreach($data['agencies'] as $key)
                                <option value="{{$key->id}}">{{$key->agency_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="addAgencyPaymentPayingNameSurname">Ödeyen Ad Soyad: <span
                                class="text-danger">*</span></label>
                        <input type="text" id="addAgencyPaymentPayingNameSurname"
                               class="form-control form-control-sm add-agency-payment-fields">
                    </div>
                    <div class="col-md-4">
                        <label for="addAgencyPaymentPaymentChannel">Ödeme Kanalı: <span
                                class="text-danger">*</span></label>
                        <select class="form-control-sm form-control add-agency-payment-fields" name=""
                                id="addAgencyPaymentPaymentChannel">
                            <option value="">Seçiniz</option>
                            <option value="EFT/HAVALE">EFT/HAVALE</option>
                            <option value="POS">POS</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="addAgencyPaymentPayment">Ödenen Tutar: <span
                                class="text-danger">*</span></label>
                        <input class="form-control form-control-sm input-mask-trigger add-agency-payment-fields"
                               id="addAgencyPaymentPayment"
                               placeholder="₺ 0.00" type="text"
                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                               im-insert="true" style="text-align: right;">
                    </div>
                    <div class="col-md-4">
                        <label for="addAgencyPaymentPaymentDate">Ödeme Tarihi: <span
                                class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control form-control-sm"
                               id="addAgencyPaymentPaymentDate" value="{{date('Y-m-d')}}T{{date('H:m')}}">
                    </div>
                    <div class="col-md-12">
                        <label for="addAgencyPaymentDescription">Açıklama:</label>
                        <textarea name="" placeholder="Açıklama girin (Opsiyonel)" id="addAgencyPaymentDescription"
                                  cols="30" rows="5"
                                  class="form-control add-agency-payment-fields"></textarea>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary" id="btnSaveAgencyPayment">Ödeme Ekle</button>
            </div>
        </div>
    </div>
</div>
