{{-- Large modal  => Calculate Desi --}}
<div style="overflow-y: auto;" class="modal fade bd-example-modal-lg unselectable" id="modalCalcDesi" tabindex="-1"
     role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog niko-modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSelectCustomerHead">Desi Hesapla</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodySelectCustomer" class="modal-body">
                {{-- CARD START --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <button style="width: 100%" id="btnDeleteAllPartDesiRow"
                                class="btn-icon btn-square btn btn-xs btn-danger mb-2">
                            <i class="lnr-cross-circle btn-icon-wrapper"> </i>Tümünü Sil
                        </button>
                    </div>

                    <div class="col-md-4 text-center">
                        <button style="width: 100%" id="btnAddNewPartDesiRow"
                                class="btn-icon btn-square btn btn-xs btn-alternate mb-2">
                            <i class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Parça
                        </button>
                    </div>

                    <div class="col-md-4">
                        <button style="width: 100%" id="btnAddMultiplePartDesiRow"
                                class="btn-icon btn-square btn btn-xs btn-primary mb-2">
                            <i class="lnr-plus-circle btn-icon-wrapper"> </i>Toplu Parça
                        </button>
                    </div>
                </div>

                <form method="post" action="" id="formPartDesiContainer">
                    <div style="max-height: 350px; overflow-y: auto; overflow-x: hidden"
                         class="mostly-customized-scrollbar">

                        <div class="cont">
                            <div id="partDesiContainer1"
                                 class="row partDesiContainer animate__animated animate__fadeInDown">

                                <div class="col-md-12">
                                    <div style="border-bottom: 1px solid lightslategray;"
                                         class="form-row">


                                        <div class="col-md-1">
                                            <div class="position-relative ">
                                                <label for=""></label>
                                            </div>

                                            <div class="input-group mb-1">
                                                <button style="margin: 9px auto;"
                                                        id="1"
                                                        class="destroyDesiRow btn-icon btn-icon-only btn-xs btn btn-danger">
                                                    <i class="lnr-cross btn-icon-wrapper"> </i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="position-relative ">
                                                <label for="partDesiEn1">En:</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input id="partDesiEn1" type="text" data="1"
                                                       class="form-control form-control-sm input-mask-trigger partDesiEn partDesiCalc validate-part-desi"
                                                       placeholder="0" name="partDesiEn1"
                                                       data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                       im-insert="true" style="text-align: right;">

                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="position-relative ">
                                                <label for="partDesiBoy1">Boy:</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input id="partDesiBoy1" type="text" data="1"
                                                       class="form-control form-control-sm input-mask-trigger partDesiBoy partDesiCalc validate-part-desi"
                                                       placeholder="0" name="partDesiBoy1"
                                                       data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                       im-insert="true" style="text-align: right;">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="position-relative ">
                                                <label for="partDesiYukseklik1">Yükseklik:</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input id="partDesiYukseklik1" data="1"
                                                       class="form-control form-control-sm input-mask-trigger partDesiYukseklik partDesiCalc validate-part-desi"
                                                       placeholder="0" name="partDesiYukseklik1"
                                                       data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                       im-insert="true" style="text-align: right;">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="position-relative ">
                                                <label for="partDesiAgirlik1">Ağırlık:</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input id="partDesiAgirlik1" data="1"
                                                       class="form-control form-control-sm input-mask-trigger partDesiAgirlik partDesiCalc validate-part-desi"
                                                       placeholder="0" name="partDesiAgirlik1"
                                                       data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0', 'max':999, 'min':0"
                                                       im-insert="true" style="text-align: right;">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="position-relative ">
                                                <label for="partTotalDesi1">Desi:</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input disabled type="number" value="0"
                                                       id="partTotalDesi1"
                                                       class="form-control no-spin text-center font-weight-bold text-success form-control-sm">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="position-relative ">
                                                <label for="partRealDesi1">Ü.E.
                                                    Ağr:</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input disabled type="number" value="0"
                                                       id="partRealDesi1"
                                                       class="form-control no-spin text-center font-weight-bold text-danger form-control-sm partRealDesi">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="position-relative">
                                                <label
                                                    for="partDesiHacim1">Hacim
                                                    (m<sup>3</sup>):</label>
                                            </div>
                                            <div class="input-group mb-1">
                                                <input id="partDesiHacim1" type="text" disabled
                                                       class="form-control form-control-sm partDesiHacim text-center text-dark font-weight-bold"
                                                       placeholder="0" value="0"
                                                       im-insert="true" style="text-align: right;">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12 text-center mt-3 font-weight-bold">

                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <td>Top. Parça Sayısı</td>
                                    <td>Top. Ücrete Esas Ağırlık</td>
                                    <td>Top. M<sup>3</sup></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <b id="hRowSummery" class="text-dark">1</b>
                                        <b class="text-dark">Adet Parça</b>
                                    </td>
                                    <td>
                                        <b id="hPartsTotalDesi" class="text-dark">0</b>
                                        <b class="text-dark"> Desi</b>
                                    </td>
                                    <td>
                                        <b id="hPartsTotalM3" class="text-dark">0</b>
                                        <b class="text-dark"> M<sup>3</sup></b>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="col-md-12">
                                <button id="btnCalculatePartDesi" style="width: 100%"
                                        class="p-3 ladda-button bg-ck mb-2 mr-2 btn btn-shadow btn-primary"
                                        data-style="slide-right">
                                    <span class="ladda-label">Hesapla</span>
                                    <span class="ladda-spinner"></span>
                                    <div class="ladda-progress" style="width: 0px;"></div>
                                </button>
                            </div>

                        </div>

                    </div>
                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
</div>
