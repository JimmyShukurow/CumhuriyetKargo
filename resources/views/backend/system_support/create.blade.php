@extends('backend.layout')

@section('title', 'Yeni Ticket Oluştur')


@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-ticket icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Yeni Ticket Oluştur
                        <div class="page-title-subheading"> Bu sayfa üzerinden istediğiniz departmana ticket
                            açabilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('systemSupport.myTickets') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Tüm Destek Taleplerim
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card ">
            <div class="card-body">
                <h5 style="text-transform: none;" class="card-title">Yeni Destek Talebi</h5>
                <form id="ticketForm" method="POST" action="{{ route('systemSupport.create') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name_surname" class="">Ad Soyad</label>
                                <input readonly id="name_surname"
                                       placeholder="Ad soyad giriniz"
                                       type="text"
                                       value="{{ Auth::user()->name_surname }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="position-relative form-group">
                                <label for="link" class="">E-Posta </label>
                                <input readonly id="name_surname"
                                       placeholder="E-Posta"
                                       type="text"
                                       value="{{Auth::user()->email }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Başlık</label>
                                <input type="text" value="{{old('title')}}" maxlength="75" name="title" required
                                       class="form-control"
                                       placeholder="Başlık Belirtiniz">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="agency_name" class="">Departman*</label>
                                <select name="department" required id="department" class="form-control">
                                    <option value="">Departman Seçiniz</option>
                                    @foreach($data['departments'] as $key)
                                        <option
                                            {{ old('department') == $key->id  ? 'selected' : '' }} value="{{$key->id}}">{{$key->department_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="agency_development_officer" class="">Öncelik Sırası*</label>
                                <select name="priority" required id="priority" class="form-control">
                                    <option value="">Öncelik Sırası Seçin</option>
                                    <option {{ old('priority') == 'Düşük' ? 'selected' : '' }} value="Düşük">Düşük
                                    </option>
                                    <option {{ old('priority') == 'Normal' ? 'selected' : '' }} value="Normal">Normal
                                    </option>
                                    <option {{ old('priority') == 'Yüksek' ? 'selected' : '' }} value="Yüksek">Yüksek
                                    </option>
                                    <option {{ old('priority') == 'Çok Yüksek' ? 'selected' : '' }} value="Çok Yüksek">
                                        Çok Yüksek
                                    </option>
                                    <option {{ old('priority') == 'Acil' ? 'selected' : '' }}  value="Acil">Acil
                                    </option>
                                    <option {{ old('priority') == 'Kritik' ? 'selected' : '' }}  value="Kritik">Kritik
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
                    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/translations/tr.js"></script>


                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="agency" class="">Açıklama*</label>
                                <div id="editor">
                                    <p>Lütfen mesajınızı bir kaç cümle ile belirtin.</p>
                                </div>

                            </div>
                        </div>
                        <input type="hidden" id="message" name="message">

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

                        <style>
                            .ck-editor__editable_inline {
                                min-height: 35vh !important;
                            }
                        </style>

                    </div>

                    <div class="form-row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="files1">Eklentiler</label>
                                <input type="file" name="file1" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file1"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="files2">Eklentiler</label>
                                <input type="file" name="file2" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file2"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="files3">Eklentiler</label>
                                <input type="file" name="file3" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file3"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="files4">Eklentiler</label>
                                <input type="file" name="file4" accept=".jpg, .gif, .jpeg, .png, .doc, .xls, .pdf, .txt, .docx,
                                    .xlsx" id="file4"
                                       class="form-control">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 text-center">
                            <em class="text-primary text-center pt-2">İzin verilen dosya türleri: .jpg, .gif, .jpeg,
                                .png,
                                .doc,
                                .xls, .pdf, .txt, .docx,
                                .xlsx
                            </em>
                        </div>
                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Destek Talebi Oluştur</span>
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

            $("#ticketForm").validate({
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

            $('#ticketForm').submit(function () {
                $('#message').val(myEditor.getData());
            })

            @if(old('message') !== null)
            myEditor.setData('{!!  old('message') !!}')
            @endif

        });

    </script>

@endsection
