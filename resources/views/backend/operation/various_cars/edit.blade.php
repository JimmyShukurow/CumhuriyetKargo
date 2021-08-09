@extends('backend.layout')

@section('title', 'Muhtelif Araç Düzenle')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-car icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Muhtelif Araç Düzenle
                        <div class="page-title-subheading">Bu sayfa üzerinden muhtelif araç düzenlenebilir.
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
                <h5 class="card-title">ARAÇ DÜZENLE</h5>
                <form id="agencyForm" method="post"
                      action="{{ route('VariousCars.update',$data['various_car']->id) }} ">
                    @method('PUT')
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="arac_marka" class="">Araç Marka</label>
                                <input name="arac_marka" required id="arac_marka"
                                       placeholder="Aracın markasını giriniz."
                                       type="text"
                                       value="{{  $data['various_car']->brand }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="model" class="">Model</label>
                                <input name="model" required id="model"
                                       placeholder="Aracın modelini giriniz."
                                       type="text"
                                       value="{{  $data['various_car']->model }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="link" class="">Plaka:</label>
                                <input type="text" placeholder="Araç plakasını giriniz." name="plaque" required
                                       id="plaque" value="{{ $data['various_car']->plaque}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="link" class="">Tonaj (KG):</label>
                                <input type="text" placeholder="Tonaj bilgisini giriniz(kg)." name="tonnage" required
                                       id="tonnage" value="{{ $data['various_car']->tonnage}}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="case_type" class="">Kasa Türü</label>
                                <select name="case_type" id="case_type" class="form-control">
                                    <option value=""> Seçiniz</option>
                                    <option value="Açık" {{ $data['various_car']->case_type == 'Açık'? 'selected': ''}}>
                                        Açık
                                    </option>
                                    <option  value="Kapalı" {{ $data['various_car']->case_type == 'Kapalı' ? 'selected' :''}}>
                                        Kapalı
                                    </option>
                                    <option  value="Panelvan" {{ $data['various_car']->case_type == 'Panelvan' ? 'selected' :''}}>
                                        Panelvan
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="position-relative form-group">
                                <label for="link" class="">Telefon (Araç Sahibi)*</label>
                                <input name="phone" id="phone" required data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text"
                                       value="{{  $data['various_car']->phone }}"
                                       class="form-control input-mask-trigger">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="link" class="">Araç Model Yıl:</label>
                                <input type="text" placeholder="Araç model yılını giriniz." name="model_year" required
                                       id="model_year" value="{{  $data['various_car']->model_year}}"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="link" class="">Şöför Adı:</label>
                                <input type="text" placeholder="Şöförün adını giriniz" name="driver_name" required
                                       id="driver_name" value="{{ $data['various_car']->driver_name}}"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="link" class="">Aracın Bulunduğu Şehir:</label>
                                <select name="where_car" id="where_car" class="form-control">
                                    <option value=""> Seçiniz</option>
                                    @foreach($data['cities'] as $city)
                                        <option
                                            value="{{$city['city_name']}}" {{ $data['various_car']->city == $city['city_name'] ? 'selected' :''}}>{{$city['city_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Aracı Düzenle</span>
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
