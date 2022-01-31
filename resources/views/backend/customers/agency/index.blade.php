@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Tüm Müşterileriniz')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Tüm Müşterileriniz
                        <div class="page-title-subheading">
                            Oluşturduğunuz gönderici ve alıcıları buradan listeleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('user.gm.AddUser') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Kullanıcı Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">

                        <div class="col-md-2">
                            <label for="customer_type">Müşteri Tipi</label>
                            <select name="customer_type" id="customer_type" class="form-control form-control-sm ">
                                <option value="">Tümü</option>
                                <option value="Alıcı">Alıcı</option>
                                <option value="Gönderici">Gönderici</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="category">Kategori</label>
                            <select name="category" id="category" class="form-control form-control-sm ">
                                <option value="">Tümü</option>
                                <option value="Bireysel">Bireysel</option>
                                <option value="Kurumsal">Kurumsal</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="currentCode">Cari Kod</label>
                            <input name="currentCode" id="currentCode" data-inputmask="'mask': '999 999 999'"
                                   placeholder="___ ___ ___" type="text"
                                   class="form-control input-mask-trigger form-control-sm niko-filter" im-insert="true">
                        </div>

                        <div class="col-md-2">
                            <label for="customer_name_surname">Müşteri Adı</label>
                            <input type="text" class="form-control form-control-sm " name="customer_name_surname"
                                   id="customer_name_surname">
                        </div>

                        <div class="col-md-2 ">
                            <div class="position-relative form-group">
                                <label for="phone" class="">Telefon </label>
                                <input name="phone" id="phone" data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text" value=""
                                       class="form-control form-control-sm input-mask-trigger" im-insert="true">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="city">Şehir</label>
                            <select name="city" id="city" class="form-control form-control-sm ">
                                <option value="">Seçiniz</option>
                                @foreach($data['cities'] as $key)
                                    <option
                                        value="{{$key->city_name}}">{{ $key->city_name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row text-center mt-3">
                        <div class="col-md-12 text-center">
                            <button id="btn-submit" type="submit" class="btn btn-primary ">Ara</button>
                            <input type="reset" class="btn btn-secondary">
                        </div>
                    </div>

                </form>


            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Tüm Kullanıcılar
                </div>

            </div>
            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-borderless table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Cari Kodu</th>
                        <th>Müşteri Tipi</th>
                        <th>Ad</th>
                        <th>Kategori</th>
                        <th>City</th>
                        <th>District</th>
                        <th>Neighborhood</th>
                        <th>Tel</th>
                        <th>Tel 2</th>
                        <th>Kayıt Yapan</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Cari Kodu</th>
                        <th>Müşteri Tipi</th>
                        <th>Ad</th>
                        <th>Kategori</th>
                        <th>City</th>
                        <th>District</th>
                        <th>Neighborhood</th>
                        <th>Tel</th>
                        <th>Tel 2</th>
                        <th>Kayıt Yapan</th>
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
    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/customers/customer-details.js"></script>
    <script src="/backend/assets/scripts/customers/index.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <style type="text/css">
        pre#json-renderer {
            border: 1px solid #aaa;
        }
    </style>
@endsection

@section('modals')
    @php $data = ['type' => 'customers']; @endphp
    @include('backend/customers/agency/modal_customer_details')
@endsection
