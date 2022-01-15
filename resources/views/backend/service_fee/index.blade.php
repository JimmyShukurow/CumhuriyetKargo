@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush

@section('title', 'Hizmet Ücretleri')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas fa-money-check-alt icon-gradient bg-plum-plate">
                        </i>
                    </div>
                    <div>Hizmet Ücretleri
                        <div class="page-title-subheading">Bu modül üzerinden kargo taşıma, ek hizmet ve dosya
                            fiyatlarını görüntüleyebilir, düzenleyebilirsiniz.
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
            <div class="mb-3 card">
                <div class="card-header">
                    <ul class="nav nav-justified">
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-additional-services"
                               class="{{ setActive($tab, 'AdditionalServices')}} nav-link tab-nav-link">Ek Hizmetler</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-desi-list"
                               class="{{ setActive($tab, 'DesiList')}} nav-link tab-nav-link">Desi Fiyatları</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#modules"
                               class="{{ setActive($tab, 'FilePrice')}} nav-link tab-nav-link">Dosya Fiyat</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#miPrice"
                               class="{{ setActive($tab, 'MiPrice')}} nav-link tab-nav-link">Mi Fiyat</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-distance-price-list"
                               class="{{ setActive($tab, 'DistancePrice')}} nav-link tab-nav-link">Mesafe Fiyat</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane {{ setActive($tab, 'AdditionalServices')}} min-vh-100"
                             id="tab-additional-services"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <button style="float: right; margin: 15px 0;" id="btnNewAdditionalService"
                                        class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success">
                                    <i class="lnr-plus-circle btn-icon-wrapper"> </i>Ek Servis Ekle
                                </button>

                                <div class="card-body">
                                    <div style="overflow-x: auto; ">
                                        <table style="white-space: nowrap;"
                                               class="table table-bordered  TableAdditionalServices table-striped Table30Padding mb-5">
                                            <thead>
                                            <tr>
                                                <th>Ek Hizmet</th>
                                                <th>Ücret</th>
                                                <th>Statü</th>
                                                <th>Kayıt Tarihi</th>
                                                <th>Değitirilme Tarihi</th>
                                                <th width="40">İşlem</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Ek Hizmet</th>
                                                <th>Ücret</th>
                                                <th>Statü</th>
                                                <th>Kayıt Tarihi</th>
                                                <th>Değitirilme Tarihi</th>
                                                <th>İşlem</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{ setActive($tab, 'DesiList') }} min-vh-100" id="tab-desi-list"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <button style="float: right; margin: 15px 0;" id="addNewDesiInterval"
                                        class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-alternate">
                                    <i class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Desi Aralığı Ekle
                                </button>

                                <div class="card-body">
                                    <table style="white-space: nowrap;"
                                           class="table table-bordered  TableDesiList table-striped Table30Padding mb-5">
                                        <thead>
                                        <tr>
                                            <th>Başlangıç Desi</th>
                                            <th>Bitiş Desi</th>
                                            <th>Desi Ücreti</th>
                                            <th>Kurumsal Birim Fiyat</th>
                                            <th>Bireysel Birim Fiyat</th>
                                            <th>Mobil Birys. Brm. Fiyat</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>Değitirilme Tarihi</th>
                                            <th width="40">İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Başlangıç Desi</th>
                                            <th>Bitiş Desi</th>
                                            <th>Desi Ücreti</th>
                                            <th>Kurumsal Birim Fiyat</th>
                                            <th>Bireysel Birim Fiyat</th>
                                            <th>Mobil Birys. Brm. Fiyat</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>Değitirilme Tarihi</th>
                                            <th width="40">İşlem</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane {{ setActive($tab, 'FilePrice') }} min-vh-100" id="modules"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <div style="overflow-x: scroll;" class="card-body min-vh-100">
                                    <table style="white-space: nowrap;"
                                           class="table table-bordered table-dark mb-5">
                                        <thead>
                                        <tr>
                                            <th>Kurumsal</th>
                                            <th>Bireysel</th>
                                            <th>Mobil</th>
                                            <th>Son Güncelleme</th>
                                            <th width="10">İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="file-price">
                                            <td>
                                                <b id="corporateFilePrice">₺{{$filePrice->corporate_file_price}}</b>
                                            </td>
                                            <td>
                                                <b id="individualFilePrice">₺{{$filePrice->individual_file_price}}</b>
                                            </td>
                                            <td>
                                                <b id="mobileFilePrice">₺{{$filePrice->mobile_file_price}}</b>
                                            </td>
                                            <td>
                                                <b id="filePriceUpdate">{{$filePrice->updated_at}}</b>
                                            </td>
                                            <td>
                                                <button id="{{$filePrice->id}}"
                                                        class="btn btn-primary editFilePrice">Düzenle
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{ setActive($tab, 'MiPrice') }} min-vh-100" id="miPrice"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <div style="overflow-x: scroll;" class="card-body min-vh-100">
                                    <table style="white-space: nowrap;"
                                           class="table table-bordered mb-5">
                                        <thead>
                                        <tr>
                                            <th>Kurumsal</th>
                                            <th>Bireysel</th>
                                            <th>Mobil</th>
                                            <th>Son Güncelleme</th>
                                            <th width="10">İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="mi-price">
                                            <td>
                                                <b id="corporateMiPrice">₺{{$filePrice->corporate_mi_price}}</b>
                                            </td>
                                            <td>
                                                <b id="individualMiPrice">₺{{$filePrice->individual_mi_price}}</b>
                                            </td>
                                            <td>
                                                <b id="mobileMiPrice">₺{{$filePrice->mobile_mi_price}}</b>
                                            </td>
                                            <td>
                                                <b id="miPriceUpdate">{{$filePrice->updated_at}}</b>
                                            </td>
                                            <td>
                                                <button id="{{$filePrice->id}}"
                                                        class="btn btn-primary editMiPrice">Düzenle
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{ setActive($tab, 'DistancePrice') }} min-vh-100"
                             id="tab-distance-price-list"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <div class="card-body">
                                    <table style="white-space: nowrap;"
                                           class="table table-bordered  DistancePriceList table-striped Table30Padding mb-5">
                                        <thead>
                                        <tr>
                                            <th>Mesafe</th>
                                            <th>Başlangıç</th>
                                            <th>Bitiş</th>
                                            <th>Fiyat</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>Değitirilme Tarihi</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Mesafe</th>
                                            <th>Başlangıç</th>
                                            <th>Bitiş</th>
                                            <th>Fiyat</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>Değitirilme Tarihi</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/backend/assets/scripts/backend-modules.js"></script>

