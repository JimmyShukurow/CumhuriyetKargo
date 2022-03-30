@extends('backend.layout')

@section('title', 'Teslimat')

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
                        <i class="fa fa-bus icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div>Teslimat Sayfası
                        <div class="page-title-subheading">Bu modül üzerinden varış şubesi olduğunuz kargolara teslimat
                            girebilirsiniz.
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
                            <label for="plaque" class="font-weight-bold">Fatura NO:</label>
                            <input id="plaque" type="text" class="form-control form-control-sm "
                                   placeholder="Kargoya Fatura Numarasını Giriniz">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title mt-2">Çıkış Birim</h5>
                        <div class="divider"></div>
                        <div id="departureBranchInfo" class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group position-relative">
                                    <label class="font-weight-bold" for="departureBranchType">Birim Tipi:</label>
                                    <select disabled="" id="departureBranchType"
                                            class="form-control form-control-sm">
                                        <option
                                            {{Auth::user()->user_type == 'Aktarma' ? 'selected' :''}} value="Aktarma">
                                            Aktarma
                                        </option>
                                        <option {{Auth::user()->user_type == 'Acente' ? 'selected="selected"' : ''}}
                                                value="Acente">Acente
                                        </option>
                                    </select>
                                </div>
                            </div>

                            @if(Auth::user()->user_type == 'Aktarma')
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group position-relative">
                                        <label class="font-weight-bold" for="departureTc">Aktarma Seçin:</label>
                                        <select disabled="" id="departureBranchType"
                                                class="form-control form-control-sm">
                                            <option value="">Seçiniz</option>
                                            @foreach($tc as $key)
                                                <option
                                                    {{Auth::user()->tc_code == $key->id ? 'selected="selected"' : ''}}
                                                    value="{{$key->id}}">{{$key->tc_name}} TRM.
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group position-relative">
                                        <label class="font-weight-bold" for="departureAgency">Acente Seçin:</label>
                                        <select disabled="" id="departureBranchType"
                                                class="form-control form-control-sm">
                                            <option value="">Seçiniz</option>
                                            @foreach($agencies as $key)
                                                <option
                                                    {{Auth::user()->agency_code == $key->id ? 'selected="selected"' : ''}} value="{{$key->id}}">{{$key->agency_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title mt-2">Varış Birim</h5>
                        <div class="divider"></div>
                        <div id="rowCargoInfo" class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group position-relative">
                                    <label class="font-weight-bold" for="arrivalBranchType">Birim Tipi:</label>
                                    <select name="" id="arrivalBranchType"
                                            class="form-control form-control-sm">
                                        <option value="Aktarma">Aktarma</option>
                                        <option value="Acente">Acente</option>
                                    </select>
                                </div>
                            </div>

                            <div id="divArrivalTc" class="col-md-6 col-sm-6">
                                <div class="form-group position-relative">
                                    <label class="font-weight-bold" for="arrivalTc">Aktarma Seçin:</label>
                                    <select name="" id="arrivalTc"
                                            class="form-control form-control-sm">
                                        <option value="">Seçiniz</option>
                                        @foreach($tc as $key)
                                            <option value="{{$key->id}}">{{$key->tc_name}} TRM.</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div style="display: none;" id="divArrivalAgency" class="col-md-6 col-sm-6">
                                <div class="form-group position-relative">
                                    <label class="font-weight-bold" for="arrivalAgency">Acente Seçin:</label>
                                    <select style="width: 100%;" name="" id="arrivalAgency"
                                            class="form-control agency-select form-control-sm">
                                        <option value="">Seçiniz</option>
                                        @foreach($agencies as $key)
                                            <option value="{{$key->id}}">{{$key->agency_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="card-title text-center mt-2">Ara Duraklar</h5>
                <div class="divider"></div>

                <div class="row">
                    <div class="col-md-12">
                        <button id="DeleteAllRoutes" type="button" class="btn mb-2 btn-danger float-right">Tümünü
                            Sil
                        </button>
                        <button id="AddRoutes" type="button" class="btn mb-2 mr-5 btn-primary float-right">Güzergah
                            Ekle
                        </button>
                        <table class="table-hover Table30Padding table table-bordered">
                            <thead>
                            <tr>
                                <th>Sıra No</th>
                                <th>Güzergah</th>
                                <th>İşlem</th>
                            </tr>
                            </thead>
                            <tbody id="tbodyExpeditionRoutes">
                            <tr>
                                <td colspan="3" class="text-danger text-center"><b>Ara durak yok.</b></td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <label for="detecting_unit">Düzenleyen Birim:</label>
                            <input type="text" id="detecting_unit" disabled
                                   value="{{ $branch['city'] . ' - ' . $branch['name'] . ' ' . $branch['type']}}"
                                   class="form-control font-weight-bold text-primary form-control-sm">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <label for="detecting_person">Düzenleyen:</label>
                            <input type="text" disabled value="{{Auth::user()->name_surname}}"
                                   id="detecting_person"
                                   class="form-control font-weight-bold text-primary form-control-sm">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group position-relative">
                            <label for="detecting_person">Açıklama:</label>
                            <textarea class="form-control form-control-sm" id="description" cols="30" rows="3"
                                      placeholder="Açıklama giriniz. (Opsiyonel)"></textarea>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary" id="btnCreateExpedition">Sefer Oluştur</button>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/expeditions/create.js"></script>
@endsection

@section('modals')
    @include('backend.expedition.create.sections.modal_add_routes')
@endsection

