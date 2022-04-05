@extends('backend.layout')

@section('title', 'Teslimat')

@push('css')
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>

    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas fa-check icon-gradient bg-amy-crisp">
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

        <div style="max-width: 1200px" class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Teslimat Formu</h5>
                <div class="form-row">
                    <div class="col-lg-3 col-sm-6 col-xs-6">
                        <div class="position-relative form-group">
                            <label for="name_surname" class="">Fatura No:</label>
                            <input name="name_surname" id="invoice_number"
                                   data-inputmask="'mask': 'AA-999999'"
                                   placeholder="__ ______"
                                   type="text" value="{{ old('name_surname') }}"
                                   class="form-control form-control-sm input-mask-trigger">
                        </div>
                    </div>


                    <div class="col-lg-3 col-sm-6 col-xs-6">
                        <div class="position-relative form-group">
                            <label for="pieces" class="">İlgili Parçalar:</label>
                            <div class="input-group">
                                <input type="text" id="pieces" readonly
                                       class="form-control font-weight-bold form-control-sm">
                                <div class="input-group-append ">
                                    <button type="button" id="piecesBtn" disabled class="btn btn-danger btn-sm">
                                        Parçalar
                                    </button>
                                </div>
                            </div>
                            <small class="text-success font-weight-bold">
                                <i id="textSelectedPieces"></i>
                            </small>
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

                <h5 class="card-title mt-2">Teslimat Bilgileri</h5>
                <div class="divider"></div>


                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="">İşlem: <small class="text-danger">*</small></label>
                        <select name="" class="form-control form-control-sm" id="transaction">
                            <option value="Teslimat">Teslimat</option>
                            <option value="İade">İade</option>
                            <option value="Devir">Devir</option>
                            <option value="Yönlendir">Yönlendir</option>
                        </select>
                    </div>
                </div>

                <div class="cont">
                    <h5 class="card-title">Alıcı Bilgileri</h5>
                    <div class="divider"></div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Teslim Alan Ad Soyad/Firma:<small class="text-danger">*</small></label>
                            <input id="receiverNameSurnameCompany" type="text" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label for="">Teslim Alan TCKN/VKN:<small class="text-danger">*</small></label>
                            <input id="receiverTCKN" style="-webkit-appearance: none;  margin: 0;" type="number"
                                   maxlength="11"
                                   class="form-control number form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label for="">Teslim Alan Yakınlık:<small class="text-danger">*</small></label>
                            <select name="receiverProximity" id="receiverProximity"
                                    class="form-control form-control-sm">
                                @foreach($proximity as $key)
                                    <option value="{{$key->proximity}}">{{$key->proximity}}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-md-3">
                            <label for="">Teslimat Zamanı:<small class="text-danger">*</small></label>
                            <input id="deliveryDate" type="datetime-local" value="{{date('Y-m-d')}}T{{date('H:m')}}"
                                   max="{{date('Y-m-d')}}T{{date('H:m')}}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group position-relative">
                            <label for="descriptionDelivery">Açıklama:<small class="text-danger">*</small></label>
                            <textarea name="descriptionDelivery" id="descriptionDelivery" cols="30" rows="3"
                                      class="form-control"
                                      placeholder="Lütfen açıklama giriniz. (Zorunlu Alan)">Teslim edilmiştir.</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group position-relative">
                            <label for="detecting_unit">Birim:</label>
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


                </div>

                <button id="btnSubmitForm" class="btn btn-primary">Kaydet</button>

            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/jquery.validate.min.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/expeditions/create.js"></script>

    <script src="/backend/assets/scripts/main-cargo/delivery/index.js"></script>
    <script>var typeOfJs = 'create_htf'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>
@endsection

@section('modals')

    @php $data = ['type' => 'create_htf']; @endphp
    @include('backend.main_cargo.cargo_details.modal_cargo_details')

    @include('backend.main_cargo.delivery.includes.modal_part_details')
@endsection

