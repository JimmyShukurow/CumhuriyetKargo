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

@section('title', 'Tüm Tutanaklar')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-note2 icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Tüm Tutanaklar
                        <div class="page-title-subheading">Bu sayfa üzerinden birminize tarafından oluşturulan
                            tutanakları görünyüleyebilir işlem yapabilirsiniz. (Tek seferde max. 500 kayıt
                            veya max 90 günlük kayıt görüntüleyebilirsiniz.)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php $params = ['title' => 'Tutanaklar', 'type' => 'reports']; @endphp
        @include('backend.OfficialReports.report_table_filter')


        {{--Statistics--}}
        <div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/official-report/report-view.js"></script>

    <script>
        $(document).ready(function () {
            initDatatable('reports', '/OfficialReport/GetOfficialReports');
        });
    </script>

    <script>var typeOfJs = 'create_htf'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>
@endsection

@section('modals')
    @php $data = ['name' => 'nikolatesla'] @endphp
    @include('backend.OfficialReports.report_modal', $data)

    @php $data = ['type' => 'create_htf']; @endphp
    @include('backend.main_cargo.cargo_details.modal_cargo_details')

@endsection
