@extends('backend.layout')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">

@endpush

@section('title', 'Bölgeye Bağlı Yerler')
@section('content')

    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-bookmark icon-gradient bg-ck"></i>
                    </div>
                    <div>Bölgeye Bağlı Yerler <b>[{{$data['tc']->tc_name . ' TRM.'}}]</b>
                        <div class="page-title-subheading">Bu modül üzerinden, <b>{{$data['tc']->tc_name . ' TRM.'}}</b>'ye
                            bağlı il-ilçe ve acenteleri listeleyebilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3 card">
            <div class="card-header">
                <ul class="nav nav-justified">
                    <li class="nav-item"><a data-toggle="tab" href="#tab-eg7-0" class="active nav-link">İl & İlçe</a>
                    </li>
                    <li class="nav-item"><a data-toggle="tab" href="#tab-eg7-1" class="nav-link">Acenteler</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-eg7-0" role="tabpanel">
                        <table
                            class="NikolasDataTable align-middle mb-0 table table-borderless table-striped table-hover ">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Bağlı Aktarma</th>
                                <th>İl</th>
                                <th>İlçe</th>
                                <th>Kayıt Tarihi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data['cities'] as $key)
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{$data['tc']->tc_name . ' TRM.'}}</td>
                                    <td>{{$key->city}}</td>
                                    <td>{{$key->district}}</td>
                                    <td>{{$key->created_at}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tab-eg7-1" role="tabpanel">
                        <table style="width: 100%"
                               class="NikolasDataTable align-middle mb-0 table table-borderless table-striped table-hover ">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Bağlı Aktarma</th>
                                <th>İl</th>
                                <th>İlçe</th>
                                <th>Acente Adı</th>
                                <th>Kayıt Tarihi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data['agencies'] as $key)
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{$data['tc']->tc_name . ' TRM.'}}</td>
                                    <td>{{$key->city}}</td>
                                    <td>{{$key->district}}</td>
                                    <td>{{$key->agency_name}}</td>
                                    <td>{{$key->created_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        {{--Statistics--}}
        <div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script>
        // and The Last Part: NikoStyle
        $(document).ready(function () {
            $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                language: {
                    "sDecimal": ",",
                    "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
                    "sInfo": "_TOTAL_ kayıttan _START_ - _END_ kayıtlar gösteriliyor",
                    "sInfoEmpty": "Kayıt yok",
                    "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "Göster: _MENU_",
                    "sLoadingRecords": "Yükleniyor...",
                    "sProcessing": "<div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div>",
                    "sSearch": "Ara:",
                    "sZeroRecords": "Eşleşen kayıt bulunamadı",
                    "oPaginate": {
                        "sFirst": "İlk",
                        "sLast": "Son",
                        "sNext": "Sonraki",
                        "sPrevious": "Önceki"
                    },
                    "oAria": {
                        "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                        "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
                    },
                    "select": {
                        "rows": {
                            "_": "%d kayıt seçildi",
                            "0": "",
                            "1": "1 kayıt seçildi"
                        }
                    }
                },
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel\'e Aktar'
                    }
                ],
                responsive: true,
            });
        });
    </script>
@endsection
