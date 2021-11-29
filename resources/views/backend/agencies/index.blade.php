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
                            listleyebilir, işlem yapablirsiniz. <b class="text-danger">Dikkat: Acente silme işlemi
                                sonrası acentenin lokasyon bilgisi de silinecektir. Bu işlem geri alınamaz.</b>
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

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-store mr-3 text-muted opacity-6"> </i>Tüm Acenteler
                </div>

            </div>

            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="filter_City">İl:</label>
                                <select id="filter_City" class="form-control form-control-sm">
                                    <option value="">İl Seçiniz</option>
                                    @foreach($data['cities'] as $key)
                                        <option value="{{$key->id}}">{{$key->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="filter_District">İlçe:</label>
                                <select disabled id="filter_District" class="form-control form-control-sm">
                                    <option value="">İlçe Seçiniz</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="filter_RegionalDirectorate">Bağlı Olduğu Bölge Mdr.:</label>
                                <select id="filter_RegionalDirectorate" class="form-control form-control-sm">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['regional_directorates'] as $key)
                                        <option value="{{$key->id}}">{{$key->name . ' B.M.'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="filter_TransshipmentCenter">Bağlı Olduğu TRM.:</label>
                                <select id="filter_TransshipmentCenter" class="form-control form-control-sm">
                                    <option value="">Seçiniz</option>
                                    @foreach($data['transshipment_centers'] as $key)
                                        <option value="{{$key->id}}">{{$key->tc_name . ' TRM.'}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="filter_AgencyCode">Şube Kodu:</label>
                                <input id="filter_AgencyCode" data-inputmask="'mask': '9999'"
                                       placeholder="____" type="text" value=""
                                       class="form-control form-control-sm input-mask-trigger" im-insert="true">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="filter_AgencyName">Şube Adı:</label>
                                <input type="text" class="form-control-sm form-control" id="filter_AgencyName">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="filter_NameSurname">Şube Sahibi:</label>
                                <input type="text" class="form-control-sm form-control" id="filter_NameSurname">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group position-relative">
                                <label for="filter_Status">Statü:</label>
                                <select id="filter_Status" class="form-control form-control-sm">
                                    <option value="">Seçiniz</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Pasif">Pasif</option>
                                </select>
                            </div>
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

            <div class="card-body">

                <table id="AgenciesTable"
                       class="align-middle mb-0 table Table30Padding table-borderless table-striped NikolasDataTable">
                    <thead>
                    <tr>
                        <th>İl/İlçe</th>
                        <th>Şube Adı</th>
                        <th>Bağ. Old. Bölge</th>
                        <th>Bağ. Old. Aktarma</th>
                        <th>Acente Sahibi</th>
                        <th>Telefon</th>
                        <th>Şube Kodu</th>
                        <th>Statü</th>
                        <th>Pers. Sayısı</th>
                        <th>Kayıt Tarihi</th>
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

        $(document).ready(function () {
            $('#filter_City').change(function () {
                getDistricts('#filter_City', '#filter_District');
            });
        });

        $(document).ready(function () {
            var oTable = $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [
                    9, 'desc'
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
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
                    url: '{!! route('agency.getAgencies') !!}',
                    data: function (d) {
                        d.city = $('#filter_City').val();
                        d.district = $('#filter_District').val();
                        d.regionalDirectorate = $('#filter_RegionalDirectorate').val();
                        d.transshipmentCenter = $('#filter_TransshipmentCenter').val();
                        d.agencyCode = $('#filter_AgencyCode').val();
                        d.agencyName = $('#filter_AgencyName').val();
                        d.nameSurname = $('#filter_NameSurname').val();
                        d.status = $('#filter_Status').val();
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
                    {data: 'status', name: 'status'},
                    {data: 'employee_count', name: 'employee_count'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'edit', name: 'edit'},
                ],
                scrollY: "500px",
            });

            $('#search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });
        });


    </script>
    <script>
        // parse a date in yyyy-mm-dd format
        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }

        function agencyPost(agency_id) {
            $.post('{{ route('agency.Info') }}', {
                _token: token,
                agency_id: agency_id
            }, function (response) {

                $('#agencyName').html(response.agency[0].agency_name + " ŞUBE");
                $('#thMainTitle').html(response.agency[0].agency_name + " ŞUBE");
                $('#agencyCityDistrict').html(response.agency[0].city + "/" + response.agency[0].district);


                $('#cityDistrict').html(response.agency[0].city + "/" + response.agency[0].district);
                $('#neighborhood').html(response.agency[0].neighborhood);
                $('#adress').html(response.agency[0].adress);
                $('#phone').html(response.agency[0].phone);
                $('#phone2').html(response.agency[0].phone2);
                $('#transshipmentCenter').html(response.agency[0].tc_name + " TRM.");
                $('#regionalDirectorate').html(response.agency[0].regional_directorates != null ? response.agency[0].regional_directorates + " BÖLGE MÜDÜRLÜĞÜ" : '');
                $('#status').html(response.agency.status == "1" ? "Aktif" : "Pasif");
                $('#statusDescription').html(response.agency[0].status_description);
                $('#agencyDevelopmentOfficer').html(response.agency[0].agency_development_officer);
                $('#agencyCode').html(response.agency[0].agency_code);
                $('#regDate').html(dateFormat(response.agency[0].created_at));
                $('#updatedDate').html(dateFormat(response.agency[0].updated_at));

                $('#tbodyEmployees').html('');

                if (response.employees.length == 0) {
                    $('#tbodyEmployees').append(
                        '<tr>' +
                        '<td class="text-center" colspan="4">Kullanıcı Yok.</td>' +
                        +'</tr>'
                    );
                } else {
                    $.each(response.employees, function (key, value) {
                        $('#tbodyEmployees').append(
                            '<tr>' +
                            '<td>' + (value['name_surname']) + '</td>' +
                            '<td>' + (value['email']) + '</td>' +
                            '<td>' + (value['display_name']) + '</td>' +
                            '<td>' + (value['phone']) + '</td>' +
                            +'</tr>'
                        );
                    });
                }
            });

            $('#ModalAgencyDetail').modal();
        }

        $('button.agency-detail').click(function () {
            let agency_id = $(this).attr('agency_id');
            agencyPost(agency_id);
        });

        $(document).on('click', '.agency-detail', function () {
            let agency_id = $(this).attr('agency_id');
            agencyPost(agency_id);
        });

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
                <div style="max-height: 65vh; overflow-y: auto;" class="modal-body">

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

                                        <div style="overflow-x: auto" class="cont">
                                            <table style="" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th id="thMainTitle" class="text-center" colspan="2">Genel Merkez
                                                        Acente
                                                    </th>
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
                                                        68
                                                        24
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Bağlı Olduğu Aktarma</td>
                                                    <td id="transshipmentCenter">İkitelli Transfer Merkezi</td>
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
                                                    <td class="static">Statü Açıklama</td>
                                                    <td id="statusDescription"></td>
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
                                                    <td class="static">Kayıt Tarihi</td>
                                                    <td id="regDate">535 427 68 24</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Son Güncellenme Tarihi</td>
                                                    <td id="updatedDate">535 427 68 24</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <h4 class="mt-3">Acente Kullanıcıları</h4>

                                        <div style="" class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees"
                                                   class="TableNoPadding table-bordered table table-striped mt-3">
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
                <li class="p-0 list-group-item">
                    <div class="grid-menu grid-menu-2col">
                        <div class="no-gutters row">
                            <div class="col-sm-6">
                                <div class="p-1">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                        <i class="lnr-lighter text-dark opacity-7 btn-icon-wrapper mb-2">
                                        </i>
                                        Kargo Geçmişine Git
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-1">
                                    <button
                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                        <i
                                            class="lnr-construction text-danger opacity-7 btn-icon-wrapper mb-2">
                                        </i>
                                        Acenteyi Kapat
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </li>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
@endsection
