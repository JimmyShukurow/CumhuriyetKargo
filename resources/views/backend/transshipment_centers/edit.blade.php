@extends('backend.layout')

@section('title', 'Aktarma Düzenle')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i style="color:#000" class="fa fa-truck con-gradient">
                        </i>
                    </div>
                    <div>Transfer Merkezi (Aktarma) Düzenle
                        <div class="page-title-subheading">Bu sayfa üzerinden yeni transfer merkezlerini
                            düzenleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('TransshipmentCenters.index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Tüm Transfer Merkezlerini Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">TRANSFER MERKEZİ DÜZENLE</h5>
                <form id="TransshipmentCenterForm" method="POST"
                      action="{{ route('TransshipmentCenters.update', $tc->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="tc_name" class="">Transfer Merkezi Adı*</label>
                                <div class="input-group">
                                    <input type="text" name="tc_name" id="tc_name" value="{{ $tc->tc_name }}"
                                           placeholder="KAYAPINAR" required class="form-control">
                                    <div class="input-group-append"><span
                                            class="input-group-text">TRANSFER MERKEZİ</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="position-relative form-group">
                                <label for="phone" class="">Aktarma İletişim (Varsa Sabit Hat)</label>
                                <input name="phone" id="phone" data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text" value="{{ $tc->phone }}"
                                       class="form-control input-mask-trigger">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="type" class="">Aktarma Türü*</label>
                                <select name="type" required id="type" class="form-control">
                                    <option value="">Seçiniz</option>
                                    <option {{ $tc->type == 'ANA'? 'selected' : '' }} value="ANA">ANA AKTARMA</option>
                                    <option {{ $tc->type == 'ARA'? 'selected' : '' }} value="ARA">ARA AKTARMA</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="form-row">

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="link" class="">İl*</label>
                                <select name="city" id="city" required class="form-control">
                                    <option value="">İl Seçiniz</option>
                                    @foreach($data['cities'] as $city)
                                        <option
                                            {{ $tc->city == $city->city_name ? 'selected' : ''  }} id="{{$city->id}}"
                                            value="{{$city->id}}">{{$city->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="district" class="">İlçe</label>
                                <select name="district" id="district" required class="form-control">
                                    <option value="">İlçe Seçiniz</option>
                                    @foreach($data['districts'] as $district)
                                        <option
                                            {{ $tc->district == $district->district_name ? 'selected' : ''  }} id="{{$district->district_id}}"
                                            value="{{$district->district_id}}">{{$district->district_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="neighborhood" class="">Mahalle</label>
                                <select name="neighborhood" id="neighborhood"  class="form-control">
                                    <option value="">Mahalle Seçiniz</option>
                                    @foreach($data['neighborhoods'] as $neighborhood)
                                        <option
                                            {{ $tc->neighborhood == $neighborhood->neighborhood_name ? 'selected' : ''  }} id="{{$neighborhood->neighborhood_id}}"
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

                                $.post('{{ route('ajax.city.to.district') }}', {
                                    _token: token,
                                    city_id: city_id
                                }, function (response) {
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
                                <label for="director" class="">Aktarma Müdürü</label>
                                <select name="director" id="director" class="form-control">
                                    <option value="">Seçiniz</option>
                                    @foreach ($data['users'] as $user)
                                        <option {{ $tc->tc_director_id  == $user->id ? 'selected' : '' }}
                                                value="{{ $user->id }}">{{ $user->name_surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="assistant_director" class="">Aktarma Müdür Yardımcısı</label>
                                <select name="assistant_director" id="assistant_director" class="form-control">
                                    <option value="">Seçiniz</option>
                                    @foreach ($data['users'] as $user)
                                        <option {{ $tc->tc_assistant_director_id == $user->id ? 'selected' : '' }}
                                                value="{{ $user->id }}">{{ $user->name_surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="status">Statü</label>
                                <select class="form-control" required name="status" id="status">
                                    <option {{$tc->status == '1' ? 'selected' :  ''}} value="1">Aktif</option>
                                    <option {{$tc->status == '0' ? 'selected' :  ''}} value="0">Pasif</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="position-relative form-group">
                                <label for="status_description">Statü Açıklama</label>
                                <input type="text" name="status_description" id="status_description" value="{{$tc->status_description}}"
                                       class="form-control" maxlength="500">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="adress" class="">Adres</label>
                                <textarea name="adress" id="adress" placeholder="Bölge müdürlüğü açık adresini giriniz."
                                          cols="30" rows="10" class="form-control">{{ $tc->adress }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Kaydet</span>
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

            $("#TransshipmentCenterForm").validate({
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
