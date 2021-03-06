<div class="form-group row">
    <label for="pendingCollectionFirstDate" class="col-sm-1 col-form-label">İlk Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="pendingCollectionFirstDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
    <label for="pendingCollectionLastDate" class="col-sm-1 col-form-label">Son Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="pendingCollectionLastDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
</div>


<table style="width: 100%;" id="tablePendingCollections" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>Fatura Tarihi</th>
        <th>Fatura No</th>
        <th>Toplam Tutar</th>
        <th>Gönd. Cari Ünvanı</th>
        <th>Gönd. Cari Kodu</th>
        <th>Alıcı Cari Ünvanı</th>
        <th>Tahsilat Tarihi</th>
        <th>İşlem</th>
    </tr>
    </thead>
</table>

<script src="/backend/assets/scripts/safe/agency/pending-collections.js"></script>
