<!-- Large modal => Modal Cargo Details -->
<div class="modal fade bd-example-modal-lg" id="ModalCargoDetails" tabindex="22" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Kargo Detayları</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="overflow-y: auto;overflow-x: hidden; max-height: 75vh;" id="ModalBodyUserDetail"
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
                                        <h5 id="titleTrackingNo" class="menu-header-title">###</h5>
                                        <h6 id="titleCargoInvoiceNumber" class="menu-header-subtitle">###/###</h6>
                                        <h5 id="titleCargoStatus"
                                            class="menu-header-subtitle text-warning"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">

                            <div class="main-card mb-12 card">
                                <div class="card-header"><i
                                        class="header-icon pe-7s-box2 icon-gradient bg-plum-plate"> </i>Kargo
                                    Detayları
                                    <div class="btn-actions-pane-right">
                                        <div class="nav">
                                            <a data-toggle="tab" href="#tabCargoInfo"
                                               class="btn-pill btn-wide btn btn-outline-alternate btn-sm show active">
                                                Kargo Bilgileri
                                            </a>
                                            <a data-toggle="tab" href="#tabCargoMovements"
                                               class="btn-pill btn-wide mr-1 ml-1 btn btn-outline-alternate btn-sm show ">
                                                Kargo Hareketleri
                                            </a>
                                            <a data-toggle="tab" href="#tabCargoSMS"
                                               class="btn-pill btn-wide btn btn-outline-alternate btn-sm show">
                                                SMS
                                            </a>
                                            <a data-toggle="tab" href="#tabCargoDetail"
                                               class="btn-pill ml-1 btn-wide btn btn-outline-alternate btn-sm show">
                                                Detay
                                            </a>
                                            <a data-toggle="tab" href="#tabCargoOfficialReports"
                                               class="btn-pill ml-1 btn-wide btn btn-outline-alternate btn-sm show">
                                                Tutanaklar
                                            </a>
                                            <a data-toggle="tab" href="#tabCargoDeliveryAndTransfer"
                                               class="btn-pill ml-1 btn-wide btn btn-outline-alternate btn-sm show">
                                                Teslimat & Devir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="tabCargoInfo" role="tabpanel">
                                            @include('backend.main_cargo.cargo_details.includes.tabs.tab_cargo_info')
                                        </div>
                                        <div class="tab-pane show" id="tabCargoMovements" role="tabpanel">
                                            @include('backend.main_cargo.cargo_details.includes.tabs.tab_cargo_movements')
                                        </div>
                                        <div class="tab-pane show" id="tabCargoSMS" role="tabpanel">
                                            @include('backend.main_cargo.cargo_details.includes.tabs.tab_cargo_sms')
                                        </div>
                                        <div class="tab-pane show" id="tabCargoDetail" role="tabpanel">
                                            @include('backend.main_cargo.cargo_details.includes.tabs.tab_cargo_detail')
                                        </div>
                                        <div class="tab-pane show" id="tabCargoOfficialReports" role="tabpanel">
                                            @include('backend.main_cargo.cargo_details.includes.tabs.tab_cargo_official_reports')
                                        </div>
                                        <div class="tab-pane show" id="tabCargoDeliveryAndTransfer" role="tabpanel">
                                            @include('backend.main_cargo.cargo_details.includes.tabs.tab_cargo_delivery_and_transfer')
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if(@$data['type'] == 'main_cargo' || @$data['type'] == 'search_cargo_gm')
                                @include('backend.main_cargo.cargo_details.includes.buttons.buttons_main_cargo')
                            @endif


                            @if(@$data['type'] == 'admin_cancel_cargo')
                                @include('backend.main_cargo.cargo_details.includes.buttons.buttons_admin_cancel_cargo')
                            @endif

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

@include('backend.main_cargo.cargo_details.modals.modal_movement_detail')

@if(@$data['type'] == 'main_cargo')
    @include('backend.main_cargo.cargo_details.modals.modal_show_barcode')
@endif


@if(@$data['type'] == 'main_cargo')
    @include('backend.main_cargo.cargo_details.modals.modal_cargo_cancel_form')
@endif
