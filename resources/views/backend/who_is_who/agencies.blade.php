@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Acenteler')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-store icon-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div> Acenteler
                        <div class="page-title-subheading">Bu modül üzerinden sistemdeki tüm acenteleri
                            listleyebilir, işlem yapablirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('agency.AddAgency') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Acente Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">

                        <div class="col-md-2">
                            <label for="agency">Acente Adı</label>
                            <input type="text" class="form-control" name="agency" id="agencyName">
                        </div>

                        <div class="col-md-2">
                            <label for="city">İl</label>
                            <select name="city" id="city" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($data['cities'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->city_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="district">İlçe</label>
                            <select name="district" id="district" disabled class="form-control">
                                <option value="">İlçe Seçin</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="dependency_tc">Bağlı Olduğu Aktarma</label>
                            <select name="dependency_tc" id="dependency_tc" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($data['tc'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->tc_name . ' T.M.'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="phone">Telefon</label>
                            <input name="phone" id="phone" data-inputmask="'mask': '(999) 999 99 99'"
                                   placeholder="(___) ___ __ __" type="text" class="form-control input-mask-trigger">
                        </div>

                        <div class="col-md-2">
                            <label for="phone">Şube Kodu</label>
                            <input name="phone" id="agencyCode" data-inputmask="'mask': '999999'"
                                   placeholder="______" type="text" class="form-control niko-filter input-mask-trigger">
                        </div>
                    </div>

                    <div class="row text-center mt-3">
                        <div class="col-md-12 text-center">
                            <button id="btn-submit" type="submit" class="btn btn-primary ">Ara</button>
                            <input type="reset" class="btn btn-secondary">
                        </div>
                    </div>

                </form>


            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Tüm Acenteler
                </div>

            </div>
            <div class="card-body">

                <table id="AgenciesTable"
                       class="align-middle mb-0 table Table30Padding table-borderless table-striped NikolasDataTable">
                    <thead>
                    <tr>
                        <th>İl/İlçe</th>
                        <th>Acente Adı</th>
                        <th>Bağ. Old. Bölge</th>
                        <th>Bağ. Old. Aktarma</th>
                        <th>Acente Sahibi</th>
                        <th>Telefon</th>
                        <th>Şube Kodu</th>
                        <th width="10" class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/city-districts-point.js"></script>

    <script>

        var oTable;

        $(document).ready(function () {
            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [
                    7, 'desc'
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
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
                buttons: [
                    'copy',
                    'pdf',
                    'csv',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },
                        title: "CK-Acenteler"
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
                    url: '{!! route('whois.GetAgencies') !!}',
                    data: function (d) {
                        d.agency_code = $('#agencyCode').val();
                        d.city = $("#city option:selected").text();
                        d.district = $("#district option:selected").text();
                        d.agency_name = $('#agencyName').val();
                        d.dependency_tc = $("#dependency_tc option:selected").text();
                        d.phone = $('#phone').val();
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'city', name: 'city'},
                    // {data: 'district', name: 'district'},
                    {data: 'agency_name', name: 'agency_name'},
                    {data: 'regional_directorates', name: 'regional_directorates'},
                    {data: 'tc_name', name: 'tc_name'},
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'phone', name: 'phone'},
                    {data: 'agency_code', name: 'agency_code'},
                    {data: 'edit', name: 'edit'},
                ],
                scrollY: false
            });
        });

    </script>
    <script>
        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });

        $('#city').change(function () {
            getDistricts('#city', '#district');
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


        $('#city_district').change(function () {
            getDistricts('#ilSelector', '#ilceSelector');
        });

        $(document).on('click', '.agency-detail', function () {
            $('#ModalUserDetail').modal();

            detailsID = $(this).prop('id');
            agencyPost($(this).prop('id'));
        });


        function agencyPost(agency_id) {
            $('#modalBodyAgencyDetail').block({
                message: $('<div class="loader mx-auto">\n' +
                    '                            <div class="ball-pulse-sync">\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                                <div class="bg-warning"></div>\n' +
                    '                            </div>\n' +
                    '                        </div>')
            });
            $('.blockUI.blockMsg.blockElement').css('width', '100%');
            $('.blockUI.blockMsg.blockElement').css('border', '0px');
            $('.blockUI.blockMsg.blockElement').css('background-color', '');

            $('#ModalAgencyDetail').modal();

            $.ajax('{{ route('whois.agencyInfo') }}', {
                method: 'POST',
                data: {
                    _token: token,
                    agency_id: agency_id
                }
            }).done(function (response) {

                var employee = response.employees;

                $('h5#agencyName').html(response.agency[0].agency_name + " ACENTE");
                $('#agencyCityDistrict').html(response.agency[0].city + "/" + response.agency[0].district);

                $('#cityDistrict').html(response.agency[0].city + "/" + response.agency[0].district);
                $('#neighborhood').html(response.agency[0].neighborhood);
                $('#adress').html(response.agency[0].adress);
                $('#phone').html(response.agency[0].phone);
                $('#phone2').html(response.agency[0].phone2);
                $('#trasfferCenter').html(response.agency[0].tc_agency_name);
                $('#regionalDirectorate').html(response.agency[0].regional_directorates != null ? response.agency[0].regional_directorates + " BÖLGE MÜDÜRLÜĞÜ" : 'Ne var');
                $('#status').html(response.agency.status == "1" ? "Aktif" : "Pasif");
                $('#agencyDevelopmentOfficer').html(response.agency[0].agency_development_officer);
                $('#agencyCode').html(response.agency[0].agency_code);
                $('#updatedDate').html(response.agency[0].updated_at);

                $('#tbodyEmployees').html('');
                if (employee.length == 0) {
                    $('#tbodyEmployees').append(
                        '<tr>' +
                        '<td class="text-center" colspan="4">Kullanıcı Yok.</td>' +
                        +'</tr>'
                    );
                } else {
                    $.each(employee, function (key, value) {

                        let email = value['email'];
                        let character = email.indexOf('@');
                        email = email.substring(0, character) + "@cumh...com.tr";


                        $('#tbodyEmployees').append(
                            '<tr>' +
                            '<td>' + (value['name_surname']) + '</td>' +
                            '<td title="' + (value['email']) + '">' + (email) + '</td>' +
                            '<td>' + (value['display_name']) + '</td>' +
                            '<td>' + (value['phone']) + '</td>' +
                            +'</tr>'
                        );
                    });
                }
            }).error(function (jqXHR, exception) {
                ajaxError(jqXHR.status)
            }).always(function () {
                $('#modalBodyAgencyDetail').unblock();
            });

        }

    </script>
@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalAgencyDetail" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Acente Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyAgencyDetail" class="modal-body">

                    {{-- CARD START --}}
                    <div class="col-md-12">
                        <div class="mb-3 profile-responsive card">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-dark">
                                    <div class="menu-header-image "
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract10.jpg');">
                                    </div>
                                    <div class="menu-header-content btn-pane-right">
                                        <div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                            <div class="avatar-icon rounded">
                                                <img src="/backend/assets/images/ck-ico-white.png" alt="Avatar 5">
                                            </div>
                                        </div>
                                        <div>
                                            <h5 id="agencyName" class="menu-header-title">###</h5>
                                            <h6 id="agencyCityDistrict" class="menu-header-subtitle">###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: scroll" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" colspan="2">Genel Merkez Acente</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">İl İlçe</td>
                                                    <td id="cityDistrict">İstanbul/Bağcılar</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Mahalle</td>
                                                    <td id="neighborhood">Mecidiye Köy</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Adres</td>
                                                    <td id="adress">Adres Satırı</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Telefon(1)</td>
                                                    <td data-inputmask="'mask': '(999) 999 99 99'" id="phone">535 427 68
                                                        24
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Telefon(2)</td>
                                                    <td data-inputmask="'mask': '(999) 999 99 99'" id="phone2">535 427
                                                        68 24
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Bağlı Olduğu Aktarma</td>
                                                    <td id="trasfferCenter">İkitelli Transfer Merkezi</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Bağlı Olduğu Bölge Müdürlüğü</td>
                                                    <td id="regionalDirectorate">Marmara Bölge Müdürlüğü</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Statü</td>
                                                    <td id="status">Aktif</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Acente Geliştirme Sorumlusu</td>
                                                    <td id="agencyDevelopmentOfficer">Zühra Orak</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Şube Kodu</td>
                                                    <td id="agencyCode">021234</td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Son Güncellenme Tarihi</td>
                                                    <td id="updatedDate">535 427 68 24</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <h4 class="mt-3">Acente Çalışanları</h4>

                                        <div style="overflow: auto; max-height: 150px;" class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees"
                                                   class="Table20Padding  table table-bordered table-striped mt-3">
                                                <thead>
                                                <tr>
                                                    <th>Ad Soyad</th>
                                                    <th>E-Posta</th>
                                                    <th>Yetki</th>
                                                    <th>İletişim</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyEmployees">

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- CARD END --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
@endsection
