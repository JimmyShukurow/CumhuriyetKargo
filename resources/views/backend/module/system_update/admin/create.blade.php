@extends('backend.layout')

@section('title', 'Sistem Güncelleme')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-config icon-gradient bg-ck"> </i>
                    </div>
                    <div>Yeni Sistem Güncelleme Girişi
                        <div class="page-title-subheading">Bu sayfa üzerinden yeni sistem güncellemesi girip
                            kullanıcılarınız ile paylaşabilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('module.systemUpdate.Index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-arrow-alt-circle-left fa-w-20"></i>
                                </span>
                                Tüm güncellemelere geri dön
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Sistem Detaylarını Güncelle</h5>
                {{--                <form id="TransshipmentCenterForm" method="POST" action="{{ route('TransshipmentCenters.store') }}">--}}
                <form method="POST" action="{{ route('module.systemUpdate.Insert') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label class="">Sistem Versiyonu*</label>
                                <div class="input-group">
                                    <input type="text" name="version" required class="form-control"
                                           value="{{old('version')}}" placeholder="Sistem Versiyonunu Giriniz">
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label class="">Versiyon Güncelleme Başlığı</label>
                                <div class="input-group">
                                    <input type="text" name="title" value="{{old('title')}}" required
                                           class="form-control"
                                           placeholder="Versiyon Güncelleme Başlığını Giriniz">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="agency" class="">Versiyon Güncellemesi İle Gelen Yenilikler*</label>
                                <textarea name="content" id="editor">{{old('title')}}</textarea>
                            </div>
                            <style>
                                .ck-editor__editable_inline {
                                    min-height: 35vh !important;
                                }
                            </style>
                        </div>
                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Güncelle</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>

                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/translations/tr.js"></script>
@endsection

@section('js')

    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                language: 'tr'
            })
            .then(editor => {
                console.log('Editor was initialized', editor);
                myEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>


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
