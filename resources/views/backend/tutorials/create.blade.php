@extends('backend.layout')

@section('title', 'Eğitim Oluştur')

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
                        <i class="metismenu-icon pe-7s-film icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Eğitim Oluştur
                        <div class="page-title-subheading">Bu modül üzerinden eğitim oluşturabilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="max-width: 1200px;" class="main-card mb-3 card">
            <div class="card-body">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group position-relative">
                            <label for="plaque" class="font-weight-bold">Video adı:</label>
                            <input id="video_name" type="text" class="form-control form-control-sm "
                                   placeholder="Video adını giriniz">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group position-relative">
                            <label for="plaque" class="font-weight-bold">Kategori:</label>
                            <input id="category" type="text" class="form-control form-control-sm "
                                   placeholder="Kategoi seçin">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group position-relative">
                            <label for="plaque" class="font-weight-bold">Link:</label>
                            <input id="embedded_link" type="text" class="form-control form-control-sm "
                                   placeholder="Kategoi seçin">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group position-relative">
                            <label for="plaque" class="font-weight-bold">Eğitmen:</label>
                            <input id="tutor" type="text" class="form-control form-control-sm "
                                   placeholder="Kategoi seçin">
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group position-relative">
                            <label for="detecting_person">Açıklama:</label>
                            <textarea class="form-control form-control-sm" id="description" cols="30" rows="3"
                                      placeholder="Açıklama giriniz. (Opsiyonel)"></textarea>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary" id="btnCreateTutorial">Eğitim Oluştur</button>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/tutorials/create.js"></script>
@endsection



