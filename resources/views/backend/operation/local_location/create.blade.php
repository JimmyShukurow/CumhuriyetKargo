@extends('backend.layout')

@push('css')
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
@endpush

@section('title', 'Gönderici Cari Oluştur')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas fa-money-check-alt icon-gradient bg-plum-plate"></i>
                    </div>
                    <div>Gönderici Cari Oluştur
                        <div class="page-title-subheading">Bu sayfa üzerinden kurumsal gönderici cari kaydı
                            yapabilirsiniz.
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

        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">

                </div>

                <form id="currentForm" method="POST" action="{{ route('SenderCurrents.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6" id="container-general-info">
                            <h6 class="text-dark text-center font-weight-bold">Genel Bilgiler</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="name_surname" class="">Ad Soyad / Firma*</label>
                                        <input name="adSoyadFirma" required id="name_surname"
                                               placeholder="Ad Soyad veya Firma adı giriniz"
                                               type="text"
                                               value="{{ old('adSoyadFirma') }}" class="form-control form-control-sm">
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
                                               value="{{ old('vknTckn') }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="phone" class="">Telefon:</label>
                                        <input name="telefon" required id="link"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               value="{{ old('telefon') }}"
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
                                                    {{ old('il') == $city->id ? 'selected' : ''  }} id="{{$city->id}}"
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
                                            @if(old('ilce'))
                                                <script>
                                                    $(document).ready(function () {
                                                        $('#city').trigger('change');
                                                        setTimeout(function () {
                                                            $('#district').val('{{old('ilce')}}');
                                                        }, 1000);
                                                    });
                                                </script>
                                            @endif
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
                                            @if(old('mahalle'))
                                                <script>
                                                    $(document).ready(function () {
                                                        setTimeout(function () {
                                                            $('#district').trigger('change');
                                                            setTimeout(function () {
                                                                $('#neighborhood').val('{{old('mahalle')}}');
                                                            }, 5000);
                                                        }, 3000);
                                                    });
                                                </script>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative ">
                                        <label for="street">Cadde:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" name="cadde"
                                               value="{{old('cadde')}}" id="street">
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
                                               value="{{old('sokak')}}" id="street2">
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
                                               value="{{old('bina_no')}}"
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
                                               value="{{old('kat_no')}}" id="floor" required>
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
                                               value="{{old('daire_no')}}" name="daire_no" required>
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="addressNote" class="">Adres Notu*</label>
                                        <textarea name="adres_notu" id="addressNote"
                                                  class="form-control form-control-sm"
                                                  cols="30"
                                                  rows="2">{{old('adres_notu')}}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6" id="container-communication-info">
                            <h6 class="text-dark text-center  font-weight-bold">İletişim Bilgileri</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="gsm" class="">GSM:</label>
                                        <input name="gsm" required id="gsm"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               value="{{ old('gsm') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="gsm2" class="">GSM 2:</label>
                                        <input name="gsm2" id="gsm2"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               value="{{ old('gsm2') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="phone2" class="">Telefon 2:</label>
                                        <input name="telefon2" id="phone2"
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(___) ___ __ __" type="text"
                                               value="{{ old('telefon2') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="email" class="">E Posta*</label>
                                        <input name="email" required id="email"
                                               data-inputmask="'alias': 'email'"
                                               type="text"
                                               value="{{ old('email') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="website" class="">Web Sitesi*</label>
                                        <input name="website" id="website"
                                               type="text"
                                               value="{{ old('website') }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="dispatchPostCode" class="">Sevk Posta Kodu</label>
                                        <input name="sevkPostaKodu" id="name_surname"
                                               type="text"
                                               value="{{ old('sevkPostaKodu') }}" class="form-control form-control-sm">
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
                                                    {{ old('sevkIl') == $city->id ? 'selected' : ''  }} id="{{$city->id}}"
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
                                        </select>
                                        @if(old('sevkIlce'))
                                            <script>
                                                $(document).ready(function () {
                                                    $('#dispatchCity').trigger('change');
                                                    setTimeout(function () {
                                                        $('#dispatchDistrict').val('{{old('sevkIlce')}}');
                                                    }, 1500);
                                                });
                                            </script>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="dispatchAddress" class="">Sevk Adres*</label>
                                        <textarea name="sevkAdres" required id="dispatchAddress"
                                                  class="form-control form-control-sm" cols="30"
                                                  rows="2">{{old('sevkAdres')}}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12" id="container-finance">
                            <h6 class="text-dark text-center  font-weight-bold">Finans</h6>
                            <div class="divider"></div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="iban" class="">IBAN No:</label>
                                        <input name="iban" id="iban" required
                                               data-inputmask="'mask': 'TR99 9999 9999 9999 9999 9999 99'"
                                               placeholder="TR__ ____ ____ ____ ____ ____ __" type="text"
                                               value="{{ old('iban') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="hesapSahibiTamIsim" class="">Hesap Sahibi Tam İsim:</label>
                                        <input name="hesapSahibiTamIsim" required id="hesapSahibiTamIsim"
                                               value="{{ old('hesapSahibiTamIsim') }}"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="discount" class="">İskonto (%):</label>
                                        <input name="iskonto" required id="discount"
                                               type="text" value="0"
                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '% ', 'placeholder': '0', 'min':0, 'max': 100"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="contractStartDate" class="">Sözleşme Başlangıç Tarihi:</label>
                                        <input name="sozlesmeBaslangicTarihi" required id="contractStartDate"
                                               type="date" value="{{old('sozlesmeBaslangicTarihi')}}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="contractEndDate" class="">Sözleşme Bitiş Tarihi:</label>
                                        <input name="sozlesmeBitisTarihi" required id="contractEndDate"
                                               type="date" value="{{old('sozlesmeBitisTarihi')}}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="reference" class="">Referans:</label>
                                        <input name="referans" id="reference"
                                               type="text"
                                               value="{{ old('referans') }}"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="filePrice" class="">Dosya:</label>
                                        <input name="dosyaUcreti" id="filePrice" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ old('dosyaUcreti') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d1_5" class="">1-5 Desi:</label>
                                        <input name="d1_5" id="d1_5" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ old('d1_5') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d6_10" class="">6-10 Desi:</label>
                                        <input name="d6_10" id="d6_10" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ old('d6_10') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d11_15" class="">11-15 Desi:</label>
                                        <input name="d11_15" id="d11_15" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ old('d11_15') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d16_20" class="">16-20 Desi:</label>
                                        <input name="d16_20" id="d16_20" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ old('d16_20') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d21_25" class="">21-25 Desi:</label>
                                        <input name="d21_25" id="d21_25" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ old('d21_25') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="d26_30" class="">26-30 Desi:</label>
                                        <input name="d26_30" id="d26_30" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ old('d26_30') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="amountOfIncrease" class="">Üstü Desi:</label>
                                        <input name="ustuDesi" id="amountOfIncrease" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ old('ustuDesi') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="addServicePrice" class="">Tahsilat Ek Hizmet Bedeli (0-200
                                            TL):</label>
                                        <input name="tahsilatEkHizmetBedeli" id="addServicePrice"
                                               type="text" required value="{{ old('tahsilatEkHizmetBedeli') }}"
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="tahsilatEkHizmetBedeli200Ustu" class="">Tahsilat Ek Hizmet Bedeli
                                            (%) (200TL+):</label>
                                        <input name="tahsilatEkHizmetBedeli200Ustu" required
                                               data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '% ', 'placeholder': '0', 'min':0, 'max': 100"
                                               id="tahsilatEkHizmetBedeli200Ustu"
                                               type="text" value="{{ old('tahsilatEkHizmetBedeli200Ustu') }}"
                                               class="form-control input-mask-trigger form-control-sm">
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <button type="submit" id="btnCreateCurrent" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Cari Oluştur</span>
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
    <script src="/backend/assets/scripts/marketing/sender-current.js"></script>

    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
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
        });
    </script>

@endsection
