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

@section('title', 'Sefer Detayları')
@section('content')


    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-bus icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Sefer Detayları [{{ $expedition->car->plaka }}]
                        <div class="page-title-subheading">Bu modül <b>{{ $expedition->car->plaka }}</b> plakalı aracın
                            sefer detaylarını görüntüleyebilirsiniz.
                        </div>
                        <div style="display: none">
                            <input type="text" id="expeditionID" value="{{ $expedition->id }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div style="min-height: 80vh;" class="card-body">
        <div class="mb-3 profile-responsive card">
            <ul class="list-group list-group-flush">

                <div class="main-card mb-12 card">
                    <div class="card-header"><i
                                class="header-icon pe-7s-box2 icon-gradient bg-plum-plate"> </i>Sefer
                        Detayları
                        <div class="btn-actions-pane-right">
                            <div class="nav">
                                <a data-toggle="tab" href="#tabExpeditionInfo"
                                   class="btn-pill btn-wide btn btn-outline-alternate btn-sm show active">Sefer
                                    Bilgileri</a>
                                <a id="TabExpeditionCargoes" data-toggle="tab" href="#tabExpeditionCargoes"
                                   class="btn-pill btn-wide mr-1 ml-1 btn btn-outline-alternate btn-sm show ">Kargolar
                                </a>
                                <a data-toggle="tab" href="#tabExpeditionSeals"
                                   class="btn-pill btn-wide btn btn-outline-alternate btn-sm show"> Mühürler </a>
                                <a data-toggle="tab" href="#tabExpeditionMovements"
                                   class="btn-pill ml-1 btn-wide btn btn-outline-alternate btn-sm show">Sefer
                                    Hareketleri</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            @include('backend.expedition.details.tabs.expedition_general_info')

                            @include('backend.expedition.details.tabs.cargoes')

                            @include('backend.expedition.details.tabs.seals')

                            @include('backend.expedition.details.tabs.expedition_movements')
                        </div>
                    </div>
                    @if($rotue != null)
                        <div class="row justify-content-center p-2">

                            <div class="p-2">
                                <button
                                        class="btn-icon-vertical alert-not-yet  btn-transition-text btn-transition btn-transition-alt p-2 btn btn-outline-success col-md-2">
                                    TTİ Oluştur
                                </button>
                            </div>
                            <div class="p-2">
                                <button id="deleteExpedition"
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt p-2 btn btn-outline-danger col-md-2 ">
                                    Seferi Sil
                                </button>
                            </div>
                            <div class="p-2">
                                <button
                                        class="btn-icon-vertical alert-not-yet btn-transition-text btn-transition btn-transition-alt p-2 btn btn-outline-info col-md-2">
                                    Düzenle
                                </button>
                            </div>
                            <div class="p-2">
                                <button
                                        class="btn-icon-vertical alert-not-yet btn-transition-text btn-transition btn-transition-alt p-2 btn btn-outline-alternate col-md-2">
                                    Mühürle
                                </button>
                            </div>
                            <div class="p-2">
                                <button
                                        class="btn-icon-vertical alert-not-yet btn-transition-text btn-transition btn-transition-alt p-2 btn btn-outline-primary col-md-2">
                                    Mühür Kır
                                </button>
                            </div>
                            <div class="p-2">
                                <button id="finishExpedition"
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt p-2 btn btn-outline-danger col-md-2">
                                    Sefer Bitir
                                </button>
                            </div>

                        </div>
                    @endif

                </div>
            </ul>
        </div>
    </div>


@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/expeditions/expedition-details.js"></script>
@endsection




