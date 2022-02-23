<div class="form-group row">

    <div style="padding-top: 0; padding-bottom: 0;" class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsFirstDate">İlk Tarih:</label>
                    <input type="date" id="agencyPaymentAppsFirstDate" value="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsLastDate">Son Tarih:</label>
                    <input type="date" id="agencyPaymentAppsLastDate" value="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsAppNo">Başvuru No:</label>
                    <input type="text" id="agencyPaymentAppsAppNo" class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsAgency">Şube:</label>
                    <select style="width: 100%;" class="form-control-sm form-control" name=""
                            id="agencyPaymentAppsAgency">
                        <option value="">Seçiniz</option>
                        @foreach($data['agencies'] as $key)
                            <option value="{{$key->id}}">{{$key->agency_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsConfirm">Onay:</label>
                    <select class="form-control-sm form-control" name="" id="agencyPaymentAppsConfirm">
                        <option value="">Seçiniz</option>
                        <option value="0">Onay Bekliyor</option>
                        <option value="1">Onaylandı</option>
                        <option value="-1">Reddedildi</option>
                    </select>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsPaymentChannel">Ödeme Kanalı:</label>
                    <select class="form-control-sm form-control" name="" id="agencyPaymentAppsPaymentChannel">
                        <option value="">Seçiniz</option>
                        @foreach($data['payment_channels'] as $key)
                            <option value="{{$key->payment_channel}}">{{$key->payment_channel}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>


<table style="width: 100%; white-space: nowrap;" id="tableAgencyPaymentApps"
       class="table Table20Padding table-bordered table-hover">
    <thead>
    <tr>
        <th>B.No</th>
        <th>Şube</th>
        <th>Başvuru Yapan</th>
        <th>Ödenen</th>
        <th>Onyln. Ödeme</th>
        <th>Ödeme Kanalı</th>
        <th>Ekler</th>
        <th>Açıklama</th>
        <th>Para Birimi</th>
        <th>Onay</th>
        <th>Onaylayan</th>
        <th>Onay Tarihi</th>
        <th>Kayıt Tarihi</th>
        <th>İşlem</th>
    </tr>
    </thead>
</table>

<script src="/backend/assets/scripts/safe/general/agency-payment-apps.js"></script>


