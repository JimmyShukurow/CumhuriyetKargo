{{-- Alıcı Modalı --}}
<div class="modal fade bd-example-modal-lg" id="ModalCustomerDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
         {{-- Alici Cem --}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Müşteri Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyCustomerdDetails" class="modal-body">

                    {{-- CARD START --}}
                    <div style="overflow: hidden;" class="col-md-12">
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
                                            <h5 id="customerName" class="menu-header-title">###</h5>
                                            <h6 id="customerType" class="menu-header-subtitle">###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        @if($data['type'] == 'customers')
                                            <ul style="display: none;" class="list-group list-group-flush" id="deleteButton">
                                                <li class="p-0 list-group-item">
                                                    <div class="grid-menu grid-menu-2col">
                                                        <div class="no-gutters row">
                                                            <div class="col-sm-12">
                                                                <div class="p-1">
                                                                    <button
                                                                        id="deleteCustomer"
                                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-danger btn-outline-dark">
                                                                        <i class="lnr-trash text-dark opacity-7 btn-icon-wrapper mb-2"></i>
                                                                        Sil
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        @endif


                                        <div style="overflow-x: auto;" class="cont">
                                            @include('backend/customers/agency/modal_tables/reciever_table')
                                            @include('backend/customers/agency/modal_tables/sender_corporate')
                                            @include('backend/customers/agency/modal_tables/sender_personal')
                                        </div>
                                        <h4 class="mt-3">Kargo Geçmişi</h4>

                                        <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                             class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees"
                                                   class="Table30Padding table table-striped mt-3">
                                                <thead>
                                                <tr>
                                                    <th class="font-weight-bold">Fatura No</th>
                                                    <th class="font-weight-bold">Gönderici Ad Soyad</th>
                                                    <th class="font-weight-bold">Alıcı Ad Soyad</th>
                                                    <th class="font-weight-bold">Durum</th>
                                                    <th class="font-weight-bold">Kargo Tipi</th>
                                                    <th class="font-weight-bold">Tutar</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyUserTopTen">

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="p-0 list-group-item">
                                            <div class="grid-menu grid-menu-2col">
                                                <div class="no-gutters row">
                                                    <div class="col-sm-12">
                                                        <div class="p-1">
                                                            <button
                                                                id="passwordResetBtn"
                                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                                                <i class="lnr-redo text-dark opacity-7 btn-icon-wrapper mb-2"></i>
                                                                Tümünü Gör
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
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
