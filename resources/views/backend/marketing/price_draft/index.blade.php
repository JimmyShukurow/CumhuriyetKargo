@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush

@section('title', 'Fiyat Taslakları')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas fa-money-check-alt icon-gradient bg-plum-plate">
                        </i>
                    </div>
                    <div>Fiyat Taslakları
                        <div class="page-title-subheading">Bu modül üzerinden Anlaşmalı carilerin kaydı esnasında
                            seçebilmeniz için fiyat taslakları oluşturabilirsiniz. Aynı şekilde acenteye izin vermeniz
                            durumunda acenteler de fiyat taslaklarını kullanabileceklerdir.
                        </div>
                    </div>
                </div>

                <div class="page-title-actions">
                    <button type="button" data-toggle="tooltip" title="" data-placement="bottom"
                            class="btn-shadow mr-3 btn btn-dark"
                            data-original-title="Bu modül üzerinden Cumhuriyet Kargonun sunduğu hizmetlerin fiyat aralıklarını belirleyebilirsiniz.">
                        <i class="fa fa-star"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="">
            {{-- MY BITCCH --}}
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div style="overflow-x: auto; ">
                        <table width="100%" style="white-space: nowrap;"
                               class="table table-bordered NikolasDataTable TablePriceDrafts table-striped Table20Padding mb-5">
                            <thead>
                            <tr>
                                <th>Taslak Adı</th>
                                <th>Acente İzin</th>
                                <th>Dosya</th>
                                <th>Mi</th>
                                <th>1-5 Desi</th>
                                <th>6-10 Desi</th>
                                <th>11-15 Desi</th>
                                <th>16-20 Desi</th>
                                <th>21-25 Desi</th>
                                <th>26-30 Desi</th>
                                <th>30+ Üstü Desi</th>
                                <th>Kayıt Tarihi</th>
                                <th width="40">İşlem</th>
                                <th width="40">İşlem</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="/backend/assets/scripts/backend-modules.js"></script>

@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/marketing/price-drafts/index.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
@endsection

@section('modals')
    @include('backend.marketing.price_draft.includes.modal_add_price_draft')

    @include('backend.marketing.price_draft.includes.modal_edit_price_draft')
@endsection
