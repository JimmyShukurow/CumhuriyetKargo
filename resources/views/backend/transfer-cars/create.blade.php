@extends('backend.layout')

@section('title', 'Yeni Muhtelif Araç Girişi')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-car icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Yeni Aktarma Araç Oluştur
                        <div class="page-title-subheading">Bu sayfa üzerinden aktarma aracı oluşturabilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('TransferCars.index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Tüm AKtarama Araçları Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <form id="agencyForm" method="POST" action="{{ route('VariousCars.store') }}">

                    @csrf
                    <div class="row">
                        <div class="col-md-6" id="container-general-info">
                            <h6 class="text-dark text-center font-weight-bold">Araç Bilgiler</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="branchCars" class="font-weight-bold">Araç Marka:</label>
                                        <input name="branchCars" required id="branchCars"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="modelCars" class="font-weight-bold">Araç Model</label>
                                        <input name="modelCars" required id="modelCars"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="modelYear" class="font-weight-bold">Araç Model Yılı</label>
                                        <input name="modelYear" required id="modelYear"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}"
                                               class="form-control form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="plaqueCar" class="font-weight-bold">Plaka:</label>
                                        <input name="plaqueCar" required id="plaqueCar"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="capacityCar" class="font-weight-bold">Araç Kapasitesi</label>
                                        <select name="capacityCar" required id="capacityCar"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                            <option
                                                {{old('stopTransfer') == 'Panelvan' ? 'selected' : ''}} value="Panelvan">
                                                Panelvan
                                            </option>
                                            <option
                                                {{old('stopTransfer') == 'Kamyonet' ? 'selected' : ''}} value="Kamyonet">
                                                Kamyonet
                                            </option>
                                            <option
                                                {{old('stopTransfer') == '6 Teker Kamyonet' ? 'selected' : ''}} value="6 Teker Kamyonet">
                                                6 Teker Kamyonet
                                            </option>
                                            <option
                                                {{old('stopTransfer') == '10 Teker Kamyonet' ? 'selected' : ''}} value="10 Teker Kamyon">
                                                10 Teker Kamyon
                                            </option>
                                            <option
                                                {{old('stopTransfer') == '40 Ayak Kamyon' ? 'selected' : ''}} value="40 Ayak Kamyon">
                                                40 Ayak Kamyon
                                            </option>
                                            <option {{old('stopTransfer') == 'Tır' ? 'selected' : ''}} value="Tır">
                                                Takılı
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="tonnage" class="font-weight-bold">Tonaj (Kg)</label>
                                        <input name="tonnage" required id="tonnage"
                                               placeholder="Tonaj"
                                               type="text"
                                               value="{{ old('tonnage') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="desiCapacity" class="font-weight-bold">Desi</label>
                                        <input name="desiCapacity" required id="desiCapacity"
                                               placeholder="Desi"
                                               type="text"
                                               value="{{ old('desiCapacity') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="atsInfo" class="font-weight-bold">ATS (Araç Takip Sistemi):</label>
                                        <select name="atsInfo" required id="atsInfo"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                            <option {{old('atsInfo') == 'Takılı' ? 'selected' : ''}} value="Takılı">
                                                Takılı
                                            </option>
                                            <option
                                                {{old('atsInfo') == 'Takılı Değil' ? 'selected' : ''}} value="Takılı Değil">
                                                Takılı Değil
                                            </option>
                                            <option
                                                {{old('atsInfo') == 'Gönderildi' ? 'selected' : ''}} value="Gönderildi">
                                                Gönderildi
                                            </option>
                                            <option
                                                {{old('atsInfo') == 'Gönderilmedi' ? 'selected' : ''}} value="Gönderilmedi">
                                                Gönderilmedi
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="line" class="font-weight-bold">Hat</label>
                                                <select name="line" required id="line" class="form-control form-control-sm">
                                                    <option value=""> Seçiniz</option>
                                                    @foreach($data['transshipment_centers'] as $trasferCars)
                                                        <option value="{{$trasferCars->tc_name}}">{{$trasferCars->tc_name.' TM'}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="exitTransfer" class="font-weight-bold">Çıkış Aktarma </label>
                                                <select name="exitTransfer" required id="exitTransfer"
                                                        class="form-control form-control-sm">
                                                    <option value=""> Seçiniz</option>
                                                    @foreach($data['transshipment_centers'] as $trasferCars)
                                                        <option value="{{$trasferCars->tc_name}}">{{$trasferCars->tc_name.' TM'}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="arriveTransfer" class="font-weight-bold">Varış Aktarma</label>
                                                <select name="arriveTransfer" required id="arriveTransfer"
                                                        class="form-control form-control-sm ">
                                                    <option value=""> Seçiniz</option>
                                                    @foreach($data['transshipment_centers'] as $trasferCars)
                                                        <option value="{{$trasferCars->tc_name}}">{{$trasferCars->tc_name.' TM'}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="stopTransfer" class="font-weight-bold">Uğradığı Aktarmalar</label>
                                                <select name="stopTransfer[]" multiple="multiple" required id="stopTransfer"
                                                        class="form-control form-control-sm">
                                                    <option value=""> Seçiniz</option>
                                                    @foreach($data['transshipment_centers'] as $trasferCars)
                                                        <option value="{{$trasferCars->tc_name}}">{{$trasferCars->tc_name.' TM'}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-md-6" id="container-communication-info">
                            <h6 class="text-dark text-center  font-weight-bold">Şöför İletişim Bilgileri</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="driverName" class="font-weight-bold">Şoför Adı</label>
                                        <input name="driverName" required id="driverName"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('arac_marka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="position-relative form-group">
                                        <label for="driverPhone" class="font-weight-bold">Şoför İletişim *</label>
                                        <input name="driverPhone" id="driverPhone" required
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(_) _ _ _" type="text"
                                               value="{{ old('driverPhone') }}"
                                               class="form-control form-control-sm input-mask-trigger">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="driverAdress" class="font-weight-bold">Şoför Adresi</label>
                                        <textarea name="driverAdress" id="driverAdress"
                                                  class="form-control form-control-sm" maxlength="500" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="carOwner" class="font-weight-bold">Araç Sahibi Ad Soyad</label>
                                        <input name="carOwner" required id="carOwner"
                                               placeholder="Araç sahibi adı."
                                               type="text"
                                               value="{{ old('carOwner') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="position-relative form-group">
                                        <label for="carOwnerPhone" class="font-weight-bold">Araç Sahibi İletişim </label>
                                        <input name="carOwnerPhone" id="carOwnerPhone" required
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(_) _ _ _" type="text"
                                               value="{{ old('carOwnerPhone') }}"
                                               class="form-control form-control-sm input-mask-trigger" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="carOwnerRelative" class="font-weight-bold">Araç Sahibi Yakını Ad Soyad</label>
                                        <input name="carOwnerRelative" required id="carOwnerRelative"
                                               placeholder="Araç sahibi yakını ad soyad"
                                               type="text"
                                               value="{{ old('carOwnerRelative') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="position-relative form-group">
                                        <label for="carOwnerRelativePhone" class="font-weight-bold">Araç Sahibi Yakını İletişim </label>
                                        <input name="carOwnerRelativePhone" id="carOwnerRelativePhone" required
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(_) _ _ _" type="text"
                                               value="{{ old('carOwnerPhone') }}"
                                               class="form-control form-control-sm input-mask-trigger">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="carOwnerAdress" class="font-weight-bold">Araç Sahibi Adres</label>
                                        <textarea name="carOwnerAdress" id="carOwnerAdress"
                                                  class="form-control form-control-sm" maxlength="500" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="carOwnerRelativeAdress" class="font-weight-bold">Araç Sahibi Yakını Adres</label>
                                        <textarea name="carOwnerRelativeAdress" id="carOwnerRelativeAdress"
                                                  class="form-control form-control-sm" maxlength="500" required></textarea>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-12">
                            <h6 class="text-dark text-center font-weight-bold">Hesaplamalar</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="monthRentPrice" class="font-weight-bold">Aylık Kira Bedeli:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">₺</span></div>
                                        <input name="monthRentPrice" id="monthRentPrice" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': ' ', 'placeholder': '0'"
                                               type="text" value="{{ old('monthRentPrice') }}"
                                               class="form-control input-mask-trigger form-control-sm calculat-for-hakedisPlusMazot text-center">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="kdvHaricHakedis" class="font-weight-bold">KDV Hariç Hakediş:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">₺</span></div>
                                        <input name="kdvHaricHakedis" id="kdvHaricHakedis" required readonly
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': ' ', 'placeholder': '0'"
                                               type="text" value="{{ old('kdvHaricHakedis') }}"
                                               class="form-control input-mask-trigger form-control-sm text-center">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="oneRentPrice" class="font-weight-bold">1 Sefer Kira Maliyeti:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">₺</span></div>
                                        <input name="oneRentPrice" id="oneRentPrice" required readonly
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': ' ', 'placeholder': '0'"
                                               type="text" value="{{ old('oneRentPrice') }}"
                                               class="form-control input-mask-trigger form-control-sm  calculate-for-SeferPlusMaliyet text-center">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="flueRate" class="font-weight-bold">Yakıt Oranı:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">%</span></div>
                                        <input name="flueRate" id="flueRate" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': ' ', 'placeholder': '0'"
                                               type="text" value="{{ old('flueRate') }}"
                                               class="form-control input-mask-trigger form-control-sm for-calculate-oneFlueJourneyPrice text-center">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="turKm" class="font-weight-bold">Tur KM:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">KM</span></div>
                                        <input name="turKm" id="turKm" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': ' ', 'placeholder': '0'"
                                               type="text" value="{{ old('turKm') }}"
                                               class="form-control input-mask-trigger form-control-sm calculate-for-SeferPlusMaliyet text-center for-calculate-oneFlueJourneyPrice">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="journeyKm" class="font-weight-bold">Sefer KM:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">KM</span></div>
                                        <input name="journeyKm" id="journeyKm" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': ' ', 'placeholder': '0'"
                                               type="text" value="{{ old('journeyKm') }}"
                                               class="form-control input-mask-trigger form-control-sm text-center">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="oneFlueJourneyPrice" class="font-weight-bold">1 Sefer Yakıt Maliyeti:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">₺</span></div>
                                        <input name="oneFlueJourneyPrice" id="oneFlueJourneyPrice" required readonly
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': ' ', 'placeholder': '0'"
                                               type="text" value="{{ old('oneFlueJourneyPrice') }}"
                                               class="form-control input-mask-trigger form-control-sm text-center">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="monthFlue" class="font-weight-bold">Aylık Yakıt:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">₺</span></div>
                                        <input name="monthFlue" id="monthFlue" required
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': ' ', 'placeholder': '0'"
                                               type="text" value="{{ old('monthFlue') }}"
                                               class="form-control input-mask-trigger form-control-sm calculat-for-hakedisPlusMazot text-center">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="monthFlue" class="font-weight-bold">Sefer Maliyeti:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">₺</span></div>
                                        <input name="seferPlusMaliyet" id="seferPlusMaliyet" required readonly
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': ' ', 'placeholder': '0'"
                                               type="text" value="{{ old('monthFlue') }}"
                                               class="form-control input-mask-trigger form-control-sm text-center">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <label for="monthFlue" class="font-weight-bold">Hakedis+ Mazot:</label>
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text">₺</span></div>
                                        <input name="hakedisPlusMazot" id="hakedisPlusMazot" required readonly
                                               data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                               type="text" value="{{ old('monthFlue') }}"
                                               class="form-control input-mask-trigger form-control-sm text-center">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h6 class="text-dark text-center font-weight-bold">Araçta Olması Gereken Malzemeler</h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="stepne" class="font-weight-bold">Stepne</label>
                                        <select name="stepne" required id="stepne" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="crick" class="font-weight-bold">Kriko</label>
                                        <select name="crick" required id="crick" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="chain" class="font-weight-bold">Zincir</label>
                                        <select name="chain" required id="chain" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="tireIron" class="font-weight-bold">Bijon Anahtarı</label>
                                        <select name="tireIron" required id="tireIron" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="reflektor" class="font-weight-bold">Reflektör</label>
                                        <select name="reflektor" required id="reflektor" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="fireTube" class="font-weight-bold">Yangın Tüpü</label>
                                        <select name="fireTube" required id="fireTube" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="firstAidKid" class="font-weight-bold">İlk Yardım Çantası</label>
                                        <select name="firstAidKid" required id="firstAidKid" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="travelerLamp" class="font-weight-bold">Seyyar Lamba</label>
                                        <select name="travelerLamp" required id="travelerLamp" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="towingline" class="font-weight-bold">Çekme Halatı</label>
                                        <select name="towingline" required id="towingline" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="giydirmeKorNoktaUyarısı" class="font-weight-bold">Giydirme Kör Nokta Uyarısı</label>
                                        <select name="giydirmeKorNoktaUyarısı" required id="giydirmeKorNoktaUyarısı" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="hataBildirimHattı" class="font-weight-bold">Hata Bildirim Hattı</label>
                                        <select name="hataBildirimHattı" required id="hataBildirimHattı" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h6 class="text-dark text-center font-weight-bold">Araçlarda Olması Gereken Evraklar </h6>
                            <div class="divider"></div>
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="muayneEvrağı" class="font-weight-bold">Muayene Evrağı</label>
                                        <select name="muayneEvrağı" required id="muayneEvrağı" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="sigortaBelgesi" class="font-weight-bold">Sigorta Belgesi</label>
                                        <select name="sigortaBelgesi" required id="sigortaBelgesi" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="soforEhliyet" class="font-weight-bold">Şoför Ehliyet</label>
                                        <select name="soforEhliyet" required id="soforEhliyet" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="srcBelgesi" class="font-weight-bold">Src Belgesi</label>
                                        <select name="srcBelgesi" required id="srcBelgesi" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="ruhsatEkpertizRaporu" class="font-weight-bold">Ruhsat Ekspertiz Raporu</label>
                                        <select name="ruhsatEkpertizRaporu" required id="ruhsatEkpertizRaporu" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="tasimaBelgesi" class="font-weight-bold">Taşıma Belgesi</label>
                                        <select name="tasimaBelgesi" required id="tasimaBelgesi" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="soferAdliSicilBelgesi" class="font-weight-bold">Şoför Adli Sicil Kaydı</label>
                                        <select name="soferAdliSicilBelgesi" required id="soferAdliSicilBelgesi" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="aracSahibiSicilKaydi" class="font-weight-bold">Arac Sahibi Sici Kaydı</label>
                                        <select name="aracSahibiSicilKaydi" required id="aracSahibiSicilKaydi" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="soferYakiniIkametgahBelgesi" class="font-weight-bold">Şoför Yakını İkamet Belgesi</label>
                                        <select name="soferYakiniIkametgahBelgesi" required id="soferYakiniIkametgahBelgesi" class="form-control form-control-sm">
                                            <option value="Hayır">Hayır</option>
                                            <option value="Evet">Evet</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Aracı Kaydet</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script>
        $(document).ready(() => {

            $("#agencyForm").validate({
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

        $('#monthRentPrice').keyup(function (){//tamam
            var monthRentPrice=$('#monthRentPrice').val().replaceAll(',','');
            var kdvHaricHakedis=monthRentPrice/1.18;
            var birSeferKiraMaliyeti=monthRentPrice/26;
             $('#kdvHaricHakedis').val(kdvHaricHakedis);
             $('#oneRentPrice').val(birSeferKiraMaliyeti);
        });




        $('.for-calculate-oneFlueJourneyPrice').keyup(function (){//tamam
            var flueRate=parseFloat($('#flueRate').val().replaceAll(',',''));

            var turKm=parseFloat($('#turKm').val().replaceAll(',',''));
            $('#oneFlueJourneyPrice').val((turKm*flueRate*6.7)/100);
        });



        $('.calculate-for-SeferPlusMaliyet').keyup(function (){
            var oneFlueJourneyPrice=parseFloat($('#oneFlueJourneyPrice').val().replaceAll(',',''));

            var oneRentPrice=parseFloat($('#oneRentPrice').val().replaceAll(',',''));
            console.log(oneRentPrice);

            $('#seferPlusMaliyet').val((oneRentPrice + oneFlueJourneyPrice));
        });

        $('.calculat-for-hakedisPlusMazot').keyup(function (){
            var monthRentPrice=parseFloat($('#monthRentPrice').val().replaceAll(',',''));

            var monthFlue=parseFloat($('#monthFlue').val().replaceAll(',',''));

            $('#hakedisPlusMazot').val(monthRentPrice + monthFlue);

        });
    </script>
@endsection
