@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush

@section('title', 'Hizmet Ücretleri')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas fa-money-check-alt icon-gradient bg-plum-plate">
                        </i>
                    </div>
                    <div>Hizmet Ücretleri
                        <div class="page-title-subheading">Bu modül üzerinden kargo taşıma, ek hizmet ve dosya
                            fiyatlarını görüntüleyebilir, düzenleyebilirsiniz.
                        </div>
                    </div>
                </div>

                <div class="page-title-actions">
                    <button type="button" data-toggle="tooltip" title="" data-placement="bottom"
                            class="btn-shadow mr-3 btn btn-dark"
                            data-original-title="Bu modül üzerinden Cumhuriyet Kargonun sunduğu hizmetlerin fiyat aralıklarını belirleyebilirsiniz.">
                        <i class="fa fa-star"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="">
            {{-- MY BITCCH --}}
            <div class="mb-3 card">
                <div class="card-header">
                    <ul class="nav nav-justified">
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-additional-services"
                               class="{{ setActive($tab, 'AdditionalServices')}} nav-link tab-nav-link">Ek Hizmetler</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#tab-desi-list"
                               class="{{ setActive($tab, 'DesiList')}} nav-link tab-nav-link">Desi Fiyatları</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#modules"
                               class="{{ setActive($tab, 'FilePrice')}} nav-link tab-nav-link">Dosya Fiyat</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane {{ setActive($tab, 'AdditionalServices')}} min-vh-100"
                             id="tab-additional-services"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <button style="float: right; margin: 15px 0;" id="btnNewAdditionalService"
                                        class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success">
                                    <i class="lnr-plus-circle btn-icon-wrapper"> </i>Ek Servis Ekle
                                </button>

                                <div class="card-body">
                                    <div style="overflow-x: auto; ">
                                        <table style="white-space: nowrap;"
                                               class="table table-bordered  TableAdditionalServices table-striped Table30Padding mb-5">
                                            <thead>
                                            <tr>
                                                <th>Ek Hizmet</th>
                                                <th>Ücret</th>
                                                <th>Statü</th>
                                                <th>Kayıt Tarihi</th>
                                                <th>Değitirilme Tarihi</th>
                                                <th width="40">İşlem</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Ek Hizmet</th>
                                                <th>Ücret</th>
                                                <th>Statü</th>
                                                <th>Kayıt Tarihi</th>
                                                <th>Değitirilme Tarihi</th>
                                                <th>İşlem</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{ setActive($tab, 'DesiList') }} min-vh-100" id="tab-desi-list"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <button style="float: right; margin: 15px 0;" id="addNewDesiInterval"
                                        class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-alternate">
                                    <i class="lnr-plus-circle btn-icon-wrapper"> </i>Yeni Desi Aralığı Ekle
                                </button>

                                <div class="card-body">
                                    <table style="white-space: nowrap;"
                                           class="table table-bordered  TableDesiList table-striped Table30Padding mb-5">
                                        <thead>
                                        <tr>
                                            <th>Başlangıç Desi</th>
                                            <th>Bitiş Desi</th>
                                            <th>Desi Ücreti</th>
                                            <th>Kurumsal Birim Fiyat</th>
                                            <th>Bireysel Birim Fiyat</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>Değitirilme Tarihi</th>
                                            <th width="40">İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Başlangıç Desi</th>
                                            <th>Bitiş Desi</th>
                                            <th>Desi Ücreti</th>
                                            <th>Kurumsal Birim Fiyat</th>
                                            <th>Bireysel Birim Fiyat</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>Değitirilme Tarihi</th>
                                            <th width="40">İşlem</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane {{ setActive($tab, 'FilePrice') }} min-vh-100" id="modules"
                             role="tabpanel">
                            <div class="main-card mb-3 card">

                                <div style="overflow-x: scroll;" class="card-body min-vh-100">
                                    <table style="white-space: nowrap;"
                                           class="table table-bordered table-dark mb-5">
                                        <thead>
                                        <tr>
                                            <th>Kurumsal</th>
                                            <th>Bireysel</th>
                                            <th>Son Güncelleme</th>
                                            <th width="10">İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="file-price">
                                            <td>
                                                <b id="corporateFilePrice">₺{{$filePrice->corporate_file_price}}</b>
                                            </td>
                                            <td>
                                                <b id="individualFilePrice">₺{{$filePrice->individual_file_price}}</b>
                                            </td>
                                            <td>
                                                <b id="filePriceUpdate">{{$filePrice->updated_at}}</b>
                                            </td>
                                            <td>
                                                <button id="{{$filePrice->id}}"
                                                        class="btn btn-primary editFilePrice">Düzenle
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/backend/assets/scripts/backend-modules.js"></script>

