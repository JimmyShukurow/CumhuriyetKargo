<!-- Large modal - Agencies Details -->
<div class="modal fade bd-example-modal-lg" id="ModalAgencyDetail" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Acente Detayları</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="ModalModyAgencyDetail" style="max-height: 65vh; overflow-y: auto; overflow-x: hidden;"
                 class="modal-body">

                <li class="p-0 mb-3 list-group-item">
                    <div class="grid-menu grid-menu-2col">
                        <div class="no-gutters row">
                            <div class="col-sm-3">
                                <div class="p-1">
                                    <a id="linkOfEditAgency" target="popup" style="cursor: pointer;" onclick="">
                                        <button
                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-info">
                                            <i class="lnr-pencil text-info opacity-7 btn-icon-wrapper mb-2"></i>
                                            Düzenle
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="p-1">
                                    <button id="BtnRefreshAgencyDetail"
                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                        <i class="lnr-redo text-success opacity-7 btn-icon-wrapper mb-2"></i>
                                        Yenile
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="p-1">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                        <i class="lnr-lighter text-dark opacity-7 btn-icon-wrapper mb-2">
                                        </i>
                                        Kargo Geçmişine Git
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="p-1">
                                    <button id="btnDisableEnableAgency"
                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                        <i class="pe-7s-settings text-danger opacity-7 btn-icon-wrapper mb-2"></i>
                                        Acente Ayarları
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

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
                                        <h6 id="agencyCityDistrict" class="menu-header-subtitle">###/###</h6>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                    <div style="overflow-x: auto" class="cont">
                                        <table style="" id="AgencyCard"
                                               class="TableNoPadding table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th id="thMainTitle" class="text-center" colspan="2">Genel Merkez
                                                    Acente
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="static">İl İlçe</td>
                                                <td id="cityDistrict">İstanbul/Bağcılar</td>
                                            </tr>
                                            <tr>
                                                <td class="static">Mahalle</td>
                                                <td id="neighborhood">Mecidiye Köy</td>
                                            </tr>
                                            <tr>
                                                <td class="static">Adres</td>
                                                <td id="adress">Adres Satırı</td>
                                            </tr>
                                            <tr>
                                                <td class="static">Telefon(1)</td>
                                                <td data-inputmask="'mask': '(999) 999 99 99'" id="phone"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Telefon(2)</td>
                                                <td data-inputmask="'mask': '(999) 999 99 99'" id="phone2"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Telefon(3)</td>
                                                <td id="phone3"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Statü Açıklama</td>
                                                <td id="statusDescription"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Acente Geliştirme Sorumlusu</td>
                                                <td id="agencyDevelopmentOfficer">Zühra Orak</td>
                                            </tr>
                                            <tr>
                                                <td class="static">Maps Link (Koordinat):</td>
                                                <td id="agencyMapsLink"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">IP Adres:</td>
                                                <td class="font-weight-bold" id="agencyIpAddress"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Kargo Kesim İzni:</td>
                                                <td class="font-weight-bold" id="agencyPermissionOfCreateCargo"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Kasa Statü:</td>
                                                <td class="font-weight-bold" id="agencySafeStatus"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Kasa Statü Açıklama:</td>
                                                <td class="font-weight-bold" id="agencySafeStatusDescription"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Operasyon Statü:</td>
                                                <td class="font-weight-bold" id="agencyOperationStatus"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">Şube Kodu</td>
                                                <td id="agencyCode">021234</td>
                                            </tr>
                                            <tr>
                                                <td class="static">Kayıt Tarihi</td>
                                                <td id="regDate">535 427 68 24</td>
                                            </tr>
                                            <tr>
                                                <td class="static">Son Güncellenme Tarihi</td>
                                                <td id="updatedDate">535 427 68 24</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <h4 class="mt-3">Acente Kullanıcıları</h4>

                                    <div style="" class="cont">
                                        <table style="white-space: nowrap" id="TableEmployees"
                                               class="TableNoPadding table-bordered table table-striped mt-3">
                                            <thead>
                                            <tr>
                                                <th>Ad Soyad</th>
                                                <th>E-Posta</th>
                                                <th>Yetki</th>
                                                <th>İletişim</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tbodyEmployees">

                                            </tbody>
                                        </table>
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
