@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <style>
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
            top: 6px;
            left: 5px;
        }
    </style>
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
@endpush

@section('title', 'Seferler')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-bus icon-gradient bg-ck">
                        </i>
                    </div>
                    <div> Seferler <b>[{{$unit}}]</b>
                        <div class="page-title-subheading">Bu modül üzerinden Cumhuriyet Kargo geneli tüm seferleri görüntüleyebilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                    <i class="header-icon fa fa-bus mr-3 text-muted opacity-6"> </i>
                    Gelen Seferler
                </div>
                <div class="btn-actions-pane-right actions-icon-btn">
                    <div class="btn-group dropdown">
                        <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i
                                    class="pe-7s-menu btn-icon-wrapper"></i></button>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">

                            <div class="p-3 text-right">
                                <button id="btnClearFilter" class="mr-2 btn-shadow btn-sm btn btn-link">Filtreyi
                                    Temizle
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="filterStartDate">İlk Tarih:</label>
                            <input type="date" id="filterStartDate" value="{{ $firstDate }}"
                                   class="form-control form-control-sm  niko-select-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="filterFinishDate">Son Tarih:</label>
                            <input type="date" id="filterFinishDate" value="{{ date('Y-m-d') }}"
                                   class="form-control form-control-sm  niko-select-filter">
                        </div>


                        <div class="col-md-3">
                            <label for="filterExpeditionSerialNo">Sefer No:</label>
                            <input type="text" data-inputmask="'mask': '999 999 999'"
                                   placeholder="___ ___ ___" type="text" id="filterExpeditionSerialNo"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="filterPlaque" class="">Plaka:</label>
                                <input type="text" id="filterPlaque"
                                       class="form-control niko-filter form-control-sm">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="filterDepartureBranch">Çıkış Birim:</label>
                            <input type="text" id="filterDepartureBranch"
                                   class="form-control niko-filter form-control-sm">
                        </div>

                        <div class="col-md-3">
                            <label for="filterArrivalBranch">Varış Birim:</label>
                            <input type="text" id="filterArrivalBranch"
                                   class="form-control niko-filter form-control-sm">
                        </div>

                        <div class="col-md-3">
                            <label for="filterCreatorUser">Oluşturan:</label>
                            <input type="text" id="filterCreatorUser"
                                   class="form-control niko-filter form-control-sm">
                        </div>

                        <div class="col-md-3">
                            <label for="filterDoneStatus">Statü:</label>
                            <select id="filterDoneStatus" class="form-control-sm form-control">
                                <option value="">Seçiniz</option>
                                <option value="0">Devam Ediyor</option>
                                <option value="1">Bitti</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">

                <table width="100%" id="OutGoingExpeditionsTable"
                       class="align-middle mb-0 table Table20Padding table-bordered table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Sefer No</th>
                        <th>Araç Plaka</th>
                        <th>Çıkış Birim</th>
                        <th>Varış Birim</th>
                        <th>Ara Durak</th>
                        <th>Kargo Sayısı</th>
                        <th>Statü</th>
                        <th>Oluşturan</th>
                        <th>Açıklama</th>
                        <th>Kayıt Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Sefer No</th>
                        <th>Araç Plaka</th>
                        <th>Çıkış Birim</th>
                        <th>Varış Birim</th>
                        <th>Ara Durak</th>
                        <th>Kargo Sayısı</th>
                        <th>Statü</th>
                        <th>Oluşturan</th>
                        <th>Açıklama</th>
                        <th>Kayıt Tarihi</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>

        {{--Statistics--}}
        <div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/expeditions/all-expeditions.js"></script>
@endsection
