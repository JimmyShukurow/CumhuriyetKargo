<div class="form-group row">
    <label for="agencySafeStatusFirstDate" class="col-sm-1 col-form-label">İlk Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="agencySafeStatusFirstDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
    <label for="agencySafeStatusLastDate" class="col-sm-1 col-form-label">Son Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="agencySafeStatusLastDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
</div>
<div class="form-group row">

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
        <th>Borç</th>
        <th>İşlem</th>
    </tr>
    </thead>
</table>

{{-- <script src="/backend/assets/scripts/safe/general/agency-safe-status.js"></script> --}}
