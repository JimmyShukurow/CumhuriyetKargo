    <!-- Large modal -->
     <div class="modal fade bd-example-modal-lg" id="ModalCarDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Araç Detayları</h5>
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
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract10.jpg');">
                                    </div>
                                    <div class="menu-header-content">
                                        <h5 id="plaka" class="menu-header-title text-center">34HV4186</h5>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: auto" class="cont">

                                            {{-- ARAÇ DETAYLARI --}}
                                            <table id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="4">Araç
                                                        Detayları
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Plaka:</td>
                                                    <td class="modal-data" id="tdPlaka"></td>
                                                    <td class="static">Baglı Birim:</td>
                                                    <td class="modal-data" id="branch"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Ekleyen Birim</td>
                                                    <td class="modal-data" id="creator-agency-name"></td>
                                                    <td class="static">Araç Tipip</td>
                                                    <td class="modal-data" id="car_type"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Kayıt Tarihi</td>
                                                    <td class="modal-data" id="created_at"></td>
                                                    <td class="static">Onaylayan</td>
                                                    <td class="modal-data" id="confirmer"></td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            {{-- ŞOFÖR İLETİŞİM --}}
                                            <table  id="AgencyCard"
                                                   class="TableNoPadding table mt-4 table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="4">Şoför
                                                        İletişim
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Şoför Adı</td>
                                                    <td class="modal-data" id="soforAdi"></td>
                                                    <td class="static">Şoför Telefon</td>
                                                    <td class="modal-data" id="soforIletisim"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Şoför Adres</td>
                                                    <td class="modal-data" colspan="3" id="soforAders"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </li>

                                <ul class="list-group list-group-flush">
                                    <li class="p-0 list-group-item">
                                        <div class="grid-menu grid-menu-2col">
                                            <div class="no-gutters row">

                                                <div class="col-sm-12">
                                                    <div class="p-1">
                                                        <button id="btnPrintModal"
                                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary">
                                                            <i class="lnr-printer text-primary opacity-7 btn-icon-wrapper mb-2">
                                                            </i>
                                                            Yazdır
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </li>
                                </ul>

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
