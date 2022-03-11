@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Acenteler')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-store icon-gradient bg-amy-crisp"></i>
                    </div>
                    <div> Acenteler
                        <div class="page-title-subheading">Bu modül üzerinden sistemdeki tüm acenteleri
                            listleyebilir, işlem yapablirsiniz. <b class="text-danger">Dikkat: Acente silme işlemi
                                sonrası acentenin lokasyon bilgisi de silinecektir. Bu işlem geri alınamaz.</b>
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('agency.AddAgency') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Acente Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @include('backend.agencies.index.includes.table')
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/agencies/index.js"></script>
@endsection

@section('modals')
    @include('backend.agencies.index.includes.modal_agency_detail')
    @include('backend.agencies.index.includes.modal_enabled_disabled')
@endsection
