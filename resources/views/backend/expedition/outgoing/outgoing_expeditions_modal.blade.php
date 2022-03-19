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
        <div class="col-md-12">

            <div class="mb-3 profile-responsive card">
                <div class="dropdown-menu-header">
                    <div class="dropdown-menu-header-inner bg-dark">
                        <div class="menu-header-image "
                             style="background-image: url('/backend/assets/images/dropdown-header/abstract10.jpg');">
                        </div>
                        <div class="menu-header-content btn-pane-right">
                            <div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                <div class="avatar-icon rounded">
                                    <img src="/backend/assets/images/ck-ico-white.png" alt="Avatar 5">
                                </div>
                            </div>
                            <div>
                                <h5 id="titleTrackingNo" class="menu-header-title"> {{ $expedition->serial_no }} </h5>
                                <h6 id="titleExpeditionCarPlaque" class="menu-header-subtitle"> {{ $expedition->car->plaka }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <a data-toggle="tab" href="#tabExpeditionCargoes"
                                       class="btn-pill btn-wide mr-1 ml-1 btn btn-outline-alternate btn-sm show ">Kargolar
                                    </a>
                                    <a data-toggle="tab" href="#tabExpeditionSeals"
                                       class="btn-pill btn-wide btn btn-outline-alternate btn-sm show"> Mühürler </a>
                                    <a data-toggle="tab" href="#tabExpeditionMovements"
                                       class="btn-pill ml-1 btn-wide btn btn-outline-alternate btn-sm show">Sefer Hareketleri</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                @include('backend.expedition.outgoing.tabs.sefer-bilgileri')

                                @include('backend.expedition.outgoing.tabs.kargolar')

                                @include('backend.expedition.outgoing.tabs.muhurler')

                                @include('backend.expedition.outgoing.tabs.sefer-hareketleri')
                            </div>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/expeditions/outgoing-expedition.js"></script>
    <script src="/backend/assets/scripts/expeditions/outgoing-expedition-details.js"></script>
@endsection




