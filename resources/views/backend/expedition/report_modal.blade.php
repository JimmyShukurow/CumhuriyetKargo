<!-- Large modal => Modal Report Details -->
<div class="modal fade bd-example-modal-lg" id="ModalReportDetails" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Tutanak Detayları </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="overflow-y: auto;overflow-x: hidden; max-height: 75vh;" id="ModalBodyUserDetail"
                 class="modal-body">

                {{--CARD START--}}
                <div class="col-md-12">
                    <div class="mb-3 profile-responsive card">
                        <div class="dropdown-menu-header">
                            <div class="dropdown-menu-header-inner bg-dark">
                                <div class="menu-header-image "
                                     style="background-image: url('/backend/assets/images/dropdown-header/abstract8.jpg');">
                                </div>
                                <div class="menu-header-content text-center">
                                    <div>
                                        <h5 id="titleReportTitleType" class="menu-header-title">###</h5>
                                        <h5 id="titleReportSerialNumber" class="menu-header-title">###</h5>
                                        <h6 style="color: #fff;" id="titleReportDate"
                                            class="menu-header-subtitle font-weight-bold">###/###</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">

                            <div class="main-card mb-12 card">
                                <div class="card-header"><i
                                        class="header-icon pe-7s-note2 icon-gradient bg-plum-plate"> </i>Tutanak
                                    Detayları
                                    <div class="btn-actions-pane-right">
                                        <div class="nav">
                                            <a data-toggle="tab" href="#tabCargoInfo"
                                               class="btn-pill btn-wide btn btn-outline-alternate btn-sm show active">Tutanak
                                                Bilgileri</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="tabCargoInfo" role="tabpanel">

                                            <div class="row mt-2 mb-2">
                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Tespit Eden Birim:</label>
                                                        <b style="display: block;"
                                                           class="text-primary font-weight-bold"
                                                           id="reportDetectingUnit"></b>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Düzenleyen:</label>
                                                        <b style="display: block;"
                                                           class="text-primary font-weight-bold"
                                                           id="reportDetectingUser"></b>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Tutanak Tutulan Birim Tipi:</label>
                                                        <b style="display: block;"
                                                           class="text-danger font-weight-bold"
                                                           id="reportRealReportedUnitType"></b>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Tutanak Tutulan (Hata Yapan) Birim:</label>
                                                        <b style="display: block;"
                                                           class="text-danger font-weight-bold"
                                                           id="reportReportedUnit"></b>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="CartHTF"
                                                           class="TableNoPadding table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" id="titleBranch" colspan="2">
                                                                HTF (Hasar Tespit Formu)
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td style="white-space: nowrap;" class="static">Fatura
                                                                Numarası:
                                                            </td>
                                                            <td style="text-decoration: underline; cursor: pointer;"
                                                                id="htfInvoiceNumber"
                                                                class="font-weight-bold cargo-invoice-number text-dark"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="white-space: nowrap;" class="static">İlgili
                                                                Parçalar:
                                                            </td>
                                                            <td id="htfCargoPieces"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="white-space: nowrap;" class="static">Hasar
                                                                Açıklaması:
                                                            </td>
                                                            <td id="htfDamageDescription"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="white-space: nowrap;" class="static">İçerik
                                                                Tespiti:
                                                            </td>
                                                            <td id="htfContentDetection"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="white-space: nowrap;" class="static">Hasar
                                                                Nedenleri:
                                                            </td>
                                                            <td id="htfDamageDetails"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="white-space: nowrap;" class="static">Yapılan
                                                                İşlemler:
                                                            </td>
                                                            <td id="htfTransactionDetails"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <table id="CartUTF"
                                                           class="TableNoPadding table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" id="titleBranch" colspan="2">
                                                                UTF (Uygunsuzluk Tespit Formu)
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td style="white-space: nowrap;" class="static pr-3">
                                                                Uygunsuzluk Nedenleri:
                                                            </td>
                                                            <td id="utfImproprietyDetails"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="white-space: nowrap;" class="static pr-3">
                                                                Uygunsuzluk Açıklaması:
                                                            </td>
                                                            <td id="utfImproprietyDescription"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>

                                            <div class="row mt-2 mb-2">
                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Tutanak Seri No:</label>
                                                        <b style="display: block;"
                                                           class="text-dark font-weight-bold"
                                                           id="reportReportSerialNumber"></b>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Onay Durumu:</label>
                                                        <b style="display: block;"
                                                           class="font-weight-bold"
                                                           id="reportReportConfirm"></b>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Onaylayan:</label>
                                                        <b style="display: block;"
                                                           class="text-primary font-weight-bold"
                                                           id="reportReportConfirmingUser"></b>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Onay Tarihi:</label>
                                                        <b style="display: block;"
                                                           class="text-primary font-weight-bold"
                                                           id="reportReportConfirmingDate"></b>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Tutanak Kayıt Tarihi:</label>
                                                        <b style="display: block;"
                                                           class="text-dark font-weight-bold"
                                                           id="reportReportCreatedAt"></b>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">İtiraz:</label>
                                                        <b style="display: block;"
                                                           class="text-danger font-weight-bold"
                                                           id="reportReportObjection"></b>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">İtiraz Eden Birim/Kişi:</label>
                                                        <b style="display: block;"
                                                           class="text-danger font-weight-bold"
                                                           id="reportReportObjecting"></b>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">İtiraz Tarihi:</label>
                                                        <b style="display: block;"
                                                           class="text-danger font-weight-bold"
                                                           id="reportReportObjectionDate"></b>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group position-relative">
                                                        <label for="">Savunma:</label>
                                                        <b style="display: block; text-decoration: underline; text-align: justify;"
                                                           class="text-alternate font-weight-bold p-2"
                                                           id="reportReportObjectionDefense"></b>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">

                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Görüş Bildirildi:</label>
                                                        <b style="display: block;"
                                                           class="text-dark font-weight-bold"
                                                           id="reportReportOpinion"></b>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Görüş Bildiren Birim/Kişi:</label>
                                                        <b style="display: block;"
                                                           class="text-dark font-weight-bold"
                                                           id="reportReportOpinionUser"></b>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group position-relative">
                                                        <label for="">Görüş Tarihi:</label>
                                                        <b style="display: block;"
                                                           class="text-dark font-weight-bold"
                                                           id="reportReportOpinionDate"></b>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group position-relative">
                                                        <label for="">Görüş:</label>
                                                        <b style="display: block; text-decoration: underline; text-align: justify;"
                                                           class="text-info font-weight-bold p-2"
                                                           id="reportReportOpinionText"></b>
                                                    </div>
                                                </div>

                                                @if(@$data['type'] == 'incoming')
                                                    <div class="col-md-12">
                                                        <button id="btnMakeAnObjection" style="width: 100%;"
                                                                data-id=""
                                                                class="mb-2 mr-2 p-3 btn-icon btn-shadow btn-outline-2x btn btn-outline-danger">
                                                            <i class="pe-7s-light btn-icon-wrapper"> </i>İtiraz Et!
                                                        </button>
                                                    </div>
                                                @elseif(@$data['type'] == 'outgoing')
                                                    <div class="col-md-12">
                                                        <button id="btnMakeAnOpinion" style="width: 100%;"
                                                                data-id=""
                                                                class="mb-2 mr-2 p-3 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary">
                                                            <i class="pe-7s-light btn-icon-wrapper"> </i>Görüş
                                                            Belirt!
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>

                                            <div style="overflow-y: auto; max-height: 400px; " class="cont">
                                                <table id="TableOfficialReportMovements"
                                                       class="TableNoPadding table table-bordered mt-2 table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th style="font-size: 1.2rem" class="text-center"
                                                            id="titleBranch" colspan="2">
                                                            Tutanak Hareketleri
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <thead>
                                                    <tr>
                                                        <th>Hareket</th>
                                                        <th>Tarih</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="tBodyOfficialReportMovements">
                                                    <tr>
                                                        <td colspan="2"
                                                            class="text-danger text-center font-weight-bold">Hareket
                                                            Bulunamadı
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
                {{--CARD END--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
