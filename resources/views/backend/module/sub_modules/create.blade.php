@extends('backend.layout')

@section('title', 'Yeni Alt Modül Girişi')


@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-diamond icon-gradient bg-warm-flame">
                        </i>
                    </div>
                    <div>Yeni Alt Modül Ekle
                        <div class="page-title-subheading">Bu sayfa üzerinden kullanacılarınıza verebileceğiniz yeni
                            alt modüller ekleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('module.Index') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-step-backward fa-w-20"></i>
                                </span>
                                Geri Dön
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">ALT MODÜL EKLE</h5>
                <form method="POST" action="{{ route('module.AddSubModule') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="sub_name" class="">Alt Modül Adı*</label>
                                <input name="sub_name" id="sub_name" placeholder="Alt modül ismini giriniz" type="text" required
                                    value="{{ old('sub_name') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="must" class="">Alt modül Sırası</label>
                                <input name="must" id="must" placeholder="Alt modüle ait sıra numarasını giriniz." type="number"
                                       value="{{ old('must') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="module_id" class="">Bağlı Olduğu Modül*</label>
                                <select class="form-control" required name="module_id" id="module_id">
                                    <option value="">Seçiniz</option>
                                    @foreach ($data as $key)
                                        <option {{ old('module_id') == $key->id ? 'selected' : '' }} value="{{ $key->id }}">{{ $key->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="link" class="">Route:</label>
                                <input name="link" id="link" placeholder="Alt modüle ait rotayı giriniz." type="text"
                                    value="{{ old('link') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="description" class="">Modül Açıklaması</label>
                                <textarea name="description" placeholder="Modülün açıklaması giriniz."
                                    class="form-control" id="description" cols="30"
                                    rows="10">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary" data-style="slide-right">
                        <span class="ladda-label">Yeni Alt Modül Ekle</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>

                </form>
            </div>
        </div>
    </div>
@endsection
