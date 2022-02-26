{{-- Large modal => Modal Payment App Details --}}
<div class="modal fade bd-example-modal-lg" id="ModalAgencySafeStatusDetails" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Acente Kasa Detayları</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyAgencySafeStatusDetails"
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
                                        <h6 id="agencyRegionName" class="menu-header-subtitle">###/###</h6>
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
                                                    Acente Kasa Detayları
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="static">Acente Adı</td>
                                                <td id="agencyName"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Bağlı Bölge Müdürlüğü:</td>
                                                <td id="agencyRegion"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Acente Müdürü</td>
                                                <td id="appAgencyOfficer"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Acente İletişim</td>
                                                <td id="agencyPhones"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Toplam Fatura Sayısı</td>
                                                <td id="agencyTotalBillCount"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Toplam Ciro</td>
                                                <td id="agencyTotalEndorsement"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Nakit Tutar</td>
                                                <td class="font-weight-bold" id="agencyTotalCashAmount"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Pos Tutar</td>
                                                <td class="font-weight-bold" id="agencyTotalPosAmount"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Yatırdığı Tutar</td>
                                                <td id="agencyAmountDeposited"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Gün İçi</td>
                                                <td id="agencyIntraday"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Borç</td>
                                                <td id="agencyTotalDebt"></td>
                                            </tr>

                                            <tr>
                                                <td class="static">Kasa Statü</td>
                                                <td id="agencySafeStatus"></td>
                                            </tr>

                                            <tr>
                                                <td class="static">Kasa Statü Açıklaması</td>
                                                <td id="agencySafeStatusDescription"></td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </li>

                            <li class="p-1 list-group-item">

                                <div class="row pl-2 pr-2 justify-content-end">
                                    <div class="col-md-12">
                                        <label for="">Kasa Kapatma Nedeni:</label>
                                        <textarea name="" class="form-control form-control-sm" id="agencySafeStatusDescription"
                                                  cols="30" rows="3"
                                                  placeholder="Lütfen kasa kapatma nedenini belirtin (Default: Acente kasanız muhasebe birimi tarafından kapatılmıştır.)"></textarea>
                                    </div>
                                </div>

                            </li>


                            <li class="p-0 list-group-item">
                                <div class="grid-menu grid-menu-2col">
                                    <div class="no-gutters row">
                                        <div class="col-sm-6">
                                            <div class="p-1">
                                                <button id="btnEnabledAgencySafeStatus" status="1"
                                                        class="btn-app-transaction change-safe-status btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                                    <i class="lnr-checkmark-circle text-success opacity-7 btn-icon-wrapper mb-2">
                                                    </i>
                                                    Kasayı Aç
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="p-1">
                                                <button id="btnDisabledAgencySafeStatus" status="0"
                                                        class="btn-app-transaction change-safe-status btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                    <i class="lnr-cross-circle text-danger opacity-7 btn-icon-wrapper mb-2">
                                                    </i>
                                                    Kasayı Kapat
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
