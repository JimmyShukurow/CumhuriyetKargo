@extends('backend.layout')

@push('css')
    {{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>--}}
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <link rel="stylesheet" href="/backend/assets/css/ck-barcode.css">
@endpush

@section('title', 'Ana Menü')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            @include('backend.main_cargo.main.index.includes.summery')
            @include('backend.main_cargo.main.index.includes.nav_buttons')
            @include('backend.main_cargo.main.index.includes.table')
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
    <script src="/backend/assets/scripts/JsBarcode.js"></script>
    <script src="/backend/assets/scripts/QrCode.min.js"></script>
    <script src="/backend/assets/scripts/main-cargo/index.js?v=1.0.0"></script>
    <script>var typeOfJs = 'main_cargo'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>
    <script src="/backend/assets/scripts/official-report/report-view.js"></script>
    <script src="/backend/assets/scripts/customers/customer-details.js"></script>
@endsection

@section('modals')
    @php $data = ['type' => 'main_cargo']; @endphp
    @include('backend.main_cargo.main.modal_cargo_details')
    @include('backend.OfficialReports.report_modal')
    @include('backend.customers.agency.modal_customer_details')
@endsection
