<div class="form-group row">

    <div style="padding-top: 0; padding-bottom: 0;" class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencySafeStatusAgencies">Şube:</label>
                    <select style="width: 100%;" class="form-control-sm form-control" name=""
                            id="agencySafeStatusAgencies">
                        <option value="">Seçiniz</option>
                        @foreach($data['agencies'] as $key)
                            <option value="{{$key->id}}">{{$key->agency_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencySafeStatusAgencyCode">Şube Kodu:</label>
                    <input type="text" id="agencySafeStatusAgencyCode" class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencySafeStatusSafeStatus">Kasa Statü:</label>
                    <select class="form-control-sm form-control" name="" id="agencySafeStatusSafeStatus">
                        <option value="">Tümü</option>
                        <option value="0">Pasif</option>
                        <option value="1">Aktif</option>
                    </select>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencySafeStatusRegion">Bağlı Bölge:</label>
                    <select class="form-control-sm form-control" name="" id="agencySafeStatusRegion">
                        <option value="">Seçiniz</option>
                        @foreach($data['regions'] as $key)
                            <option value="{{$key->id}}">{{$key->tc_name}}</option>
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
