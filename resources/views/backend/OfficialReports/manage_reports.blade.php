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

@section('title', 'Tutanak Yönetim')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-note2 icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Tutanak Yönetim <b>[{{$unit}}]</b>
                        <div class="page-title-subheading">Bu sayfa üzerinden birminizin veya biriminize bağlı alt
                            kullanıcılarınızın oluşturdukları tutanakları görüntüleyebilir yönetip
                            işlem sağlayabilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php $params = ['title' => 'Tutanak Yönetim &nbsp; <b>' . $unit . '</b>', 'type' => 'manage']; @endphp
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
    <script src="/backend/assets/scripts/official-report/manage-reports.js"></script>


    <script>
        $(document).ready(function () {
            initDatatable('manage', '/OfficialReport/GetManageReports');
        });
    </script>

    <script>var typeOfJs = 'create_htf'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>
@endsection




@section('modals')
    @php $data = ['name' => 'nikolatesla'] @endphp
    @include('backend.OfficialReports.report_modal', $data)

    @php $data = ['type' => 'create_htf']; @endphp
    @include('backend.main_cargo.main.modal_cargo_details')

@endsection

