    <li class="p-0 list-group-item">
        <div class="grid-menu grid-menu-2col">
            <div class="no-gutters row">
                <div class="col-sm-4">
                    <div class="p-1">
                        <button
                            id="btnCargoCancel"
                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                            <i class="lnr-cross text-danger opacity-7 btn-icon-wrapper mb-2"> </i>
                            Kargo İptal Başvurusu
                        </button>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="p-1">
                        <a style="text-decoration: none;" id="PrintStatementOfResposibility"
                           target="_blank"
                           href="">
                            <button id=""
                                    class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary">
                                <i class="pe-7s-ribbon text-primary opacity-7 btn-icon-wrapper mb-2">
                                </i>
                                Sorumluluk Taahütnamesi
                            </button>
                        </a>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="p-1">
                        <button style="display: none;" id="btnCargoPrintBarcode"></button>
                        <button style="display: none;"
                                id="btnCargoPrintPartBarcode"></button>
                        <button
                            aria-haspopup="true" aria-expanded="false"
                            data-toggle="dropdown"
                            class="dropdown-toggle btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                            <i class="lnr-printer text-alternate opacity-7 btn-icon-wrapper mb-2"></i>
                            Barkod Yazdır
                        </button>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-hover-link dropdown-menu">
                            <h6 tabindex="-1" class="dropdown-header">Barkod Yazdır</h6>
                            <button style="display: none;" crypted-data=""
                                    id="printBarcodeAllPieces"></button>
                            <button type="button"
                                    onclick="clicker('#printBarcodeAllPieces')"
                                    tabindex="0" class="dropdown-item">
                                <i class="dropdown-icon pe-7s-news-paper print-all-barcodes"></i>
                                <span>Tüm Parçaları Yazdır</span>
                            </button>
                            @if(Auth::user()->role_id == "1")
                                <button onclick="clicker('#btnCargoPrintBarcode')"
                                        class="btn btn-primary btn-sm">Print
                                </button>
                            @endif
                            <button onclick="" type="button"
                                    tabindex="0" class="dropdown-item">
                                <i class="dropdown-icon lnr-file-empty"></i>
                                <span>Belirli Parçaları Yazdır (Özel)</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </li>
