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
                        <a href="{{ route('TCCars.index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Tüm Acente Araçları Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <form id="tc_car_form" method="POST" action="{{ route('TCCars.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12" id="container-general-info">
                            <h6 class="text-dark text-center font-weight-bold">Araç Bilgileri</h6>
                            <div class="divider"></div>
                            <div class="form-row">

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="plaka" class="font-weight-bold">Plaka:</label>
                                        <input name="plaka" required id="plaka"
                                               placeholder="Aracın plakasını giriniz."
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
                                               placeholder="Aracın nodelini giriniz."
                                               type="text"
                                               value="{{ old('model') }}" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="model_yili" class="font-weight-bold">Araç Model Yılı</label>
                                        <input name="model_yili" required id="model_yili"
                                               placeholder="Aracın model yılını giriniz."
                                               type="text"
                                               value="{{ old('model_yili') }}"
                                               class="form-control form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="doors_to_be_sealed" class="font-weight-bold">Mühür Vurulacak Kapı Sayısı</label>
                                        <input name="doors_to_be_sealed" required id="doors_to_be_sealed"
                                               placeholder="Kapı sayısını giriniz."
                                               type="text"
                                               class="form-control form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="branch_code" class="font-weight-bold">Bağlı olduğu birim</label>
                                        <input name="branch_code" required id="branch_code"
                                               type="text"
                                               value= "{{ $branch ?? ''}}"
                                               class="form-control form-control form-control-sm" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="creator" class="font-weight-bold">Ekleyen</label>
                                        <input name="creator" required id="creator"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="{{$user ?? ''}}"
                                               class="form-control form-control form-control-sm" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="car_type" class="font-weight-bold">Araç tipi</label>
                                        <input name="car_type" required id="car_type"
                                               placeholder="Aracın markasını giriniz."
                                               type="text"
                                               value="Aktarma"
                                               class="form-control form-control form-control-sm" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12" id="container-communication-info">
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

            $("#agency_tc_car_form").submit(function (e) {

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


            $("#agency_tc_car_form").validate({
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
