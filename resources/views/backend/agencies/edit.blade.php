@extends('backend.layout')

@section('title', 'Acente Düzenle')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-store icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Acente Düzenle
                        <div class="page-title-subheading">Bu sayfa üzerinden acente düzenleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('agency.Index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Tüm Acenteleri Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Acente Düzenle</h5>
                <form id="agencyForm" method="POST" action="{{ route('agency.UpdateAgency', ['id' => $agency->id]) }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name_surname" class="">Ad Soyad (Acente Sahibi)*</label>
                                <input name="name_surname" required id="name_surname"
                                       placeholder="Acente sahibi ad soyad bilgisini giriniz."
                                       type="text"
                                       value="{{ $agency->name_surname }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="position-relative form-group">
                                <label for="link" class="">Telefon (Acente Sahibi)*</label>
                                <input name="phone" id="link" required data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text"
                                       value="{{ $agency->phone  }}" class="form-control input-mask-trigger">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="position-relative form-group">
                                <label for="phone2" class="">Telefon2 (Acente Sahibi)*</label>
                                <input name="phone2" id="phone2" data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text"
                                       value="{{ $agency->phone2 }}" class="form-control input-mask-trigger">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="link" class="">İl:</label>
                                <select name="city" id="city" required class="form-control">
                                    <option value="">İl Seçiniz</option>
                                    @foreach($data['cities'] as $city)
                                        <option
                                            {{ $agency->city == $city->city_name ? 'selected' : ''  }} id="{{$city->id}}"
                                            value="{{$city->id}}">{{$city->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="district" class="">İlçe*</label>
                                <select name="district" id="district" required class="form-control">
                                    <option value="">İlçe Seçiniz</option>
                                    @foreach($data['districts'] as $district)
                                        <option
                                            {{ $agency->district == $district->district_name ? 'selected' : ''  }} id="{{$district->district_id}}"
                                            value="{{$district->district_id}}">{{$district->district_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="neighborhood" class="">Mahalle*</label>
                                <select name="neighborhood" id="neighborhood" required class="form-control">
                                    <option value="">Mahalle Seçiniz</option>
                                    @foreach($data['neighborhoods'] as $neighborhood)
                                        <option
                                            {{ $agency->neighborhood == $neighborhood->neighborhood_name ? 'selected' : ''  }} id="{{$neighborhood->neighborhood_id}}"
                                            value="{{$neighborhood->neighborhood_id}}">{{$neighborhood->neighborhood_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <script>

                        $(document).ready(function () {

                            $('#city').change(function () {

                                var city_id = $(this).children(":selected").attr("id");

                                $.post('{{route('ajax.city.to.district')}}', {
                                    _token: token,
                                    city_id: city_id
                                }, function (response) {
                                    console.log(response);

                                    $('#district').html('');
                                    $('#district').append(
                                        '<option  value="">İlçe Seçin</option>'
                                    );
                                    $('#neighborhood').html('');
                                    $('#neighborhood').append(
                                        '<option  value="">Mahalle Seçin</option>'
                                    );
                                    $('#neighborhood').prop('disabled', true);
                                    $.each(response, function (key, value) {
                                        $('#district').append(
                                            '<option id="' + (value['key']) + '"  value="' + (value['key']) + '">' + (value['name']) + '</option>'
                                        );
                                    });
                                    $('#district').prop('disabled', false);
                                });
                            });

                            $('#district').change(function () {

                                var district_id = $(this).children(":selected").attr("id");

                                $.post('{{route('ajax.district.to.neighborhood')}}', {
                                    _token: token,
                                    district_id: district_id
                                }, function (response) {

                                    $('#neighborhood').html('');
                                    $('#neighborhood').append(
                                        '<option  value="">Mahalle Seçin</option>'
                                    );
                                    $.each(response, function (key, value) {
                                        $('#neighborhood').append(
                                            '<option id="' + (value['key']) + '"  value="' + (value['key']) + '">' + (value['name']) + '</option>'
                                        );
                                    });
                                    $('#neighborhood').prop('disabled', false);
                                });
                            });
                        });
                    </script>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="agency_name" class="">Acente Adı*</label>
                                <input type="text" name="agency_name" value="{{$agency->agency_name}}" required
                                       class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="agency_development_officer" class="">Acente Geliştirme Sorumlusu*</label>
                                <input type="text" name="agency_development_officer"
                                       value="{{$agency->agency_development_officer}}" required class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="transshipment_center" class="">Bağlı Olduğu Transfer Merkezi</label>
                                <select name="transshipment_center" id="transshipment_center" class="form-control">
                                    <option value=""> Seçiniz</option>
                                    @foreach($data['transshipment_centers'] as $key)
                                        <option
                                            {{ $agency->transshipment_center_code == $key->id ? 'selected' : ''  }}
                                            value="{{$key->id}}">{{$key->tc_name . ' TRANSFER MERKEZİ (' . $key->city . ')'}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="agency" class="">Adres*</label>
                                <textarea name="adress" id="" placeholder="Acente açık adresini giriniz." required
                                          cols="30" rows="10" class="form-control">{{$agency->adress}}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Acente Düzenle</span>
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

    <script>

        $('#email').keyup(function () {
            var charMap = {
                Ç: 'C',
                Ö: 'O',
                Ş: 'S',
                İ: 'I',
                I: 'i',
                Ü: 'U',
                Ğ: 'G',
                ç: 'c',
                ö: 'o',
                ş: 's',
                ı: 'i',
                ü: 'u',
                ğ: 'g'
            };
            var str = $('#email').val();
            str_array = str.split('');

            for (var i = 0, len = str_array.length; i < len; i++) {
                str_array[i] = charMap[str_array[i]] || str_array[i];
            }
            str = str_array.join('');
            var clearStr = str.replace(" ", "").replace("-", "").replace(/[^a-z0-9-.çöşüğı]/gi, "");

            $('#email').val(clearStr.toLowerCase());

        });

        $('#email').focusout(function () {
            check_email($(this).val())
        });


        function check_email(email) {
            $.post('/Users/CheckEmail', {
                _token: token,
                email: email
            }, function (response) {
                if (response == 1) {
                    $('#email').addClass("is-invalid").removeClass("is-valid");
                    $('#email-error').show();
                } else {
                    $('#email').addClass("is-valid").removeClass("is-invalid");
                    $('#email-error').hide();
                }
                console.log(response);
            })
        }
    </script>
@endsection
