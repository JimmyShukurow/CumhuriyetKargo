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
                                            <table style="white-space: nowrap;" id="AgencyCard"
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
                                                    <td class="static">Marka:</td>
                                                    <td class="modal-data" id="marka"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Model</td>
                                                    <td class="modal-data" id="model"></td>
                                                    <td class="static">Model Yılı</td>
                                                    <td class="modal-data" id="modelYili"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Araç Kapasitesi</td>
                                                    <td class="modal-data" id="aracKapasitesi"></td>
                                                    <td class="static">Tonaj</td>
                                                    <td class="modal-data" id="tonaj"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Araç Takip Sistemi</td>
                                                    <td class="modal-data" id="aracTakipSistemi"></td>
                                                    <td class="static">hat</td>
                                                    <td class="modal-data" id="hat"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Çıkış Aktarma</td>
                                                    <td class="modal-data" id="cikisAktarma"></td>
                                                    <td class="static">Varış Aktarma</td>
                                                    <td class="modal-data" id="varisAktarma"></td>
                                                </tr>
                                                <tr aria-rowspan="2">
                                                    <td class="static">Uğradığı Aktarmalar</td>
                                                    <td class="modal-data" colspan="3" id="ugradigiAktarmalar"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Muayene Baş.-Bit. Tarhi</td>
                                                    <td class="modal-data" id="muayeneBaslangicBitisTarihi"></td>
                                                    <td class="static">Muayene Bitimi Kalan Gün</td>
                                                    <td class="modal-data" id="muayeneBitimiKalanGun"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Sigorta Baş.-Bit. Tarhi</td>
                                                    <td class="modal-data" id="sigortaBaslangicBitisTarihi"></td>
                                                    <td class="static">Sigorta Bitimi Kalan Gün</td>
                                                    <td class="modal-data" id="sigortaBitimiKalanGun"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Kayıt Tarihi</td>
                                                    <td class="modal-data" id="kayitTarihi"></td>
                                                    <td class="static"></td>
                                                    <td id=""></td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            {{-- HESAPLAMALAR --}}
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table mt-4 table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="4">Hesaplamalar
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Aylık Kira Bedeli</td>
                                                    <td class="modal-data" id="aylikKiraBedeli"></td>
                                                    <td class="static">Yakıt Oranı</td>
                                                    <td class="modal-data" id="yakitOrani"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Tur KM</td>
                                                    <td class="modal-data" id="turKm"></td>
                                                    <td class="static">Sefer KM</td>
                                                    <td class="modal-data" id="seferKM"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Aylık Yakıt</td>
                                                    <td class="modal-data" id="aylikYakit"></td>
                                                    <td class="static">KDV Hariç Hakediş</td>
                                                    <td class="modal-data" id="kdvHaricHakedis"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">1 Sefer Kira Maliyeti</td>
                                                    <td class="modal-data" id="birSeferKiraMaliyeti"></td>
                                                    <td class="static">1 Sefer Yakıt Maliyeti</td>
                                                    <td class="modal-data" id="birSeferYakitMaliyeti"></td>
                                                </tr>


                                                <tr>
                                                    <td class="static">Sefer Maliyeti</td>
                                                    <td class="modal-data" id="seferMaliyeti"></td>
                                                    <td class="static">Hakediş + Mazot</td>
                                                    <td class="modal-data" id="hakedisArtiMazot"></td>
                                                </tr>

                                                </tbody>
                                            </table>

                                            {{-- ŞOFÖR İLETİŞİM --}}
                                            <table style="white-space: nowrap" id="AgencyCard"
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
                                                    <td class="static">Şoför İletişim</td>
                                                    <td class="modal-data" id="soforIletisim"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Şoför Adres</td>
                                                    <td class="modal-data" colspan="3" id="soforAders"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Araç Sahibi Adı</td>
                                                    <td class="modal-data" id="aracSahibiAdi"></td>
                                                    <td class="static">Araç Sahibi İletişim</td>
                                                    <td class="modal-data" id="aracSahibiIletisim"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Araç Sahibi Adres</td>
                                                    <td class="modal-data" colspan="3" id="aracSahibiAders"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Araç Sahibi Yakını Adı</td>
                                                    <td class="modal-data" id="aracSahibiYakiniAdi"></td>
                                                    <td class="static">Araç Sahibi Yakını İletişim</td>
                                                    <td class="modal-data" id="aracSahibiYakiniIletisim"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Araç Sahibi Yakını Adres</td>
                                                    <td class="modal-data" colspan="3" id="aracSahibiYakiniAders"></td>
                                                </tr>

                                                </tbody>
                                            </table>

                                            {{-- TRAFİK SETİ --}}
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table mt-4 table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="6">Trafik Seti
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Stepne</td>
                                                    <td class="modal-data" id="stepne"></td>
                                                    <td class="static">Kriko</td>
                                                    <td class="modal-data" id="kriko"></td>
                                                    <td class="static">Zincir</td>
                                                    <td class="modal-data" id="Zincir"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Bijon Anahtarı</td>
                                                    <td class="modal-data" id="bijonAnahtari"></td>
                                                    <td class="static">Reflektör</td>
                                                    <td class="modal-data" id="reflektor"></td>
                                                    <td class="static">Yangın Tüpü</td>
                                                    <td class="modal-data" id="yanginTupu"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">İlk Yardım Çantası</td>
                                                    <td class="modal-data" id="ilkYardimCantasi"></td>
                                                    <td class="static">Seyyar Lamba</td>
                                                    <td class="modal-data" id="seyyarLamba"></td>
                                                    <td class="static">Çekme Halatı</td>
                                                    <td class="modal-data" id="cekmeHalati"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Giydirme</td>
                                                    <td class="modal-data" id="giydirme"></td>
                                                    <td class="static">Kör Nokta Uyarısı</td>
                                                    <td class="modal-data" id="korNoktaUyarisi"></td>
                                                    <td class="static">Hata Bildirim Hattı</td>
                                                    <td class="modal-data" id="hataBildirimHatti"></td>
                                                </tr>

                                                </tbody>
                                            </table>

                                            {{-- EVRAKLAR --}}
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table mt-4 table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="6">Evraklar</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Muayene Evrağı</td>
                                                    <td class="modal-data" id="muayeneEvragi"></td>
                                                    <td class="static">Sigorta Belgesi</td>
                                                    <td class="modal-data" id="sigortaBelgesi"></td>
                                                    <td class="static">Şoför Ehliyet</td>
                                                    <td class="modal-data" id="soforEhliyet"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Src Belgesi</td>
                                                    <td class="modal-data" id="srcBelgesi"></td>
                                                    <td class="static">Ruhsat Ekspertiz Raporu</td>
                                                    <td class="modal-data" id="ruhsatEkspertizRaporu"></td>
                                                    <td class="static">Taşıma Belgesi</td>
                                                    <td class="modal-data" id="tasimaBelgesi"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Şoför Adli Sicil Kaydı</td>
                                                    <td class="modal-data" id="soforAdliSicilKaydi"></td>
                                                    <td class="static">Araç Sahibi Sicil Kaydi</td>
                                                    <td class="modal-data" id="aracSahibiSicilKaydi"></td>
                                                    <td class="static">Şoför Yakını İkametgah Belgesi</td>
                                                    <td class="modal-data" id="soforYakiniIkametgahBelgesi"></td>
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