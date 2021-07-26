@extends('backend.layout')

@section('title', 'Modül Grubu Düzenle')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-diamond icon-gradient bg-warm-flame">
                        </i>
                    </div>
                    <div>Modül Grubu Düzenle
                        <div class="page-title-subheading">Bu sayfa üzerinden modül grubu düzenleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{route('module.Index')}}">
                            <button type="button" aria-haspopup="true"
                                    aria-expanded="false" class="btn-shadow btn btn-info">
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
            <div class="card-body"><h5 class="card-title">MODÜL GRUBU DÜZENLE</h5>
                <form method="POST" action="{{route('module.UpdateModuleGroup' , ['id' => $mg->id])}}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="title" class="">Grup Adı*</label>
                                <input name="title" id="rolename"
                                       placeholder="Grup adını giriniz"
                                       type="text" required value="{{$mg->title}}"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="must" class="">Sırası</label>
                                <input name="must"
                                       id="must"
                                       placeholder="Sıra numarasımı giriniz(opsiyonel)."
                                       type="number" value="{{$mg->must}}"
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="description" class="">Grup Açıklaması*</label>
                                <textarea name="description" required placeholder="Grup açıklaması giriniz."
                                          class="form-control" id="description" cols="30"
                                          rows="10">{{$mg->description}}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
                            data-style="slide-right">
                        <span class="ladda-label">Modül Grubu Düzenle</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>

                </form>
            </div>
        </div>
    </div>
@endsection
