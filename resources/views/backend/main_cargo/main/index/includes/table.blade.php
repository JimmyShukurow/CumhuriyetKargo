<div class="card mt-3">
    <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                class="header-icon pe-7s-box2 mr-3 text-muted opacity-6"> </i>Kestiğiniz Kargolar
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
                    <label for="startDate">Başlangıç Tarih:</label>
                    <input type="datetime-local" id="startDate" value="{{ date('Y-m-d') }}T00:00"
                           class="form-control niko-filter form-control-sm">
                </div>

                <div class="col-md-2">
                    <label for="finishDate">Bitiş Tarihi:</label>
                    <input type="datetime-local" id="finishDate" value="{{ date('Y-m-d') }}T23:59"
                           class="form-control niko-filter form-control-sm">
                </div>

                <div class="col-md-2">
                    <label for="record">Kayıt:</label>
                    <select name="record" id="record"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="1">Kayıt</option>
                        <option value="0">Arşiv</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="paymentType">Ödeme Tipi:</label>
                    <select name="paymentType" id="paymentType"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        <option value="Alıcı Ödemeli">Alıcı Ödemeli</option>
                        <option value="Gönderici Ödemeli">Gönderici Ödemeli</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="collectible">Tahsilatlı:</label>
                    <select name="collectible" id="collectible"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        <option value="Evet">Evet</option>
                        <option value="Hayır">Hayır</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="cargoType">Kargo Tipi:</label>
                    <select name="cargoType" id="cargoType"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        @foreach(allCargoTypes() as $key)
                            <option value="{{$key}}">{{$key}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row pt-2">

                <div class="col-md-2">
                    <label for="status">Statü:</label>
                    <select name="status" id="status"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        @foreach($data['status'] as $key)
                            <option value="{{$key->status}}">{{$key->status}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="statusForHuman">Durum:</label>
                    <select name="statusForHuman" id="statusForHuman"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        @foreach($data['status_for_human'] as $key)
                            <option value="{{$key->status_for_human}}">{{$key->status_for_human}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="transporter">Taşıyıcı:</label>
                    <select name="transporter" id="transporter"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        @foreach($data['transporters'] as $key)
                            <option value="{{$key->transporter}}">{{$key->transporter}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="system">Sistem:</label>
                    <select name="system" id="system"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        @foreach($data['systems'] as $key)
                            <option value="{{$key->system}}">{{$key->system}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="creatorUser">Oluşturan:</label>
                    <select name="creatorUser" class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        @foreach($data['agency_users'] as $key)
                            <option value="{{$key->id}}">{{$key->name_surname}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="cargoContent">Kargo İçeriği:</label>
                    <select name="cargoContent" id="cargoContent"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        @foreach($data['cargo_contents'] as $key)
                            <option value="{{$key->cargo_content}}">{{$key->cargo_content}}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="row pt-2">

                <div class="col-md-2">
                    <label for="receiverCode">Kargo Takip No:</label>
                    <input type="text" data-inputmask="'mask': '99999 99999 99999'"
                           placeholder="_____ _____ _____" type="text" id="trackingNo"
                           class="form-control input-mask-trigger form-control-sm niko-filter">
                </div>

                <div class="col-md-2">
                    <label for="receiverCode">Fatura NO:</label>
                    <input type="text" data-inputmask="'mask': 'AA-999999'"
                           placeholder="__ ______" type="text" id="invoice_number"
                           class="form-control input-mask-trigger form-control-sm niko-filter">
                </div>

                <div class="col-md-2">
                    <label for="receiverName">Alıcı Adı:</label>
                    <input type="text" id="receiverName" class="form-control niko-filter form-control-sm">
                </div>

                <div class="col-md-2">
                    <label for="receiverCity">Alıcı İl:</label>
                    <select id="receiverCity"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        @foreach($data['cities'] as $key)
                            <option value="{{$key->city_name}}">{{$key->city_name}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-2">
                    <label for="currentName">Gönderici Adı:</label>
                    <input type="text" id="currentName"
                           class="form-control niko-filter form-control-sm">
                </div>

                <div class="col-md-2">
                    <label for="currentCity">Gönderici İl:</label>
                    <select name="cargoContent" id="currentCity"
                            class="form-control form-control-sm niko-select-filter">
                        <option value="">Seçiniz</option>
                        @foreach($data['cities'] as $key)
                            <option value="{{$key->city_name}}">{{$key->city_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">

        <table style="white-space: nowrap;" id="CargoesTable"
               class="align-middle mb-0 table Table20Padding table-bordered table-striped table-hover NikolasDataTable">
            <thead>
            <tr>
                <th class="check text-center">
                    <input style="width: 50px; height: 17px;" id="tableCheckAll" class="cursor-pointer" type="checkbox">
                </th>
                <th>Fatura NO</th>
                <th>KTNO</th>
                <th>Gönderici Adı</th>
                <th>Gönderici İl</th>
                <th>Alıcı Adı</th>
                <th>Alıcı İl</th>
                <th>Alıcı İlçe</th>
                <th>Kargo Tipi</th>
                <th>Ödeme Tipi</th>
                <th>Ücret</th>
                <th>Parça Sayısı</th>
                <th>Ağırlık (KG)</th>
                <th>Hacim (m<sup>3</sup>)</th>
                <th>Tahsilat Tipi</th>
                <th>Tahsilatlı</th>
                <th>Fatura Tutarı</th>
                <th>Statü</th>
                <th>Durum</th>
                <th>Taşıyan</th>
                <th>Sistem</th>
                <th>Oluşturan</th>
                <th>Kayıt Tarihi</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
            <tr>
                <th class="check"></th>
                <th>Fatura NO</th>
                <th>KTNO</th>
                <th>Gönderici Adı</th>
                <th>Gönderici İl</th>
                <th>Alıcı Adı</th>
                <th>Alıcı İl</th>
                <th>Alıcı İlçe</th>
                <th>Kargo Tipi</th>
                <th>Ödeme Tipi</th>
                <th>Ücret</th>
                <th>Parça Sayısı</th>
                <th>Ağırlık (KG)</th>
                <th>Hacim (m<sup>3</sup>)</th>
                <th>Tahsilat Tipi</th>
                <th>Tahsilatlı</th>
                <th>Fatura Tutarı</th>
                <th>Statü</th>
                <th>Durum</th>
                <th>Taşıyan</th>
                <th>Sistem</th>
                <th>Oluşturan</th>
                <th>Kayıt Tarihi</th>
            </tr>
            </tfoot>
        </table>

    </div>
</div>
