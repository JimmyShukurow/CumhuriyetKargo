<div class="form-group row">

    <div style="padding-top: 0; padding-bottom: 0;" class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsFirstDate">Marka:</label>
                    <input type="text" id="markaFilterAgency"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsLastDate">Model:</label>
                    <input type="text" id="modelFilterAgency"
                           class="form-control form-control-sm  niko-select-filter">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsAppNo">Plaka:</label>
                    <input type="text" id="plakaFilterAgency" class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsAppNo">Şöför Adı:</label>
                    <input type="text" id="soforAdiFilterAgency" class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsConfirm">Oluşturan:</label>
                    <input type="text" id="creatorFilterAgency" class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group position-relative">
                    <label for="agencyPaymentAppsConfirm">Onaylanma Durumu:</label>
                    <select type="text" id="confirmationFilterAgency" class="form-control form-control-sm">
                        <option value="">Seçiniz</option>
                        <option value="-1"> Reddedildi </option>
                        <option value="0"> Onay Bekliyor </option>
                        <option value="1"> Onaylandi </option>
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>


<table style="width: 100%; white-space: nowrap;" id="agencyBranchTransferCarsTable"
       class="table Table20Padding table-bordered table-hover">
    <thead>
    <tr>
        <th>MARKA</th>
        <th>MODEL</th>
        <th>PLAKA</th>
        <th>MÜHÜR SAYISI</th>
        <th>ŞOFÖR ADI</th>
        <th>ŞOFÖR İLETİŞİM</th>
        <th>OLUŞTURAN</th>
        <th>ŞUBE</th>
        <th>KAYIT TARİHİ</th>
        <th>ONAY DURUMU</th>
        <th>İŞLEMLER</th>
    </tr>
    </thead>
</table>


@include('backend.operation.tc_cars.agency_branch_tc_cars_js')

