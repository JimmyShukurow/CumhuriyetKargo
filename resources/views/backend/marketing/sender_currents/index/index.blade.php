@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
@endpush


@section('title', 'Gönderici Cariler')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas fa-money-check-alt icon-gradient bg-plum-plate"></i>
                    </div>
                    <div> Gönderici Cariler
                        <div class="page-title-subheading">Bu modül üzerinden tüm Kurumsal-Bireysel gönderici carileri
                            düzenleyebilir, detayını görüntüleyebilir ve yeni cari oluşturabilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('SenderCurrents.create') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
						<span class="btn-icon-wrapper pr-2 opacity-7">
							<i class="fa fa-plus fa-w-20"></i>
						</span>
                                Yeni Cari Oluştur
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>CK - Cariler
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
                        <div class="col-md-2">
                            <label for="agency">Bağlı Acente</label>
                            <select name="agency" id="agency" style="width: 100%"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->city . '/' . $key->district . '-' . $key->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="name">Ad Soyad/Firma</label>
                            <input type="text" class="form-control form-control-sm niko-filter" name="name_surname"
                                   id="name">
                        </div>

                        <div class="col-md-2">
                            <label for="currentCode">Cari Kod</label>
                            <input name="name_surname" id="currentCode"
                                   data-inputmask="'mask': '999 999 999'"
                                   placeholder="___ ___ ___" type="text"
                                   class="form-control input-mask-trigger form-control-sm niko-filter">
                        </div>

                        <div class="col-md-2">
                            <label for="status">Statü</label>
                            <select name="status" id="status" class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                <option value="1">Aktif</option>
                                <option value="0">Pasif</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="creatorUser">Ekleyen Personel</label>
                            <select name="agency" style="width: 100%" id="creatorUser"
                                    class="form-control form-control-sm niko-select-filter">
                                <optgroup label="GM YETKİLİLERİ">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['gm_users'] as $key)
                                        <option value="{{$key->id}}">{{$key->name_surname}}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="category">Kategori</label>
                            <select name="agency" id="category" class="form-control form-control-sm niko-select-filter">
                                <option value="-1">Seçiniz</option>
                                <option value="Anlaşmalı">Anlaşmalı</option>
                                <option value="Kurumsal">Kurumsal</option>
                                <option value="Bireysel">Bireysel</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="record">Kayıt</label>
                            <select name="record" id="record" class="form-control form-control-sm niko-select-filter">
                                <option value="1">Kayıt</option>
                                <option value="0">Arşiv</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="confirmed">Onay</label>
                            <select name="record" id="confirmed"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                <option value="1">Onaylandı</option>
                                <option value="0">Onay Bekliyor</option>
                            </select>
                        </div>

                    </div>
                </form>

            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-borderless table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Cari Kod</th>
                        <th>Kategori</th>
                        <th>Ad/Firma</th>
                        <th>İl/İlçe</th>
                        <th>Bağlı Şube</th>
                        <th>Oluşturan</th>
                        <th>Onay</th>
                        <th>Statü</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Cari Kod</th>
                        <th>Kategori</th>
                        <th>Ad/Firma</th>
                        <th>İl/İlçe</th>
                        <th>Bağlı Şube</th>
                        <th>Oluşturan</th>
                        <th>Onay</th>
                        <th>Statü</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <script src="/backend/assets/scripts/marketing/sender-currents/index.js"></script>
    <style type="text/css">
        pre#json-renderer {
            border: 1px solid #aaa;
        }
    </style>
@endsection

@section('modals')

    @include('backend.marketing.sender_currents.index.includes.modal_customer_detail')

    @include('backend.marketing.sender_currents.index.includes.modal_enabled_disabled')
@endsection
