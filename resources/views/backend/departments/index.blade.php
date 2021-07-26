@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Departmanlar')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-briefcase icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Departmanlar
                        <div class="page-title-subheading">Bu modül üzerinden sistemdeki tüm deparmanları
                            listleyebilir, işlem yapablirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('Departments.create') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Departman Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-briefcase mr-3 text-muted opacity-6"> </i>Tüm Departmanlar
                </div>
            </div>
            <div class="card-body">

                <table
                    class="NikolasDataTable align-middle mb-0 table TableNoPadding table-borderless table-striped table-hover ">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Departman</th>
                        <th>Açıklama</th>
                        <th>Kayıt Tarihi</th>
                        <th class="text-center">İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($data['departments'] as $key)
                        <tr id="department-item-{{$key->id}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$key->department_name}}</td>
                            <td data-content="{{ $key->explantion }}">
                                <a href="javascript:void(0)"
                                   data-title="{{ $key->department_name }}"
                                   data-toggle="popover-custom-bg"
                                   data-bg-class="text-light bg-premium-dark"
                                   data-content="{{ $key->explantion }}">{{ Str::words($key->explantion, 3 , '...')}}
                                </a>
                            </td>
                            <td>{{$key->created_at}}</td>
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
                                           href="{{ route('Departments.edit', $key->id) }}">
                                            Düzenle
                                        </a>
                                        <button type="button" id="{{ $key->id }}" tabindex="0" from="department"
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
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [
                    [0, 'desc']
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
                    'copy',
                    'pdf',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3],
                            format: {
                                body: function (data, row, column, node) {
                                    if (typeof $(node).attr('data-content') !== 'undefined') {
                                        data = $(node).attr('data-content');
                                    }
                                    return data;
                                }
                            }
                        },
                        title: "CK-Deparmanlar - {{ date('d/m/Y') }}",
                    }
                ],
                responsive: true,
                scrollY: "500px",
            });
        });

    </script>


    <script src="/backend/assets/scripts/delete-method.js"></script>
@endsection
