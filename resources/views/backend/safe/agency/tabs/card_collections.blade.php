<div class="form-group row">
    <label for="cardCollectionFirstDate" class="col-sm-1 col-form-label">İlk Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="cardCollectionFirstDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
    <label for="cardCollectionLastDate" class="col-sm-1 col-form-label">Son Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="cardCollectionLastDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
</div>
<div class="form-group row">

</div>

<table style="white-space: nowrap; width: 100% !important;" id="tableCardCollections"
       class="table Table20Padding table-bordered table-hover">
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

<script src="/backend/assets/scripts/safe/agency/card-collections.js"></script>
