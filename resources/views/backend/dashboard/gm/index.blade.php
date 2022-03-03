@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'GM Dashboard')

@section('content')
    <div class="app-main__inner">
        <div class="app-inner-layout">
            <div class="app-inner-layout__header-boxed p-0">
                <div class="app-inner-layout__header page-title-icon-rounded text-white bg-asteroid mb-4">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fa fa-university icon-gradient bg-asteroid"> </i>
                                </div>
                                <div>
                                    Dashboard (Genel Müdürlük)
                                    <div class="page-title-subheading">Cumhuriyet Kargo Türkiye Geneli Ciro
                                        Raporu.
                                    </div>
                                </div>
                            </div>
                            <div class="page-title-actions">

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group position-relative">
                                            <label for="firstDate">İlk Tarih:</label>
                                            <input type="date" id="firstDate" value="2022-03-03"
                                                   class="form-control form-control-sm date-filter niko-select-filter">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group position-relative">
                                            <label for="lastDate">Son Tarih:</label>
                                            <input type="date" id="lastDate" value="2022-03-03"
                                                   class="form-control form-control-sm date-filter niko-select-filter">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group position-relative">
                                            <label for=""></label>
                                            <button id="btnReloadDashboard" class="btn btn-primary bg-success">Yenile
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="card mb-3 widget-chart widget-chart2 bg-asteroid text-left">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content text-white">
                                <div class="widget-chart-flex">
                                    <div class="widget-title opacity-5">Ciro</div>
                                    <div class="widget-subtitle opacity-5 text-white">Belirtilen Tarih</div>
                                </div>
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <small>₺</small>
                                        <span id="endorsementCurrentDate">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-progress-wrapper">
                                <div class="progress-bar-xs progress-bar-animated-alt progress">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="65"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-3 widget-chart widget-chart2 bg-asteroid text-left">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content text-white">
                                <div class="widget-chart-flex">
                                    <div class="widget-title opacity-5">Toplam Kargo</div>
                                    <div class="widget-subtitle opacity-5 text-white">Belirtilen Tarih</div>
                                </div>
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <span id="totalCargosCurrentDate">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-progress-wrapper">
                                <div class="progress-bar-xs progress-bar-animated-alt progress">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="65"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-3 widget-chart widget-chart2 bg-asteroid text-left">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content text-white">
                                <div class="widget-chart-flex">
                                    <div class="widget-title opacity-5">Koli/Dosya</div>
                                    <div class="widget-subtitle opacity-5 text-white">Belirtilen Tarih</div>
                                </div>
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <span id="cargoFileCurrentDate">0/0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-progress-wrapper">
                                <div class="progress-bar-xs progress-bar-animated-alt progress">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="65"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-3 widget-chart widget-chart2 bg-asteroid text-left">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content text-white">
                                <div class="widget-chart-flex">
                                    <div class="widget-title opacity-5">Toplam Ds</div>
                                    <div class="widget-subtitle opacity-5 text-white">Belirtilen Tarih</div>
                                </div>
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <small>ds</small>
                                        <span id="totalDesiCurrentDate">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-progress-wrapper">
                                <div class="progress-bar-xs progress-bar-animated-alt progress">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="65"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-3 widget-chart widget-chart2 bg-slick-carbon text-left">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content text-white">
                                <div class="widget-chart-flex">
                                    <div class="widget-title opacity-5">Ciro</div>
                                    <div class="widget-subtitle opacity-5 text-white">Tüm Zamanlar</div>
                                </div>
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <small class="text-warning">₺</small>
                                        <span id="endorsementAllTime" class="text-warning">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-progress-wrapper">
                                <div class="progress-bar-xs progress-bar-animated-alt progress">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="65"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3 widget-chart widget-chart2 bg-night-sky text-left">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content text-white">
                                <div class="widget-chart-flex">
                                    <div class="widget-title opacity-5">Kasaya Giren</div>
                                    <div class="widget-subtitle opacity-5 text-white">Tüm Zamanlar</div>
                                </div>
                                <div class="widget-chart-flex">
                                    <div class="widget-numbers">
                                        <small>₺</small>
                                        <span id="inSafeAllTime">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-progress-wrapper">
                                <div class="progress-bar-xs progress-bar-animated-alt progress">
                                    <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="65"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-7 col-lg-12">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                                CKG-Sis Türkiye Geneli Bölgesel Ciro Analiz (Belirtilen Tarih)
                            </div>
                            <div class="btn-actions-pane-right text-capitalize">
                            </div>
                        </div>
                        <div class="pt-0 card-body">
                            <div id="chart-regions"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3 card">
                        <div class="tabs-lg-alternate card-header">
                            <ul class="nav nav-justified">
                                <li class="nav-item">

                                    <a data-toggle="tab" href="#idle-districts" class="nav-link show active">
                                        <div class="widget-number">Verilmeyen İlçeler</div>
                                        <div class="tab-subheading">
                                            <span class="pr-2 opactiy-6">
                                                <i class="fa fa-comment-dots"></i>
                                            </span>Bölge müdürlüğüne verilmeyen boşta kalan ilçeler.
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#idle-agiencies-region" class="nav-link show">
                                        <div class="widget-number">Verilmeyen Acenteler</div>
                                        <div class="tab-subheading">Herhangi bir bölge müdürlüğüne bağlı olmayan
                                            acenteler
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#idle-agencies-tc" class="nav-link show">
                                        <div class="widget-number text-danger">Aktarması Olmayan Acenteler</div>
                                        <div class="tab-subheading">
                                        <span class="pr-2 opactiy-6">
                                        <i class="fa fa-bullhorn"></i>
                                         </span>Herhangi bir transfer merkezine bağlı olmayan acenteler.
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="idle-districts" role="tabpanel">
                                <div class="card-body">
                                    <div class="card-body">
                                        <table id="TableRolePermissions"
                                               style="white-space: nowrap; width: 100% !important;"
                                               class="table table-hover table-striped  table-bordered TableNoPadding NikolasDataTable IdleDistricts table-hover">
                                            <thead>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Bölge</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Bölge</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show" id="idle-agiencies-region" role="tabpanel">
                                <div class="card-body">
                                    <div class="card-body">
                                        <table id="TableRolePermissions"
                                               style="white-space: nowrap; width: 100% !important;"
                                               class="table table-hover table-striped  table-bordered TableNoPadding NikolasDataTable IdleAgenciesRegion table-hover">
                                            <thead>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Acente İsmi</th>
                                                <th>Acente Sahibi</th>
                                                <th>Bölge</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Acente İsmi</th>
                                                <th>Acente Sahibi</th>
                                                <th>Bölge</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show" id="idle-agencies-tc" role="tabpanel">
                                <div class="card-body">
                                    <div class="card-body">
                                        <div class="card-body">
                                            <table id="TableRolePermissions"
                                                   style="white-space: nowrap; width: 100%;"
                                                   class="table table-hover table-striped  table-bordered TableNoPadding NikolasDataTable IdleAgenciesTC"
                                                   role="grid">
                                                <thead>

                                                <tr>
                                                    <th>İl</th>
                                                    <th>İlçe</th>
                                                    <th>Acente İsmi</th>
                                                    <th>Acente Sahibi</th>
                                                    <th>Bağlı Old. Aktarma</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>İl</th>
                                                    <th>İlçe</th>
                                                    <th>Acente İsmi</th>
                                                    <th>Acente Sahibi</th>
                                                    <th>Bağlı Old. Aktarma</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="/backend/assets/scripts/circle-progress.min.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/dashboard/gm/index.js"></script>
@endsection
