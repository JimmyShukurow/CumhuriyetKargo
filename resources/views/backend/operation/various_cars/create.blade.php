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
                    <div>Yeni Muhtelif Araç Oluştur
                        <div class="page-title-subheading">Bu sayfa üzerinden muhtelif araç oluşturabilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('VariousCars.index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Tüm Muhtelif Araçları Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">ARAÇ EKLE</h5>
                <form id="agencyForm" method="POST" action="{{ route('VariousCars.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="arac_marka" class="">Araç Marka</label>
                                <input name="arac_marka" required id="arac_marka"
                                       placeholder="Aracın markasını giriniz."
                                       type="text"
                                       value="{{ old('arac_marka') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="model" class="">Model</label>
                                <input name="model" required id="model"
                                       placeholder="Aracın modelini giriniz."
                                       type="text"
                                       value="{{ old('model') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="plaque" class="">Plaka:</label>
                                <input type="text" placeholder="Araç plakasını giriniz." name="plaque"
                                       id="plaque" value="{{old('plaque')}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="tonnage" class="">Tonaj(KG):</label>
                                <input type="text" placeholder="Tonaj bilgisini giriniz(kg)." name="tonnage" required
                                       id="tonnage" value="{{old('tonnage')}}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="case_type" class="">Kasa Türü</label>
                                <select name="case_type" required id="case_type" class="form-control">
                                    <option value=""> Seçiniz</option>
                                    <option {{old('case_type') == 'Açık' ? 'selected' : ''}} value="Açık">Açık</option>
                                    <option {{old('case_type') == 'Kapalı' ? 'selected' : ''}} value="Kapalı">Kapalı
                                    <option {{old('case_type') == 'Panelvan' ? 'selected' : ''}} value="Panelvan">
                                        Panelvan
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="position-relative form-group">
                                <label for="phone" class="">Telefon (Araç Sahibi)*</label>
                                <input name="phone" id="phone" required data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text"
                                       value="{{ old('phone') }}" class="form-control input-mask-trigger">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="model_year" class="">Araç Model Yıl:</label>
                                <input type="text" placeholder="Araç model yılını giriniz." name="model_year" required
                                       id="model_year" value="{{old('model_year')}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="driver_name" class="">Muhattap:</label>
                                <input type="text" placeholder="Muhattap adını giriniz" name="driver_name" required
                                       id="driver_name" value="{{old('driver_name')}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="where_car" class="">Aracın Bulunduğu Şehir:</label>
                                <select name="where_car" required id="where_car" class="form-control">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['cities'] as $city)
                                        <option value="{{$city['city_name']}}">{{$city['city_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>


                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Araç Oluştur</span>
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

    </script>
@endsection
