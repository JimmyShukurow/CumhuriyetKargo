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
                                            <input type="date" id="firstDate" value="{{date('Y-m-d')}}"
                                                   class="form-control form-control-sm date-filter niko-select-filter">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group position-relative">
                                            <label for="lastDate">Son Tarih:</label>
                                            <input type="date" id="lastDate" value="{{date('Y-m-d')}}"
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

            @include('backend.dashboard.gm.sections.boxes')


            <div class="row">
                <div class="col-sm-12 col-md-7 col-lg-12">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title">
                                <i class="header-icon lnr-map-marker icon-gradient bg-love-kiss"> </i>
                                CKG-Sis Türkiye Geneli Bölgesel Ciro Analiz (Belirtilen Tarih)
                            </div>
                            <ul class="nav">
                                <li class="nav-item">
                                    <a id="tabChart" data-toggle="tab" href="#graph" class="nav-link active">Grafik</a>
                                </li>

                                <li class="nav-item">
                                    <a id="tabTable" data-toggle="tab" href="#table" class="nav-link">Tablo</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="graph" role="tabpanel">
                                    <div class="pt-0 card-body">
                                        <div id="chart-regions"></div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="table" role="tabpanel">
                                    @include('backend.dashboard.gm.sections.graph_table')
                                </div>
                            </div>
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
                                        <div class="widget-number">En Çok Ciro Yapan İlk 10 Şube</div>
                                        <div class="tab-subheading">
                                            <span class="pr-2 opactiy-6">
                                                <i class="fa fa-comment-dots"></i>
                                            </span>
                                            En çok ciro yapan şubeler. (Belirtilen Tarih)
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="idle-districts" role="tabpanel">
                                <div class="card-body">
                                    <div class="card-body">
                                        <table id="tableAgencies"
                                               style="white-space: nowrap; width: 100% !important;"
                                               class="table table-hover table-striped  table-bordered NikolasDataTable IdleDistricts table-hover">
                                            <thead>
                                            <tr>
                                                <th>Acente</th>
                                                <th>Bölge</th>
                                                <th>Personel Sayısı</th>
                                                <th>Kargo Sayısı</th>
                                                <th>Koli</th>
                                                <th>Ds</th>
                                                <th>Dosya</th>
                                                <th>Ciro</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Acente</th>
                                                <th>Bölge</th>
                                                <th>Personel Sayısı</th>
                                                <th>Kargo Sayısı</th>
                                                <th>Koli</th>
                                                <th>Ds</th>
                                                <th>Dosya</th>
                                                <th>Ciro</th>
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
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="/backend/assets/scripts/circle-progress.min.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/dashboard/gm/index.js"></script>
@endsection
