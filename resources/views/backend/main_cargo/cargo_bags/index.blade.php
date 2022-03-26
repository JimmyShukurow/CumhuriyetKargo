@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <link rel="stylesheet" href="/backend/assets/css/ck-bag-barcode.css">
@endpush()

@section('title', 'Torba & Çuval (Manifesto)')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Torba & Çuval (Manifesto)
                        <div class="page-title-subheading">
                            Bu modül üzerinden oluşturmuş olduğunuz torba ve çuvallarınızı görüntüleyebilirsiniz. <b>
                                Çuval
                                veya Torba silebilmeniz için, içerdiği kargo sayısı alanının 0 olması gerekmektedir.
                            </b>
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <button id="btnCreateNewBag" type="button" aria-haspopup="true" aria-expanded="false"
                                class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                            Yeni Torba & Çuval
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-box2 mr-3 text-muted opacity-6"> </i> Torba & Çuval
                </div>

            </div>
            <form id="search-form">
                <div class="row p-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="startDate">Başlangıç Tarihi:</label>
                            <input type="date" value="{{date('Y-m-d')}}"
                                   class="form-control form-control-sm niko-select-filter niko-filter" id="startDate">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="endDate">Bitiş Tarihi:</label>
                            <input type="date" value="{{date('Y-m-d')}}"
                                   class="form-control form-control-sm niko-select-filter niko-filter" id="endDate">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="creatorUser">Oluşturan Kullanıcı:</label>
                            <input type="text" class="form-control form-control-sm niko-filter" id="creatorUser">
                        </div>
                    </div>
                </div>
                <div class="p-2">
                    <input type="checkbox" name="filter-by-time" id="filter-by-time">
                    <label for="filter-by-time"> Zamana göre filtrele</label>
                </div>
            </form>

            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-borderless table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Çuval Takip No</th>
                        <th>Tip</th>
                        <th>İçerdiği Kargo Sayısı</th>
                        <th>Statü</th>
                        <th>Oluşturan</th>
                        <th>Çıkış birim</th>
                        <th>Varış Birim</th>
                        <th>Son İndiren Kişi</th>
                        <th>Son İndirme Tarihi</th>
                        <th>İndirme Yapıldı</th>
                        <th>Oluşturulma Zamanı</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Çuval Takip No</th>
                        <th>Tip</th>
                        <th>İçerdiği Kargo Sayısı</th>
                        <th>Statü</th>
                        <th>Oluşturan</th>
                        <th>Çıkış birim</th>
                        <th>Varış Birim</th>
                        <th>Son İndiren Kişi</th>
                        <th>Son İndirme Tarihi</th>
                        <th>İndirme Yapıldı</th>
                        <th>Oluşturulma Zamanı</th>
                        <th>İşlem</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/JsBarcode.js"></script>
    <script src="/backend/assets/scripts/QrCode.min.js"></script>
    <script src="/backend/assets/scripts/cargo-bags/index.js"></script>

@endsection

@section('modals')

    @include('backend.main_cargo.cargo_bags.includes.modal_create_bag')

    @include('backend.main_cargo.cargo_bags.includes.modal_bag_details')

    @include('backend.main_cargo.cargo_bags.includes.modal_show_barcode')

@endsection
