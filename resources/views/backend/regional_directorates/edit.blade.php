@extends('backend.layout')

@section('title', 'Bölge Müdürlüğü Düzenle')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i style="color:#ddd" class="fa fa-university con-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Bölge Müdürlüğü Düzenle
                        <div class="page-title-subheading">Bu sayfa üzerinden bölge müdürlüklerini düzenleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('rd.Index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Tüm Bölgeleri Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Bölge Müdürlüğü DÜZENLE</h5>
                <form id="regionalDirectoratesForm" method="POST"
                      action="{{ route('rd.UpdateRd', ['id' => $area->id]) }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name" class="">Bölge Müdürlüğü Adı*</label>
                                <div class="input-group">
                                    <input type="text" name="name" id="name" value="{{ $area->name }}"
                                           placeholder="İÇ ANADOLU" required class="form-control">
                                    <div class="input-group-append"><span
                                            class="input-group-text">BÖLGE MÜDÜRLÜĞÜ</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="position-relative form-group">
                                <label for="phone" class="">Bölge İletişim (Varsa Sabit Hat)</label>
                                <input name="phone" id="phone" data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text" value="{{ $area->phone }}"
                                       class="form-control input-mask-trigger">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="city" class="">İl:</label>
                                <select name="city" id="city" required class="form-control">
                                    <option value="">İl Seçiniz</option>
                                    @foreach ($data['cities'] as $city)
                                        <option {{ $area->city == $city->city_name ? 'selected' : '' }}
                                                id="{{ $city->id }}" value="{{ $city->id }}">{{ $city->city_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="district" class="">İlçe*</label>
                                <select name="district" id="district" class="form-control">
                                    <option value="">İlçe Seçiniz</option>
                                    @foreach ($data['districts'] as $district)
                                        <option {{ $area->district == $district->district_name ? 'selected' : '' }}
                                                id="{{ $district->district_id }}" value="{{ $district->district_id }}">
                                            {{ $district->district_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="neighborhood" class="">Mahalle*</label>
                                <select name="neighborhood" id="neighborhood" class="form-control">
                                    <option value="">Mahalle Seçiniz</option>
                                    @foreach ($data['neighborhoods'] as $neighborhood)
                                        <option
                                            {{ $area->neighborhood == $neighborhood->neighborhood_name ? 'selected' : '' }}
                                            id="{{ $neighborhood->neighborhood_id }}"
                                            value="{{ $neighborhood->neighborhood_id }}">
                                            {{ $neighborhood->neighborhood_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $('#city').change(function () {

                                var city_id = $(this).children(":selected").attr("id");

                                $.post('{{ route('ajax.city.to.district') }}', {
                                    _token: token,
                                    city_id: city_id
                                }, function (response) {
                                    console.log(response);

                                    $('#district').html('');
                                    $('#district').append(
                                        '<option  value="">İlçe Seçin</option>'
                                    );
                                    $('#neighborhood').prop('disabled', true);
                                    $.each(response, function (key, value) {
                                        $('#district').append(
                                            '<option id="' + (value['key']) +
                                            '"  value="' + (value['key']) + '">' + (
                                                value['name']) + '</option>'
                                        );
                                    });
                                    $('#district').prop('disabled', false);
                                });
                            });

                            $('#district').change(function () {

                                var district_id = $(this).children(":selected").attr("id");

                                $.post('{{ route('ajax.district.to.neighborhood') }}', {
                                    _token: token,
                                    district_id: district_id
                                }, function (response) {

                                    $('#neighborhood').html('');
                                    $('#neighborhood').append(
                                        '<option  value="">Mahalle Seçin</option>'
                                    );
                                    $.each(response, function (key, value) {
                                        $('#neighborhood').append(
                                            '<option id="' + (value['key']) +
                                            '"  value="' + (value['key']) + '">' + (
                                                value['name']) + '</option>'
                                        );
                                    });
                                    $('#neighborhood').prop('disabled', false);
                                });
                            });
                        });

                    </script>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="director" class="">Bölge Müdürü</label>
                                <select name="director" id="director" class="form-control">
                                    <option value="">Seçiniz</option>
                                    @foreach ($data['users'] as $user)
                                        <option {{ $area->director_id == $user->id ? 'selected' : '' }}
                                                value="{{ $user->id }}">{{ $user->name_surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="assistant_director" class="">Bölge Müdür Yardımcısı</label>
                                <select name="assistant_director" id="assistant_director" class="form-control">
                                    <option value="">Seçiniz</option>
                                    @foreach ($data['users'] as $user)
                                        <option {{ $area->assistant_director_id == $user->id ? 'selected' : '' }}
                                                value="{{ $user->id }}">{{ $user->name_surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="adress" class="">Adres</label>
                                <textarea name="adress" id="adress" placeholder="Bölge müdürlüğü açık adresini giriniz."
                                          cols="30" rows="10" class="form-control">{{ $area->adress }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Bölge Müdürlüğünü Düzenle</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>
                </form>
                <script>
                    $('#regionalDirectoratesForm').submit(function () {
                        $("#district").prop('disabled', false);
                        $("#neighborhood").prop('disabled', false);
                    });

                </script>

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
