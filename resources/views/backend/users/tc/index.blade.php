@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', $data['transshipment_centers']->tc_name . ' Aktarma Kullanıcıları')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Tüm Kullanıcılar
                        ({{$data['transshipment_centers']->city . '/' . $data['transshipment_centers']->district . ' - ' . $data['transshipment_centers']->tc_name}}
                        )
                        <div class="page-title-subheading">Bu modül üzerinden
                            sistemdeki {{$data['transshipment_centers']->tc_name}} Transfer Merkezine bağlı tüm
                            kullanıcıları
                            listleyebilir, işlem yapablirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('TCUsers.create') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Kullanıcı Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-store mr-3 text-muted opacity-6"> </i>
                    Tüm Kullanıcılar {{$data['transshipment_centers']->tc_name . ' Transfer Merkezi'}}
                </div>
            </div>
            <div class="card-body min-vh-100">

                <table class="NikolasDataTable align-middle mb-0 table table-borderless table-striped table-hover ">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Ad Soyad</th>
                        <th style="display: none;">Yetki</th>
                        <th>Eposta</th>
                        <th>Telefon</th>
                        <th class="text-center">Statü</th>
                        <th>Kullan. Tipi</th>
                        <th>Kayıt Tarihi</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data['users'] as $user)
                        <tr id="TCUser-item-{{ $user->id }}">
                            <td width="10" class="text-center text-muted">#{{ $loop->iteration }}</td>
                            <td data-content="{{ $user->name_surname }}">
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            <div class="widget-content-left">
                                                <img width="40" class="rounded-circle"
                                                     src="/backend/assets/images/ck-ico-blue.png" alt="">
                                            </div>
                                        </div>
                                        <div class="widget-content-left flex2">
                                            <div class="widget-heading">{{ $user->name_surname }}</div>
                                            <div class="widget-subheading opacity-7">{{ $user->display_name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="display: none;">{{ $user->display_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td data-content="{{$user->status == '1' ? 'AKTİF' : 'PASİF'}}" class="text-center">
                                @if ($user->status)
                                    <div class="badge badge-success">AKTİF</div>
                                @else
                                    <div class="badge badge-danger">PASİF</div>
                                @endif
                            </td>
                            <td>{{ $user->user_type }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td class="text-center" width="150">
                                <div class="dropdown d-inline-block">
                                    <button type="button" aria-haspopup="true" aria-expanded="false"
                                            data-toggle="dropdown"
                                            class="mb-2 mr-2 dropdown-toggle btn btn-sm btn-primary">
                                        İşlemler
                                    </button>
                                    <div
                                        style="min-width: 1rem; max-width: 140px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);"
                                        role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start">

                                        <a class="dropdown-item"
                                           href="{{ route('TCUsers.edit', $user->id) }}">
                                            Düzenle
                                        </a>
                                        <button type="button" id="{{ $user->id }}" tabindex="0" from="TCUser"
                                                class="dropdown-item trash">
                                            Sil
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
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
                lengthMenu: dtLengthMenu,
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
                columnDefs: [
                    {
                        targets: [2],
                        visible: false,
                        searchable: false
                    }
                ],
                buttons: [
                    'copy',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7],
                            format: {
                                body: function (data, row, column, node) {
                                    if (typeof $(node).attr('data-content') !== 'undefined') {
                                        data = $(node).attr('data-content');
                                    }
                                    return data;
                                }
                            }
                        },
                        title: "CK-{{$data['transshipment_centers']->tc_name}} Transfer Merkezi Kullanıcıları"
                    }
                ],
                responsive: true,
                scrollY: '750px',
            });
        });
    </script>

    <script>
        $(document).on('click', '.trash', function () {
            var from = $(this).attr("from");
            var object;

            destroy_id = $(this).attr('id');

            if (from == "TCUser") {
                url = "TCUsers/" + destroy_id;
                object = "Kullanıcı";
            }

            swal({
                title: "Silme İşlemini Onaylayın!",
                text: "Emin misiniz? Bu işlem geri alınamaz!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        deleteItem(url, destroy_id, token, object, from);
                    } else {
                        ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi');
                    }
                });
        });

        function deleteItem(url, destroy_id, token, object, from) {
            $.ajax({
                type: "DELETE",
                url: url,
                data: {
                    destroy_id: destroy_id,
                    _token: token
                },
                success: function (msg) {
                    if (msg == 1) {
                        $('#' + from + '-item-' + destroy_id).remove();
                        ToastMessage('success', object + ' Silindi.',
                            'İşlem Başarılı!');
                    } else
                        ToastMessage('error',
                            'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                            'İşlem Başarısız!');
                }
            });
        }

        $('.trash').click(function () {

            var from = $(this).attr("from");
            var object;

            destroy_id = $(this).attr('id');

            if (from == "TCUser") {
                url = "TCUsers/" + destroy_id;
                object = "Kullanıcı";
            }

            swal({
                title: "Silme İşlemini Onaylayın!",
                text: "Emin misiniz? Bu işlem geri alınamaz!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        deleteItem(url, destroy_id, token, object, from);
                    } else {
                        ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi');
                    }
                });
        });

    </script>

@endsection
