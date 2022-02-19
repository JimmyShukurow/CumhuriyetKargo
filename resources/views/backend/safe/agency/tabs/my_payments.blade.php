<div class="form-group row">
    <label for="myPaymentFirstDate" class="col-md-1 col-form-label">İlk Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="myPaymentFirstDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
    <label for="myPaymentLastDate" class="col-md-1 col-form-label">Son Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="myPaymentLastDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
</div>

<table style="white-space: nowrap; width: 100% !important;" id="tableMyPayments"
       class="table Table20Padding table-bordered table-hover">
    <thead>
    <tr>
        <th>Acente</th>
        <th>Onay Tipi</th>
        <th>Başvuru No</th>
        <th>Ödeme</th>
        <th>Ödeme Kanalı</th>
        <th>Ödeme Tarihi</th>
        <th>Ödeme Yapan</th>
        <th>Açıklama</th>
        <th>Onaylayan</th>
        <th>Kayıt Tarihi</th>
    </tr>
    </thead>
</table>

<script src="/backend/assets/scripts/safe/agency/my-payments.js"></script>
