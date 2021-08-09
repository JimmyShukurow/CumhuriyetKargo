@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
    <link href="/backend/assets/css/select2.min.css" rel="stylesheet"/>
    <link href="/backend/assets/css/select2-mini.css" rel="stylesheet"/>
@endpush

@section('title', 'Mahalli Lokasyon')

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fas lnr-map icon-gradient bg-plum-plate"></i>
                    </div>
                    <div> Mahalli Lokasyon
                        <div class="page-title-subheading">Türkiye geneli acentelerin dağıtım alanlarını bu modül
                            üzerinden görüntüleyebilir, düzenleyenilirsiniz. (Filtreleme işlemi sadece acente seçilerek
                            yapılabilir.)
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('SenderCurrents.create') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
						<span class="btn-icon-wrapper pr-2 opacity-7">
							<i class="fa fa-plus fa-w-20"></i>
						</span>
                                Yeni Cari Oluştur
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-map mr-3 text-muted opacity-6"> </i>Mahalli Lokasyon
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
                <form id="search-form">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="position-relative form-control-sm form-group">
                                <label for="link" class="">İl:</label>
                                <select name="city" id="city"
                                        class="form-control form-control-sm">
                                    <option value="">İl Seçiniz</option>
                                    @foreach($data['cities'] as $city)
                                        <option id="{{$city->id}}" data="{{$city->city_name}}"
                                                value="{{$city->id}}">{{$city->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="district" class="">İlçe*</label>
                                <select name="district" id="district"
                                        class="form-control form-control-sm">
                                    <option value="">İlçe Seçiniz</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="agency">Acente</label>
                            <select name="agency" id="selectAgency"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option
                                        value="{{$key->id}}">{{$key->agency_name . ' ACENTE'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="button" style="width: 100%;" id="btnGiveNeighborhood"
                                    class="mt-4 btn-icon btn-shadow btn-outline-2x btn btn-outline-light">
                                <i class="lnr-plus-circle btn-icon-wrapper"></i>Acenteye Bağlanacak Mahalleler
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-borderless table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>İl</th>
                        <th>İlçe</th>
                        <th>Mahalle</th>
                        <th>Dağıtan</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>İl</th>
                        <th>İlçe</th>
                        <th>Mahalle</th>
                        <th>Dağıtan</th>
                        <th>İşlem</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>

    <script>

        $(document).on('change', '#cityX', function () {
            getDistricts('#cityX', '#districtX');
        });

        $(document).on('change', '#districtX', function () {

            $.ajax('/Operation/GetNeighborhoodsOfAgency', {
                method: 'POST',
                data: {
                    _token: token,
                    agency: $('#selectAgency').val(),
                    city: $('#cityX').val(),
                    district: $('#districtX').val(),
                }
            });
        });

        $(document).ready(function () {

            $('#district').prop('disabled', true);

            $('#city').change(function () {

                let city_id = $(this).children(":selected").attr("id");
                let city_name = $('#city').children(":selected").attr("data");
                let district_name = $('#district').children(":selected").attr("data");

                $.post('{{route('ajax.city.to.district')}}', {
                    _token: token,
                    city_id: city_id
                }, function (response) {

                    $('#district').html('');
                    $('#district').append(
                        '<option  value="">İlçe Seçin</option>'
                    );
                    $('#neighborhood').prop('disabled', true);
                    $.each(response, function (key, value) {
                        $('#district').append(
                            '<option data="' + (value['name']) + '" id="' + (value['key']) + '"  value="' + (value['key']) + '">' + (value['name']) + '</option>'
                        );
                    });
                    $('#district').prop('disabled', false);
                });

                getAgencies(city_name, district_name);
            });

            $('#district').change(function () {

                var district_id = $(this).children(":selected").attr("id");
                let city_name = $('#city').children(":selected").attr("data");
                let district_name = $('#district').children(":selected").attr("data");

                $.post('{{route('ajax.district.to.neighborhood')}}', {
                    _token: token,
                    district_id: district_id
                }, function (response) {

                    $('#neighborhood').html('');
                    $('#neighborhood').append(
                        '<option  value="">Mahalle Seçin</option>'
                    );
                    $.each(response, function (key, value) {
                        $('#neighborhood').append(
                            '<option id="' + (value['key']) + '"  value="' + (value['name']) + '">' + (value['name']) + '</option>'
                        );
                    });
                });
                getAgencies(city_name, district_name);
            });

            function getAgencies(city = '', district = '') {

                $.ajax('/Ajax/GetAgency', {
                    method: 'POST',
                    data: {
                        _token: token,
                        city: city,
                        district: district
                    }
                }).done(function (response) {

                    $('#selectAgency').html('<option value="">Seçiniz</option>');

                    $.each(response, function (key, value) {
                        $('#selectAgency').append(
                            '<option id="' + (value['id']) + '"  value="' + (value['id']) + '">' + (value['agency_name']) + ' ACENTE </option>'
                        );
                    });

                }).error(function (jqXHR, exception) {
                    ajaxError(jqXHR.status)
                }).always(function () {
                    $('#modalBodySelectCustomer').unblock();
                });


            }
        });
    </script>


    <script>

        var oTable;
        var detailsID = null;
        // and The Last Part: NikoStyle
        $(document).ready(function () {
            $('#agency').select2();
            $('#creatorUser').select2();

            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 25,
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
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
                buttons: [
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        },
                        title: "CK - Gönderici Cariler"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Sütun Görünüm'
                    },
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('operation.getLocation') !!}',
                    data: function (d) {
                        d.city = $('#city').val();
                        d.district = $('#district').val();
                        d.neighborhood = $('#neighborhood').val();
                        d.agency = $('#agency').val();
                    },
                    error: function (xhr, error, code) {
                        ajaxError(xhr.status);
                    },
                    complete: function () {
                        ToastMessage('info', 'Tamamlandı!', 'Bilgi');
                    }
                },
                columns: [
                    {data: 'current_code', name: 'current_code'},
                    {data: 'category', name: 'category'},
                    {data: 'name', name: 'name'},
                    {data: 'city', name: 'city'},
                    {data: 'edit', name: 'edit'}
                ],
                scrollY: "400px",
            });
        });

        function drawDT() {
            oTable.draw();
        }

        $('.niko-select-filter').change(delay(function (e) {
            drawDT();
        }, 1000));

        $('.niko-filter').keyup(delay(function (e) {
            drawDT();
        }, 1000));

        $('#btnClearFilter').click(function () {
            $('#search-form').trigger("reset");
            $('#select2-creatorUser-container').text('Seçiniz');
            $('#select2-agency-container').text('Seçiniz');
            drawDT();
        });


        $('#btnGiveNeighborhood').click(delay(function () {
            if ($('#selectAgency').val() == '')
                ToastMessage('warning', '', 'Önce acente seçin!');
            else {
                $('#ModalCityDistrictNeighborhoods').modal();
            }


        }, 450));


    </script>
