@extends('backend.layout')

@section('title', 'Yetki Düzenle')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-diamond icon-gradient bg-warm-flame">
                        </i>
                    </div>
                    <div>Yetki Düzenle
                        <div class="page-title-subheading">Bu sayfa üzerinden kullanacılarınıza verebileceğiniz
                            yetkileri düzenleyebilirsiniz.
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
            <div class="card-body"><h5 class="card-title">YETKİ EKLE</h5>
                <form method="POST" action="{{route('module.UpdateRole', ['id' => $role->id])}}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="rolename" class="">Yetki Adı*</label>
                                <input name="name" id="rolename"
                                       placeholder="Yetki ismini giriniz"
                                       type="text" required value="{{$role->name}}"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="displayname" class="">Yetki Görünür Adı*</label>
                                <input name="display_name"
                                       id="displayname"
                                       placeholder="Yetkinin görünür adını giriniz."
                                       type="text" required value="{{$role->display_name}}"
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="description" class="">Yetki Açıklaması*</label>
                                <textarea name="description" required placeholder="Yetki açıklaması giriniz."
                                          class="form-control" id="description" cols="30"
                                          rows="10">{{$role->description}}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="ladda-button mb-2 mr-2 btn btn-gradient-secondary"
                            data-style="slide-right">
                        <span class="ladda-label">Yetkiyi Düzenle</span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>

                </form>
            </div>
        </div>
    </div>
@endsection
