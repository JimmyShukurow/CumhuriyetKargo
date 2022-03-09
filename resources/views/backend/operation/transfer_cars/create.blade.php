@extends('backend.layout')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endpush


@section('title', 'Yeni Aktarma Aracı Girişi')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-truck icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Yeni Aktarma Aracı Oluştur
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
                                Tüm Aktarama Araçları Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <form id="tc_car_form" method="POST" action="{{ route('TransferCars.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6" id="container-general-info">
                            <h6 class="text-dark text-center font-weight-bold">Araç Bilgileri</h6>
                            <div class="divider"></div>
                            <div class="form-row">

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="plaka" class="font-weight-bold">Plaka:</label>
                                        <input name="plaka" required id="plaka"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('plaka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="marka" class="font-weight-bold">Araç Marka:</label>
                                        <input name="marka" required id="marka"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('marka') }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="model" class="font-weight-bold">Araç Model</label>
                                        <input name="model" required id="model"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('model') }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="model_yili" class="font-weight-bold">Araç Model Yılı</label>
                                        <input name="model_yili" required id="model_yili"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('model_yili') }}"
                                               class="form-control form-control form-control-sm">
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="arac_kapasitesi" class="font-weight-bold">Araç Kapasitesi</label>
                                        <select name="arac_kapasitesi" required id="arac_kapasitesi"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                            <option
                                                {{old('arac_kapasitesi') == 'Panelvan' ? 'selected' : ''}} value="Panelvan">
                                                Panelvan
                                            </option>
                                            <option
                                                {{old('arac_kapasitesi') == 'Kamyonet' ? 'selected' : ''}} value="Kamyonet">
                                                Kamyonet
                                            </option>
                                            <option
                                                {{old('arac_kapasitesi') == '6 Teker Kamyonet' ? 'selected' : ''}} value="6 Teker Kamyonet">
                                                6 Teker Kamyonet
                                            </option>
                                            <option
                                                {{old('arac_kapasitesi') == '10 Teker Kamyonet' ? 'selected' : ''}} value="10 Teker Kamyon">
                                                10 Teker Kamyon
                                            </option>
                                            <option
                                                {{old('arac_kapasitesi') == '40 Ayak Kamyon' ? 'selected' : ''}} value="40 Ayak Kamyon">
                                                40 Ayak Kamyon
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="tonaj" class="font-weight-bold">Tonaj (Kg)</label>
                                        <input name="tonaj" required id="tonaj"
                                               placeholder="Tonaj"
                                               type="text"
                                               value="{{ old('tonaj') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="desi_kapasitesi" class="font-weight-bold">Desi Kapasitesi</label>
                                        <input name="desi_kapasitesi" required id="desi_kapasitesi"
                                               placeholder="Desi Kapasitesi" type="text"
                                               value="{{ old('desi_kapasitesi') }}"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="arac_takip_sistemi" class="font-weight-bold">ATS (Araç Takip
                                            Sistemi):</label>
                                        <select name="arac_takip_sistemi" required id="arac_takip_sistemi"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                            <option
                                                {{old('arac_takip_sistemi') == 'Takılı' ? 'selected' : ''}} value="Takılı">
                                                Takılı
                                            </option>
                                            <option
                                                {{old('arac_takip_sistemi') == 'Takılı Değil' ? 'selected' : ''}} value="Takılı Değil">
                                                Takılı Değil
                                            </option>
                                            <option
                                                {{old('arac_takip_sistemi') == 'Gönderildi' ? 'selected' : ''}} value="Gönderildi">
                                                Gönderildi
                                            </option>
                                            <option
                                                {{old('arac_takip_sistemi') == 'Gönderilmedi' ? 'selected' : ''}} value="Gönderilmedi">
                                                Gönderilmedi
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="car_type" class="font-weight-bold">Araç Tipi:</label>
                                        <select name="car_type"  id="car_type"
                                                class="form-control form-control-sm">
                                            <option value=""> Seçiniz</option>
                                            <option
                                                    {{old('car_type') == 'Aktarma' ? 'selected' : ''}} value="Aktarma">
                                                Aktarma
                                            </option>
                                            <option
                                                    {{old('car_type') == 'Acente' ? 'selected' : ''}} value="Acente">
                                                Acente
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="hat" class="font-weight-bold">Hat</label>
                                                <select name="hat" required id="hat"
                                                        class="form-control form-control-sm">
                                                    <option value=""> Seçiniz</option>
                                                    <option {{old('hat') == 'Anahat' ? 'selected' : ''}} value="Anahat">
                                                        Anahat
                                                    </option>
                                                    <option {{old('hat') == 'Arahat' ? 'selected' : ''}} value="Arahat">
                                                        Arahat
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="cikis_aktarma" class="font-weight-bold">Çıkış
                                                    Aktarma </label>
                                                <select name="cikis_aktarma" required id="cikis_aktarma"
                                                        class="form-control form-control-sm">
                                                    <option value=""> Seçiniz</option>
                                                    @foreach($data['transshipment_centers'] as $trasferCars)
                                                        <option
                                                            {{old('cikis_aktarma') == $trasferCars->id ? 'selected' : ''}}
                                                            value="{{$trasferCars->id}}">{{$trasferCars->tc_name.' T.M.'}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="varis_aktarma" class="font-weight-bold">Varış
                                                    Aktarma</label>
                                                <select name="varis_aktarma" required id="varis_aktarma"
                                                        class="form-control form-control-sm">
                                                    <option value=""> Seçiniz</option>
                                                    @foreach($data['transshipment_centers'] as $trasferCars)
                                                        <option
                                                            {{old('varis_aktarma') == $trasferCars->id ? 'selected' : ''}}
                                                            value="{{$trasferCars->id}}">{{$trasferCars->tc_name.' T.M.'}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="ugradigi_aktarmalar" class="font-weight-bold">Uğradığı
                                                    Aktarmalar</label>
                                                <select style="display: none;width: 100%;" multiple="multiple" required
                                                        name="ugradigi_aktarmalarx"
                                                        id="ugradigi_aktarmalar"
                                                        class="form-control">
                                                    @foreach($data['transshipment_centers'] as $trasferCars)
                                                        <option
                                                            value="{{$trasferCars->id}}">{{$trasferCars->tc_name. ' ('.$trasferCars->type.')' . ' T.M.' }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="ugradigi_aktarmalar"
                                               value="{{old('ugradigi_aktarmalar')}}"
                                               id="ugradigi_aktarmalar_dizi">

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="muayene_baslangic_tarihi">Muayene Başlangıç Tarihi</label>
                                                <input type="date" required class="form-control form-control-sm"
                                                       name="muayene_baslangic_tarihi"
                                                       value="{{old('muayene_baslangic_tarihi')}}"
                                                       id="muayene_baslangic_tarihi">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="muayene_bitis_tarihi">Muayene Bitiş Tarihi</label>
                                                <input type="date" required class="form-control form-control-sm"
                                                       name="muayene_bitis_tarihi"
                                                       value="{{old('muayene_bitis_tarihi')}}"
                                                       id="muayene_bitis_tarihi">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="trafik_sigortasi_baslangic_tarihi">Trafik Sigortası
                                                    Başlangıç Tarihi</label>
                                                <input type="date" required class="form-control form-control-sm"
                                                       name="trafik_sigortasi_baslangic_tarihi"
                                                       value="{{old('trafik_sigortasi_baslangic_tarihi')}}"
                                                       id="trafik_sigortasi_baslangic_tarihi">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="trafik_sigortasi_bitis_tarihi">Trafik Sigortası Bitiş
                                                    Tarihi</label>
                                                <input type="date" required class="form-control form-control-sm"
                                                       name="trafik_sigortasi_bitis_tarihi"
                                                       value="{{old('trafik_sigortasi_bitis_tarihi')}}"
                                                       id="trafik_sigortasi_bitis_tarihi">
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
                                        <label for="sofor_ad" class="font-weight-bold">Şoför Adı</label>
                                        <input name="sofor_ad" required id="sofor_ad"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{ old('sofor_ad') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="position-relative form-group">
                                        <label for="sofor_telefon" class="font-weight-bold">Şoför İletişim *</label>
                                        <input name="sofor_telefon" id="sofor_telefon" required
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(_) _ _ _" type="text"
                                               value="{{ old('sofor_telefon') }}"
                                               class="form-control form-control-sm input-mask-trigger">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="sofor_adres" class="font-weight-bold">Şoför Adresi</label>
                                        <textarea name="sofor_adres" id="sofor_adres"
                                                  class="form-control form-control-sm" maxlength="500"
                                                  required>{{ old('sofor_adres') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="arac_sahibi_ad" class="font-weight-bold">Araç Sahibi Ad
                                            Soyad</label>
                                        <input name="arac_sahibi_ad" required id="arac_sahibi_ad"
                                               placeholder="Araç sahibi adı."
                                               type="text"
                                               value="{{ old('arac_sahibi_ad') }}" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="position-relative form-group">
                                        <label for="arac_sahibi_telefon" class="font-weight-bold">Araç Sahibi
                                            İletişim </label>
                                        <input name="arac_sahibi_telefon" id="arac_sahibi_telefon" required
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(_) _ _ _" type="text"
                                               value="{{ old('arac_sahibi_telefon') }}"
                                               class="form-control arac_sahibi_telefon form-control-sm input-mask-trigger">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="arac_sahibi_adres" class="font-weight-bold">Araç Sahibi
                                            Adres</label>
                                        <textarea name="arac_sahibi_adres" id="arac_sahibi_adres"
                                                  class="form-control form-control-sm" maxlength="500"
                                                  required>{{ old('arac_sahibi_adres') }}</textarea>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="carOwnerRelative" class="font-weight-bold">Araç Sahibi Yakını Ad
                                            Soyad</label>
                                        <input name="arac_sahibi_yakini_ad" required id="arac_sahibi_yakini_ad"
                                               placeholder="Araç sahibi yakını ad soyad"
                                               type="text"
                                               value="{{ old('arac_sahibi_yakini_ad') }}"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="position-relative form-group">
                                        <label for="arac_sahibi_yakini_telefon" class="font-weight-bold">Araç Sahibi
                                            Yakını
                                            İletişim </label>
                                        <input name="arac_sahibi_yakini_telefon" id="arac_sahibi_yakini_telefon"
                                               required
                                               data-inputmask="'mask': '(999) 999 99 99'"
                                               placeholder="(_) _ _ _" type="text"
                                               value="{{ old('arac_sahibi_yakini_telefon') }}"
                                               class="form-control form-control-sm input-mask-trigger">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="arac_sahibi_yakini_adres" class="font-weight-bold">Araç Sahibi
                                            Yakını
                                            Adres</label>
                                        <textarea name="arac_sahibi_yakini_adres" id="arac_sahibi_yakini_adres"
                                                  class="form-control form-control-sm" maxlength="500"
                                                  required>{{ old('arac_sahibi_yakini_adres') }}</textarea>
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
    <script type="text/javascript" src="/backend/assets/scripts/bootstrap-multiselect.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>


        $(document).ready(() => {

            $("#tc_car_form").submit(function (e) {

                if ($('#ugradigi_aktarmalar').val() == '') {
                    ToastMessage('error', 'Aracın Uğradığı Aktarmaları Giriniz!', 'Hata');
                    e.preventDefault();
                }

            });


            @if(old('ugradigi_aktarmalar') != '')
            $('#ugradigi_aktarmalar').val([{{old('ugradigi_aktarmalar')}}]).trigger('change');
            selectPrepare();
            @endif

            $('#ugradigi_aktarmalar').select2({
                theme: "bootstrap4",
                placeholder: "Uğradığı Aktarmalar",
                width: 'resolve'
            });

            $('#ugradigi_aktarmalar').on('select2:selecting', function (e) {
                console.log('Selecting: ', e.params.args.data);
                selectPrepare();
            });
            $('#ugradigi_aktarmalar').on('select2:unselecting', function (e) {
                console.log('Selecting: ', e.params.args.data);
                selectPrepare();
            });

            function selectPrepare() {

                setTimeout(function () {
                    $('.select2-selection__choice__remove').addClass('btn btn-xs btn-danger');
                    $('.select2-selection__choice__remove').css('color', 'white');

                    let data = $("#ugradigi_aktarmalar").select2('data');
                    let text = "";

                    for (let i = 0; i < data.length; i++)
                        text += data[i].id + ",";

                    $('#ugradigi_aktarmalar_dizi').val(text);

                }, 100);

            }


            $("#tc_car_form").validate({
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

        $('#monthRentPrice').keyup(function () {//tamam
            var monthRentPrice = $('#monthRentPrice').val().replaceAll(',', '');
            var kdvHaricHakedis = monthRentPrice / 1.18;
            var birSeferKiraMaliyeti = monthRentPrice / 26;
            $('#kdvHaricHakedis').val(kdvHaricHakedis);
            $('#oneRentPrice').val(birSeferKiraMaliyeti);
        });


        $('.for-calculate-oneFlueJourneyPrice').keyup(function () {//tamam
            var flueRate = parseFloat($('#flueRate').val().replaceAll(',', ''));

            var turKm = parseFloat($('#turKm').val().replaceAll(',', ''));
            $('#oneFlueJourneyPrice').val((turKm * flueRate * 6.7) / 100);
        });


        $('.calculate-for-SeferPlusMaliyet').keyup(function () {
            var oneFlueJourneyPrice = parseFloat($('#oneFlueJourneyPrice').val().replaceAll(',', ''));

            var oneRentPrice = parseFloat($('#oneRentPrice').val().replaceAll(',', ''));
            console.log(oneRentPrice);

            $('#seferPlusMaliyet').val((oneRentPrice + oneFlueJourneyPrice));
        });

        $('.calculat-for-hakedisPlusMazot').keyup(function () {
            var monthRentPrice = parseFloat($('#monthRentPrice').val().replaceAll(',', ''));

            var monthFlue = parseFloat($('#monthFlue').val().replaceAll(',', ''));

            $('#hakedisPlusMazot').val(monthRentPrice + monthFlue);

        });
    </script>
@endsection
