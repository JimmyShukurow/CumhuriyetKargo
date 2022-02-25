<div class="form-group row">

    <div style="padding-top: 0; padding-bottom: 0;" class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencySafeStatusFirstDate">İlk Tarih:</label>
                    <input type="date" id="agencySafeStatusFirstDate" value="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencySafeStatusLastDate">Son Tarih:</label>
                    <input type="date" id="agencySafeStatusLastDate" value="{{ date('Y-m-d') }}"
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
                    <label for="agencyPaymentsConfirm">Onay:</label>
                    <select class="form-control-sm form-control" name="" id="agencyPaymentsConfirm">
                        <option value="">Seçiniz</option>
                        <option value="0">Onay Bekliyor</option>
                        <option value="1">Onaylandı</option>
                        <option value="-1">Reddedildi</option>
                    </select>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsPaymentChannel">Ödeme Kanalı:</label>
                    <select class="form-control-sm form-control" name="" id="agencyPaymentsPaymentChannel">
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


<table style="width: 100%; white-space: nowrap;" id="tableAgencySafeStatus"
       class="table Table20Padding table-bordered table-hover">
    <thead>
    <tr>
        <th>Şube</th>
        <th>Şube Kodu</th>
        <th>Bağ. Bölge</th>
        <th>Toplam Fatura</th>
        <th>Ciro</th>
        <th>Nakit Tutar</th>
        <th>Pos Tutar</th>
        <th>Yatırdığı Tutar</th>
        <th>Gün İçi</th>
        <th>Borç</th>
        <th>Kasa Statü</th>
        <th>İşlem</th>
    </tr>
    </thead>
</table>

<script src="/backend/assets/scripts/safe/general/agency-safe-status.js"></script>
