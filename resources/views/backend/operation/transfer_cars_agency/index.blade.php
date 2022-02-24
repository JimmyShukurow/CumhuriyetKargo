@extends('backend.layout')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <style>
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
            top: 6px;
            left: 5px;
        }

        .modal-data {
            font-weight: bold;
        }
    </style>
@endpush

@section('title', 'Aktarma Araçları')
@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-car icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div> Aktarma Araçları
                        <div class="page-title-subheading">Bu modül üzerinden sistemdeki tüm aktarma araçları
                            listleyebilir, işlem yapablirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('AgencyTransferCars.create') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Acente Aracı Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon pe-7s-car mr-3 text-muted opacity-6"> </i>Tüm Aktarma Araçları
                </div>

                <div class="btn-actions-pane-right actions-icon-btn">
                    <div class="btn-group dropdown">
                        <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i
                                class="pe-7s-menu btn-icon-wrapper"></i></button>
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
                            <label for="filter_marka">Marka:</label>
                            <input type="text" id="filter_marka"
                                   class="form-control niko-filter form-control-sm niko-select-filter">
                        </div>
                        <div class="col-md-3">
                            <label for="filter_model">Model:</label>
                            <input type="text" id="filter_model"
                                   class="form-control niko-filter form-control-sm niko-select-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="filter_plaka">Plaka:</label>
                            <input type="text" id="filter_plaka"
                                   class="form-control niko-filter form-control-sm niko-select-filter">
                        </div>

                        <div class="col-md-3">
                            <label for="filter_hat">Hat:</label>
                            <select name="" id="filter_hat" class="form-control niko-select-filter form-control-sm">
                                <option value="">Seçiniz</option>
                                <option value="Anahat">Anahat</option>
                                <option value="Arahat">Arahat</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_arac_kapasitesi">Araç Kapasitesi:</label>
                            <select name="filter_arac_kapasitesi" required="" id="filter_arac_kapasitesi"
                                    class="form-control niko-select-filter form-control-sm">
                                <option value=""> Seçiniz</option>
                                <option value="Panelvan">Panelvan</option>
                                <option value="Kamyonet">Kamyonet</option>
                                <option value="6 Teker Kamyonet">6 Teker Kamyonet</option>
                                <option value="10 Teker Kamyon">10 Teker Kamyon</option>
                                <option value="40 Ayak Kamyon">40 Ayak Kamyon</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_cikisAktarma">Çıkış Aktarma:</label>
                            <select id="filter_cikisAktarma"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['transshipment_centers'] as $key)
                                    <option value="{{$key->id}}">{{$key->tc_name . ' ('.$key->type.') T.M.'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_varisAktarma">Varış Aktarma:</label>
                            <select id="filter_varisAktarma"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['transshipment_centers'] as $key)
                                    <option value="{{$key->id}}">{{$key->tc_name . ' ('.$key->type.') T.M.'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_soforIletisim">Şoför İletişim:</label>
                            <input type="text" data-inputmask="'mask': '(999) 999 99 99'"
                                   placeholder="___ ___ __ __" type="text" id="filter_soforIletisim"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                    </div>
                </form>
            </div>

            <div class="card-body">
                <table  style="width:100%;" id="AgenciesTable"
                       class="align-middle mb-0 table Table30Padding table-borderless table-striped NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Marka</th>
                        <th>Model</th>
                        <th>Plaka</th>
                        <th>Baglı Birim</th>
                        <th>Şöför Adı</th>
                        <th>Ekleyen</th>
                        <th>Araç Tipi</th>
                        <th>Kayıt Tarihi</th>
                        <th>Onay Durumu</th>
                        <th class="text-center">Detay</th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>

                    <tfoot>
                    <tr>
                        <th>Marka</th>
                        <th>Model</th>
                        <th>Plaka</th>
                        <th>Baglı Birim</th>
                        <th>Şöför Adı</th>
                        <th>Ekleyen</th>
                        <th>Araç Tipi</th>
                        <th>Kayıt Tarihi</th>
                        <th>Onay Durumu</th>
                        <th class="text-center">Detay</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('backend.operation.transfer_cars_agency.agency_js')
@endsection

@section('modals')
    @include('backend.operation.transfer_cars_agency.agency_modals')
@endsection
