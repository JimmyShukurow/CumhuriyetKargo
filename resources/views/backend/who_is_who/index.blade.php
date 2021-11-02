@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Kim Kimdir?')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas fa-users icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Kim Kimdir?
                        <div class="page-title-subheading">Bu modül üzerinden sisteme kayıtlı tüm <b>Cumhuriyet
                                Kargo</b>
                            çalışanlarını görüntüleyebilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">

                        <div class="col-md-2">
                            <label for="tc">Aktarma</label>
                            <select name="tc" id="tc" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($data['tc'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->city . '-' . $key->tc_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="agency">Acente</label>
                            <select name="agency" id="agency" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->city . '/' . $key->district . '-' . $key->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="name_surname">Ad Soyad</label>
                            <input type="text" class="form-control" name="name_surname" id="name_surname">
                        </div>

                        <div class="col-md-2">
                            <label for="user_type">Kullanıcı Tipi</label>
                            <select name="user_type" id="user_type" class="form-control">
                                <option value="">Seçiniz</option>
                                <option value="Acente">Acente</option>
                                <option value="Aktarma">Aktarma</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="role">Yetki</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($data['roles'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->display_name }}</option>
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
                       class="align-middle mb-0 table table-borderless table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Ad Soyad</th>
                        <th>Yetki</th>
                        <th>Eposta</th>
                        <th>Telefon</th>
                        <th>Şube İl</th>
                        <th>Şube İlçe</th>
                        <th>Şube Adı</th>
                        <th>Kullanıcı Tipi</th>
                        <th>Detay</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Ad Soyad</th>
                        <th>Yetki</th>
                        <th>Eposta</th>
                        <th>Telefon</th>
                        <th>Şube İl</th>
                        <th>Şube İlçe</th>
                        <th>Şube Adı</th>
                        <th>Kullanıcı Tipi</th>
                        <th>Detay</th>
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
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/whoiswho/persons.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">

    <style type="text/css">
        pre#json-renderer {
            border: 1px solid #aaa;
        }
    </style>
@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalUserDetail" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Kullanıcı Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyUserDetail" class="modal-body">

                    {{-- CARD START --}}
                    <div class="col-md-12">
                        <div class="mb-3 profile-responsive card">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-dark">
                                    <div class="menu-header-image "
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract10.jpg');">
                                    </div>
                                    <div class="menu-header-content btn-pane-right">
                                        <div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                            <div class="avatar-icon rounded">
                                                <img src="/backend/assets/images/ck-ico-white.png" alt="Avatar 5">
                                            </div>
                                        </div>
                                        <div>
                                            <h5 id="agencyName" class="menu-header-title">###</h5>
                                            <h6 id="agencyCityDistrict" class="menu-header-subtitle">###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">


                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: auto" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="2">Genel Merkez
                                                        Acente
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">Ad Soyad</td>
                                                    <td id="name_surname">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Telefon</td>
                                                    <td id="phone">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Yetki</td>
                                                    <td id="authority">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">E-Mail</td>
                                                    <td id="email"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>


                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: auto" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="2">BAĞIMLILIKLAR
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">Bağlı olduğu kişi</td>
                                                    <td id="dependency">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Bağlı olduğu yer</td>
                                                    <td id="district"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Çalıştığı yer</td>
                                                    <td id="place"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">İletişim</td>
                                                    <td id="phone"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </li>


                            </ul>
                        </div>
                    </div>
                    {{-- CARD END --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
@endsection
