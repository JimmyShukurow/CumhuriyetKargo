<div class="form-group row">

    <div style="padding-top: 0; padding-bottom: 0;" class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsFirstDate">İlk Tarih:</label>
                    <input type="date" id="agencyPaymentsFirstDate" value="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsLastDate">Son Tarih:</label>
                    <input type="date" id="agencyPaymentsLastDate" value="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsAppNo">Başvuru No:</label>
                    <input type="text" id="agencyPaymentsAppNo" class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsAgency">Şube:</label>
                    <select style="width: 100%;" class="form-control-sm form-control" name=""
                            id="agencyPaymentsAgency">
                        <option value="">Seçiniz</option>
                        @foreach($data['agencies'] as $key)
                            <option value="{{$key->id}}">{{$key->agency_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsConfirm">Oany:</label>
                    <select class="form-control-sm form-control" name="" id="agencyPaymentsConfirm">
                        <option value="">Seçiniz</option>
                        <option value="0">Onay Bekliyor</option>
                        <option value="1">Onaylandı</option>
                        <option value="-1">Reddedildi</option>
                    </select>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentsPaymentChannel">Ödeme Kanalı:</label>
                    <select class="form-control-sm form-control" name="" id="agencyPaymentsPaymentChannel">
                        <option value="">Seçiniz</option>
                        @foreach($data['payment_channels'] as $key)
                            <option value="{{$key->payment_channel}}">{{$key->payment_channel}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>


<table style="width: 100%; white-space: nowrap;" id="tableAgencyPayments"
       class="table Table20Padding table-bordered table-hover">
    <thead>
    <tr>
        <th>Ödeme No:</th>
        <th>Onay Tipi</th>
        <th>BaşvuruNo</th>
        <th>Acente</th>
        <th>Ödenen Tutar</th>
        <th>Ödeme Tarihi</th>
        <th>Ödeyen Ad Soyad</th>
        <th>Ödeme Kanalı</th>
        <th>Onaylayan</th>
        <th>Açıklama</th>
        <th>Kayıt Tarihi</th>
        <th>İşlem</th>
    </tr>
    </thead>
</table>

<script src="/backend/assets/scripts/safe/general/agency-payments.js"></script>


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
@endsection
