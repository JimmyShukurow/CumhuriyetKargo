<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Forgot Password Boxed - ArchitectUI HTML Bootstrap 4 Dashboard Template</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"
    />
    <meta name="description" content="ArchitectUI HTML Bootstrap 4 Dashboard Template">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <link href="/backend/assets/css/main.8d288f825d8dffbbe55e.css" rel="stylesheet">

    <link rel="icon" href="/backend/assets/images/ck-ico-white.png" type="image/x-icon"/>

    <script src="/backend/assets/scripts/jquery.js"></script>
    <link href="/backend/assets/css/toastr.min.css" rel="stylesheet" type="text/css"/>
    <script src="/backend/assets/scripts/general-up.js"></script>
</head>

<body>
<div class="app-container app-theme-white body-tabs-shadow">
    <div class="app-container">
        <div class="h-100 bg-plum-plate bg-animation">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="mx-auto app-login-box col-md-6">
                    <div class="app-logo-inverse mx-auto mb-3"></div>
                    <div class="modal-dialog w-100">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="h5 modal-title">Şifrenizi mi Unuttunuz?<h6 class="mt-1 mb-0 opacity-8">
                                        <span>Yenilemek için aşağıdaki formu kulanın.</span>
                                    </h6></div>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <form id="frmRecoverPassword" method="POST" action="{{route('confirmEmail')}}">
                                        @csrf

                                        @if(Session::has('time_out'))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-danger">
                                                        {{session('time_out')}}
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($TimeOut != false)
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-danger">
                                                        Zaman aşımına uğradınız. Lütfen tekrar deneyiniz!
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="position-relative form-group">
                                                    <label for="email" class="">E-Posta:</label>
                                                    <input name="email" value="{{old('email')}}" required id="email"
                                                           placeholder="E-Posta adresinizi girin..."
                                                           type="email" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="position-relative form-group">
                                                    <label for="phone" class="">Telefon:</label>
                                                    <input name="phone" data-inputmask="'mask': '(999) 999 99 99'"
                                                           placeholder="(___) ___ __ __" value="{{old('phone')}}"
                                                           required id="phone"
                                                           class="form-control input-mask-trigger">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="divider"></div>
                                <h6 class="mb-0">
                                    <a href="{{route('Login')}}" class="text-primary">Veya Giriş Yapın</a>
                                </h6>
                            </div>
                            <div class="modal-footer clearfix">
                                <div class="float-right">
                                    <button id="btnRecoverPassword" class="btn btn-primary btn-lg">Şifre Yenile</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-white opacity-8 mt-3">Copyright © Cumhuriyet Kargo 2021</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/backend/assets/scripts/toastr.js"></script>


<script>
    $('#btnRecoverPassword').click(function () {
        if ($('#email').val().trim() == '') {
            ToastMessage('error', 'Lütfen mail adresi girin!', 'Hata!');
            return false;
        }
        if ($('#phone').val().trim() == '') {
            ToastMessage('error', 'Lütfen telefon numaranızı girin!', 'Hata!');
            return false;
        }


        $('#frmRecoverPassword').submit();
    });

    @if(Session::has('error'))
    ToastMessage('error', '{{session('error')}}', 'Hata!');
    @elseif(Session::has('success'))
    ToastMessage('success', '{{session('success')}}', 'İşlem Başarılı!');
    @endif

</script>


<script type="text/javascript" src="/backend/assets/scripts/main.8d288f825d8dffbbe55e.js"></script>

</body>
</html>
