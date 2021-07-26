@extends('backend.layout')

@section('title', 'Yeni Modül Girişi')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-diamond icon-gradient bg-warm-flame">
                        </i>
                    </div>
                    <div>Yeni Modül Ekle
                        <div class="page-title-subheading">Bu sayfa üzerinden kullanacılarınıza verebileceğiniz yeni
                            modüller ekleyebilirsiniz.
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
                <h5 class="card-title">MODÜL EKLE</h5>
                <form method="POST" action="{{ route('module.InsertModule') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="rolename" class="">Modül Adı*</label>
                                <input name="name" id="rolename" placeholder="Modül ismini giriniz" type="text" required
                                    value="{{ old('name') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="ico" class="">Iconu*</label>
                                <input name="ico" id="ico" placeholder="Modüle ait iconu giriniz." type="text"
                                    required value="{{ old('ico') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="rolename" class="">Modül Grubu*</label>
                                <select class="form-control" required name="module_group_id" id="rolname">
                                    <option value="">Seçiniz</option>
                                    @foreach ($data as $key)
                                        <option {{ old('module_group_id') == $key->id ? 'selected' : '' }} value="{{ $key->id }}">{{ $key->title }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="must" class="">Modül Sırası</label>
                                <input name="must" id="must" placeholder="Modüle ait iconu giriniz." type="number"
                                    value="{{ old('must') }}" class="form-control">
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
                        <span class="ladda-label">Yeni Modül Ekle</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>

                </form>
            </div>
        </div>
    </div>
@endsection
