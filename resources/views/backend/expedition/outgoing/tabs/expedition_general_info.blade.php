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
                    <td style="width: 150px;" class="static">Sefer No:</td>
                    <td style="text-decoration: underline; color: #000;"
                        class="customer-detail unselectable font-weight-bold"
                        id="expeditionSerialNo"> {{ $expedition->serial_no }}</td>
                </tr>
                <tr>
                    <td class="static">Plaka:</td>
                    <td id="expeditionCarPlaque">{{ $expedition->car->plaka }}</td>
                </tr>
                <tr>
                    <td class="static">Oluşturan:</td>
                    <td id="expeditionCreator"> {{ $expedition->user->name_surname }}
                        ( {{ $expedition->user->display_name  }})
                    </td>
                </tr>
                <tr>
                    <td class="static">Oluşturan Birim:</td>
                    <td id="expeditionBranch"> {{ $expedition->branch }}</td>
                </tr>
                <tr>
                    <td class="static">Açıklama:</td>
                    <td id="expeditionDescription"> {{ $expedition->description  }}</td>
                </tr>
                <tr>
                    <td class="static">Sefer Durumu:</td>
                    <td id="expeditionDone">
                        @if($expedition->done == 0)
                            <b class="text-success">Devam Ediyor</b>
                        @else
                            <b class="text-danger">Bitti</b>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="static"> Toplam Kargo Sayısı</td>
                    <td id="totalCargoCount"> <b> {{ $expedition->cargoes->count() == 0 ? ' Kargo Yok' : $expedition->cargoes->count() }} </b></td>
                </tr>
                <tr>
                    <td class="static">Kayıt Tarihi:</td>
                    <td id="expeditionCreatedAt"> {{ $expedition->created_at }}</td>
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
                    <td style="width: 180px;" class="static">Çıkış Birim:</td>
                    <td style="text-decoration: underline; cursor:pointer; color: #000;"
                        class="customer-detail unselectable font-weight-bold text-danger"
                        id="expeditionDepartureBranch"> {{  $expedition->departure_branch  }}</td>
                </tr>
                @foreach($expedition->betweens as $between)
                    <tr>
                        <td class="static">{{ $loop->index + 1}} Güzergah</td>
                        <td id="expeditionArrivalBranch"
                            class="customer-detail unselectable font-weight-bold text-dark">
                            {{ $between }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="static">Varış Birim:</td>
                    <td id="expeditionArrivalBranch" class="customer-detail unselectable font-weight-bold text-primary">
                        {{ $expedition->arrival_branch }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
