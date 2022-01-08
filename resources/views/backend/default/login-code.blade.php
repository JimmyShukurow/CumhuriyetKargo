<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CKGSis - İki adımlı doğrulama - güvenlik kodunu girin</title>
    <link rel="icon" href="/backend/assets/images/ck-ico-white.png" type="image/x-icon"/>

    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"/>

    <meta name="description" content="ArchitectUI HTML Bootstrap 4 Dashboard Template">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <link href="/backend/assets/css/main.8d288f825d8dffbbe55e.css" rel="stylesheet">

    <script src="/backend/assets/scripts/jquery.js"></script>
    <link href="/backend/assets/css/toastr.min.css" rel="stylesheet" type="text/css"/>
    <script src="/backend/assets/scripts/general-up.js?v=1.0.1"></script>

    <script>
        $(document).ready(function () {

            let successMessage = localStorage.getItem('swal');
            if (successMessage) {
                swal(localStorage.getItem('swal-title'), localStorage.getItem('swal-message'), localStorage.getItem('swal-type'));

                localStorage.removeItem('swal');
                localStorage.removeItem('swal-title');
                localStorage.removeItem('swal-message');
                localStorage.removeItem('swal-type');
            }
        })
    </script>
</head>

<body>
<div class="app-container app-theme-white body-tabs-shadow">
    <div class="app-container">
        <div class="h-100 bg-asteroid bg-animation">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="mx-auto app-login-box col-md-6">
                    <div class="app-logo-inverse mx-auto mb-3"></div>
                    <div class="modal-dialog w-100">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="h5 modal-title text-dark"><b
                                        class="text-dark">Güvenlik Kodunu Gir</b>

                                    <div class="mb-3 card text-white text-center card-body"
                                         style="background-color: rgb(51, 51, 51); border-color: rgb(51, 51, 51);">

                                        <a style="display: inline-block; margin:0 auto;" href="javascript:void(0);"
                                           class="avatar-icon-wrapper btn-hover-shine avatar-icon-xl mb-3">
                                            <div class="avatar-icon rounded">
                                                <img
                                                    src="/backend/assets/images/ck-ico-white.png"
                                                    alt="">
                                            </div>
                                        </a>

                                        <h5 class="text-white card-title">{{$user->name_surname}}</h5>
                                        <h6 class="card-text">{{$userAllInfo->display_name}}</h6>
                                        <small
                                            class="font-size-md">({{$userAllInfo->branch_city . '/' . $userAllInfo->branch_district . '-'. $userAllInfo->branch_name  . ' ' . tr_strtoupper($userAllInfo->user_type)}}
                                            )</small>
                                    </div>

                                    <h6 class="mt-1 mb-0 opacity-8" style="color: #000;">
                                        <span>Sn. <b>{{$user->name_surname}}</b>, <br> sisteme giriş yapabilmek için <b>(***) *** {{substr($user->phone,9 ,6)}}</b> numaralı cep telefonunuza göndermiş olduğumuz 6 haneli güvenlik kodunu giriniz.</span>
                                    </h6>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div>
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="phone" class="">Güvenlik Kodu :</label>
                                                <input name="code" id="code" data-inputmask="'mask': 'N999-999'"
                                                       required
                                                       placeholder="N___-___"
                                                       class="form-control input-mask-trigger">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="token" name="token" value="{{Crypte4x($user->id)}}">
                                <div class="divider"></div>
                                <span
                                    class="font-weight-bold text-dark float-left font-size-lg" id="time">Kalan Süre: {{$dateHumens['minute']}}:{{$dateHumens['seconds']}}</span>
                            </div>
                            <input type="hidden" id="leftseconds" value="{{$diffSeconds}}">
                            <div class="modal-footer clearfix">
                                <div class="float-right">
                                    <button id="btnConfirmCode" type="submit" class="btn btn-primary btn-lg">
                                        Giriş Yap
                                    </button>
                                </div>
                            </div>
                            {{--                            </form>--}}
                            <input type="hidden" id="niko_token" value="{{$password}}">
                        </div>
                    </div>
                    <div class="text-center text-white opacity-8 mt-3">Copyright © Cumhuriyet Kargo 2022 | CKG-Sis</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/backend/assets/scripts/toastr.js"></script>
<script src="/backend/assets/scripts/login-code-confirm.js"></script>


<script>
    @if(Session::has('error'))
    ToastMessage('error', '{{session('error')}}', 'Hata!');
    @elseif(Session::has('success'))
    ToastMessage('success', '{{session('success')}}', 'İşlem Başarılı!');
    @endif
</script>


<script type="text/javascript" src="/backend/assets/scripts/main.8d288f825d8dffbbe55e.js"></script>

</body>
</html>
