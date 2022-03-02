<div class="form-group row">

    <div style="padding-top: 0; padding-bottom: 0;" class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsFirstDate">İlk Tarih:</label>
                    <input type="date" id="agencyPaymentAppsFirstDate" value="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsLastDate">Son Tarih:</label>
                    <input type="date" id="agencyPaymentAppsLastDate" value="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsAppNo">Başvuru No:</label>
                    <input type="text" id="agencyPaymentAppsAppNo" class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsAgency">Şube:</label>
                    <select style="width: 100%;" class="form-control-sm form-control" name=""
                            id="agencyPaymentAppsAgency">
                        <option value="">Seçiniz</option>
                        @foreach($data['agencies'] as $key)
                            <option value="{{$key->id}}">{{$key->agency_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsConfirm">Onay:</label>
                    <select class="form-control-sm form-control" name="" id="agencyPaymentAppsConfirm">
                        <option value="">Seçiniz</option>
                        <option value="0">Onay Bekliyor</option>
                        <option value="1">Onaylandı</option>
                        <option value="-1">Reddedildi</option>
                    </select>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsPaymentChannel">Ödeme Kanalı:</label>
                    <select class="form-control-sm form-control" name="" id="agencyPaymentAppsPaymentChannel">
                        <option value="">Seçiniz</option>
                        @foreach($data['payment_channels'] as $key)
                            <option value="{{$key->payment_channel}}">{{$key->payment_channel}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>


<table style="width: 100%; white-space: nowrap;" id="tableAgencyPaymentApps"
       class="table Table20Padding table-bordered table-hover">
    <thead>
    <tr>
        <th>B.No</th>
        <th>Şube</th>
        <th>Başvuru Yapan</th>
        <th>Ödenen</th>
        <th>Onyln. Ödeme</th>
        <th>Ödeme Kanalı</th>
        <th>Ekler</th>
        <th>Açıklama</th>
        <th>Para Birimi</th>
        <th>Onay</th>
        <th>Onaylayan</th>
        <th>Onay Tarihi</th>
        <th>Kayıt Tarihi</th>
        <th>İşlem</th>
    </tr>
    </thead>
</table>

<div id="AgencyPaymentAppRow" class="row">
    <div class="col-md-6 col-lg-3">
        <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <h6 class="widget-subheading">Tüm Zamanlar</h6>
                    <div class="widget-chart-flex">
                        <div class="widget-numbers mb-0 w-100">
                            <div class="widget-chart-flex">
                                <div class="fsize-4">
                                    <small class="opacity-5"><i class="lnr-clock text-primary"></i></small>
                                    <span id="WaitingApp">0</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                        <span class="text-primary font-weight-bold pl-2">Onay Bekleyenler</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <h6 class="widget-subheading">Tüm Zamanlar</h6>
                    <div class="widget-chart-flex">
                        <div class="widget-numbers mb-0 w-100">
                            <div class="widget-chart-flex">
                                <div class="fsize-4">
                                    <small class="opacity-5"><i class="lnr-checkmark-circle text-success"></i></small>
                                    <span id="SuccessApp">0</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                        <span class="text-success font-weight-bold pl-2">Onaylananlar</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <h6 class="widget-subheading">Tüm Zamanlar</h6>
                    <div class="widget-chart-flex">
                        <div class="widget-numbers mb-0 w-100">
                            <div class="widget-chart-flex">
                                <div class="fsize-4">
                                    <small class="opacity-5"><i class="lnr-cross-circle text-danger"></i></small>
                                    <span id="RejectApp">0</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                        <span class="text-danger font-weight-bold pl-2">Reddedilenler</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <h6 class="widget-subheading">Tüm Zamanlar</h6>
                    <div class="widget-chart-flex">
                        <div class="widget-numbers mb-0 w-100">
                            <div class="widget-chart-flex">
                                <div class="fsize-4">
                                    <small class="opacity-5"><i class="lnr-cloud text-info"></i></small>
                                    <span id="AllApp">0</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                        <span class="text-info font-weight-bold pl-2">Tümü</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="/backend/assets/scripts/safe/general/agency-payment-apps.js"></script>



