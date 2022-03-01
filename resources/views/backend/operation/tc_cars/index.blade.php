@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
    <style>
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
            top: 6px;
            left: 5px;
        }
    </style>
@endpush

@push('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@section('title', 'Aktarma Araçları')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-safe icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Aktarma Araçlar
                        <div class="page-title-subheading">Bu modül üzerinden Türkiye geneli Cumhuriyet Kargo A.Ş.
                            acentelerinin kasasını takip edebilir, işlem yapabilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .tab-pane {
                min-height: 50vh;
            }
        </style>

        <div class="main-card mb-3 card">
            <div class="card-header">
                <i class="header-icon pe-7s-safe icon-gradient bg-ck"> </i>Aktarma Araçlar Modülü
                <div class="btn-actions-pane-right">
                    <div class="nav">
                        <a id="tabTransferCars" data-toggle="tab" href="#transferCars"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger active">Aktarma Araçları</a>

                        <a id="tabAgencyPaymentApps" data-toggle="tab" href="#agencyPaymentApps"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger">Bölgeye Bağlı Acente Araçları</a> 
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="transferCars" role="tabpanel">
                        @include('backend.operation.tc_cars.tabs.transfer_cars')
                    </div>

                    <div class="tab-pane" id="agencyPaymentApps" role="tabpanel">
                        @include('backend.operation.tc_cars.tabs.agency_cars_of_branch')
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
@endsection



@section('modals')
    @include('backend.operation.tc_cars.tc_cars_modals')
@endsection
