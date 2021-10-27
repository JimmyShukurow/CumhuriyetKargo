@extends('backend.layout')

@section('title', 'Yeni Acente Girişi')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-note2 icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>HTF (Hasar Tespit Tutanağı) Oluştur
                        <div class="page-title-subheading">Bu sayfa üzerinden <b>HTF (Hasar Tespit Tutanağı)</b>
                            oluşturabilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">HTF (Hasar Tespit Tutanağı)</h5>
                <form id="agencyForm" method="POST" action="{{ route('agency.InsertAgency') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-lg-3 col-sm-6 col-xs-6">
                            <div class="position-relative form-group">
                                <label for="name_surname" class="">Fatura No:</label>
                                <input name="name_surname" required id="name_surname"
                                       data-inputmask="'mask': 'AA 999999'"
                                       placeholder="__ ______"
                                       type="text" value="{{ old('name_surname') }}"
                                       class="form-control form-control-sm input-mask-trigger">
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-xs-6">
                            <div class="position-relative form-group">
                                <label for="name_surname" class="">Kargo Takip No:</label>
                                <input name="name_surname" required id="name_surname"
                                       data-inputmask="'mask': '99999 99999 99999'"
                                       placeholder="_____ _____ _____"
                                       type="text" value="{{ old('name_surname') }}"
                                       class="form-control form-control-sm input-mask-trigger">
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">HTF Oluştur</span>
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
