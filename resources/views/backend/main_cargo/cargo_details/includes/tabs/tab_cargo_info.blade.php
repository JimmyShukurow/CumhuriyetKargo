<div class="row">
    <div class="col-sm-6">
        <table id="AgencyCard"
               class="TableNoPadding table table-bordered table-striped">
            <thead>
            <tr>
                <th class="text-center" colspan="2">
                    Gönderici Bilgileri
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="static">Cari Kodu:</td>
                <td style="text-decoration: underline; cursor:pointer; color: #000;"
                    class="customer-detail unselectable font-weight-bold"
                    id="senderCurrentCode"></td>
            </tr>
            <tr>
                <td class="static">Müşteri Tipi:</td>
                <td id="senderCustomerType"></td>
            </tr>
            <tr>
                <td class="static">TCKN/VKN:</td>
                <td id="senderTcknVkn"></td>
            </tr>
            <tr>
                <td class="static">Ad Soyad:</td>
                <td id="senderNameSurname"></td>
            </tr>
            <tr>
                <td class="static">Telefon:</td>
                <td id="senderPhone"></td>
            </tr>
            <tr>
                <td class="static">İl/İlçe:</td>
                <td id="senderCityDistrict"></td>
            </tr>
            <tr>
                <td class="static">Mahalle:</td>
                <td id="senderNeighborhood"></td>
            </tr>
            <tr>
                <td class="static">Adres:</td>
                <td style="white-space: initial;"
                    id="senderAddress"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">
        <table style="white-space: nowrap" id="AgencyCard"
               class="TableNoPadding table table-bordered table-striped">
            <thead>
            <tr>
                <th class="text-center" colspan="2">
                    Alıcı Bilgileri
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="static">Cari Kodu:</td>
                <td style="text-decoration: underline; cursor:pointer; color: #000;"
                    class="customer-detail unselectable font-weight-bold"
                    id="receiverCurrentCode"></td>
            </tr>
            <tr>
                <td class="static">Müşteri Tipi:</td>
                <td id="receiverCustomerType"></td>
            </tr>
            <tr>
                <td class="static">TCKN/VKN:</td>
                <td id="receiverTcknVkn"></td>
            </tr>
            <tr>
                <td class="static">Ad Soyad:</td>
                <td id="receiverNameSurname"></td>
            </tr>
            <tr>
                <td class="static">Telefon:</td>
                <td id="receiverPhone"></td>
            </tr>
            <tr>
                <td class="static">İl/İlçe:</td>
                <td id="receiverCityDistrict"></td>
            </tr>
            <tr>
                <td class="static">Mahalle:</td>
                <td id="receiverNeighborhood"></td>
            </tr>
            <tr>
                <td class="static">Adres:</td>
                <td style="white-space: initial;"
                    id="receiverAddress"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="divider"></div>

