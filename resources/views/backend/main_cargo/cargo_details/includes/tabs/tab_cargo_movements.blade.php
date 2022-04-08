<div class="mb-3 card">
    <div class="card-header-tab card-header">
        <div class="card-header-title">
            <i class="header-icon pe-7s-box2 icon-gradient bg-ck"> </i>
            Kargo Hareketleri
        </div>
        <ul class="nav">
            <li class="nav-item">
                <a data-toggle="tab" href="#tab-eg5-0"
                   class="nav-link active">Ana Hareketler</a>
            </li>
            {{--<li class="nav-item">--}}
            {{--<a data-toggle="tab" href="#tab-eg5-1" class="nav-link">--}}
            {{--Detaylı Hareketler--}}
            {{--</a>--}}
            {{--</li>--}}
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="tab-eg5-0" role="tabpanel">
                <div
                    style="overflow-x: auto; white-space: nowrap; max-height: 500px;"
                    class="cont">
                    <table style="white-space: nowrap" id="TableEmployees"
                           class="Table30Padding table table-bordered table-striped mt-3">
                        <thead>
                        <tr>
                            <th>Durum</th>
                            <th>Bilgi</th>
                            <th>Parça</th>
                            <th>Kullanıcı</th>
                            <th>İşlem Zamanı</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="tbodyCargoMovements">
                        <tr>
                            <td colspan="5" class="text-center">Burda hiç
                                veri yok.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="tab-eg5-1" role="tabpanel">
                <div
                    style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                    class="cont">
                    <table style="white-space: nowrap" id="TableEmployees"
                           class="Table30Padding table table-bordered table-striped mt-3">
                        <thead>
                        <tr>
                            <th>Durum</th>
                            <th>Bilgi</th>
                            <th>Parça</th>
                            <th>İşlem Zamanı</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="tbodyCargoMovementsSecondary">
                        <tr>
                            <td colspan="4" class="text-center">Burda hiç
                                veri yok.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
