<div class="form-group row">
    <label for="safeFirstDate" class="col-sm-1 col-form-label">İlk Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="safeFirstDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
    <label for="safeLastDate" class="col-sm-1 col-form-label">Son Tarih:</label>
    <div class="col-sm-2">
        <input type="date" id="safeLastDate" value="{{ date('Y-m-d') }}"
               class="form-control form-control-sm  niko-select-filter">
    </div>
</div>
<div class="form-group row">

</div>

<table style="white-space: nowrap; width: 100% !important;" id="tableSafe"
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

<div id="SafeDailySummeryRow" class="row">
    <div class="col-md-4">
        <div class="card mb-3 widget-chart widget-chart2 bg-plum-plate text-left">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content text-white">
                    <div class="widget-chart-flex">
                    </div>
                    <div class="widget-chart-flex">
                        <div class="widget-numbers">
                            <small>₺</small>
                            <span id="spanDevredenKasa">{{$data['devreden_kasa']}}</span>
                        </div>
                    </div>
                </div>
                <div class="widget-progress-wrapper">
                    <div class="progress-sub-label text-white">Devreden Kasa</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3 widget-chart widget-chart2 bg-night-sky text-left">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content text-white">
                    <div class="widget-chart-flex">
                    </div>
                    <div class="widget-chart-flex">
                        <div class="widget-numbers">
                            <small>₺</small>
                            <span id="spanGunIci">{{$data['gun_ici']}}</span>
                        </div>
                    </div>
                </div>
                <div class="widget-progress-wrapper">
                    <div class="progress-sub-label text-white">Gün İçi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3 widget-chart widget-chart2 bg-asteroid text-left">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content text-white">
                    <div class="widget-chart-flex">
                    </div>
                    <div class="widget-chart-flex">
                        <div class="widget-numbers">
                            <small>₺</small>
                            <span id="spanTotal">{{$data['total']}}</span>
                        </div>
                    </div>
                </div>
                <div class="widget-progress-wrapper">
                    <div class="progress-sub-label text-white">Toplam Borç</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/backend/assets/scripts/safe/agency/safe.js"></script>