<div class="row">
    <div class="col-sm-6">
        <table style="white-space: nowrap" id="AgencyCard"
               class="TableNoPadding table table-bordered table-striped">
            <thead>
            <tr>
                <th class="text-center" colspan="2">Kargo Bilgileri - I</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="static">Kargo Takip No:</td>
                <td class="font-weight-bold text-dark"
                    id="cargoTrackingNo"></td>
            </tr>
            <tr>
                <td class="static">Kayıt Tarihi:</td>
                <td id="cargoCreatedAt"></td>
            </tr>
            <tr>
                <td class="static">Parça Sayısı:</td>
                <td id="numberOfPieces"></td>
            </tr>
            <tr>
                <td class="static">KG:</td>
                <td id="cargoKg"></td>
            </tr>
            <tr>
                <td class="static">Desi:</td>
                <td id="desi"></td>
            </tr>
            <tr>
                <td class="static">Hacim (m<sup>3</sup>):</td>
                <td id="cubicMeterVolume"></td>
            </tr>
            <tr>
                <td class="static">Gönderi Türü:
                <td id="cargoType"></td>
            </tr>
            <tr>
                <td class="static">Ödeme Tipi:</td>
                <td id="paymentType"></td>
            </tr>
            <tr>
                <td class="static">Sistem:</td>
                <td id="system"></td>
            </tr>
            <tr>
                <td class="static">Oluşturan:</td>
                <td id="creatorUserInfo"></td>
            </tr>
            <tr>
                <td class="static">Müşteri Kodu:</td>
                <td id="customerCode"></td>
            </tr>
            <tr>
                <td class="static">Statü:</td>
                <td id="cargoStatus"
                    class="font-weight-bold text-warning"></td>
            </tr>

            <tr>
                <td class="static">İnsanlar İçin Statü:</td>
                <td id="cargoStatusForHumen"
                    class="font-weight-bold text-primary"></td>
            </tr>
            <tr>
                <td class="static">Teslimat Tarihi:</td>
                <td id="cargoDeliveryDate"></td>
            </tr>
            <tr>
                <td class="static">Teslim Edilen:</td>
                <td id="cargoReceiverName"></td>
            </tr>
            <tr>
                <td class="static">Kargo İçeriği:</td>
                <td id="cargoContent"></td>
            </tr>
            <tr>
                <td class="static">Kargo İçerik Açıklaması:</td>
                <td style="white-space: initial;"
                    id="cargoContentEx"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">
        <table style="white-space: nowrap" id="AgencyCard"
               class="TableNoPadding table table-bordered table-striped">
            <thead>
            <tr>
                <th class="text-center" colspan="2">Kargo Bilgileri - II
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="static">Taşıyan:</td>
                <td id="transporter"></td>
            </tr>
            <tr>
                <td class="static">Torbada mı:</td>
                <td id="inBag"></td>
            </tr>
            <tr>
                <td class="static">Tahislatlı:</td>
                <td id="collectible"></td>
            </tr>
            <tr>
                <td class="static">Fatura Tutarı:</td>
                <td id="collection_fee"
                    class="font-weight-bold text-primary"></td>
            </tr>

            <tr>
                <td class="static">Çıkış Şube:</td>
                <td class="text-primary" id="exitBranch"></td>
            </tr>

            <tr>
                <td class="static">Çıkış Transfer:</td>
                <td class="text-primary" id="exitTransfer"></td>
            </tr>

            <tr>
                <td class="static">Varış Şube:</td>
                <td class="text-alternate" id="arrivalBranch"></td>
            </tr>

            <tr>
                <td class="static">Varış Transfer:</td>
                <td class="text-alternate" id="arrivalTC"></td>
            </tr>
            <tr>
                <td class="static">Mesafe (KM):</td>
                <td id="distance"></td>
            </tr>

            <tr>
                <td class="static">Mesafe Ücreti:</td>
                <td class="font-weight-bold text-dark"
                    id="distancePrice"></td>
            </tr>

            <tr>
                <td class="static">Posta Hizmetleri Bedeli:</td>
                <td class="font-weight-bold text-dark"
                    id="postServicesPrice"></td>
            </tr>

            <tr>
                <td class="static">Ağır Yük Taşıma Bedeli:</td>
                <td class="font-weight-bold text-dark"
                    id="heavyLoadCarryingCost"></td>
            </tr>

            <tr>
                <td class="static">KDV (%18):</td>
                <td class="font-weight-bold text-dark" id="kdv"></td>
            </tr>

            <tr>
                <td class="static">Ek Hizmet Tutarı:</td>
                <td class="font-weight-bold text-dark"
                    id="addServiceFee"></td>
            </tr>
            <tr>
                <td class="static">Hizmet Ücreti:</td>
                <td class="font-weight-bold text-dark"
                    id="serviceFee"></td>
            </tr>
            <tr>
                <td class="static">Mobil Hizmet Ücreti:</td>
                <td class="font-weight-bold text-dark"
                    id="mobileServiceFee"></td>
            </tr>
            <tr>
                <td class="static">Genel Toplam:</td>
                <td class="font-weight-bold text-primary"
                    id="totalFee"></td>
            </tr>

            </tbody>
        </table>
    </div>
</div>


<div class="mt-3 card">
    <div class="card-header-tab card-header">
        <div class="card-header-title">
            <i class="header-icon pe-7s-box2 icon-gradient bg-ck"> </i>
            Kargo Ek Hizmetleri
        </div>
    </div>
    <div style="padding-top: 0;" class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <table style="white-space: nowrap" id="AgencyCard"
                       class="TableNoPadding table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Ek Hizmet</th>
                        <th>Maliyeti</th>
                    </tr>
                    </thead>
                    <tbody id="tbodyCargoAddServices">
                    <tr>
                        <td>Adrese Teslim</td>
                        <td>8.5₺</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="divider"></div>
<h3 class="text-dark text-center mb-4"></h3>

