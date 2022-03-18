<!-- Large modal => Modal Cargo Details -->
<div class="modal fade bd-example-modal-lg" id="ModalExpeditionDetails" tabindex="22" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Sefer Detayları</h5>
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
                                        <h6 id="titleExpeditionCarPlaque" class="menu-header-subtitle">###/###</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">

                            <div class="main-card mb-12 card">
                                <div class="card-header"><i
                                            class="header-icon pe-7s-box2 icon-gradient bg-plum-plate"> </i>Sefer
                                    Detayları
                                    <div class="btn-actions-pane-right">
                                        <div class="nav">
                                            <a data-toggle="tab" href="#tabExpeditionInfo"
                                               class="btn-pill btn-wide btn btn-outline-alternate btn-sm show active">Sefer
                                                Bilgileri</a>
                                            <a data-toggle="tab" href="#tabExpeditionCargoes"
                                               class="btn-pill btn-wide mr-1 ml-1 btn btn-outline-alternate btn-sm show ">Kargolar
                                                </a>
                                            <a data-toggle="tab" href="#tabExpeditionSeals"
                                               class="btn-pill btn-wide btn btn-outline-alternate btn-sm show"> Mühürler </a>
                                            <a data-toggle="tab" href="#tabExpeditionMovements"
                                               class="btn-pill ml-1 btn-wide btn btn-outline-alternate btn-sm show">Sefer Hareketleri</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <x-expedition.modal.tabs.sefer-bilgileri/>

                                        <x-expedition.modal.tabs.kargolar/>

                                        <x-expedition.modal.tabs.muhurler/>

                                        <x-expedition.modal.tabs.sefer-hareketleri/>
                                    </div>
                                </div>
                            </div>
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

<!-- Large modal => Modal Movements Detail -->
<div class="modal fade bd-example-modal-lg" id="ModalMovementsDetail" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Hareket Detayları</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyCargoMovementsDetails" style="overflow-x: hidden; max-height: 75vh;"
                 class="modal-body">

                <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                     class="cont">
                    <table style="white-space: nowrap" id="TableEmployees"
                           class="Table30Padding table table-striped mt-3">
                        <thead>
                        <tr>
                            <th>Durum</th>
                            <th>Bilgi</th>
                            <th>Parça Numarası</th>
                            <th>İşlem Zamanı</th>
                        </tr>
                        </thead>
                        <tbody id="tbodyCargoMovementDetails">
                        <tr>
                            <td colspan="4" class="text-center">Burda hiç veri yok.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

