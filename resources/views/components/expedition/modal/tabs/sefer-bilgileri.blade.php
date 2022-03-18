
<div class="tab-pane show active" id="tabExpeditionInfo" role="tabpanel">

        <div class="row">
            <div class="col-sm-12">
                <table style="white-space: nowrap" id="AgencyCard"
                       class="TableNoPadding table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="text-center" id="titleBranch" colspan="2">
                            Sefer Bilgileri
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="static">Sefer No:</td>
                        <td style="text-decoration: underline; cursor:pointer; color: #000;"
                            class="customer-detail unselectable font-weight-bold"
                            id="expeditionSerialNo"></td>
                    </tr>
                    <tr>
                        <td class="static">Plaka:</td>
                        <td id="expeditionCarPlaque"></td>
                    </tr>
                    <tr>
                        <td class="static">Oluşturan:</td>
                        <td id="expeditionCreator"></td>
                    </tr>
                    <tr>
                        <td class="static">Oluşturan Birim:</td>
                        <td id="expeditionBranch"></td>
                    </tr>
                    <tr>
                        <td class="static">Açıklama:</td>
                        <td id="expeditionDescription"></td>
                    </tr>
                    <tr>
                        <td class="static">Sefer Durumu:</td>
                        <td id="expeditionDone"></td>
                    </tr>
                    <tr>
                        <td class="static">Kayıt Tarihi:</td>
                        <td id="expeditionCreatedAt"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-12">
                <table style="white-space: nowrap" id="ExpeditionBranchs"
                       class="TableNoPadding table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="text-center" id="titleBranch" colspan="2">
                            Güzergah Bilgileri
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id="departure">
                        <td class="static">Çıkış Birim:</td>
                        <td style="text-decoration: underline; cursor:pointer; color: #000;"
                            class="customer-detail unselectable font-weight-bold text-danger"
                            id="expeditionDepartureBranch"></td>
                    </tr>

                    <tr>
                        <td class="static">Varış Birim:</td>
                        <td id="expeditionArrivalBranch"  class="customer-detail unselectable font-weight-bold text-primary"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <div class="row justify-content-center p-2">
        <x-button type="success" buttonText="TTİ Oluştur"/>
        <x-button type="danger" buttonText="Seferi Sil"/>
        <x-button type="info" buttonText="Düzenle"/>
        <x-button type="alternate" buttonText="Mühürle"/>
        <x-button type="primary" buttonText="Mühür Kır"/>
        <x-button type="dark" buttonText="Sefer Değiştir "/>
    </div>

    </div>
