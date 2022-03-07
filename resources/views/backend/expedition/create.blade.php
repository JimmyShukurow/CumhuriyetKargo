@extends('backend.layout')

@section('title', 'Sefer Oluştur')

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
                    <div>Sefer Oluştur
                        <div class="page-title-subheading">Bu modül üzerinden sefer oluşturabilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <form id="HtfCreateForm" method="POST" action="{{ route('agency.InsertAgency') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mt-2">Çıkış Birim</h5>
                            <div class="divider"></div>
                            <div id="rowCargoInfo" class="row">

                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group position-relative">
                                        <label class="font-weight-bold" for="departureBranchType">Birim Tipi:</label>
                                        <select name="" id="departureBranchType"
                                                class="form-control form-control-sm">
                                            <option value="Aktarma">Aktarma</option>
                                            <option value="Acente">Acente</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group position-relative">
                                        <label class="font-weight-bold" for="departureBranchType">Birim Seçin:</label>
                                        <select name="" id="departureBranchType"
                                                class="form-control form-control-sm">
                                            <option value="Aktarma">Aktarma</option>
                                            <option value="Acente">Acente</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title mt-2">Varış Birim</h5>
                            <div class="divider"></div>
                        </div>
                    </div>

                    <h5 class="card-title mt-2">Tutanak Bilgileri</h5>
                    <div class="divider"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="detecting_unit">Tespit Eden Birim:</label>
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

                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="reported_unit">(Hata Yapan) Tutanak Tutulan Birim Tipi:</label>
                                <select name="reported_unit_type" required class="form-control form-control-sm"
                                        id="reported_unit_type">
                                    <option value="">Seçiniz</option>
                                    @foreach(ReportedUnitTypes() as $key)
                                        <option value="{{$key}}">{{$key}}</option>
                                    @endforeach
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
                                        class="form-control form-control-sm reported-units"
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
                                        class="form-control form-control-sm reported-units"
                                        id="select_reported_tc">
                                    <option value="">Seçiniz</option>
                                    @foreach($tc as $key)
                                        <option value="{{$key->id}}">{{$key->tc_name . ' TRM.'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input style="display: none;" type="hidden" id="reported_unit_id">

                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="reported_unit">(Hata Yapan) Tutanak Tutulan Birim:</label>
                                <input type="text" id="reported_unit" readonly required
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
                                           class="custom-control-input cb-damges" damage-id="{{$key->id}}"
                                           id="cb-damage-{{$key->id}}">
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
                                    <input {{$key->status == '0' ? 'disabled' : ''}}  transaction-id="{{$key->id}}"
                                           type="checkbox"
                                           class="custom-control-input cb-transactions"
                                           id="cb-transaction-{{$key->id}}">
                                    <label class="custom-control-label cursor-pointer unselectable"
                                           for="cb-transaction-{{$key->id}}">{{$key->transaction_name}}</label>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-12 mt-2">
                            <div class="form-group position-relative">
                                <label for="content_detection">İçerik Tespiti <small class="text-danger"><i>(Zorunlu
                                            Alan)</i></small>:</label>
                                <textarea name="content_detection" required id="content_detection" cols="30"
                                          rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>


                    <button type="submit" id="btnSubmitForm" class="ladda-button mb-2 mr-2 btn btn-gradient-primary"
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
    <script src="/backend/assets/scripts/select2.js"></script>


    <script>
        $(document).ready(() => {

            $("#HtfCreateForm").validate({
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

    <script>var typeOfJs = 'create_htf'; </script>
    <script src="/backend/assets/scripts/main-cargo/cargo-details.js"></script>
    <script src="/backend/assets/scripts/official-report/htf-create.js"></script>
@endsection

@section('modals')

    @php $data = ['type' => 'create_htf']; @endphp
    @include('backend.main_cargo.main.modal_cargo_details')

    <!-- Large modal => Modal Barcode -->
    <div class="modal fade bd-example-modal-lg" id="ModalPartDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Parça Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-x: hidden;min-height: 30vh; max-height: 60vh;" id="ModalBarcodes"
                     class="modal-body">
                    <div id="ContainerBarcodes"
                         class="container">


                        <h3 class="text-dark text-center mb-4">İlgili Parçaları Seçin</h3>

                        <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                             class="cont">
                            <table style="white-space: nowrap;" id="TableEmployees"
                                   class="Table30Padding table-striped table-bordered table-hover table mt-3">
                                <thead>
                                <tr>
                                    <th>
                                        <input style="width: 20px;margin-left: 7px;" type="checkbox"
                                               class="select-all-cb form-control">
                                    </th>
                                    <th>Kargo Tipi</th>
                                    <th>Parça No</th>
                                    <th>En</th>
                                    <th>Boy</th>
                                    <th>Yükseklik</th>
                                    <th>KG</th>
                                    <th>Desi</th>
                                    <th>Hacim m<sup>3</sup></th>
                                </tr>
                                </thead>
                                <tbody id="tbodyCargoPartDetails">
                                <tr>
                                    <td class="text-center" colspan="8">Burda hiç veri yok.</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <label id="modalBarcodeFooterLabel" style="float: left;width: 100%;"><b id="barcodeCount">Lütfen HTF
                            için ilgili parçaları seçin.</b></label>
                    <button style="white-space: nowrap;" type="button" id="btnSelectPieces" class="btn btn-primary">
                        Parçaları Seç
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection

