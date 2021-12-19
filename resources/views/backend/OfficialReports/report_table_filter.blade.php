<div class="card mt-3">
    <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                class="header-icon pe-7s-note2 mr-3 text-muted opacity-6"> </i> {!! $params['title'] !!}
        </div>
        <div class="btn-actions-pane-right actions-icon-btn">
            <div class="btn-group dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i
                        class="pe-7s-menu btn-icon-wrapper"></i></button>
                <div tabindex="-1" role="menu" aria-hidden="true"
                     class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">

                    <div class="p-3 text-right">
                        <button id="btnClearFilter" class="mr-2 btn-shadow btn-sm btn btn-link">Filtreyi
                            Temizle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" id="search-form">
            <div class="row">

                <div class="col-md-2">
                    <label for="filterReportSerialNumber">Tutanak NO:</label>
                    <input type="text" data-inputmask="'mask': 'AA-999999'"
                           placeholder="__ ______" type="text" id="filterReportSerialNumber"
                           class="form-control input-mask-trigger form-control-sm niko-filter">
                </div>

                <div class="col-md-2">
                    <label for="filterConfirm">Onay:</label>
                    <select id="filterConfirm"
                            class="form-control form-control-sm niko-select-filter">
                        @if($params['type'] == 'reports')
                            <option selected value="1">Onaylandı</option>
                        @else
                            <option value="">Seçiniz</option>
                            <option selected value="0">Onay Bekliyor</option>
                            <option value="1">Onaylandı</option>
                            <option value="-1">Onaylanmadı</option>
                        @endif

                    </select>
                </div>


                <div class="col-md-2">
                    <label for="filterInvoiceNumber">Kargo - Fatura NO:</label>
                    <input type="text" data-inputmask="'mask': 'AA-999999'"
                           placeholder="__ ______" type="text" id="filterInvoiceNumber"
                           class="form-control input-mask-trigger form-control-sm niko-filter">
                </div>


                <div class="col-md-2">
                    <label for="filterReportType">Tutanak Tipi:</label>
                    <select id="filterReportType"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        <option value="HTF">HTF</option>
                        <option value="UTF">UTF</option>
                    </select>
                </div>


                <div class="col-md-2 mt-2">
                    <label for="filterStartDate">Başlangıç Tarih:</label>
                    <input type="datetime-local" id="filterStartDate" value="{{ date('Y-m-d') }}T00:00"
                           class="form-control form-control-sm  niko-select-filter">
                </div>

                <div class="col-md-2 mt-2">
                    <label for="filterFinishDate">Bitiş Tarihi:</label>
                    <input type="datetime-local" id="filterFinishDate" value="{{ date('Y-m-d') }}T23:59"
                           class="form-control form-control-sm  niko-select-filter">
                </div>

                <div id="column-agency" class="col-md-2 mt-2">
                    <div class="form-group position-relative">
                        <label for="filterSelectReportedAgency">Şube Seçin:</label>
                        <select style="width:100%;" name="select_reported_agency"
                                class="form-control form-control-sm reported-units"
                                id="filterSelectReportedAgency">
                            <option value="">Seçiniz</option>
                            @foreach($agencies as $key)
                                <option
                                    value="{{$key->id}}">{{$key->agency_name . ' ŞUBE'}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="column-tc" class="col-md-2 mt-2">
                    <div class="form-group position-relative">
                        <label for="filterSelectReportedTc">Transfer Merkezi Seçin:</label>
                        <select style="width:100%;" name="select_reported_tc"
                                class="form-control form-control-sm reported-units"
                                id="filterSelectReportedTc">
                            <option value="">Seçiniz</option>
                            @foreach($tc as $key)
                                <option value="{{$key->id}}">{{$key->tc_name . ' TRM.'}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="col-md-2">
                    <label for="filterDetectingUser">Düzenleyen:</label>
                    <input type="text" id="filterDetectingUser"
                           class="form-control niko-filter form-control-sm">
                </div>

                <div class="col-md-2">
                    <div class="position-relative form-group">
                        <label for="filterDescription" class="">Açıklama:</label>
                        <input type="text" id="filterDescription"
                               class="form-control niko-filter form-control-sm">
                    </div>
                </div>
            </div>

            <div class="row pt-2">
                <div class="col-md-2">
                    <label for="filterByDate">Tarihe göre ara</label>
                    <input type="checkbox" id="filterByDate" name="filterByDate" class="niko-filter">
                </div>
                @if($params['type'] == 'manage')
                    <div class="col-md-3">
                        <button id="btnConfirmSelected" confirm="yes" type="button"
                                class="btn-confirm-report btn btn-success">Seçilenleri Onayla
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" confirm="no" class="btn-confirm-report btn btn-danger">Seçilenleri
                            Onaylama
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" confirm="wait" class="btn-confirm-report btn btn-primary">Seçilenleri
                            Beklet
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">

        <table style="white-space: nowrap;" id="OfficialReportsTable"
               class="align-middle mb-0 table Table20Padding table-bordered table-striped table-hover NikolasDataTable">
            <thead>
            <tr>
                <th></th>
                <th class="check"></th>
                <th>Tutanak No</th>
                <th>Tutanak Tipi</th>
                <th>Tespit Eden Birim</th>
                <th>Düzenleyen</th>
                <th>Tutanak Tutulan Birim</th>
                <th>Açıklama</th>
                <th>Onay</th>
                <th>İtiraz</th>
                <th>Kayıt Tarihi</th>
                <th>Detay</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th class="check"></th>
                <th>Tutanak No</th>
                <th>Tutanak Tipi</th>
                <th>Tespit Eden Birim</th>
                <th>Düzenleyen</th>
                <th>Tutanak Tutulan Birim</th>
                <th>Açıklama</th>
                <th>Onay</th>
                <th>İtiraz</th>
                <th>Kayıt Tarihi</th>
                <th>Detay</th>
            </tr>
            </tfoot>
        </table>

    </div>
</div>
