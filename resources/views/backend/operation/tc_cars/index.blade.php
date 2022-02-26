@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
    <style>
        table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
            top: 6px;
            left: 5px;
        }
    </style>
@endpush

@push('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@section('title', 'Aktarma Araçları')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-safe icon-gradient bg-ck">
                        </i>
                    </div>
                    <div>Aktarma Araçlar
                        <div class="page-title-subheading">Bu modül üzerinden Türkiye geneli Cumhuriyet Kargo A.Ş.
                            acentelerinin kasasını takip edebilir, işlem yapabilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .tab-pane {
                min-height: 50vh;
            }
        </style>

        <div class="main-card mb-3 card">
            <div class="card-header">
                <i class="header-icon pe-7s-safe icon-gradient bg-ck"> </i>Aktarma Araçlar Modülü
                <div class="btn-actions-pane-right">
                    <div class="nav">
                        <a id="tabTransferCars" data-toggle="tab" href="#transferCars"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger active">Aktarma Araçları</a>

                        <a id="tabAgencyPaymentApps" data-toggle="tab" href="#agencyPaymentApps"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger">Acente Ödeme
                            Bildirgeleri</a>

                        <a id="tabAgencyPayments" data-toggle="tab" href="#agencyPayments"
                           class="border-0 btn-pill btn-wide btn-transition btn btn-outline-danger">Acente Ödemeleri</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="transferCars" role="tabpanel">
                        @include('backend.operation.tc_cars.tabs.transfer_cars')
                    </div>

                    <div class="tab-pane" id="agencyPaymentApps" role="tabpanel">
                        @include('backend.operation.tc_cars.tabs.agency_cars_of_branch')
                    </div>

                    <div class="tab-pane" id="agencyPayments" role="tabpanel">
                        @include('backend.operation.tc_cars.tabs.agency_cars_waiting_confirmation')
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
@endsection



@section('modals')
    {{-- Large modal => Modal Payment App Details --}}
    <div class="modal fade bd-example-modal-lg" id="ModalAgencyPaymentAppDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Ödeme Başvurusu Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyAgencyPaymentAppDetails"
                     class="modal-body">

                    {{-- CARD START --}}
                    <div class="col-md-12">
                        <div class="mb-3 profile-responsive card">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-dark">
                                    <div class="menu-header-image "
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract10.jpg');">
                                    </div>
                                    <div class="menu-header-content btn-pane-right">
                                        <div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                            <div class="avatar-icon rounded">
                                                <img src="/backend/assets/images/ck-ico-white.png" alt="Avatar 5">
                                            </div>
                                        </div>
                                        <div>
                                            <h5 id="agencyName" class="menu-header-title">###</h5>
                                            <h6 id="appUserNameSurname" class="menu-header-subtitle">###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: auto" class="cont">
                                            <table id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="2">
                                                        Ödeme Başvuru Detayları
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">Başvuru Kayıt Tarihi</td>
                                                    <td id="appRegDate"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Başvuru No:</td>
                                                    <td id="appNo">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Başvuru Yapan Şube</td>
                                                    <td id="appAgencyName"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Başvuru Yapan</td>
                                                    <td id="appUserNameSurname"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Ödenen</td>
                                                    <td id="appPayment"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Onaylanan Ödeme</td>
                                                    <td id="appConfirmingPayment"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Ödeme Kanalı</td>
                                                    <td class="font-weight-bold" id="appPaymentChannel"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Ekler</td>
                                                    <td id="appAdd"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Açıklama</td>
                                                    <td id="appDescription"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Onay</td>
                                                    <td id="appConfirm"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Onaylayan</td>
                                                    <td id="appConfirmingUser"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Onay Tarihi</td>
                                                    <td id="appConfirmDate"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Ret Nedeni</td>
                                                    <td id="appRejectReason"></td>
                                                </tr>


                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </li>

                                <li class="p-1 list-group-item">

                                    <div class="row pl-2 pr-2 justify-content-end">
                                        <div class="col-md-4">
                                            <label for="">Onaylanacak Tutar:</label>
                                            <input class="form-control form-control-sm input-mask-trigger"
                                                   id="appConfirmPaidAmount"
                                                   placeholder="₺ 0.00" type="text"
                                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                                   im-insert="true" style="text-align: right;">
                                        </div>
                                        <div class="col-md-4">
                                            <a style="text-decoration: underline; display: block; margin-top: 30px;"
                                               class="text-alternate" href="javascript:void(0)" id="appSameAmountLink">Ödenen
                                                Tutar ile Aynı</a>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="">Ret Nedeni:</label>
                                            <textarea name="" class="form-control form-control-sm" id="appRejectReason"
                                                      cols="30" rows="3"
                                                      placeholder="Lütfen ret nedeni belirtin (Opsiyonel)"></textarea>
                                        </div>
                                    </div>

                                </li>


                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnAppConfirmWait"
                                                            class="btn-app-transaction btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary">
                                                        <i class="lnr-clock text-primary opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Başvuruyu Beklet
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnAppConfirmSuccess"
                                                            class="btn-app-transaction btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                                        <i class="lnr-checkmark-circle text-success opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Başvuruyu Onayla
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnAppConfirmReject"
                                                            class="btn-app-transaction btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                        <i class="lnr-cross-circle text-danger opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Başvuruyu Reddet
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </li>


                            </ul>
                        </div>
                    </div>
                    {{-- CARD END --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Large modal => Modal Payment App Details --}}
    <div class="modal fade bd-example-modal-lg" id="ModalAddAgencyPayment" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Acente Ödemesi Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyAgencyPaymentAppDetails"
                     class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="addAgencyPaymentAgency">Acente: <span class="text-danger">*</span></label>
                            <select style="width: 100%;" class="form-control-sm form-control add-agency-payment-fields"
                                    name=""
                                    id="addAgencyPaymentAgency">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option value="{{$key->id}}">{{$key->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="addAgencyPaymentPayingNameSurname">Ödeyen Ad Soyad: <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="addAgencyPaymentPayingNameSurname"
                                   class="form-control form-control-sm add-agency-payment-fields">
                        </div>
                        <div class="col-md-4">
                            <label for="addAgencyPaymentPaymentChannel">Ödeme Kanalı: <span class="text-danger">*</span></label>
                            <select class="form-control-sm form-control add-agency-payment-fields" name=""
                                    id="addAgencyPaymentPaymentChannel">
                                <option value="">Seçiniz</option>
                                <option value="EFT/HAVALE">EFT/HAVALE</option>
                                <option value="POS">POS</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="addAgencyPaymentPayment">Ödenen Tutar: <span
                                    class="text-danger">*</span></label>
                            <input class="form-control form-control-sm input-mask-trigger add-agency-payment-fields" id="addAgencyPaymentPayment"
                                   placeholder="₺ 0.00" type="text"
                                   data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                   im-insert="true" style="text-align: right;">
                        </div>
                        <div class="col-md-4">
                            <label for="addAgencyPaymentPaymentDate">Ödeme Tarihi: <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control form-control-sm"
                                   id="addAgencyPaymentPaymentDate" value="{{date('Y-m-d')}}T{{date('H:m')}}">
                        </div>
                        <div class="col-md-12">
                            <label for="addAgencyPaymentDescription">Açıklama:</label>
                            <textarea name="" placeholder="Açıklama girin (Opsiyonel)" id="addAgencyPaymentDescription"
                                      cols="30" rows="5"
                                      class="form-control add-agency-payment-fields"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary" id="btnSaveAgencyPayment">Ödeme Ekle</button>
                </div>
            </div>
        </div>
    </div>
@endsection
