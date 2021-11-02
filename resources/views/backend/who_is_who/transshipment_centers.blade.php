@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Transfer Merkezleri')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-truck icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div> Transfer Merkezleri
                        <div class="page-title-subheading">Bu modül üzerinden sistemdeki tüm transfer merkezlerini ve transfer merkezlerine bağlı acenteleri görüntüleyebilirsiniz.
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon fa fa-truck mr-3 text-muted opacity-6"> </i>Tüm Aktarmalar
                </div>

            </div>
            <div class="card-body">

                <table id="AgenciesTable"
                    class="align-middle mb-0 table Table30Padding table-borderless table-striped NikolasDataTable">
                    <thead>
                    <tr>
                        <th>İl/İlçe</th>
                        <th>Aktarma Adı</th>
                        <th>Bağ. Old. Bölge</th>
                        <th>Bağ. Old. Acente Sayısı</th>
                        <th>Aktarma Tipi</th>
                        <th>Aktarma Müdürü</th>
                        <th>İletişim</th>
                        <th width="10" class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/whoiswho/ts.js"></script>
@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalAgencyDetail" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Transfer Merkezi Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyAgencyDetail" class="modal-body">

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

                                        <div style="overflow-x: scroll" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" colspan="2" id="trmheader"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">İl İlçe</td>
                                                    <td id="cityDistrict">İstanbul/Bağcılar</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Aktarma Adı</td>
                                                    <td id="transshippingname">Mecidiye Köy</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">AktarmaTipi</td>
                                                    <td id="transshippingtype">Adres Satırı</td>
                                                </tr>                                                           
                                                <tr>
                                                    <td class="static">Aktarma Müdürü</td>
                                                    <td id="directorname">İkitelli Transfer Merkezi</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Aktarma Müdürü İletişim</td>
                                                    <td data-inputmask="'mask': '(999) 999 99 99'" id="directphone">535 427 68
                                                        24
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Aktarma Müdürü Email</td>
                                                    <td id="directemail">Aktif</td>
                                                </tr>                                           
                                                <tr>
                                                    <td class="static">Aktarma Müdür Yardımcısı</td>
                                                    <td id="assistantdirectorname">Aktif</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Aktarma Müdür Yardımcısı İletişim</td>
                                                    <td data-inputmask="'mask': '(999) 999 99 99'" id="assistantdirectorphone">535 427 68
                                                        24
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Aktarma Müdür Yardımcısı Email</td>
                                                    <td id="assistantdirectoremail">021234</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <h4 class="mt-3">Aktarmaya Bağlı Şubeler</h4>

                                        <div style="overflow: auto; max-height: 150px;" class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees" class="Table20Padding  table table-bordered table-striped mt-3">
                                                <thead>
                                                <tr>
                                                    <th>Şube Kodu</th>
                                                    <th>İl/İlçe</th>
                                                    <th>Acente Adı</th>
                                                    <th>İletişim</th>
                                                    <th>Acente Sahibi</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyEmployees">

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