@endsection

@section('js')

    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>

    <script>
        $(document).ready(function () {
            $('.tab-nav-link').click(function () {
                $('.TableAdditionalServices, .TableDesiList').DataTable().ajax.reload();
            });
        });

        var edit_additional_service_id = 0, edit_desi_interval = 0, edit_file = 0;

        // # Additional Service Transasction
        $(document).ready(function () {
            $('.TableAdditionalServices').DataTable({
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [
                    3, 'desc'
                ],
                language: {
                    "sDecimal": ",",
                    "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
                    "sInfo": "_TOTAL_ kayıttan _START_ - _END_ kayıtlar gösteriliyor",
                    "sInfoEmpty": "Kayıt yok",
                    "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_",
                    "sLoadingRecords": "Yükleniyor...",
                    "sProcessing": "<div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div>",
                    "sSearch": "",
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
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">f>rtip',
                buttons: [
                    {
                        extend: 'pdf',
                        title: 'CK - Ek Hizmet Ücretleri',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4],
                        }
                    },
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        title: "CK - Ek Hizmet Ücretleri"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('AdditionalServices.index') !!}',
                    data: function (d) {

                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'service_name', name: 'service_name'},
                    {data: 'price', name: 'price'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'edit', name: 'edit'}
                ],
                scrollY: false,
            });
        });

        $('#btnNewAdditionalService').click(function () {
            $('#modalNewAdditionalService').modal();
        });

        $(document).on('click', '#btnInsertAdditionalService', function () {
            $(this).prop('disabled', true);
            ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

            $.ajax('{{route('AdditionalServices.store')}}', {
                method: 'POST',
                data: {
                    _token: token,
                    service_name: $('#additionalServiceName').val(),
                    price: $('#additionalServicePrice').val()
                }
            }).done(function (response) {
                if (response.status == 1) {
                    $('.modal input[type=text]').val('');
                    ToastMessage('success', 'Ek hizmet oluşturuldu!', 'İşlem Başarılı!');
                    $('#modalNewAdditionalService').modal('toggle');
                    $('.TableAdditionalServices').DataTable().ajax.reload();
                } else if (response.status == 0)
                    ToastMessage('error', response.message, 'Hata!');
                else if (response.status == -1)
                    $.each(response.errors, function (index, value) {
                        ToastMessage('error', value, 'Hata!')
                    });
            }).error(function () {
                ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
            }).always(function () {
                $('#btnInsertAdditionalService').prop('disabled', false);
            });
        });

        $(document).on('click', '.edit-additional-service', function () {
            edit_additional_service_id = $(this).prop('id');
            $('#editAdditionalServiceName').val($("#additional-service-" + edit_additional_service_id + " > td:nth-child(1)").text())
            $('#editAdditionalServicePrice').val($("#additional-service-" + edit_additional_service_id + " > td:nth-child(2)").text())
            var status = $("#additional-service-" + edit_additional_service_id + " > td:nth-child(3)").text();
            $('#editAdditionalServiceStatus').val(status == 'Aktif' ? '1' : '0');
            $('#modalEditAdditionalService').modal();
        });

        $(document).on('click', '#btnUpdateAdditionalService', function () {
            $(this).prop('disabled', true);
            ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

            $.ajax('AdditionalServices/' + edit_additional_service_id, {
                method: 'PUT',
                data: {
                    _token: token,
                    service_name: $('#editAdditionalServiceName').val(),
                    price: $('#editAdditionalServicePrice').val(),
                    status: $('#editAdditionalServiceStatus').val()
                }
            }).done(function (response) {
                if (response.status == 1) {
                    $('.modal input[type=text]').val('');
                    ToastMessage('success', 'Ek hizmet kaydedildi!', 'İşlem Başarılı!');
                    $('#modalEditAdditionalService').modal('toggle');
                    $('.TableAdditionalServices').DataTable().ajax.reload();
                } else if (response.status == 0)
                    ToastMessage('error', response.message, 'Hata!');
                else if (response.status == -1)
                    $.each(response.errors, function (index, value) {
                        ToastMessage('error', value, 'Hata!')
                    });
            }).error(function () {
                ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
            }).always(function () {
                $('#btnUpdateAdditionalService').prop('disabled', false);
            });
        });

        $(document).on('click', '.trash', function () {
            var from = $(this).attr("from");
            var object;

            destroy_id = $(this).attr('id');

            if (from == "additional-service") {
                url = "AdditionalServices/" + destroy_id;
                object = "Ek hizmet";
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
                                    $('.TableAdditionalServices').DataTable().ajax.reload();

                                } else
                                    ToastMessage('error',
                                        'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                                        'İşlem Başarısız!');
                            }
                        });

                    } else {
                        ToastMessage('info', 'Silme işlemi iptal edilidi.', 'Bilgi');
                    }
                });
        });

        // # Desi List
        $(document).ready(function () {
            $('.TableDesiList').DataTable({
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                // order: [
                //     2, 'desc'
                // ],
                language: {
                    "sDecimal": ",",
                    "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
                    "sInfo": "_TOTAL_ kayıttan _START_ - _END_ kayıtlar gösteriliyor",
                    "sInfoEmpty": "Kayıt yok",
                    "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_",
                    "sLoadingRecords": "Yükleniyor...",
                    "sProcessing": "<div class=\"lds-ring\"><div></div><div></div><div></div><div></div></div>",
                    "sSearch": "",
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
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">f>rtip',
                buttons: [
                    {
                        extend: 'pdf',
                        title: 'CK - Desi Ücretleri',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6],
                        }
                    },
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        },
                        title: "CK - Desi Ücretleri"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('DesiList.index') !!}',
                    data: function (d) {

                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'start_desi', name: 'start_desi'},
                    {data: 'finish_desi', name: 'finish_desi'},
                    {data: 'desi_price', name: 'desi_price'},
                    {data: 'corporate_unit_price', name: 'corporate_unit_price'},
                    {data: 'individual_unit_price', name: 'individual_unit_price'},
                    {data: 'created_at', name: 'updated_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'edit', name: 'edit'}
                ],
                scrollY: "500px",
            });
        });

        $(document).on('click', '#addNewDesiInterval', function () {
            $('#modalNewDesiInterval').modal();
        });

        $(document).on('click', '#btnInsertNewDesiInterval', function () {
            $(this).prop('disabled', true);
            ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

            $.ajax('{{route('DesiList.store')}}', {
                method: 'POST',
                data: {
                    _token: token,
                    start_desi: $('#startDesi').val(),
                    finish_desi: $('#finishDesi').val(),
                    desi_price: $('#desiPrice').val(),
                    corporate_unit_price: $('#corporateUnitPrice').val(),
                    individual_unit_price: $('#individualUnitPrice').val()
                }
            }).done(function (response) {
                if (response.status == 1) {
                    $('.modal input[type=text]').val('');
                    ToastMessage('success', 'Ek hizmet oluşturuldu!', 'İşlem Başarılı!');
                    $('#modalNewDesiInterval').modal('toggle');
                    $('.TableDesiList').DataTable().ajax.reload();
                } else if (response.status == 0)
                    ToastMessage('error', response.message, 'Hata!');
                else if (response.status == -1)
                    $.each(response.errors, function (index, value) {
                        ToastMessage('error', value, 'Hata!')
                    });
            }).error(function () {
                ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
            }).always(function () {
                $('#btnInsertNewDesiInterval').prop('disabled', false);
            });
        });

        $(document).on('click', '.edit-desi-list', function () {
            edit_desi_interval = $(this).prop('id');
            $('#editStartDesi').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(1)").text());
            $('#editFinishDesi').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(2)").text());
            $('#editDesiPrice').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(3)").text());
            $('#editCorporateUnitPrice').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(4)").text());
            $('#editIndividualUnitPrice').val($("#desi-interval-" + edit_desi_interval + " > td:nth-child(5)").text());
            $('#modelEditDesiInterval').modal();
        });

        $(document).on('click', '#btnUpdateDesiInterval', function () {
            $(this).prop('disabled', true);
            ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

            $.ajax('DesiList/' + edit_desi_interval, {
                method: 'PUT',
                data: {
                    _token: token,
                    start_desi: $('#editStartDesi').val(),
                    finish_desi: $('#editFinishDesi').val(),
                    desi_price: $('#editDesiPrice').val(),
                    corporate_unit_price: $('#editCorporateUnitPrice').val(),
                    individual_unit_price: $('#editIndividualUnitPrice').val()
                }
            }).done(function (response) {
                if (response.status == 1) {
                    $('.modal input[type=text]').val('');
                    ToastMessage('success', 'Ek hizmet kaydedildi!', 'İşlem Başarılı!');
                    $('#modelEditDesiInterval').modal('toggle');
                    $('.TableDesiList').DataTable().ajax.reload();
                } else if (response.status == 0)
                    ToastMessage('error', response.message, 'Hata!');
                else if (response.status == -1)
                    $.each(response.errors, function (index, value) {
                        ToastMessage('error', value, 'Hata!')
                    });
            }).error(function () {
                ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
            }).always(function () {
                $('#btnUpdateDesiInterval').prop('disabled', false);
            });
        });

        // # File Price
        $(document).on('click', '.editFilePrice', function () {
            edit_file = $(this).prop('id');
            $('#editCorporteFilePrice').val($("#file-price > td:nth-child(1)").text());
            $('#editIndividualFilePrice').val($("#file-price > td:nth-child(2)").text());
            $('#modalEditFilePrice').modal();
        });

        $(document).on('click', '#btnUpdateFilePrice', function () {
            $(this).prop('disabled', true);
            ToastMessage('info', 'İstek alındı lütfen bekleyiniz!', 'Bilgi');

            $.ajax('ServiceFees/FilePrice/' + edit_file, {
                method: 'POST',
                data: {
                    _token: token,
                    corporate_file_price: $('#editCorporteFilePrice').val(),
                    individual_file_price: $('#editIndividualFilePrice').val(),
                }
            }).done(function (response) {
                if (response.status == 1) {
                    $('.modal input[type=text]').val('');
                    ToastMessage('success', 'Dosya Fiyatları kaydedildi!', 'İşlem Başarılı!');
                    $('#modalEditFilePrice').modal('toggle');
                    getFilePrice();
                } else if (response.status == 0)
                    ToastMessage('error', response.message, 'Hata!');
                else if (response.status == -1)
                    $.each(response.errors, function (index, value) {
                        ToastMessage('error', value, 'Hata!')
                    });
            }).error(function () {
                ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
            }).always(function () {
                $('#btnUpdateFilePrice').prop('disabled', false);
            });
        });

        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }

        function getFilePrice() {
            $.ajax('ServiceFees/GetFilePrice', {
                method: 'POST',
                data: {_token: token}
            }).done(function (response) {
                if (response.status == 1) {

                    ToastMessage('success', 'Fiyat Çekildi!', 'İşlem Başarılı!');
                    $('#corporateFilePrice').html('₺' + response.price.corporate_file_price);
                    $('#individualFilePrice').html('₺' + response.price.individual_file_price);
                    $('#filePriceUpdate').html(dateFormat(response.price.updated_at));

                } else if (response.status == 0)
                    ToastMessage('error', response.message, 'Hata!');
                else if (response.status == -1)
                    $.each(response.errors, function (index, value) {
                        ToastMessage('error', value, 'Hata!')
                    });
            }).error(function () {
                ToastMessage('error', 'Bir hata oluştu lütfen daha sonra tekrar deneyiniz.', 'Hata!');
            }).always(function () {
                $('#btnUpdateFilePrice').prop('disabled', false);
            });
        }
    </script>
