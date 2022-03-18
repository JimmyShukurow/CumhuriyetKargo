<!-- Large modal -->
<div class="modal fade bd-example-modal-lg" id="ModalCustomerDetail" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Cari Detayları</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyUserDetail" class="modal-body">

                {{-- CARD START --}}
                <div class="col-md-12">
                    <div class="mb-3 profile-responsive card">
                        <div class="dropdown-menu-header">
                            <div class="dropdown-menu-header-inner bg-dark">
                                <div class="menu-header-image "
                                     style="background-image: url('/backend/assets/images/dropdown-header/abstract5.jpg');">
                                </div>
                                <div class="menu-header-content btn-pane-right">
                                    <div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                        <div class="avatar-icon rounded">
                                            <img src="/backend/assets/images/ck-ico-white.png" alt="Avatar 5">
                                        </div>
                                    </div>
                                    <div>
                                        <h5 id="agencyName" class="menu-header-title">###</h5>
                                        <h6 id="agencyCityDistrict" class="menu-header-subtitle">###/###</h6>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="p-0 list-group-item">
                                <div class="grid-menu grid-menu-2col">
                                    <div class="no-gutters row">

                                        <div class="col-sm-3">
                                            <div class="p-1">
                                                <button id="btnEnabledDisabled"
                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                    <i class="lnr-construction text-danger opacity-7 btn-icon-wrapper mb-2">
                                                    </i>
                                                    Hesabı Aktif/Pasif Yap
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="p-1">
                                                <button id="btnPrintModal"
                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary">
                                                    <i class="lnr-printer text-primary opacity-7 btn-icon-wrapper mb-2">
                                                    </i>
                                                    Yazdır
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="p-1">
                                                <button id="btnCurrentPerformanceReport"
                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                                                    <i class="pe-7s-timer text-alternate opacity-7 btn-icon-wrapper mb-2">
                                                    </i>
                                                    Cari Performans Raporu
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="p-1">
                                                <a style="text-decoration: none;" id="PrintCurrentContract"
                                                   target="_blank"
                                                   href="">
                                                    <button id=""
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                                        <i class="pe-7s-ribbon text-dark opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Sözleşme Yazdır
                                                    </button>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                    <div style="overflow-x: auto" class="cont">
                                        <table style="white-space: nowrap" id="InfoCard"
                                               class="TableNoPadding table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center" id="titleBranch" colspan="2">Özet</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr>
                                                <td class="static">Onay</td>
                                                <td id="currentConfirmed"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Kategori</td>
                                                <td id="currentCategory"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Cari Kodu</td>
                                                <td id="modalCurrentCode"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Ad Soyad/Firma</td>
                                                <td id="nameSurnameCompany"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Bağlı Şube</td>
                                                <td id="currentAgency"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Vergi Dairesi</td>
                                                <td id="taxOffice"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">TCKN/VKN</td>
                                                <td id="tcknVkn"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Telefon</td>
                                                <td id="phone"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">İl/İlçe</td>
                                                <td id="cityDistrict"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Adress</td>
                                                <td id="address"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">GSM</td>
                                                <td id="gsm"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">GSM (2)</td>
                                                <td id="gsm2"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Telefon (2)</td>
                                                <td id="phone2"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">E-Posta</td>
                                                <td id="email"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Website</td>
                                                <td id="website"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Sevk İl/İlce</td>
                                                <td id="dispatchCityDistrict"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Sevk Adres</td>
                                                <td id="dispatchAddress"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">IBAN</td>
                                                <td id="iban"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Hesap Sahibi</td>
                                                <td id="bankOwner"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Sözleşme Başlangıç Tarihi</td>
                                                <td id="contractStartDate"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Sözleşme Bitiş Tarihi</td>
                                                <td id="contractEndDate"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Referans</td>
                                                <td id="reference"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Oluşturan Kullanıcı</td>
                                                <td id="currentCreatorUser"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Kayıt Tarihi</td>
                                                <td id="regDate"></td>
                                            </tr>

                                            <tr>
                                                <td class="static">Mobil Bölge Ücreti
                                                    Uygulansın mı
                                                </td>
                                                <td id="mbStatus"></td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <table style="white-space: nowrap" id="InfoCard"
                                           class="TableNoPadding table table-bordered table-striped mt-3 InfoCard">
                                        <thead>
                                        <tr>
                                            <th class="text-center" id="" colspan="4">Anlaşmalı Standart Fiyatlar
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr>
                                            <td class="static">Dosya</td>
                                            <td class="font-weight-bold" id="currentFilePrice"></td>
                                            <td class="static">Mi</td>
                                            <td class="font-weight-bold" id="currentMiPrice"></td>
                                        </tr>

                                        <tr>
                                            <td class="static">1-5 Desi</td>
                                            <td class="font-weight-bold" id="current1_5Desi"></td>
                                            <td class="static">6-10 Desi</td>
                                            <td class="font-weight-bold" id="current6_10Desi"></td>
                                        </tr>
                                        <tr>

                                        </tr>
                                        <tr>
                                            <td class="static">11-15 Desi</td>
                                            <td class="font-weight-bold" id="current11_15Desi"></td>
                                            <td class="static">16-20 Desi</td>
                                            <td class="font-weight-bold" id="current16_20Desi"></td>
                                        </tr>

                                        <tr>
                                            <td class="static">21-25 Desi</td>
                                            <td class="font-weight-bold" id="current21_25Desi"></td>
                                            <td class="static">26-30 Desi</td>
                                            <td class="font-weight-bold" id="current26_30Desi"></td>
                                        </tr>

                                        <tr>
                                            <td class="static">Üstü Desi</td>
                                            <td class="font-weight-bold" id="currentAmountOfIncrease"></td>
                                            <td class="static">Tahsilat Bedeli (0-200₺)</td>
                                            <td class="font-weight-bold" id="currentCollectPrice"></td>
                                        </tr>

                                        <tr>
                                            <td class="static">Tahsilat Bedeli Oranı (200₺ Üstü)</td>
                                            <td class="font-weight-bold" id="collectAmountOfIncrease"></td>
                                        </tr>
                                        </tbody>
                                    </table>


                                    <div id="divConfirmCurrent" class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">

                                            <div class="col-sm-12">
                                                <div class="p-1">
                                                    <button id="btnConfirmCurrent"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                                        <i class="lnr-checkmark-circle text-success opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Cari Hesabını Onayla
                                                    </button>
                                                </div>
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
