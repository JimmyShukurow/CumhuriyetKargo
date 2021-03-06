<div style="padding: 0" class="col-sm-12 col-lg-12">
    <div class="mb-3 card">
        <div class="p-0 d-block card-footer">
            <div class="grid-menu grid-menu-3col">
                <div class="no-gutters row">

                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <button id="btnRefreshMainCargoPage"
                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                            <i class="lnr-sync text-success opacity-7 btn-icon-wrapper mb-2"> </i>
                            Yenile
                        </button>
                    </div>

                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <a style="text-decoration: none;" href="{{route('mainCargo.newCargo')}}">
                            <button id="btnNewCargo"
                                    class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary">
                                <i class="lnr-plus-circle text-primary opacity-7 btn-icon-wrapper mb-2"> </i>
                                Yeni Fatura
                            </button>
                        </a>
                    </div>

                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger"
                           href="{{route('delivery.index')}}" target="_blank">
                            <i class="fas fa-check text-danger opacity-7 btn-icon-wrapper mb-2"> </i>
                            Teslimat
                        </a>
                    </div>

                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <button id="btnExportExcel"
                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning">
                            <i class="fas fa-file-excel text-warning opacity-7 btn-icon-wrapper mb-2"> </i>
                            Excel
                        </button>
                    </div>

                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <button id="btnPrintSelectedBarcodeWCKGBarcoder" crypted-data="{{$cryptedData}}"
                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                            <i class="lnr-printer text-alternate opacity-7 btn-icon-wrapper mb-2"> </i>
                            Yazd??r
                            @if(Auth::user()->role_id == "1")
                                <a id="btnPrintSelectedBarcode" href="#">Print</a>
                            @endif
                        </button>
                    </div>

                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <button
                            aria-haspopup="true" aria-expanded="false"
                            data-toggle="dropdown"
                            class="dropdown-toggle  btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-info">
                            <i class="pe-7s-note2 text-info opacity-7 btn-icon-wrapper mb-2"> </i>
                            Tutanak
                        </button>

                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-hover-link dropdown-menu">
                            <h6 tabindex="-1" class="dropdown-header">Tutanak Olu??tur</h6>

                            <a href="{{route('OfficialReport.createHTF')}}"
                               target="popup"
                               onclick="window.open('{{route('OfficialReport.createHTF')}}','popup','width=700,height=700'); return false;">
                                <button type="button"
                                        tabindex="0" class="dropdown-item">
                                    <i class="dropdown-icon pe-7s-news-paper print-all-barcodes"></i>
                                    <span>HTF (Hasar Tespit Tutana????)</span>
                                </button>
                            </a>

                            <a href="{{route('OfficialReport.createUTF')}}"
                               target="popup"
                               onclick="window.open('{{route('OfficialReport.createUTF')}}','popup','width=700,height=700'); return false;">
                                <button type="button" tabindex="0" class="dropdown-item">
                                    <i class="dropdown-icon lnr-file-empty"></i>
                                    <span>UTF (Uygunsuzluk Tespit Tutana????)</span>
                                </button>
                            </a>

                            <a href="{{route('OfficialReport.index')}}">
                                <button type="button" tabindex="0" class="dropdown-item">
                                    <i class="dropdown-icon pe-7s-search"></i>
                                    <span>Tutanak Sorgula</span>
                                </button>
                            </a>
                        </div>
                    </div>


                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger"
                           href="{{route('cargoBags.agencyIndex')}}">
                            <i class="pe-7s-check text-danger opacity-7 btn-icon-wrapper mb-2"> </i>
                            Manifesto
                        </a>
                    </div>

                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success"
                           href="{{route('customers.index')}}">
                            <i class="lnr-briefcase text-success opacity-7 btn-icon-wrapper mb-2"> </i>
                            M????teriler
                        </a>
                    </div>

                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary"
                           href="{{route('safe.agency.index')}}">
                            <i class="pe-7s-safe text-primary opacity-7 btn-icon-wrapper mb-2"> </i>
                            Kasa
                        </a>
                    </div>

                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning"
                           href="{{route('mainCargo.search')}}">
                            <i class="pe-7s-box2 text-warning opacity-7 btn-icon-wrapper mb-2"> </i>
                            Sorgula
                        </a>
                    </div>


                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <button
                            aria-haspopup="true" aria-expanded="false"
                            data-toggle="dropdown"
                            class="dropdown-toggle  btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                            <i class="fa fa-bus text-alternate opacity-7 btn-icon-wrapper mb-2"> </i>
                            Sefer
                        </button>

                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-hover-link dropdown-menu">
                            <h6 tabindex="-1" class="dropdown-header">Sefer ????lemleri</h6>

                            <a href="{{route('expedition.incoming')}}">
                                <button type="button"
                                        tabindex="0" class="dropdown-item">
                                    <i class="dropdown-icon pe-7s-news-paper print-all-barcodes"></i>
                                    <span>Gelen Sefer</span>
                                </button>
                            </a>

                            <a href="{{route('expedition.outGoing')}}">
                                <button type="button" tabindex="0" class="dropdown-item">
                                    <i class="dropdown-icon lnr-file-empty"></i>
                                    <span>Giden Sefer</span>
                                </button>
                            </a>

                            <a href="{{route('expedition.all')}}">
                                <button type="button" tabindex="0" class="dropdown-item">
                                    <i class="dropdown-icon pe-7s-search"></i>
                                    <span>Sefer Sorgula</span>
                                </button>
                            </a>

                            <a href="{{route('expedition.create')}}">
                                <button type="button" tabindex="0" class="dropdown-item">
                                    <i class="dropdown-icon pe-7s-plus"></i>
                                    <span>Sefer Olu??tur</span>
                                </button>
                            </a>
                        </div>
                    </div>


                    <div class="p-0 col-lg-1 col-sm-4 col-xs-6">
                        <a class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark"
                           href="javascript:void(0)">
                            <i class="pe-7s-timer text-dark opacity-7 btn-icon-wrapper mb-2"> </i>
                            Module
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
