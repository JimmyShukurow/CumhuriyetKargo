<div class="form-group row">
    <label for="staticEmail" class="col-sm-1 col-form-label">İlk Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="startDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
    <label for="inputPassword" class="col-sm-1 col-form-label">Son Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="startDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
</div>
<div class="form-group row">

</div>

<table id="tablePendingCollections" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>Tahsilat Tipi</th>
        <th>Fatura Tarihi</th>
        <th>Fatura No</th>
        <th>Evrak No</th>
        <th>Cari Ünvanı</th>
        <th>Cari Kodu</th>
        <th>Tahsilat Tarihi</th>
        <th>İşlem</th>
    </tr>
    </thead>
</table>

<script src="/backend/assets/scripts/safe/agency/pending-collections.js"></script>
