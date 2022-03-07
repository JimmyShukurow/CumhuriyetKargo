<div class="form-group row">

    <div style="padding-top: 0; padding-bottom: 0;" class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsFirstDate">İlk Tarih:</label>
                    <input type="date" id="agencyPaymentsFirstDate" value="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsLastDate">Son Tarih:</label>
                    <input type="date" id="agencyPaymentsLastDate" value="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsAppNo">Başvuru No:</label>
                    <input type="text" id="agencyPaymentsAppNo" class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsAgency">Şube:</label>
                    <select style="width: 100%;" class="form-control-sm form-control" name=""
                            id="agencyPaymentsAgency">
                        <option value="">Seçiniz</option>
                        @foreach($data['agencies'] as $key)
                            <option value="{{$key->id}}">{{$key->agency_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsAppNo">Ödeme No:</label>
                    <input type="text" id="agencyPaymentsPaymentNo" class="form-control form-control-sm">
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsPaymentChannel">Ödeme Kanalı:</label>
                    <select class="form-control-sm form-control" name="" id="agencyPaymentsPaymentChannel">
                        <option value="">Seçiniz</option>
                        @foreach($data['agency_payments_payment_channels'] as $key)
                            <option value="{{$key->payment_channel}}">{{$key->payment_channel}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>


<table style="width: 100%; white-space: nowrap;" id="tableAgencyPayments"
       class="table Table20Padding table-bordered table-hover">
    <thead>
    <tr>
        <th>Ödeme No:</th>
        <th>Onay Tipi</th>
        <th>BaşvuruNo</th>
        <th>Acente</th>
        <th>Ödenen Tutar</th>
        <th>Ödeme Tarihi</th>
        <th>Ödeyen Ad Soyad</th>
        <th>Ödeme Kanalı</th>
        <th>Onaylayan</th>
        <th>Açıklama</th>
        <th>Kayıt Tarihi</th>
        <th>İşlem</th>
        <th>İşlem</th>
    </tr>
    </thead>
</table>

<script src="/backend/assets/scripts/safe/general/agency-payments.js"></script>


