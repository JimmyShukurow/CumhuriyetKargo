<div class="card mb-3">
    <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                class="header-icon lnr-store mr-3 text-muted opacity-6"> </i>Tüm Acenteler
        </div>

    </div>

    <div class="card-body">
        <form method="POST" id="search-form">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_City">İl:</label>
                        <select id="filter_City" class="form-control form-control-sm">
                            <option value="">İl Seçiniz</option>
                            @foreach($data['cities'] as $key)
                                <option value="{{$key->id}}">{{$key->city_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_District">İlçe:</label>
                        <select disabled id="filter_District" class="form-control form-control-sm">
                            <option value="">İlçe Seçiniz</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_RegionalDirectorate">Bağlı Olduğu Bölge Mdr.:</label>
                        <select id="filter_RegionalDirectorate" class="form-control form-control-sm">
                            <option value="">Seçiniz</option>
                            @foreach($data['regional_directorates'] as $key)
                                <option value="{{$key->id}}">{{$key->name . ' BM.'}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_TransshipmentCenter">Bağlı Olduğu TRM.:</label>
                        <select id="filter_TransshipmentCenter" class="form-control form-control-sm">
                            <option value="">Seçiniz</option>
                            @foreach($data['transshipment_centers'] as $key)
                                <option value="{{$key->id}}">{{$key->tc_name . ' TRM.'}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_AgencyCode">Şube Kodu:</label>
                        <input id="filter_AgencyCode" data-inputmask="'mask': '9999'"
                               placeholder="____" type="text" value=""
                               class="form-control form-control-sm input-mask-trigger" im-insert="true">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_AgencyName">Şube Adı:</label>
                        <input type="text" class="form-control-sm form-control" id="filter_AgencyName">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_NameSurname">Şube Sahibi:</label>
                        <input type="text" class="form-control-sm form-control" id="filter_NameSurname">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_Status">Statü:</label>
                        <select id="filter_Status" class="form-control form-control-sm">
                            <option value="">Seçiniz</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Pasif">Pasif</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_phone">Telefon:</label>
                        <input name="phone" id="filter_phone"
                               data-inputmask="'mask': '(999) 999 99 99'"
                               placeholder="(___) ___ __ __" type="text" value=""
                               class="form-control form-control-sm input-mask-trigger" im-insert="true">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_phone2">Telefon 2:</label>
                        <input name="phone" id="filter_phone2"
                               data-inputmask="'mask': '(999) 999 99 99'"
                               placeholder="(___) ___ __ __" type="text" value=""
                               class="form-control form-control-sm input-mask-trigger" im-insert="true">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_phone3">Telefon 3:</label>
                        <input name="phone" id="filter_phone3"
                               data-inputmask="'mask': '(999) 999 99 99'"
                               placeholder="(___) ___ __ __" type="text" value=""
                               class="form-control form-control-sm input-mask-trigger" im-insert="true">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_MapsLink">Maps Link:</label>
                        <select id="filter_MapsLink" class="form-control form-control-sm">
                            <option value="">Seçiniz</option>
                            <option value="Girildi">Girildi</option>
                            <option value="Girilmedi">Girilmedi</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_address">Adres Biglsi:</label>
                        <select id="filter_address" class="form-control form-control-sm">
                            <option value="">Seçiniz</option>
                            <option value="Girildi">Girildi</option>
                            <option value="Girilmedi">Girilmedi</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_IpAddress">IP Adresi:</label>
                        <input id="filter_IpAddress"
                               class="form-control form-control-sm font-weight-bold input-mask-trigger"
                               value="" id="ip"
                               data-inputmask="'alias': 'ip'"
                               im-insert="true">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_IPAddressInfo">IP Adresi Biglsi:</label>
                        <select id="filter_IPAddressInfo" class="form-control form-control-sm">
                            <option value="">Seçiniz</option>
                            <option value="Girildi">Girildi</option>
                            <option value="Girilmedi">Girilmedi</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_PermissionOfCreateCargo">Kargo Kesim İzini:</label>
                        <select id="filter_PermissionOfCreateCargo" class="form-control form-control-sm">
                            <option value="">Seçiniz</option>
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_OperationStatus">Operasyon:</label>
                        <select id="filter_OperationStatus" class="form-control form-control-sm">
                            <option value="">Seçiniz</option>
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group position-relative">
                        <label for="filter_SafeStatus">Kasa:</label>
                        <select id="filter_SafeStatus" class="form-control form-control-sm">
                            <option value="">Seçiniz</option>
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>


            </div>
            <div class="row text-center mt-3">
                <div class="col-md-12 text-center">
                    <button id="btn-submit" type="submit" class="btn btn-primary ">Ara</button>
                    <input type="reset" class="btn btn-secondary">
                </div>
            </div>
        </form>
    </div>

    <div class="card-body">

        <table id="AgenciesTable"
               class="align-middle mb-0 table Table30Padding table-hover table-borderless table-striped NikolasDataTable">
            <thead>
            <tr>
                <th>İl/İlçe</th>
                <th>Şube Adı</th>
                <th>Bağ. Old. Bölge</th>
                <th>Bağ. Old. Aktarma</th>
                <th>Acente Sahibi</th>
                <th>Telefon</th>
                <th>Telefon 2</th>
                <th>Telefon 3</th>
                <th>Şube Kodu</th>
                <th>Statü</th>
                <th>Kargo Kesim</th>
                <th>Operasyon</th>
                <th>Kasa</th>
                <th>Maps</th>
                <th>Pers. Sayısı</th>
                <th>Kayıt Tarihi</th>
                <th width="10" class="text-center"></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>
