@extends('backend.layout')

@push('css')
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@section('title', 'Ücret Hesapla')

@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Ücret Hesapla
                        <div class="page-title-subheading">Bu modül üzerinden standart-anlaşmalı gönderim bedelini
                            hesaplayabilirsiniz.
                        </div>
                    </div>
                </div>

                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">

                    </div>
                </div>

            </div>
        </div>


        <div class="tabs-animation unselectable">

            <div class="card mb-3">
                {{-- CARD START --}}

                <div class="row p-3">


                    {{-- Customer START --}}
                    <div class="col-md-4 border-box" id="divider-gonderici">
                        <h6 class="font-weight-bold">Müşteri - Seçenekler</h6>
                        <div style="margin-top: 0;" class="divider"></div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="gondericiCariKod">Cari KOD:</label>
                                </div>
                                <div class="input-group">
                                    <input id="gondericiCariKod"
                                           data-inputmask="'mask': '999 999 999'"
                                           placeholder="___ ___ ___" type="text"
                                           class="form-control input-mask-trigger form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="gondericiCariKod">Müşteri Tipi:</label>
                                </div>
                                <div class="input-group">
                                    <input style="color:blue;" id="customerType" type="text" value="Bireysel" readonly
                                           class="form-control font-weight-bold form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="gondericiAdi">Müşteri Adı:</label>
                                </div>
                                <div class="input-group">
                                    <select class="form-control" name="" style="width:100%;" id="gondericiAdi">
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="GondericiTelefon">Müşteri Telefon:</label>
                                </div>
                                <div class="input-group">
                                    <input type="text" id="GondericiTelefon" data-inputmask="'mask': '(999) 999 99 99'"
                                           placeholder="(___) ___ __ __"
                                           class="form-control input-mask-trigger form-control-sm" im-insert="true">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2 mb-2">
                            <div class="col-md-6">
                                <button id="btnClearSenderInfo"
                                        class="float-left btn-icon btn-square btn btn-sm btn-danger"><i
                                        class="lnr-cross btn-icon-wrapper"> </i>Temizle
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button id="searchCurrent"
                                        class="float-right btn-icon btn-square btn btn-sm btn-primary"><i
                                        class="fa fa-search-plus btn-icon-wrapper"> </i>Ara
                                </button>
                            </div>
                        </div>


                        <div class="form-group row mt-3">
                            <label for="colFormLabelSm"
                                   class="col-sm-5 col-form-label">Gönderi Türü:</label>


                            <div id="divCargoType" class="col-sm-7 p-0">

                                <select name="" id="selectCargoType" class="form-control form-control-sm">
                                    <option value="">Seçiniz</option>
                                    @foreach(allCargoTypes() as $key)
                                        <option value="{{$key}}">{{$key}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input style="display: none;" type="button" id="fakeButton">

                        <div class="form-group row">
                            <label for="colFormLabelSm"
                                   class="col-sm-5 col-form-label">Ödeme Tipi:</label>
                            <div id="divPaymentType" class="col-sm-7 p-0">
                                <input class="check_user_type" data-width="100%"
                                       style="display: none; "
                                       onchange="" type="checkbox"
                                       checked id="paymentType"
                                       data-toggle="toggle"
                                       data-on="Gönderici Öd."
                                       data-off="Alıcı Öd." data-onstyle="primary" data-offstyle="danger">
                            </div>
                        </div>
                        <div class="form-row collection-container">
                            <div class="col-md-4">
                                <div class="position-relative ">
                                    <label for="gondericiMusteriTipi">Fatura Tutarı:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input id="TahsilatFaturaTutari" readonly type="text"
                                           data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                           type="text" value="{{ old('dosyaUcreti') }}"
                                           class="form-control text-center text-primary input-mask-trigger form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative ">
                                    <label for="tahsilatOdenecekTutar">Ödenecek Tutar:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="tahsilatOdenecekTutar"
                                           class="form-control text-center text-success form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative ">
                                    <label for="tahsilatKesintiTutari">Kesinti:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="tahsilatKesintiTutari"
                                           class="form-control text-center text-danger form-control-sm">
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- Customer END --}}

                    {{-- CALC DESİ START--}}
                    <div id="divider-calculate" class="col-md-8">

                        <h6 class="font-weight-bold text-center">Ebat Bilgileri</h6>
                        <div style="margin-top: 0;" class="divider"></div>

                        {{-- CARD START --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <button style="width: 100%" id="btnDeleteAllPartDesiRow"
                                        class="btn-icon btn-square btn btn-xs btn-danger mb-2">
                                    <i class="lnr-cross-circle btn-icon-wrapper"> </i>Tümünü Sil
                                </button>
                            </div>

                            <div class="col-md-4 text-center">
                                <button style="width: 100%" id="btnAddNewPartDesiRow"
                                        class="btn-icon btn-square btn btn-xs btn-alternate mb-2">
                                    <i class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Parça
                                </button>
                            </div>

                            <div class="col-md-4">
                                <button style="width: 100%" id="btnAddMultiplePartDesiRow"
                                        class="btn-icon btn-square btn btn-xs btn-primary mb-2">
                                    <i class="lnr-plus-circle btn-icon-wrapper"> </i>Toplu Parça
                                </button>
                            </div>
                        </div>

                        <form method="post" action="" id="formPartDesiContainer">
                            <div style="max-height: 350px; overflow-y: auto; overflow-x: hidden"
                                 class="mostly-customized-scrollbar">

                                <div class="cont">
                                    <div id="partDesiContainer1"
                                         class="row partDesiContainer animate__animated animate__fadeInDown">

                                        <div class="col-md-12">
                                            <div style="border-bottom: 1px solid lightslategray;"
                                                 class="form-row">


                                                <div class="col-md-1">
                                                    <div class="position-relative ">
                                                        <label for=""></label>
                                                    </div>

                                                    <div class="input-group mb-1">
                                                        <button style="margin: 9px auto;"
                                                                id="1"
                                                                class="destroyDesiRow btn-icon btn-icon-only btn-xs btn btn-danger">
                                                            <i class="lnr-cross btn-icon-wrapper"> </i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="position-relative ">
                                                        <label for="partDesiEn1">En:</label>
                                                    </div>
                                                    <div class="input-group mb-1">
                                                        <input id="partDesiEn1" type="text" data="1"
                                                               class="form-control form-control-sm input-mask-trigger partDesiEn partDesiCalc validate-part-desi"
                                                               placeholder="0" name="partDesiEn1"
                                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                               im-insert="true" style="text-align: right;">

                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="position-relative ">
                                                        <label for="partDesiBoy1">Boy:</label>
                                                    </div>
                                                    <div class="input-group mb-1">
                                                        <input id="partDesiBoy1" type="text" data="1"
                                                               class="form-control form-control-sm input-mask-trigger partDesiBoy partDesiCalc validate-part-desi"
                                                               placeholder="0" name="partDesiBoy1"
                                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                               im-insert="true" style="text-align: right;">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="position-relative ">
                                                        <label for="partDesiYukseklik1">Yükseklik:</label>
                                                    </div>
                                                    <div class="input-group mb-1">
                                                        <input id="partDesiYukseklik1" data="1"
                                                               class="form-control form-control-sm input-mask-trigger partDesiYukseklik partDesiCalc validate-part-desi"
                                                               placeholder="0" name="partDesiYukseklik1"
                                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                               im-insert="true" style="text-align: right;">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="position-relative ">
                                                        <label for="partDesiAgirlik1">Ağırlık:</label>
                                                    </div>
                                                    <div class="input-group mb-1">
                                                        <input id="partDesiAgirlik1" data="1"
                                                               class="form-control form-control-sm input-mask-trigger partDesiAgirlik partDesiCalc validate-part-desi"
                                                               placeholder="0" name="partDesiAgirlik1"
                                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                               im-insert="true" style="text-align: right;">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="position-relative ">
                                                        <label for="partTotalDesi1">Desi:</label>
                                                    </div>
                                                    <div class="input-group mb-1">
                                                        <input disabled type="number" value="0"
                                                               id="partTotalDesi1"
                                                               class="form-control no-spin text-center font-weight-bold text-success form-control-sm">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="position-relative ">
                                                        <label for="partRealDesi1">Ü.E.
                                                            Ağr:</label>
                                                    </div>
                                                    <div class="input-group mb-1">
                                                        <input disabled type="number" value="0"
                                                               id="partRealDesi1"
                                                               class="form-control no-spin text-center font-weight-bold text-danger form-control-sm partRealDesi">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="position-relative">
                                                        <label
                                                            for="partDesiHacim1">Hacim
                                                            (m<sup>3</sup>):</label>
                                                    </div>
                                                    <div class="input-group mb-1">
                                                        <input id="partDesiHacim1" type="text" disabled
                                                               class="form-control form-control-sm partDesiHacim text-center text-dark font-weight-bold"
                                                               placeholder="0" value="0"
                                                               im-insert="true" style="text-align: right;">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-md-12 text-center mt-3 font-weight-bold">

                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <td>Top. Parça Sayısı</td>
                                            <td>Top. Ücrete Esas Ağırlık</td>
                                            <td>Top. M<sup>3</sup></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <b id="hRowSummery" class="text-dark">1</b>
                                                <b class="text-dark">Adet Parça</b>
                                            </td>
                                            <td>
                                                <b id="hPartsTotalDesi" class="text-dark">0</b>
                                                <b class="text-dark"> Desi</b>
                                            </td>
                                            <td>
                                                <b id="hPartsTotalM3" class="text-dark">0</b>
                                                <b class="text-dark"> M<sup>3</sup></b>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="col-md-12">
                                        <button id="btnCalculatePartDesi" style="width: 100%"
                                                class="p-3 ladda-button bg-ck mb-2 mr-2 btn btn-shadow btn-primary"
                                                data-style="slide-right">
                                            <span class="ladda-label">Hesapla</span>
                                            <span class="ladda-spinner"></span>
                                            <div class="ladda-progress" style="width: 0px;"></div>
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </form>
                    </div>
                    {{-- CALC DESİ END--}}

                    {{-- Ek Hizmetler START--}}
                    <div class="col-md-12 border-box" id="divider-hizmetler">

                        <div class="form-row mt-4">

                            <div style="padding-top: 35px; padding-left: 20px; border: 2px solid #ce2427"
                                 class="col-md-3 unselectable">
                                <h6 class="font-weight-bold">Ek Hizmetler</h6>
                                <form id="formAdditionalServices">
                                    @foreach($data['additional_service'] as $service)
                                        <div class="form-check mb-1">
                                            <input class="form-check-input add-fee cursor-pointer" type="checkbox"
                                                   value="{{$service->price}}" name="add-service-{{$service->id}}"
                                                   {{$service->default == '1' ? 'checked' : ''}}
                                                   id="add-service-{{$service->id}}" {{$service->status == 0 ? 'disabled' : ''}}>
                                            <label class="form-check-label cursor-pointer"
                                                   for="add-service-{{$service->id}}">{{$service->service_name}}
                                                ({{$service->price}}₺)
                                            </label>
                                        </div>
                                    @endforeach
                                </form>
                                <div class="form-check mb-1">

                                    <input class="form-check-input cursor-pointer" id="add-service-tahsilatli"
                                           type="checkbox"
                                           {{$data['collectible_cargo']->value == '0' ? 'disabled' : ''}} value="0">
                                    <label class="form-check-label cursor-pointer"
                                           for="add-service-tahsilatli">Tahsilatlı Kargo
                                    </label>
                                </div>
                                {{--                                <img id="loginLogo" class="loginLogo" src="/backend/login_assets/CKG_Sis_Gif.gif"--}}
                                {{--                                     alt="branding logo" width="100%"--}}
                                {{--                                     style="max-width:400px; position: absolute; bottom: 0; left: 0;">--}}
                            </div>

                            <div style="padding: 0 40px; border: 2px solid #ce2427; border-left: 0px solid #ce2427;"
                                 class="col-md-9">

                                <fieldset class="row ">
                                    <h3 style="width: 100%;" class="font-weight-bold text-center">Özet</h3>

                                    <table class="table-bordered table-striped unselectable"
                                           style="width: 100%;padding-left: 20px;" id="tableSummery">
                                        <tbody>
                                        <tr>
                                            <td>Mesafe(Km):</td>
                                            <td width="100" class="text-center"><b id="distance">0</b></td>
                                        </tr>
                                        <tr>
                                            <td>Mesafe Ücreti:</td>
                                            <td class="text-center">
                                                <label id="labelDistancePrice" class="font-weight-bold">
                                                    0</label><b>₺</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Posta Hizmetleri Bedeli:</td>
                                            <td class="text-center">
                                                <label id="postServicePrice"
                                                       class="font-weight-bold">{{$fee['postal_services_fee']}}</label><b>₺</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ağır Yük Taşıma Bedeli:</td>
                                            <td class="text-center">
                                                <label id="heavyLoadCarryingCost"
                                                       class="font-weight-bold">0</label><b>₺</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b style="text-decoration: underline" id="btnDesi"
                                                   class="cursor-pointer">Desi:</b>
                                            </td>
                                            <td class="text-center">
                                                <label id="labelDesi" class="font-weight-bold"> 0</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Parça Sayısı:</td>
                                            <td class="text-center">
                                                <label id="partQuantity" class="font-weight-bold ">1</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ek Hizmet:</td>
                                            <td class="text-center">
                                                <label id="addFeePrice"
                                                       class="font-weight-bold">{{$fee['first_add_service']}}</label><b>₺</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Hizmet Ücreti:</td>
                                            <td class="text-center">
                                                <label id="serviceFee"
                                                       class="font-weight-bold">{{$fee['first_file_price']}}</label><b>₺</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Mobil Hizmet Ücreti:</td>
                                            <td class="text-center">
                                                <label id="mobileServiceFee"
                                                       class="font-weight-bold">0</label><b>₺</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>KDV:</td>
                                            <td class="text-center"><b>%</b><label id="kdvPercent"
                                                                                   class="font-weight-bold">18</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>KDV Hariç:</td>
                                            <td class="text-center">
                                                <label id="kdvExcluding"
                                                       class="font-weight-bold">{{$fee['first_total_no_kdv']}}</label><b>₺</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold" style="font-size: 1.4rem; color: black;">Genel
                                                Toplam:
                                            </td>
                                            <td class="text-center" style="font-size: 1.3rem; color: black;">
                                                <label id="totalPrice"
                                                       class="font-weight-bold">{{$fee['first_total']}}</label><b>₺</b>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="unselectable col-sm-12"></div>

                                </fieldset>
                            </div>

                        </div>
                    </div>
                    {{--  Ek Hizmetler START--}}

                </div>
                {{-- CARD END --}}

            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script src="/backend/assets/scripts/main-cargo/calculate-service-fee.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
@endsection

@section('modals')
    {{-- Large modal  => New Sender (Current) --}}
    <div class="modal fade bd-example-modal-lg" id="modalNewCurrent" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Yeni Gönderici Oluştur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        {{-- CARD START --}}

                        <div class="row">
                            {{-- Gönderici START --}}
                            <div class="col-md-4 border-box" id="divider-gonderici">

                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="currentSelectCategory">Kategori:</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <select id="currentSelectCategory" class="form-control" readonly="" name="">
                                                <option value="Bireysel">Bireysel</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="currentTckn">Gönderici TCKN:</label> <b
                                                class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="currentTckn" maxlength="11"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="currentName">Adı:</label><b class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="currentName" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="currentSurName">Soyadı:</label><b class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="currentSurName" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="currentYearOfBirth">Doğum Yılı:</label><b
                                                class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="currentYearOfBirth"
                                                   data-inputmask="'mask': '9999'"
                                                   placeholder="____" type="text"
                                                   class="form-control input-mask-trigger form-control-sm">
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        <button id="btnTCConfirm" type="button" style="width: 100%"
                                                class="text-center btn-icon btn-square btn btn-danger">
                                            <i class="fa fa-check btn-icon-wrapper"> </i>Doğrula
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 border-box" id="divider-gonderici2">

                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="position-relative ">
                                            <label for="currentGSM">Cep Telefonu:</label><b class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="currentGSM"
                                                   data-inputmask="'mask': '(999) 999 99 99'"
                                                   placeholder="(___) ___ __ __" type="text"
                                                   class="form-control input-mask-trigger form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="position-relative ">
                                            <label for="currentPhone">Telefon:</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="currentPhone"
                                                   data-inputmask="'mask': '(999) 999 99 99'"
                                                   placeholder="(___) ___ __ __" type="text"
                                                   class="form-control input-mask-trigger form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="position-relative ">
                                            <label for="currentEmail">Email:</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="currentEmail"
                                                   data-inputmask="'alias': 'email'" type="email"
                                                   class="form-control input-mask-trigger form-control-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="position-relative ">
                                            <label for="currentCity">İl:</label><b class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <select name="" id="currentCity" class="form-control form-control-sm">
                                                <option value="">İl Seçiniz</option>
                                                @foreach($data['cities'] as $city)
                                                    <option
                                                        {{$data['user_city'] == $city->city_name ? 'selected' : ''}}
                                                        value="{{$city->id}}">{{$city->city_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative ">
                                            <label for="currentDistrict">İlçe:</label><b class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <select name="" id="currentDistrict" class="form-control form-control-sm">
                                                <option value="">İlçe Seçiniz</option>
                                                @foreach($data['districts'] as $district)
                                                    <option
                                                        {{$data['user_district'] == $district->district_name ? 'selected' : ''}}
                                                        value="{{$district->district_id}}">{{$district->district_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="position-relative ">
                                            <label for="currentDistrict">Mahalle/Köy:</label><b
                                                class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <select name="" id="currentNeighborhood"
                                                    class="form-control form-control-sm">
                                                <option value="">Mahalle Seçiniz</option>

                                                @foreach($data['neighborhoods'] as $key)
                                                    <option
                                                        {{$data['user_neighborhood'] == $key->neighborhood_name ? 'selected' : ''}}
                                                        value="{{$key->neighborhood_id}}">{{$key->neighborhood_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="position-relative ">
                                            <label for="currentStreet">Cadde:</label><b class="text-danger">-</b>
                                        </div>

                                        <div class="input-group input-group-sm mb-1">
                                            <input type="text" class="form-control" name="cadde"
                                                   id="currentStreet">
                                            <div class="input-group-append"><span
                                                    class="input-group-text">CD.</span></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="position-relative ">
                                            <label for="currentStreet2">Sokak:</label><b class="text-danger">-</b>
                                        </div>
                                        <div class="input-group input-group-sm mb-1">
                                            <input type="text" class="form-control" name="sokak" id="currentStreet2">
                                            <div class="input-group-append"><span
                                                    class="input-group-text">SK.</span></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="position-relative">
                                            <label for="currentBuildingNo">Bina No:</label><b class="text-danger">*</b>
                                        </div>
                                        <div class="input-group input-group-sm mb-1">
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text">NO:</span></div>
                                            <input type="text" class="form-control" id="currentBuildingNo"
                                                   name="bina_no">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="position-relative">
                                            <label for="currentFloor">Kat No:</label><b class="text-danger">*</b>
                                        </div>
                                        <div class="input-group input-group-sm mb-1">
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text">KAT:</span></div>
                                            <input type="text" class="form-control" name="kat_no" id="currentFloor">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="position-relative">
                                            <label for="currentDoorNo">Daire No:</label><b class="text-danger">*</b>
                                        </div>

                                        <div class="input-group input-group-sm mb-1">
                                            <div class="input-group-prepend"><span
                                                    class="input-group-text">D:</span></div>
                                            <input type="text" class="form-control" id="currentDoorNo" name="daire_no">
                                        </div>

                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="currentAdressNote">Adres Notu:</label>
                                        </div>
                                        <div class="input-group mb-1">
                                        <textarea name="" id="currentAdressNote" cols="30" rows="2"
                                                  class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <button style="width: 100%" type="button" disabled id="btnSaveCurrent"
                                                class="text-center btn-icon btn-square btn btn-primary">
                                            <i class="fa fa-plus btn-icon-wrapper"> </i>Gönderici Kaydet
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{-- Gönderici END --}}
                        </div>
                    </div>
                    <div style="display: block; padding-bottom: 3rem;" class="modal-footer">
                        <button style="float: left !important;" type="reset" class="btn btn-secondary float-left">Formu
                            Temizle
                        </button>
                        <b style="float: left;"> <b class="text-danger">*</b> ile belirtilen alanlar zorunludur.</b>

                        <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Kapat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Large modal  => New Reciver --}}
    <div class="modal fade bd-example-modal-lg" id="modalNewReciver" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Yeni Alıcı Oluştur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- CARD START --}}

                    <div class="row">
                        {{-- Gönderici START --}}
                        <div class="col-md-6 border-box" id="divider-gonderici">
                            <h6>Kişisel Bilgiler</h6>
                            <div class="divider mt-0"></div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative ">
                                        <label for="selectReciverCategory">Kategori:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <select id="selectReciverCategory" class="form-control form-control-sm" name="">
                                            <option value="Bireysel">Bireysel</option>
                                            <option value="Kurumsal">Kurumsal</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="containerReciverIndividual">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="receiverTCKN">Alıcı TCKN:</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="receiverTCKN" maxlength="11"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="receiverIndividualName">Adı:</label><b class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="receiverIndividualName"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="receiverIndividualSurname">Soyadı:</label><b
                                                class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="receiverIndividualSurname"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="containerReciverCorporate">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="receiverVKN">Alıcı VKN:</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" maxlength="10" id="receiverVKN"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative ">
                                            <label for="receiverCompanyName">Firma Ünvanı:</label><b
                                                class="text-danger">*</b>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="receiverCompanyName"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="position-relative ">
                                            <label for="receiverAuthorizedName">Yetkili Adı:</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="receiverAuthorizedName"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative ">
                                            <label for="receiverAuthorizedSurname">Yetkili Soyadı:</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input type="text" id="receiverAuthorizedSurname"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative ">
                                        <label for="receiverGSM">Cep Telefonu:</label> <b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="receiverGSM"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative ">
                                        <label for="receiverPhone">Telefon:</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="receiverPhone"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative ">
                                        <label for="receiverEmail">Email:</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <input type="text" id="receiverEmail"
                                               data-inputmask="'alias': 'email'" type="email"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6 border-box" id="divider-gonderici2">

                            <h6>Adres Bilgileri</h6>
                            <div class="divider mt-0"></div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative ">
                                        <label for="receiverCity">İl:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <select name="" id="receiverCity" class="form-control form-control-sm">
                                            <option value="">İl Seçiniz</option>
                                            @foreach($data['cities'] as $city)
                                                <option value="{{$city->id}}"
                                                        id="{{$city->id}}">{{$city->city_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative ">
                                        <label for="receiverDistrict">İlçe:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <select name="" id="receiverDistrict" disabled
                                                class="form-control form-control-sm">
                                            <option value="">İlçe Seçiniz</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative">
                                        <label for="receiverNeighborhood">Mahalle/Köy:</label><b
                                            class="text-danger">*</b>
                                    </div>
                                    <div class="input-group mb-1">
                                        <select name="" id="receiverNeighborhood" disabled
                                                class="form-control form-control-sm">
                                            <option value="">Mahalle Seçiniz</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <label for="receiverStreet">Cadde:</label><b class="text-danger">-</b>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" id="receiverStreet">
                                        <div class="input-group-append"><span
                                                class="input-group-text">CD.</span></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <label for="receiverStreet2">Sokak:</label><b class="text-danger">-</b>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" id="receiverStreet2">
                                        <div class="input-group-append"><span
                                                class="input-group-text">SK.</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="receiverBuildingNo">Bina No:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">NO:</span></div>
                                        <input type="text" class="form-control" id="receiverBuildingNo">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="receiverFloor">Kat No:</label><b class="text-danger">*</b>
                                    </div>

                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">KAT:</span></div>
                                        <input type="text" class="form-control" id="receiverFloor">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="receiverDoorNo">Daire No:</label><b class="text-danger">*</b>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">D:</span></div>
                                        <input type="text" class="form-control" id="receiverDoorNo">
                                    </div>
                                </div>

                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative">
                                        <label for="receiverAdress">Adres Notu:</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <textarea name="" id="receiverAdress" cols="30" rows="3"
                                                  class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 text-center">
                                    <button style="width: 100%" id="btnSaveReceiver"
                                            class="text-center btn-icon btn-square btn btn-primary">
                                        <i class="fa fa-plus btn-icon-wrapper"> </i>Alıcıyı Kaydet
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- Gönderici END --}}
                    </div>
                </div>
                <div style="display: block;" class="modal-footer">
                    <b style="float: left;"> <b class="text-danger">*</b> ile belirtilen alanlar zorunludur.</b>
                    <button style="float: right;" type="button" class="btn btn-danger" data-dismiss="modal">Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Large modal  => Select Customer --}}
    <div class="modal fade bd-example-modal-lg" id="modalSelectCustomer" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSelectCustomerHead">Müşteri Seçin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodySelectCustomer" class="modal-body">
                    {{-- CARD START --}}

                    <div class="row">
                        <div style="max-height: 500px;overflow-x: auto;" id="table-scroll"
                             class="table-scroll col-md-12">
                            <table style="white-space: nowrap;"
                                   id="main-table"
                                   class="main-table table table-bordered Table30Padding table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Ad Soyad</th>
                                    <th>Cep Telefonu</th>
                                    <th>İl/İlçe</th>
                                    <th>Adres</th>
                                    <th>Kategori</th>
                                    <th>Kayıt Tarihi</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyCustomers">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Standart modal  =>  Add Row Quantity --}}
    <div class="modal fade" id="modalAddRowQuantity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Toplu Parça Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="multipleRowQuantity">Eklenecek Parça Adedi:</label>
                                    <input id="multipleRowQuantity" type="text"
                                           class="form-control form-control-sm input-mask-trigger"
                                           placeholder="0"
                                           data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':500, 'min':0"
                                           im-insert="true" style="text-align: right;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative ">
                                            <label for="enT">En (cm):</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input id="enT" type="text"
                                                   class="form-control form-control-sm input-mask-trigger"
                                                   placeholder="0"
                                                   data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                   im-insert="true" style="text-align: right;">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative ">
                                            <label for="BoyT">Boy (cm):</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input id="BoyT" type="text"
                                                   class="form-control form-control-sm input-mask-trigger"
                                                   placeholder="0"
                                                   data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                   im-insert="true" style="text-align: right;">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative ">
                                            <label for="YukseklikT">Yükseklik (cm):</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input id="YukseklikT"
                                                   class="form-control form-control-sm input-mask-trigger"
                                                   placeholder="0"
                                                   data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                   im-insert="true" style="text-align: right;">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative ">
                                            <label for="AgirlikT">Ağırlık (kg):</label>
                                        </div>
                                        <div class="input-group mb-1">
                                            <input id="AgirlikT"
                                                   class="form-control form-control-sm input-mask-trigger"
                                                   placeholder="0"
                                                   data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                   im-insert="true" style="text-align: right;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3 justify-content-md-center">
                                    <div class="col-md-11">
                                        <div class="form-row justify-content-md-center">
                                            <div class="col-md-4">
                                                <div class="position-relative ">
                                                    <label for="receiverIndividualName">Desi:</label>
                                                </div>
                                                <div class="input-group mb-1">
                                                    <input readonly type="number" value="0"
                                                           id="totalDesi"
                                                           class="form-control no-spin text-center font-weight-bold text-success form-control-sm">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="position-relative ">
                                                    <label for="receiverIndividualName">Ücrete Esas
                                                        Ağırlık:</label>
                                                </div>
                                                <div class="input-group mb-1">
                                                    <input readonly type="number" value="0"
                                                           id="RealDesi"
                                                           class="form-control no-spin text-center font-weight-bold text-danger form-control-sm">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="position-relative ">
                                                    <label for="receiverIndividualName">Hacim (m<sup>3</sup>):</label>
                                                </div>
                                                <div class="input-group mb-1">
                                                    <input readonly type="number" value="0"
                                                           id="TotalHacim"
                                                           class="form-control no-spin text-center font-weight-bold text-dark form-control-sm">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div style="display: block; margin-bottom: 2rem;" class="modal-footer">
                        <button type="reset" class="float-left btn btn-danger">Formu Sıfırla</button>
                        <button id="btnAddMultipleRow" type="button" class="float-right btn btn-primary">Kaydet</button>
                        <button type="button" class="float-right btn btn-secondary" data-dismiss="modal">Kapat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
