@extends('backend.layout')

@push('css')
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@section('title', 'Yeni Fatura')

@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Yeni Fatura
                        <div class="page-title-subheading">Bu sayfa üzerinden yeni kargo girişi yapabilirsiniz.
                        </div>
                    </div>
                </div>

                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">

                        <div style="display: inline-block; margin-right: 15px;" class="form-check mb-1">
                            <input id="seriMod" class="form-check-input cursor-pointer" type="checkbox" value="">
                            <label class="form-check-label font-weight-bold cursor-pointer"
                                   for="seriMod">
                                Seri Mod
                            </label>
                        </div>

                        <a href="{{ route('mainCargo.index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Anasayfaya Geri Dön
                            </button>
                        </a>
                    </div>
                </div>

            </div>
        </div>


        <div class="tabs-animation unselectable">

            <div style="max-width: 1200px;" class="card mb-3">
                {{-- CARD START --}}

                <div class="row">
                    <div class="col-md-12 text-left">

                    </div>
                </div>
                <div class="row p-3">
                    {{-- Gönderici START --}}
                    <div class="col-md-6 border-box" id="divider-gonderici">
                        <h6 class="font-weight-bold">Gönderici - Seçenekler</h6>
                        <div style="margin-top: 0;" class="divider"></div>

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative ">
                                    <label for="gondericiCariKod">Cari KOD:</label>
                                </div>
                                <div class="input-group">
                                    <input id="gondericiCariKod"
                                           data-inputmask="'mask': '999 999 999'"
                                           placeholder="___ ___ ___" type="text"
                                           class="form-control input-mask-trigger form-control-sm">
                                    <div class="input-group-append">
                                        <button id="btnGondericiOlustur"
                                                class="btn-icon btn-square btn btn-xs btn-alternate"><i
                                                class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Oluştur
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="gondericiAdi">Göndericinin Adı:</label>
                                </div>
                                <div class="input-group">
                                    <select class="form-control" name="" style="width:100%;" id="gondericiAdi">
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="GondericiTelefon">Gönderici Telefon:</label>
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

                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative ">
                                    <label for="gondericiMusteriTipi">Müşteri Tipi:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="gondericiMusteriTipi"
                                           class="form-control form-control-sm font-weight-bold">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative ">
                                    <label for="cikisSube">Çıkış Şube:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly
                                           value="{{$agency->agency_name . '-' . $agency->agency_code}}" id="cikisSube"
                                           class="form-control text-alternate form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative ">
                                    <label for="cikisTM">Çıkış TM:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="cikisTM" value="{{$tc->tc_name}} TM"
                                           class="form-control text-alternate form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="gondericiTCKN">Gönderici TCKN/VKN:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="gondericiTCKN" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="gondericiTelNo">Gönderici Tel No:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="gondericiTelNo"
                                           data-inputmask="'mask': '(999) 999 99 99'"
                                           placeholder="(___) ___ __ __" type="text"
                                           value="{{ old('phone') }}"
                                           class="form-control input-mask-trigger form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative ">
                                    <label for="gondericiAdres">Gönderici Adres:</label>
                                </div>
                                <div class="input-group mb-1">
                                        <textarea name="" id="gondericiAdres" class="form-control form-control-sm"
                                                  cols="30" readonly rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">

                            <div class="col-md-12">
                                <div class="position-relative ">
                                    <label for="cariKod">Kargo İçerik Açıklaması:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <textarea name="" id="explantion" class="form-control" cols="30"
                                              rows="2"></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row mt-3">
                            <label for="colFormLabelSm"
                                   class="col-sm-5 col-form-label">Gönderi Türü:</label>


                            <div id="divCargoType" class="col-sm-7 p-0">

                                <select name="" id="selectCargoType" class="form-control form-control-sm">
                                    @foreach(allCargoTypes() as $key)
                                        <option value="{{$key}}">{{$key}}</option>
                                    @endforeach
                                </select>

                                {{-- <input  class="check_user_type" data-width="100%"--}}
                                {{--  style="display: none;"--}}
                                {{--  onchange="" type="checkbox"--}}
                                {{--  checked id="checkCargoType"--}}
                                {{--   data-toggle="toggle"--}}
                                {{--   data-on="Dosya"--}}
                                {{--   data-off="Koli" data-onstyle="alternate" data-offstyle="primary">--}}
                            </div>
                        </div>

                        <input style="display: none;" type="button" id="fakeButton">


                        <div class="form-group row">
                            <label for="colFormLabelSm"
                                   class="col-sm-5 col-form-label">Ödeme Tipi:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input radio-payment-type" checked type="radio"
                                       name="radioPaymentType" id="radioPaymentType1"
                                       value="Gönderici Ödemeli">
                                <label class="form-check-label cursor-pointer" for="radioPaymentType1">Gönderici
                                    Ödemeli</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input radio-payment-type" type="radio" name="radioPaymentType"
                                       id="radioPaymentType2"
                                       value="Alıcı Ödemeli">
                                <label class="form-check-label cursor-pointer" for="radioPaymentType2">Alıcı
                                    Ödemeli</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="radioPaymentType"
                                       id="radioPaymentType3"
                                       value="PÖCH" disabled>

                                <label class="form-check-label cursor-pointer" for="radioPaymentType3">PÖCH</label>
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
                    {{-- Gönderici END --}}

                    {{-- Alıcı START--}}
                    <div class="col-md-6 border-box" id="divider-alici">
                        <h6 class="font-weight-bold">Alıcı - Teslimat Bilgileri</h6>
                        <div style="margin-top: 0;" class="divider"></div>

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative ">
                                    <label for="aliciCariKod">Cari KOD</label>
                                </div>
                                <div class="input-group">
                                    <input id="aliciCariKod"
                                           data-inputmask="'mask': '999 999 999'"
                                           placeholder="___ ___ ___" type="text"
                                           class="form-control input-mask-trigger form-control-sm">
                                    <div class="input-group-append">
                                        <button id="btnYeniAlici"
                                                class="btn-icon btn-square btn btn-xs  btn-alternate"><i
                                                class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Oluştur
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="aliciAdi">Alıcının Adı:</label>
                                </div>
                                <div class="input-group">
                                    <select class="form-control" name="" style="width:100%;" id="aliciAdi">
                                        <optgroup label="Alıcılar">
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="AliciTelefon">Alıcı Telefon:</label>
                                </div>
                                <div class="input-group">
                                    <input type="text" id="AliciTelefon" data-inputmask="'mask': '(999) 999 99 99'"
                                           placeholder="(___) ___ __ __"
                                           class="form-control input-mask-trigger form-control-sm" im-insert="true">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 mb-2">

                            <div class="col-md-6">
                                <button id="btnClearReceiverInfo"
                                        class="float-left btn-icon btn-square btn btn-sm btn-danger"><i
                                        class="lnr-cross btn-icon-wrapper"> </i>Temizle
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button id="searchReceiver"
                                        class="float-right btn-icon btn-square btn btn-sm btn-primary"><i
                                        class="fa fa-search-plus btn-icon-wrapper"> </i>Ara
                                </button>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="position-relative ">
                                    <label for="aliciMusteriTipi">Müşteri Tipi:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="aliciMusteriTipi"
                                           class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative ">
                                    <label for="aliciMusteriTipi">Varış Şube:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="varisSube"
                                           class="form-control text-alternate form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative ">
                                    <label for="aliciMusteriTipi">Varış TM:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="varisTransferMerkezi"
                                           class="form-control text-alternate form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative ">
                                    <label for="dagitimDurumu">Dağıtım:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="dagitimDurumu"
                                           class="form-control text-alternate form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative">
                                    <label for="aliciTelNo">Alıcının Tel No:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" readonly id="aliciTelNo"
                                           data-inputmask="'mask': '(999) 999 99 99'"
                                           placeholder="(___) ___ __ __" type="text"
                                           value="{{ old('phone') }}"
                                           class="form-control input-mask-trigger form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative">
                                    <label for="aliciAdres">Alıcı Adres:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <textarea readonly="" name="" id="aliciAdres" cols="30" rows="3"
                                              class="form-control form-control-sm"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative">
                                    <label for="musteriKodu">Müşteri Kodu:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" id="musteriKodu"
                                           type="text"
                                           class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>


                        <div class="form-row">
                            <label for="selectCollectionType" class="col-sm-5 col-form-label">Tahsilat Türü:</label>
                            <div id="divCargoType" class="col-sm-7 pt-2 pb-2">
                                <select name="selectCollectionType" id="selectCollectionType"
                                        class="form-control form-control-sm">
                                    <option value="NAKİT">NAKİT</option>
                                    {{--                                    <option value="POS">POS</option>--}}
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="tahsilatOnayKodu">Onay Kodu:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" id="tahsilatOnayKodu" disabled
                                           class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative ">
                                    <label for="KartSahibi">Kart Sahibi Ad Soyad:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <input type="text" id="tahsilatKartSahibi" disabled
                                           class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative">
                                    <label for="TahsilatNotu">Tahsilat Açıklaması:</label>
                                </div>
                                <div class="input-group mb-1">
                                    <textarea name="" id="tahsilatAciklama" cols="30" rows="3"
                                              class="form-control form-control-sm">Kargo nakit tahsilat:</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- Alıcı START--}}

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
                                <button id="btnCargoComplate" style="width: 100%;" type="button"
                                        class="btn btn-primary mt-3">Tamamla
                                </button>
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
    <script src="/backend/assets/scripts/main-cargo/create.js?v=1.0.0.0"></script>
    <script src="/backend/assets/scripts/customers/create/create-sender.js"></script>
    <script src="/backend/assets/scripts/customers/create/create-receiver.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
@endsection

@section('modals')

    @include('backend.main_cargo.main.create.includes.modal_new_current')

    @include('backend.main_cargo.main.create.includes.modal_new_receiver')

    @include('backend.main_cargo.main.create.includes.modal_select_customer')

    @include('backend.main_cargo.main.create.includes.modal_calc_desi')

    @include('backend.main_cargo.main.create.includes.modal_add_row_quantity')

@endsection