@endsection

@section('js')

    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/marketing/service-fee-index.js"></script>

@endsection

@section('modals')
    {{-- Standart Modal - NewAdditionalService --}}
    <div class="modal fade" id="modalNewAdditionalService" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Ek Servis Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="additionalServiceName">Servis Adı</label>
                                <input id="additionalServiceName" class="form-control" type="text"
                                       placeholder="Örn:Öncelikli Teslimat" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="additionalServicePrice">Ek hizmet ücreti</label>
                                <input class="form-control input-mask-trigger" id="additionalServicePrice"
                                       type="text"
                                       placeholder="₺ 0.00"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnInsertAdditionalService" type="button" class="btn btn-primary">Ek Hizmet Ekle
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Edit Additional Service --}}
    <div class="modal fade" id="modalEditAdditionalService" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ek Servis Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editAdditionalServiceName">Servis Adı</label>
                                <input id="editAdditionalServiceName" class="form-control" type="text"
                                       placeholder="Örn:Öncelikli Teslimat" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editAdditionalServicePrice">Ek hizmet ücreti</label>
                                <input class="form-control input-mask-trigger" id="editAdditionalServicePrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editAdditionalServiceStatus">Statü</label>
                                <select class="form-control" name="" id="editAdditionalServiceStatus">
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnUpdateAdditionalService" type="button" class="btn btn-primary">Ek Hizmeti Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - New Desi Interval --}}
    <div class="modal fade" id="modalNewDesiInterval" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Desi Aralığı Gir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="startDesi">Başlangıç Desi</label>
                                <input id="startDesi" class="form-control" type="text"
                                       value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="finishDesi">Bitiş Desi</label>
                                <input id="finishDesi" class="form-control" type="text"
                                       value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="desiPrice">Desi ücreti</label>
                                <input class="form-control input-mask-trigger" id="desiPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="corporateUnitPrice">Kurumsal Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="corporateUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="individualUnitPrice">Bireysel Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="individualUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobileIndividualUnitPrice">Mobil Bireysel Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="mobileIndividualUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnInsertNewDesiInterval" type="button" class="btn btn-primary">Desi Aralığını Ekle
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Edit Desi Interval --}}
    <div class="modal fade" id="modelEditDesiInterval" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Desi Aralığını Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editStartDesi">Başlangıç Desi</label>
                                <input id="editStartDesi" class="form-control" type="text"
                                       value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFinishDesi">Bitiş Desi</label>
                                <input id="editFinishDesi" class="form-control" type="text"
                                       value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editDesiPrice">Desi ücreti</label>
                                <input class="form-control input-mask-trigger" id="editDesiPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editCorporateUnitPrice">Kurumsal Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="editCorporateUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editIndividualUnitPrice">Bireysel Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="editIndividualUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editMobileIndividualUnitPrice">Mobil Bireysel Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="editMobileIndividualUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnUpdateDesiInterval" type="button" class="btn btn-primary">Desi Aralığını Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Edit File Price --}}
    <div class="modal fade" id="modalEditFilePrice" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dosya Fiyatlarını Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editCorporteFilePrice">Kurumsal dosya ücreti:</label>
                                <input class="form-control input-mask-trigger" id="editCorporteFilePrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editIndividualFilePrice">Bireysel dosya ücreti:</label>
                                <input class="form-control input-mask-trigger" id="editIndividualFilePrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editIndividualFilePrice">Mobil dosya ücreti:</label>
                                <input class="form-control input-mask-trigger" id="editMobileFilePrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnUpdateFilePrice" type="button" class="btn btn-primary">Dosya Fiyatlarını Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Edit Mi Price --}}
    <div class="modal fade" id="modalEditMiPrice" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mi Fiyatlarını Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editCorporteFilePrice">Kurumsal mi ücreti:</label>
                                <input class="form-control input-mask-trigger" id="editCorporteMiPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editIndividualFilePrice">Bireysel mi ücreti:</label>
                                <input class="form-control input-mask-trigger" id="editIndividualMiPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editIndividualFilePrice">Mobil mi ücreti:</label>
                                <input class="form-control input-mask-trigger" id="editMobileMiPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnUpdateMiPrice" type="button" class="btn btn-primary">Mi Fiyatlarını Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
