<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CKG-Sis - Giriş Sayfası</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"
    />
    <meta name="description" content="ArchitectUI HTML Bootstrap 4 Dashboard Template">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="icon" href="/backend/assets/images/ck-ico-white.png" type="image/x-icon"/>


    <link href="/backend/assets/css/main.8d288f825d8dffbbe55e.css" rel="stylesheet">

    <script src="/backend/assets/scripts/jquery.js"></script>
    <link href="/backend/assets/css/toastr.min.css" rel="stylesheet" type="text/css"/>
    <script src="/backend/assets/scripts/general-up.js"></script>
</head>

<body>
<div class="app-container app-theme-white body-tabs-shadow">
    <div class="app-container">
        <div class="h-100">
            <div class="h-100 no-gutters row">
                <div class="d-none d-lg-block col-lg-4">
                    <div class="slider-light">
                        <div class="slick-slider">
                            <div>
                                @foreach ($lyrics as $l)
                                    @php
                                        $loop->index == 0 ? $first = $l->lyrics : '';
                                        $loop->index == 1 ? $second = $l->lyrics: '';
                                        $loop->index == 2 ? $third = $l->lyrics: '';
                                    @endphp
                                @endforeach
                                <div id="imgDiv1"
                                     class="position-relative h-100 d-flex justify-content-center align-items-center bg-plum-plate"
                                     tabindex="-1">
                                    <div id="img1" class="slide-img-bg"
                                         style="background-image: url('/backend/assets/images/originals/city.jpg');"></div>
                                    <div class="slider-content"><h3>{{$first}}</h3>
                                    </div>
                                </div>
                            </div>
                            <div id="imgDiv2">
                                <div
                                    class="position-relative h-100 d-flex justify-content-center align-items-center bg-premium-dark"
                                    tabindex="-1">
                                    <div id="img2" class="slide-img-bg"
                                         style="background-image: url('/backend/assets/images/originals/citynights.jpg');"></div>
                                    <div class="slider-content"><h3>{{$second}}</h3>
                                    </div>
                                </div>
                            </div>
                            <div id="imgDiv3">
                                <div
                                    class="position-relative h-100 d-flex justify-content-center align-items-center bg-sunny-morning"
                                    tabindex="-1">
                                    <div id="img3" class="slide-img-bg"
                                         style="background-image: url('/backend/assets/images/originals/citydark.jpg');"></div>
                                    <div class="slider-content"><h3>{{$third}}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
                    <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                        <div class="app-logo"></div>
                        <h4 class="mb-0">
                            <span class="d-block">Hoşgeldiniz,</span>
                            <span>Lütfen Giriş Yapın.</span></h4>
                        </h6>
                        <div class="divider row"></div>
                        <div>
                            <form method="POST" action="{{route('admin.Authenticate')}}">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">E-Mail</label>
                                            <input name="email" id="exampleEmail" value="{{old('email')}}"
                                                   placeholder="E-Postanızı Girin"
                                                   type="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="examplePassword" class="">Şifre</label>
                                            <input name="password" id="examplePassword" value="{{old('password')}}"
                                                   placeholder="Şifrenizi Girin"
                                                   type="password" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="position-relative form-check">
                                    <input name="remember_me" id="exampleCheck" type="checkbox"
                                           class="form-check-input">
                                    <label for="exampleCheck" class="form-check-label">Beni Hatırla</label>
                                </div>
                                <div class="divider row"></div>
                                <div class="d-flex align-items-center">
                                    <div class="ml-auto"><a href="{{route('forgetPassword')}}"
                                                            class="btn-lg btn btn-link">Şifreni mi Unuttun?</a>
                                        <button class="btn btn-primary btn-lg">Giriş Yap</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/backend/assets/scripts/toastr.js"></script>

<script>
    @if(Session::has('error'))
    ToastMessage('error', '{{session('error')}}', 'Hata!');
    @elseif(Session::has('success'))
    ToastMessage('success', '{{session('success')}}', 'İşlem Başarılı!');
    @endif

    $('#imgDiv2').hide();
    $('#imgDiv3').hide();

    setTimeout(function () {
        $('#imgDiv2').show();
        $('#imgDiv3').show();
    }, 2500);
</script>

<script type="text/javascript" src="/backend/assets/scripts/main.8d288f825d8dffbbe55e.js"></script>
