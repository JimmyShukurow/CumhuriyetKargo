@extends('backend.layout')

@push('css')
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
    <link href="/backend/assets/css/multiselect-minifier.css" rel="stylesheet"/>
@endpush

@section('title', 'Gönderici Cari Düzenle')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas fa-money-check-alt icon-gradient bg-plum-plate"></i>
                    </div>
                    <div>Gönderici Cari Düzenle
                        <div class="page-title-subheading">Bu sayfa üzerinden kurumsal gönderici cariyi
                            düzenleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('SenderCurrents.index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Gönderici Carileri Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div style="max-width: 1100px;" class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">

                </div>

                <form id="currentForm" method="POST" action="{{ route('SenderCurrents.update', $current->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-6" id="container-general-info">
                            <h6 class="text-dark font-weight-bold text-center">Genel Bilgiler</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-7">
                                    <div class="position-relative form-group">
                                        <label for="name_surname" class="">Ad Soyad / Firma*</label>
                                        <input name="adSoyadFirma" required id="name_surname"
                                               placeholder="Ad Soyad veya Firma adı giriniz"
                                               type="text"
                                               value="{{ $current->name }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="position-relative form-group">
                                        <label for="name_surname" class="">Cari Kod*</label>
                                        <input name="name_surname" id="currentCode" disabled
                                               data-inputmask="'mask': '999 999 999'"
                                               placeholder="___ ___ ___" type="text"
                                               value="{{ $current->current_code }}"
                                               class="form-control input-mask-trigger form-control-sm niko-filter">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="agency" class="">Bağlı Şube*</label>
                                        <select name="acente" required class="form-control form-control-sm"
                                                style="width:100%;"
                                                id="agency">
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="tax-office" class="">Vergi Dairesi*</label>
                                        <select name="vergiDairesi" required class="form-control form-control-sm"
                                                style="width:100%;"
                                                id="tax-office">
                                            @if(old('vergiDairesi') != '')
                                                <option selected
                                                        value="{{old('vergiDairesi')}}">{{old('vergiDairesi')}}</option>
                                            @endif

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="vkn-tckn" class="">VKN/TCKN*</label>
                                        <input name="vknTckn" required id="vkn-tckn" maxlength="11"
                                               placeholder="Kullanıcı ad soyad bilgisini giriniz"
                                               type="text"
                                               value="{{ $current->tckn }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="phone" class="">Telefon:</label>
                                        <input name="telefon" required id="link"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               value="{{ $current->phone }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="city" class="">İl:</label>
                                        <select name="il" required id="city" class="form-control form-control-sm">
                                            <option value="">İl Seçiniz</option>
                                            @foreach($data['cities'] as $city)
                                                <option
                                                    {{ $current->city  == $city->city_name ? 'selected' : ''  }} id="{{$city->id}}"
                                                    value="{{$city->id}}">{{$city->city_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="district" class="">İlçe*</label>
                                        <select name="ilce" required id="district"
                                                class="form-control form-control-sm">
                                            <option value="">İlçe Seçiniz</option>
                                            @foreach($data['districts'] as $key)
                                                <option
                                                    {{ $current->district == $key->district_name ? 'selected' : '' }} value="{{$key->district_id}}">{{$key->district_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative ">
                                        <label for="neighborhood">Mahalle/Köy:</label>
                                    </div>
                                    <div class="input-group mb-1">
                                        <select name="mahalle" id="neighborhood" required
                                                class="form-control form-control-sm">

                                            <option value="">Mahalle Seçiniz</option>
                                            @foreach($data['neighborhoods'] as $key)
                                                <option
                                                    {{ $current->neighborhood == $key->neighborhood_name ? 'selected' : '' }}
                                                    value="{{$key->neighborhood_id}}">{{$key->neighborhood_name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative ">
                                        <label for="street">Cadde:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" name="cadde"
                                               value="{{$current->street}}" id="street">
                                        <div class="input-group-append"><span
                                                class="input-group-text">CD.</span></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative ">
                                        <label for="street2">Sokak:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" name="sokak"
                                               value="{{$current->street2}}" id="street2">
                                        <div class="input-group-append"><span
                                                class="input-group-text">SK.</span></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="buildingNo">Bina No:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">NO:</span></div>
                                        <input type="text" class="form-control" name="bina_no"
                                               value="{{$current->building_no}}"
                                               id="buildingNo" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="floor">Kat No:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">KAT:</span></div>
                                        <input type="text" class="form-control" name="kat_no"
                                               value="{{$current->floor}}" id="floor" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="doorNo">Daire No:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">D:</span></div>
                                        <input type="text" class="form-control" id="doorNo"
                                               value="{{$current->door_no}}" name="daire_no" required>
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="addressNote" class="">Adres Notu*</label>
                                        <textarea name="adres_notu" id="addressNote"
                                                  class="form-control form-control-sm"
                                                  cols="30"
                                                  rows="2">{{ $current->address_note}}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6" id="container-communication-info">
                            <h6 class="text-dark font-weight-bold text-center">İletişim Bilgileri</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="gsm" class="">GSM:</label>
                                        <input name="gsm" required id="gsm"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               value="{{ $current->gsm }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="gsm2" class="">GSM 2:</label>
                                        <input name="gsm2" id="gsm2"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               value="{{ $current->gsm2 }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="phone2" class="">Telefon 2:</label>
                                        <input name="telefon2" id="phone2"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               value="{{ $current->phone2 }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="email" class="">E Posta*</label>
                                        <input name="email" required id="email"
                                               data-inputmask="'alias': 'email'"
                                               type="text"
                                               value="{{ $current->email }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="website" class="">Web Sitesi*</label>
                                        <input name="website" id="website"
                                               type="text"
                                               value="{{ $current->web_site }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="dispatchPostCode" class="">Sevk Posta Kodu</label>
                                        <input name="sevkPostaKodu" id="name_surname"
                                               type="text"
                                               value="{{ $current->dispatch_post_code }}"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="dispatchCity" class="">Sevk İl:</label>
                                        <select name="sevkIl" required id="dispatchCity"
                                                class="form-control form-control-sm">
                                            <option value="">İl Seçiniz</option>
                                            @foreach($data['cities'] as $city)
                                                <option
                                                    {{ $current->dispatch_city  == $city->city_name ? 'selected' : '' }} id="{{$city->id}}"
                                                    value="{{$city->id}}">{{$city->city_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="dispatchDistrict" class="">Sevk İlçe*</label>
                                        <select name="sevkIlce" required id="dispatchDistrict"
                                                class="form-control form-control-sm">
                                            <option value="">İlçe Seçiniz</option>
                                            @foreach($data['dispatch_districts'] as $key)
                                                <option
                                                    {{ $current->dispatch_district == $key->district_name ? 'selected' : '' }} value="{{$key->district_id}}">{{$key->district_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="dispatchAddress" class="">Sevk Adres*</label>
                                        <textarea name="sevkAdres" required id="dispatchAddress"
                                                  class="form-control form-control-sm" cols="30"
                                                  rows="2">{{ $current->dispatch_adress }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12" id="container-finance">
                            <h6 class="text-dark font-weight-bold text-center">Finans</h6>
                            <div class="divider"></div>

                        </div>
                        <div class="col-md-12">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="iban" class="">IBAN No:</label>
                                        <input name="iban" id="iban" required
                                               data-inputmask="'mask': 'TR99 9999 9999 9999 9999 9999 99'"
                                               placeholder="TR__ ____ ____ ____ ____ ____ __" type="text"
                                               value="{{ $current->bank_iban }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="hesapSahibiTamIsim" class="">Hesap Sahibi Tam İsim:</label>
                                        <input name="hesapSahibiTamIsim" required id="hesapSahibiTamIsim"
                                               value="{{ $current->bank_owner_name }}"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="discount" class="">İskonto (%):</label>
                                        <input name="iskonto" required id="discount"
                                               type="text" value="{{$current->discount}}"
                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '% ', 'placeholder': '0', 'min':0, 'max': 100"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="contractStartDate" class="">Sözleşme Başlangıç Tarihi:</label>
                                        <input name="sozlesmeBaslangicTarihi" required id="contractStartDate"
                                               type="date" value="{{ substr($current->contract_start_date, 0 ,10) }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="contractEndDate" class="">Sözleşme Bitiş Tarihi:</label>
                                        <input name="sozlesmeBitisTarihi" required id="contractEndDate"
                                               type="date" value="{{substr($current->contract_end_date, 0 ,10)}}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="reference" class="">Referans:</label>
                                        <input name="referans" id="reference"
                                               type="text"
                                               value="{{ $current->reference }}"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12" id="container-finance">
                            <h6 class="text-dark font-weight-bold text-center">Fiyatlar</h6>
                            <div class="divider"></div>

                        </div>
                        <div class="col-md-12">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="priceDraft" class="">Fiyat Taslağı:</label>
                                        <select required class="form-control-sm form-control" name="priceDraft"
                                                id="priceDraft">
                                            <option value="0">Özel</option>
                                            @foreach($data['price_drafts'] as $key)
                                                <option
                                                    {{$price->price_draft_id == $key->id ? 'selected' : ''}} value="{{$key->id}}">{{$key->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-cargo-price col-md-12">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="filePrice" class="">Dosya:</label>
                                        <input name="dosyaUcreti" id="filePrice" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ $price->file }}"
                                               class="form-control input-mask-trigger price-of-cargo form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="miPrice" class="">Mi:</label>
                                        <input name="miUcreti" id="miPrice" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ $price->mi  }}"
                                               class="form-control input-mask-trigger price-of-cargo form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d1_5" class="">1-5 Desi:</label>
                                        <input name="d1_5" id="d1_5" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ $price->d_1_5 }}"
                                               class="form-control input-mask-trigger price-of-cargo form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d6_10" class="">6-10 Desi:</label>
                                        <input name="d6_10" id="d6_10" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ $price->d_6_10}}"
                                               class="form-control input-mask-trigger price-of-cargo form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d11_15" class="">11-15 Desi:</label>
                                        <input name="d11_15" id="d11_15" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ $price->d_11_15 }}"
                                               class="form-control input-mask-trigger price-of-cargo form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d16_20" class="">16-20 Desi:</label>
                                        <input name="d16_20" id="d16_20" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{  $price->d_16_20 }}"
                                               class="form-control input-mask-trigger price-of-cargo form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d21_25" class="">21-25 Desi:</label>
                                        <input name="d21_25" id="d21_25" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ $price->d_21_25 }}"
                                               class="form-control input-mask-trigger price-of-cargo form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d26_30" class="">26-30 Desi:</label>
                                        <input name="d26_30" id="d26_30" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ $price->d_26_30 }}"
                                               class="form-control input-mask-trigger price-of-cargo form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="amountOfIncrease" class="">Üstü Desi:</label>
                                        <input name="ustuDesi" id="amountOfIncrease" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ $price->amount_of_increase }}"
                                               class="form-control input-mask-trigger price-of-cargo form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="addServicePrice" class="">Tahsilat Ek Hizmet Bedeli (0-200
                                            TL):</label>
                                        <input name="tahsilatEkHizmetBedeli" id="addServicePrice"
                                               type="text" required value="{{ $price->collect_price }}"
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="tahsilatEkHizmetBedeli200Ustu" class="">Tahsilat Ek Hizmet Bedeli
                                            (%) (200TL+):</label>
                                        <input name="tahsilatEkHizmetBedeli200Ustu" required
                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '% ', 'placeholder': '0', 'min':0, 'max': 100"
                                               id="tahsilatEkHizmetBedeli200Ustu"
                                               type="text" value="{{ $price->collect_amount_of_increase }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="mbStatus" class="">Mobil Bölge Ücreti
                                            Uygulansın mı?</label><br>
                                        <select name="mbStatus" id="" required class="form-control-sm form-control">
                                            <option {{$current->mbStatus == '1' ? 'selected' : '' }} value="1">Evet
                                            </option>
                                            <option {{$current->mbStatus == '0' ? 'selected' : '' }} value="0">Hayır
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="">Türkiye Geneli Çıkış:</label>
                                    <select name="departureForTurkeyGeneral" id="departureForTurkeyGeneral"
                                            class="form-control form-control-sm">
                                        <option
                                            {{$current->departure_all_agencies == '0' ? 'selected' : '' }} value="0">
                                            HAYIR
                                        </option>
                                        <option
                                            {{$current->departure_all_agencies == '1' ? 'selected' : '' }} value="1">
                                            EVET
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="">Çıkış yapılacak şubeler:</label>
                                    <select style="width: 100%; display: none" id="departureAgencies" type="select"
                                            multiple=""
                                            class="custom-select">
                                        @foreach($data['agencies'] as $key)
                                            <option
                                                {{$data['departure_agencies']->contains($key->id) ? 'selected' : ''}} value="{{$key->id}}">{{$key->agency_name}}</option>
                                        @endforeach
                                    </select>
                                    <input name="realDepartureAgencies" style="display: none;" type="text"
                                           id="realDepartureAgencies">
                                </div>
                            </div>

                        </div>
                    </div>

                    <button type="submit" id="btnCreateCurrent" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Cariyi Kaydet</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>

                </form>


            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/marketing/sender-currents/create-edit.js"></script>
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/backend/assets/scripts/bootstrap-multiselect.js"></script>

    <script>
        $(document).ready(() => {

            $("#currentForm").validate({
                errorElement: "em",
                errorPlacement: function (error, element) {
                    // Add the `invalid-feedback` class to the error element
                    error.addClass("invalid-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.next("label"));
                    } else {
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-valid").removeClass("is-invalid");
                }
            });

            var agencyOption = new Option('{{@$agency->agency_name}}', {{ $current->agency}}, true, true);
            $('#agency').append(agencyOption).trigger('change');

            var taxOfficeOption = new Option('{{$current->tax_administration}}', '{{$current->tax_administration}}', true, true);
            $('#tax-office').append(taxOfficeOption).trigger('change');
        });


        priceDraft = "{{$price->price_draft_id}}";

        if (priceDraft != 0)
            $('.price-of-cargo').prop('readonly', true)

    </script>

@endsection