@endsection

@section('modals')
    {{-- Standart Modal - NewAdditionalService --}}
    <div class="modal fade" id="modalNewAdditionalService" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Ek Servis Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="additionalServiceName">Servis Adı</label>
                                <input id="additionalServiceName" class="form-control" type="text"
                                       placeholder="Örn:Öncelikli Teslimat" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="additionalServicePrice">Ek hizmet ücreti</label>
                                <input class="form-control input-mask-trigger" id="additionalServicePrice"
                                       type="text"
                                       placeholder="₺ 0.00"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnInsertAdditionalService" type="button" class="btn btn-primary">Ek Hizmet Ekle
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Edit Additional Service --}}
    <div class="modal fade" id="modalEditAdditionalService" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ek Servis Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editAdditionalServiceName">Servis Adı</label>
                                <input id="editAdditionalServiceName" class="form-control" type="text"
                                       placeholder="Örn:Öncelikli Teslimat" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editAdditionalServicePrice">Ek hizmet ücreti</label>
                                <input class="form-control input-mask-trigger" id="editAdditionalServicePrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editAdditionalServiceStatus">Statü</label>
                                <select class="form-control" name="" id="editAdditionalServiceStatus">
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnUpdateAdditionalService" type="button" class="btn btn-primary">Ek Hizmeti Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - New Desi Interval --}}
    <div class="modal fade" id="modalNewDesiInterval" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yeni Desi Aralığı Gir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="startDesi">Başlangıç Desi</label>
                                <input id="startDesi" class="form-control" type="text"
                                       value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="finishDesi">Bitiş Desi</label>
                                <input id="finishDesi" class="form-control" type="text"
                                       value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="desiPrice">Desi ücreti</label>
                                <input class="form-control input-mask-trigger" id="desiPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="corporateUnitPrice">Kurumsal Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="corporateUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="individualUnitPrice">Bireysel Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="individualUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnInsertNewDesiInterval" type="button" class="btn btn-primary">Desi Aralığını Ekle
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Edit Desi Interval --}}
    <div class="modal fade" id="modelEditDesiInterval" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Desi Aralığını Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editStartDesi">Başlangıç Desi</label>
                                <input id="editStartDesi" class="form-control" type="text"
                                       value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFinishDesi">Bitiş Desi</label>
                                <input id="editFinishDesi" class="form-control" type="text"
                                       value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="editDesiPrice">Desi ücreti</label>
                                <input class="form-control input-mask-trigger" id="editDesiPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="editCorporateUnitPrice">Kurumsal Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="editCorporateUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="editIndividualUnitPrice">Bireysel Birim Fiyatı</label>
                                <input class="form-control input-mask-trigger" id="editIndividualUnitPrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnUpdateDesiInterval" type="button" class="btn btn-primary">Desi Aralığını Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Edit File Price --}}
    <div class="modal fade" id="modalEditFilePrice" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dosya Fiyatlarını Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editCorporteFilePrice">Kurumsal dosya ücreti</label>
                                <input class="form-control input-mask-trigger" id="editCorporteFilePrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editIndividualFilePrice">Bireysel dosya ücreti</label>
                                <input class="form-control input-mask-trigger" id="editIndividualFilePrice"
                                       placeholder="₺ 0.00"
                                       type="text"
                                       data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '₺ ', 'placeholder': '0'"
                                       im-insert="true" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnUpdateFilePrice" type="button" class="btn btn-primary">Dosya Fiyatlarını Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
