@extends('backend.layout')

@section('title', 'Kullanıcı Düzenle (General Managment)')

@push('css')
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
@endpush


@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Kullanıcı Düzenle
                        <div class="page-title-subheading">Bu sayfa üzerinden kullanıcılarınızı düzenleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('user.gm.Index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Tüm Kullanıcıları Listele
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-sm-6">
                        <h5 style="display: inline-block" class="card-title">Kullanıcı Düzenle</h5>
                    </div>
                    <div class="col-6 text-right">
                        <input class="check_user_type" style="display: none;" onchange="change()" type="checkbox"
                               {{$user->user_type == 'Acente' ? 'checked' : ''}}
                               data-toggle="toggle"
                               data-on="Acente"
                               data-off="Aktarma" data-onstyle="success" data-offstyle="primary">
                    </div>
                </div>
                <form id="UserForm" method="POST" action="{{ route('user.gm.Update', ['id' => $user->id]) }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name_surname" class="">Ad Soyad*</label>
                                <input name="name_surname" required id="name_surname"
                                       placeholder="Alt modül ismini giriniz"
                                       type="text"
                                       value="{{ $user->name_surname }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative ">
                                <label for="email" class="">E-Posta*</label>
                            </div>
                            <div class="input-group">
                                <input placeholder="Kullanıcı Adı" required value="{{ JustUsername($user->email) }}"
                                       id="email"
                                       name="email"
                                       type="text"
                                       class="form-control">
                                <div class="input-group-append"><span
                                        class="input-group-text">@cumhuriyetkargo.com.tr</span></div>
                            </div>
                            <em id="email-error" class="error invalid-feedback">Bu kullanıcı adı
                                kullanılıyor, Lütfen farklı bir tane deneyin.</em>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="link" class="">Telefon:</label>
                                <input name="phone" id="link" required data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text"
                                       value="{{ $user->phone }}" class="form-control input-mask-trigger">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="password" class="">Şifre*</label>
                                <input name="password" id="password"
                                       placeholder="8 Haneli bir şifre belirleyiniz."
                                       type="text"
                                       value="{{ old('password') }}" class="form-control">
                                <em class="text-success">Şifreyi değiştirmek istemiyorsanız boş bırakın.</em>

                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="role" class="">Yetki*</label>
                                <select name="role" id="role" required class="form-control">
                                    <option value="">Yetki Seçiniz</option>
                                    @foreach($data['roles'] as $role)
                                        <option
                                            {{$user->role_id == $role->id ? 'selected' : ''}} value="{{$role->id}}">{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div id="agency_block" class="position-relative form-group">
                                <label for="agency" class="">Acente*</label>
                                <select style="width: 100%;" name="agency" id="agency" required
                                        class="form-control select2">
                                    <option value="">Acente Seçiniz</option>
                                    @foreach($data['agencies'] as $agency)
                                        <option {{$user->agency_code == $agency->id ? 'selected' : ''}}
                                                value="{{$agency->id}}">{{$agency->city . '/' . $agency->district. '/' . $agency->neighbourhood  .  ' - ' .$agency->agency_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="display: none;" id="tc_block" class="position-relative form-group">
                                <label for="agency" class="">Aktarma*</label>
                                <select style="width: 100%;" name="tc" id="tc" disabled required
                                        class="form-control select2">
                                    <option value="">Aktarma Seçiniz</option>
                                    @foreach($data['transshipment_centers'] as $tc)
                                        <option {{$user->tc_code == $tc->id ? 'selected' : ''}}
                                                value="{{$tc->id}}">{{$tc->city  .' - ' .$tc->tc_name . ' TRANSFER MERKEZİ'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Kullanıcıyı Düzenle</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>

                </form>


            </div>
        </div>
    </div>
@endsection

@section('js')
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

        function change() {
            let val = $('.check_user_type').prop('checked');

            if (val) {
                $('#agency_block').show();
                $('#tc_block').hide();

                $('#agency').prop('disabled', false);
                $('#tc').prop('disabled', true);
            } else {
                $('#agency_block').hide();
                $('#tc_block').show();

                $('#agency').prop('disabled', true);
                $('#tc').prop('disabled', false);
            }
        }

        change();

        $(document).ready(function () {
            $('.select2').select2({
                language: "es",
                placeholder: "Şube Adı Girin",
            });
        });
    </script>


@endsection
