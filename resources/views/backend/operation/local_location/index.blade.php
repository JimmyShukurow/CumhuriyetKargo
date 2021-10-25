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
                    {{--                    <div class="d-inline-block dropdown">--}}
                    {{--                        <a href="{{ route('SenderCurrents.create') }}">--}}
                    {{--                            <button type="button" aria-haspopup="true" aria-expanded="false"--}}
                    {{--                                    class="btn-shadow btn btn-info">--}}
                    {{--						<span class="btn-icon-wrapper pr-2 opacity-7">--}}
                    {{--							<i class="fa fa-plus fa-w-20"></i>--}}
                    {{--						</span>--}}
                    {{--                                Yeni Cari Oluştur--}}
                    {{--                            </button>--}}
                    {{--                        </a>--}}
                    {{--                    </div>--}}
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
                                        class="form-control niko-select-filter form-control-sm">
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
                                        class="form-control form-control-sm niko-select-filter">
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
                                        value="{{$key->id}}">{{$key->agency_name . ' ŞUBE'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="button" style="width: 100%;" id="btnGiveNeighborhood"
                                    class="mt-4 btn-icon btn-shadow btn-outline-2x btn btn-outline-alternate">
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
                       class="align-middle mb-0 table Table20Padding table-bordered table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>İl</th>
                        <th>İlçe</th>
                        <th>Mahalle</th>
                        <th>Bölge Tipi</th>
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
                        <th>Bölge Tipi</th>
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
            $('#TbodyNeighborhoods').html('');
        });

        $(document).on('change', '.select-all-cb-ab', function () {
            $('input:checkbox:not(:disabled).location-ab').prop('checked', this.checked);

            if ($(this).prop('checked') == true) {
                $('input:checkbox:not(:disabled).location-mb').prop('checked', false);
                $('#cb-select-all-mb').prop('checked', false);
            }

        });

        $(document).on('change', '.select-all-cb-mb', function () {
            $('input:checkbox:not(:disabled).location-mb').prop('checked', this.checked);

            if ($(this).prop('checked') == true) {
                $('input:checkbox:not(:disabled).location-ab').prop('checked', false);
                $('#cb-select-all-ab').prop('checked', false);
            }

        });


        $(document).on('click', '.location-mb', function () {
            let cb_id = $(this).prop('value');

            if ($(this).prop('checked') == true)
                $('#ab-' + cb_id).prop('checked', false);
        });

        $(document).on('click', '.location-ab', function () {
            let cb_id = $(this).prop('value');

            if ($(this).prop('checked') == true)
                $('#mb-' + cb_id).prop('checked', false);
        });


        $(document).on('click', '#SaveBtn', function () {

            $('#SaveBtn').prop('disabled', true);

            var NeighborhoodArray = [];
            $("input.check-give-district-to-region[type=checkbox]:checked:not(:disabled)").each(function () {
                NeighborhoodArray.push($(this).prop('id'));
            });

            $.ajax('{{ route('LocalLocation.store') }}', {
                method: 'POST',
                data: {
                    _token: token,
                    city_id: $('#cityX').val(),
                    district_id: $('#districtX').val(),
                    agency_code: $('#selectAgency').val(),
                    neighborhood_array: NeighborhoodArray,
                }
            }).done(function (response) {

                if (response.status == 1) {
                    ToastMessage('success', 'Mahalleler tanımlandı!', 'İşlem Başarılı!');
                    $('#ModalCityDistrictNeighborhoods').modal('hide');
                    $('#districtX').val('');
                    $('#city').val('');
                    $('#TbodyNeighborhoods').html('');
                    $('#AgenciesTable').DataTable().ajax.reload();
                } else {
                    ToastMessage('error', response.message, 'Hata!');
                }

                $('#TbodyRegionalDistricts').html('');
                $('.select-all-cb').prop('checked', false);

                $('#SaveBtn').prop('disabled', false);


            }).always(function () {
                $('#SaveBtn').prop('disabled', false);
            }).error(function (jqXHR, exception) {
                ajaxError(jqXHR.status)
            });

        });


        $(document).on('change', '#districtX', function () {

            $('#modalBodyTCDistricts.modal-body').block({
                message: $('<div class="loader mx-auto">\n' +
                    '                            <div class="ball-pulse-sync">\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                            </div>\n' +
                    '                        </div>')
            });
            $('.blockUI.blockMsg.blockElement').css('border', '0px');
            $('.blockUI.blockMsg.blockElement').css('background-color', '');

            $.ajax('/Operation/GetNeighborhoodsOfAgency', {
                method: 'POST',
                data: {
                    _token: token,
                    agency: $('#selectAgency').val(),
                    city: $('#cityX').val(),
                    district: $('#districtX').val(),
                }
            }).done(function (response) {

                $('#TbodyNeighborhoods').html('');

                $.each(response, function (key, value) {

                    let stringAB = '', stringMB = '', stringAgencyName = '';

                    if (value['count'] == '1') {
                        if (value['area_type'] == 'AB') {
                            stringAB = 'checked disabled';
                            stringMB = 'disabled'
                        } else if (value['area_type'] == 'MB') {
                            stringMB = 'checked disabled';
                            stringAB = 'disabled';
                        }
                    }


                    if (value['agency_name'] != null) {
                        stringAgencyName = ' (' + value['agency_name'] + ')';
                    }

                    $('#TbodyNeighborhoods').append(
                        '<tr>' +

                        '<td width="10"> <input style="width: 20px"' +
                        ' value="' + (value['neighborhood_id']) + '" type="checkbox" ' +
                        (stringAB) + ' id="ab-' + (value['neighborhood_id']) + '"' +
                        ' class="form-control check-give-district-to-region location-ab ck-' +
                        (value['neighborhood_id']) +
                        '">' + '</td>' +

                        '<td width="10"> <input style="width: 20px" ' +
                        'value="' + (value['neighborhood_id']) + '" type="checkbox" ' + (stringMB) +
                        ' id="mb-' + (value['neighborhood_id']) + '"' + ' class="form-control check-give-district-to-region location-mb  ck-' +
                        (value['neighborhood_id']) +
                        '">' + '</td>' +

                        '<td>' + (value['neighborhood_name']) + stringAgencyName + '</td>' +

                        '</tr>'
                    );
                });

                $('.select-all-cb').prop('checked', false);

                $('#modalBodyTCDistricts.modal-body').unblock();

            });
        });

        $(document).ready(function () {

            $('#district').prop('disabled', true)
            $('#selectAgency').val('');

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
                            columns: [0, 1, 2, 3, 4]
                        },
                        title: "CK - Mahalli Lokasyon"
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
                        d.agency = $('#selectAgency').val();
                    },
                    error: function (xhr, error, code) {
                        ajaxError(xhr.status);
                    },
                    complete: function () {

                    }
                },
                columns: [
                    {data: 'city', name: 'city'},
                    {data: 'district', name: 'district'},
                    {data: 'neighborhood', name: 'neighborhood'},
                    {data: 'area_type', name: 'area_type'},
                    {data: 'agency_name', name: 'agency_name'},
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
                $('#districtX').val('');
                $('#TbodyNeighborhoods').html('');
                $('#cb-select-all-mb').prop('checked', false);
                $('#cb-select-all-ab').prop('checked', false);
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
                        <th title="Ana Bölge" style="width: 50px;padding:0">
                            (AB) <input style="width: 20px;margin-left: 7px;" type="checkbox" id="cb-select-all-ab"
                                        class="select-all-cb-ab form-control">
                        </th>
                        <th title="Mobil Bölge" style="width: 50px;padding:0">
                            (MB) <input style="width: 20px;margin-left: 7px;" type="checkbox" id="cb-select-all-mb"
                                        class="select-all-cb-mb form-control">
                        </th>
                        <th style="vertical-align: middle;">Mahalle</th>
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
                    <button type="button" class="btn btn-primary" id="SaveBtn">Kaydet</button>
                </div>
            </div>
        </div>
    </div>
@endsection
