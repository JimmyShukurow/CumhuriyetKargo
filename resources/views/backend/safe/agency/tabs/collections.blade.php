<div class="form-group row">
    <label for="collectionFirstDate" class="col-sm-1 col-form-label">İlk Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="collectionFirstDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
    <label for="collectionLastDate" class="col-sm-1 col-form-label">Son Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="collectionLastDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
</div>
<div class="form-group row">

</div>

<table style="width: 100%; white-space: nowrap;" id="tableCollections" class="table Table20Padding table-bordered table-hover">
    <thead>
    <tr>
        <th>Fatura Tarihi</th>
        <th>Fatura No</th>
        <th>Toplam Tutar</th>
        <th>Ödeme Tipi</th>
        <th>Çıkış BR.</th>
        <th>Varış İl</th>
        <th>Gönd. Cari Ünvanı</th>
        <th>Gönd. Cari Kodu</th>
        <th>Alıcı Cari Ünvanı</th>
        <th>Tahsilat Tarihi</th>
        <th>Açıklama</th>
    </tr>
    </thead>
</table>

<script src="/backend/assets/scripts/safe/agency/collections.js"></script>
