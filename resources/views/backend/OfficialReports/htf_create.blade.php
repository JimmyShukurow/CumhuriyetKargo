@extends('backend.layout')

@section('title', 'HTF Oluştur')

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
                                <input name="name_surname" required id="invoice_number"
                                       data-inputmask="'mask': 'AA 999999'"
                                       placeholder="__ ______"
                                       type="text" value="{{ old('name_surname') }}"
                                       class="form-control form-control-sm input-mask-trigger">
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-xs-6">
                            <div class="position-relative form-group">
                                <label for="name_surname" class="">Kargo Takip No:</label>
                                <input name="name_surname" required id="tracking_no"
                                       data-inputmask="'mask': '99999 99999 99999'"
                                       placeholder="_____ _____ _____"
                                       type="text" value="{{ old('name_surname') }}"
                                       class="form-control form-control-sm input-mask-trigger">
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-2">Kargo Bilgileri</h5>
                    <div class="divider"></div>
                    <div id="rowCargoInfo" class="row">

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group position-relative">
                                <label class="font-weight-bold" for="sender_name">Fatura Numarası:</label>
                                <h3><b class="cargo-information text-danger font-large-3" id="invoice_number">-</b></h3>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group position-relative">
                                <label class="font-weight-bold" for="sender_name">Kargo Takip Numarası:</label>
                                <h3><b class="cargo-information text-primary" id="tracking_no">-</b></h3>
                            </div>
                        </div>


                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="sender_name">Gönderici Adı:</label>
                                <p><b class="cargo-information" id="sender_name">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="receiver_name">Alıcı Adı:</label>
                                <p><b class="cargo-information" id="receiver_name">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="departure_branch">Çıkış Şube:</label>
                                <p><b class="cargo-information" id="departure_branch">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="departure_tc">Çıkış TRM:</label>
                                <p><b class="cargo-information" id="departure_tc">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="arrival_branch">Varış Şube:</label>
                                <p><b class="cargo-information" id="arrival_branch">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="arrival_tc">Varış TRM:</label>
                                <p><b class="cargo-information" id="arrival_tc">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="cargo_type">Kargo Tipi:</label>
                                <p><b class="cargo-information" id="cargo_type">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="number_of_pieces">Parça Sayısı:</label>
                                <p><b class="cargo-information" id="number_of_pieces">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="desi">Desi:</label>
                                <p><b class="cargo-information" id="desi">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="status">Statü:</label>
                                <p><b class="cargo-information" id="status">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="total_price">Toplam Ücret</label>
                                <p><b class="cargo-information" id="total_price">-</b></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group position-relative">
                                <label for="cargo_info">Diğer Bilgiler:</label>
                                <input disabled id="cargo_info" style="width: 100%;" type="button" class="btn btn-info"
                                       value="Kargo Bilgileri">
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-2">Tutanak Bilgileri</h5>
                    <div class="divider"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="detecting_unit">Tespit Eden Birim:</label>
                                <input type="text" id="detecting_unit" disabled
                                       value="#{{$branch['code'] . ' ' . $branch['city'] . ' - ' . $branch['name'] . ' ' . $branch['type']}}"
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

                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="reported_unit">Tutanak Tutulan Birim Tipi:</label>
                                <select name="reported_unit_type" class="form-control form-control-sm"
                                        id="reported_unit_type">
                                    <option value="">Seçiniz</option>
                                    <option value="Çıkış Şube">Çıkış Şube</option>
                                    <option value="Çıkış TRM.">Çıkış TRM.</option>
                                    <option value="Varış Şube">Varış Şube</option>
                                    <option value="Varış TRM.">Varış TRM.</option>
                                    <option value="Diğer Şube">Diğer Şube</option>
                                    <option value="Diğer TRM.">Diğer TRM.</option>
                                </select>
                            </div>
                        </div>

                        <div id="column_fake_unit" class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="select_reported_unit">Birim Seçin:</label>
                                <select disabled name="select_reported_unit" class="form-control form-control-sm"
                                        id="select_reported_unit">
                                    <option value="">Seçiniz</option>
                                    <option value="Çıkış Şube">Çıkış Şube</option>
                                    <option value="Çıkış TRM.">Çıkış TRM.</option>
                                    <option value="Varış Şube">Varış Şube</option>
                                    <option value="Varış TRM.">Varış TRM.</option>
                                    <option value="Diğer Şube">Diğer Şube</option>
                                    <option value="Diğer TRM.">Diğer TRM.</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: none;" id="column-agency" class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="select_reported_agency">Şube Seçin:</label>
                                <select style="width:100%;" disabled name="select_reported_agency"
                                        class="form-control form-control-sm"
                                        id="select_reported_agency">
                                    <option value="">Seçiniz</option>
                                    @foreach($agencies as $key)
                                        <option
                                            value="{{$key->id}}">{{$key->agency_name . ' ŞUBE'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div style="display: none;" id="column-tc" class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="select_reported_tc">Transfer Merkezi Seçin:</label>
                                <select style="width:100%;" name="select_reported_tc" disabled
                                        class="form-control form-control-sm"
                                        id="select_reported_tc">
                                    <option value="">Seçiniz</option>
                                    @foreach($tc as $key)
                                        <option value="{{$key->id}}">{{$key->tc_name . ' TRM.'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="reported_unit">Tutanak Tutulan Birim:</label>
                                <input type="text" id="reported_unit" disabled
                                       value=""
                                       class="form-control font-weight-bold text-danger form-control-sm">
                            </div>
                        </div>

                    </div>

                    <h5 class="card-title mt-2">Hasar Nedeni</h5>
                    <div class="divider"></div>

                    <div class="row">
                        @foreach($damage_types as $key)
                            <div class="col-lg-2 col-md-4 col-sm-4 col-6">
                                <div class="custom-control custom-checkbox">
                                    <input {{$key->status == '0' ? 'disabled' : ''}} type="checkbox"
                                           class="custom-control-input" id="cb-damage-{{$key->id}}">
                                    <label class="custom-control-label cursor-pointer unselectable"
                                           for="cb-damage-{{$key->id}}">{{$key->damage_name}}</label>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-12 mt-2">
                            <div class="form-group position-relative">
                                <label for="damage_description">Açıklama</label>
                                <textarea name="damage_description" id="damage_description" cols="30"
                                          rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title mt-2">Yapılan İşlem</h5>
                    <div class="divider"></div>

                    <div class="row">
                        @foreach($transactions as $key)
                            <div class="col-lg-2 col-md-4 col-sm-4 col-6">
                                <div class="custom-control custom-checkbox">
                                    <input {{$key->status == '0' ? 'disabled' : ''}} type="checkbox"
                                           class="custom-control-input" id="cb-transaction-{{$key->id}}">
                                    <label class="custom-control-label cursor-pointer unselectable"
                                           for="cb-transaction-{{$key->id}}">{{$key->transaction_name}}</label>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-12 mt-2">
                            <div class="form-group position-relative">
                                <label for="damage_description">Açıklama</label>
                                <textarea name="damage_description" id="damage_description" cols="30"
                                          rows="2" class="form-control"></textarea>
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
    <script src="/backend/assets/scripts/official-report/htf-create.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>


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


    </script>
@endsection
