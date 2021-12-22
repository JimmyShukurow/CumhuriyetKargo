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

@section('title', 'Gelen Tutanaklar')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-note2 icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Gelen Tutanaklar <b>[{{$unit}}]</b>
                        <div class="page-title-subheading">Bu sayfa üzerinden birminize tutulan tutanakları
                            görünyüleyebilir işlem yapabilirsiniz. (Tek seferde max. 500 kayıt
                            veya max 90 günlük kayıt görüntüleyebilirsiniz.)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php $params = ['title' => "Gelen Tutanaklar &nbsp; <b>$unit</b>", 'type' => 'reports']; @endphp
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
    <script src="/backend/assets/scripts/official-report/incoming-reports.js"></script>

    <script>
        $(document).ready(function () {
            initDatatable('reports', '/OfficialReport/GetIncomingReports');

            @if($requestID != null)
            getReportInfo('{{$requestID}}');
            @endif
        });
    </script>

    <script>var typeOfJs = 'create_htf'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>
@endsection




@section('modals')
    @php $data = ['type' => 'incoming'] @endphp
    @include('backend.OfficialReports.report_modal', $data)

    @php $data = ['type' => 'create_htf']; @endphp
    @include('backend.main_cargo.main.modal_cargo_details')

    {{-- Standart Modal - Objection --}}
    <div class="modal fade" id="modalObjection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ítiraz Et</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="enableDisable_agencyName">Şube Adı</label>
                                <input id="enableDisable_agencyName" class="form-control form-control-sm" type="text"
                                       readonly
                                       value="{{$unit}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="accountStatus">Ad Soyad:</label>
                                <input type="text" readonly class="form-control form-control-sm"
                                       value="{{Auth::user()->name_surname}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="statusDesc">Savunma:</label>
                                <textarea name="" maxlength="1000" id="defenseText" cols="30" rows="10"
                                          class="form-control"></textarea>
                                <em class="text-danger">Zorunlu Alan! Max:1000 Karakter.</em>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnSaveDefense" type="button" class="btn btn-primary">İtiraz Et</button>
                </div>
            </div>
        </div>
    </div>

@endsection
