<div class="form-group row">
    <label for="paymentAppFirstDate" class="col-md-1 col-form-label">İlk Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="paymentAppFirstDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
    <label for="paymentAppLastDate" class="col-md-1 col-form-label">Son Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="paymentAppLastDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>

    <div class="col-md-6">

        <a href="{{route('safe.agency.createPaymentApp')}}"
           target="popup"
           onclick="window.open('{{route('safe.agency.createPaymentApp')}}','popup','width=700,height=700'); return false;">
            <button class="btn btn-outline-primary float-right">Ödeme Bildir</button>
        </a>

    </div>
</div>

<table style="white-space: nowrap; width: 100% !important;" id="tablePaymentApps"
       class="table Table20Padding table-bordered table-hover">
    <thead>
    <tr>
        <th>Başvuru No</th>
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

<script src="/backend/assets/scripts/safe/agency/payment-apps.js"></script>
