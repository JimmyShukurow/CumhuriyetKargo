@extends('backend.layout')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <style>
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
            top: 6px;
            left: 5px;
        }
    </style>
@endpush

@section('title', 'Acente Kasa Ekranı')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-safe icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Acente Kasa Modülü
                        <div class="page-title-subheading">Bu modül üzerinden acentenizin ile ilgili kasa işlemlerini
                            yapabilirsiniz.
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
                <i class="header-icon pe-7s-safe icon-gradient bg-ck"> </i>Acente Kasa
                <div class="btn-actions-pane-right">
                    <div class="nav">

                        <a data-toggle="tab" href="#collections"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger active">Tahsilatlar</a>

                        <a id="tabPendingCollection" data-toggle="tab" href="#pendingCollections"
                           class="mr-1 ml-1 btn-pill btn-wide border-0 btn-transition btn btn-outline-danger">Bekleyen
                            Tahsilatlar</a>

                        <a id="tabSafe" data-toggle="tab" href="#safe"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger">Kasa</a>

                        <a id="tabPaymentApps" data-toggle="tab" href="#paymentApps"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger">Ödeme Bildirgesi</a>

                        <a id="tabMyPayments" data-toggle="tab" href="#myPayments"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger">Ödemelerim</a>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="collections" role="tabpanel">
                        @include('backend.safe.agency.tabs.collections')
                    </div>
                    <div class="tab-pane" id="pendingCollections" role="tabpanel">
                        @include('backend.safe.agency.tabs.pending_collections')
                    </div>
                    <div class="tab-pane" id="safe" role="tabpanel">
                        @include('backend.safe.agency.tabs.safe')
                    </div>
                    <div class="tab-pane" id="paymentApps" role="tabpanel">
                        @include('backend.safe.agency.tabs.payment_apps')
                    </div>
                    <div class="tab-pane" id="myPayments" role="tabpanel">
                        @include('backend.safe.agency.tabs.my_payments')
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>

    <script>var typeOfJs = 'index_cargo'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>
@endsection


@section('modals')
    @php $data = ['type' => 'incoming_cargo']; @endphp
    @include('backend.main_cargo.main.modal_cargo_details')
@endsection
