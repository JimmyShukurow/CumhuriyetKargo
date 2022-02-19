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

@section('title', 'Genel Kasa Ekranı')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-safe icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Genel Kasa Modülü
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
                <i class="header-icon pe-7s-safe icon-gradient bg-ck"> </i>Genel Kasa
                <div class="btn-actions-pane-right">
                    <div class="nav">

                        <a data-toggle="tab" href="#collections"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger active">Acente Kasa
                            Durumu</a>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="collections" role="tabpanel">
                        @include('backend.safe.general.tabs.agency_safe_status')
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
    @php $data = ['type' => 'incoming_cargo']; @endphp
    @include('backend.main_cargo.main.modal_cargo_details')
@endsection
