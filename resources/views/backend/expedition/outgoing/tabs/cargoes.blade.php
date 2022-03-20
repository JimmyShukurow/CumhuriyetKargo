<div class="tab-pane show" id="tabExpeditionCargoes" role="tabpanel">

    <div class="mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-box2 icon-gradient bg-ck"> </i>
                Sefer Kargoları
            </div>
            <ul class="nav">
                <li class="nav-item">
                    <a data-toggle="tab" href="#tab-eg5-0"
                       class="nav-link active">Kargolar</a>
                </li>

                <li class="nav-item">
                    <a data-toggle="tab" href="#tab-eg5-1" class="nav-link">
                        Detay
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab-eg5-0" role="tabpanel">
                    <div
                            style="overflow-x: auto; white-space: nowrap;"
                            class="cont">
                        <table style="white-space: nowrap" id="TableEmployees"
                               class="TableNoPadding table table-bordered table-striped mt-3">
                            <thead>
                            <tr>
                                <th>Fatura numarası</th>
                                <th>Kargo Adeti</th>
                                <th>Kargo Tipi</th>
                                <th>Alıcı</th>
                                <th>Gönderici</th>
                                <th>Varış İl/İlçe</th>
                                <th>Kargo Statü</th>
                                <th>Yükleyen</th>
                                <th>Yükleme Tarihi</th>
                            </tr>
                            </thead>
                            <tbody id="tbodyExpedtionCargoes">
                            @foreach($expedition->cargoes as $cargo)
                                <tr>
                                    <td> {{ $cargo->cargo->invoice_number }} </td>
                                    <td> {{ $cargo->part_no }} </td>
                                    <td> {{ $cargo->cargo->cargo_type }} </td>
                                    <td> {{ $cargo->cargo->receiver_name }} </td>
                                    <td> {{ $cargo->cargo->sender_name }} </td>
                                    <td> {{ $cargo->cargo->arrival_city }} /{{ $cargo->cargo->arrival_district }}   </td>
                                    <td> {{ $cargo->cargo->status }} </td>
                                    <td> {{ $cargo->user->name_surname }} ({{ $cargo->user->role->display_name }}) </td>
                                    <td> {{ $cargo->cargo->created_at }} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="tab-eg5-1" role="tabpanel">
                    <div
                            style="overflow-x: auto; white-space: nowrap;"
                            class="cont">
                        <table style="white-space: nowrap" id="TableEmployees"
                               class="TableNoPadding table table-bordered table-striped mt-3">
                            <thead>
                            <tr>
                                <th>Fatura numarası</th>
                                <th>Parça No</th>
                                <th>Kargo Tipi</th>
                                <th>Alıcı</th>
                                <th>Gönderici</th>
                                <th>Varış İl/İlçe</th>
                                <th>Kargo Statü</th>
                                <th>Yükleyen</th>
                                <th>Yükleme Tarihi</th>
                                <th>İndiren Kullanıcı</th>
                                <th>İndirilme Tarihi</th>
                                <th>Silen Kullanıcı</th>
                                <th>Silinme Tarihi</th>
                            </tr>
                            </thead>
                            <tbody id="tbodyExpedtionCargoesSecondary">
                            <tr>
                            @foreach($expedition->allCargoes as $cargo)
                                @if($cargo->unloading_at != null)
                                    <tr class="text-primary">
                                @elseif($cargo->deleted_at != null)
                                    <tr class="text-danger">
                                @else
                                    <tr>
                                @endif
                                    <td> {{ $cargo->cargo->invoice_number }} </td>
                                    <td> {{ $cargo->part_no }} </td>
                                    <td> {{ $cargo->cargo->cargo_type }} </td>
                                    <td> {{ $cargo->cargo->receiver_name }} </td>
                                    <td> {{ $cargo->cargo->sender_name }} </td>
                                    <td> {{ $cargo->cargo->arrival_city }} /{{ $cargo->cargo->arrival_district }}   </td>
                                    <td> {{ $cargo->cargo->status }} </td>
                                    <td> {{ $cargo->user->name_surname }} ({{ $cargo->user->role->display_name}}) </td>
                                    <td> {{ $cargo->cargo->created_at }} </td>
                                    <td>
                                        {{ $cargo->unloadedUser ? $cargo->unloadedUser->name_surname . '(' .$cargo->unloadedUser->role->display_name . ')'  : ''  }}
                                    </td>
                                    <td> {{ $cargo->unloading_at }} </td>
                                    <td> {{ $cargo->deletedUser ? $cargo->deletedUser->name_surname. '(' . $cargo->deletedUser->role->display_name . ')' : ''}}</td>
                                    <td> {{ $cargo->deleted_at }} </td>
                                </tr>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