@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade" id="ModalCityDistrictNeighborhoods" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalCityDistrictsTitle">Mahalle Seçin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyTCDistricts" class="modal-body">
                    <p class="mb-0">Aktarmaya bağlanacak il ve ilçeleri aşağıdan seçebilirsiniz.</p>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <select name="cityX" id="cityX" required class="form-control">
                                    <option value="">İl Seçiniz</option>
                                    @foreach ($data['cities'] as $city)
                                        <option {{ old('city') == $city->id ? 'selected' : '' }} id="{{ $city->id }}"
                                                value="{{ $city->id }}">{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <select name="districtX" id="districtX" required class="form-control">
                                    <option value="">İlçe Seçiniz</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <table style="margin-bottom:  0;" class="table table-striped">
                        <thead>
                        <th style="width: 50px;padding:0">
                            <input style="width: 20px;margin-left: 7px;" type="checkbox"
                                   class="select-all-cb form-control">
                        </th>
                        <th>Mahalle</th>
                        </thead>
                    </table>
                    <div style="max-height: 50vh; overflow-y: scroll;">
                        <table id="TableRegionalDistricts" class="table table-striped">
                            <tbody id="TbodyNeighborhoods"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">İptal Et</button>
                    <button type="button" class="btn btn-primary" id="SaveBtn" data-dismiss="modal">Kaydet</button>
                </div>
            </div>
        </div>
    </div>
@endsection
