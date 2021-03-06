




@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <style>
        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child:before {
            top: 6px;
            left: 5px;
        }

    </style>
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet" />
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet" />
@endpush

@section('title', 'Eğitimler')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="metismenu-icon pe-7s-film icon-gradient bg-ck">
                        </i>
                    </div>
                    <div> Eğitimler
                        <div class="page-title-subheading"> Bu modül üzerinden Cumhuriyet Kargonun Kargo ve Operasyon
                            Sistemleri için hazırlanmış olan eğitim videolarını izleyebilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                    <i class="header-icon metismenu-icon pe-7s-film icon-gradient bg-ck mr-3 text-muted opacity-6"> </i>
                    Eğitimler
                </div>
                <div class="btn-actions-pane-right actions-icon-btn">
                    <div class="btn-group dropdown">
                        <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                            class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">

                            <div class="p-3 text-right">
                                <button id="btnClearFilter" class="mr-2 btn-shadow btn-sm btn btn-link">Filtreyi
                                    Temizle
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">
                       

                        <div class="col-md-3">
                            <label for="videoName">Video Adı:</label>
                            <input type="text" id="videoName" class="form-control form-control-sm  niko-select-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="category">Kategorisi:</label>
                            <input type="text" id="category" class="form-control form-control-sm  niko-select-filter">
                        </div>


                        <div class="col-md-3">
                            <label for="description">Açıklama:</label>
                            <input type="text" type="text" id="description"
                                class="form-control  form-control-sm niko-filter">
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="tutor" class="">Eğitmen:</label>
                                <input type="text" id="tutor" class="form-control niko-filter form-control-sm">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="start_date">İlk tarih:</label>
                            <input type="date" id="start_date"
                                value="{{ \Carbon\Carbon::createFromDate(date('Y-m-d'))->subDay(7)->format('Y-m-d') }}"
                                class="form-control niko-filter form-control-sm">
                        </div>

                        <div class="col-md-3">
                            <label for="end_date">Son tarih:</label>
                            <input type="date" id="end_date"
                                value="{{ \Carbon\Carbon::createFromDate(date('Y-m-d'))->format('Y-m-d') }}"
                                class="form-control niko-filter form-control-sm">
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-3 ">
            <div class="card-body" id="allVideos">
                <table style="white-space: nowrap;" width="100%" id="AllTutorials"
                    class="align-middle mb-0 table Table20Padding table-bordered table-striped table-hover NikolasDataTable">
                    <thead>
                        <tr>
                            <th>Video adı</th>
                            <th>Tanımı</th>
                            <th>Kategori</th>
                            <th>Link</th>
                            <th>Eğitmen</th>
                            <th>Oluşturulduğu tarih</th>
                            <th>İşlem</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Video adı</th>
                            <th>Tanımı</th>
                            <th>Kategori</th>
                            <th>Link</th>
                            <th>Eğitmen</th>
                            <th>Oluşturulduğu tarih</th>
                            <th>İşlem</th>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Statistics --}}
        <div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/tutorials/all_tutorials.js"></script>
@endsection

@include('backend.tutorials.modal')
