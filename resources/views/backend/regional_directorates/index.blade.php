@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush

@section('title', 'Bölge Müdürlükleri')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i style="color:#ddd" class="fa fa-university con-gradient bg-amy-crisp">
                        </i>
                    </div>
                    <div> Bölge Müdürlükleri
                        <div class="page-title-subheading">Bu modül üzerinden bölge müdürlüklerini
                            listleyebilir, işlem yapablirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('rd.addRd') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Bölge Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Tüm Bölgeler
                </div>

            </div>
            <div class="card-body">

                <table id="AgenciesTable"
                       class="align-middle mb-0 table table-borderless table-striped NikolasDataTable">
                    <thead style="white-space: nowrap">
                    <tr>
                        <th>Bölge Adı</th>
                        <th>İl/İlçe</th>
                        <th>Kaps. İlçe Say.</th>
                        <th>Kaps. Acente Say.</th>
                        <th>Bölge Müdürü</th>
                        <th>Bölge Müdür Yrd.</th>
                        <th>Telefon</th>
                        <th>Kayıt Tarihi</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($data as $data)
                        <tr id="regional_directorates-item-{{ $data->id }}">
                            <td>{{ $data->name }} BÖLGE MÜDÜRLÜĞÜ</td>
                            <td>{{ $data->city . '/' . $data->district }}</td>
                            <td class="text-center">{{$data->district_covered_quantity}}</td>
                            <td class="text-center">{{$data->agency_covered_quantity}}</td>
                            <td>
                                <b>{{ $data->director_name }}</b>
                            </td>
                            <td>
                                <b>{{ $data->assistant_director_name }}</b>
                            </td>
                            <td>{{ $data->phone }}</td>
                            <td>{{ $data->created_at }}</td>
                            <td class="text-center" width="150">
                                <div class="dropdown d-inline-block">
                                    <button type="button" aria-haspopup="true" aria-expanded="false"
                                            data-toggle="dropdown"
                                            class="mb-2 mr-2 dropdown-toggle btn btn-sm btn-primary sonOfBitcj">
                                        İşlemler
                                    </button>
                                    <div
                                        style="min-width: 1rem; max-width: 140px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 29px, 0px);"
                                        role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start">


                                        <a href="{{ route('rd.RegionDistrict', ['id' => $data->id]) }}"
                                           class="dropdown-item">
                                            Bağlı İlçeler
                                        </a>

                                        <button type="button" region_id="{{ $data->id }}" tabindex="0"
                                                class="dropdown-item  region-detail">
                                            Detay
                                        </button>

                                        <a class="dropdown-item"
                                           href="{{ route('rd.EditRd', ['id' => $data->id]) }}">
                                            Düzenle
                                        </a>
                                        <button type="button" id="{{ $data->id }}" tabindex="0"
                                                from="regional_directorates" class="dropdown-item trash">
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

    <script src="/backend/assets/scripts/backend-modules.js"></script>

    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>

    <script>
        $("td:contains(ATANMADI)").addClass("text-danger");

        // and The Last Part: NikoStyle
        $(document).ready(function () {
            $('.NikolasDataTable').DataTable({
                pageLength: 10,
                lengthMenu: dtLengthMenu,
                language: dtLanguage,
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
                buttons: [
                    'copy',
                    'pdf',
                    'csv',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                        title: "CK-Bölge Müdürlükleri"
                    }
                ],
                responsive: true,
                scrollY: false
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

        function agencyPost(region_id) {
            $.post('{{ route('rd.Info') }}', {
                _token: token,
                region_id: region_id
            }, function (response) {

                console.log(response);
                let district = response.region.district;
                if (district == null) district = ' ';

                $('.RegionName').html(response.region.name + " BÖLGE MÜDÜRLÜĞÜ");
                $('#regionCityDistrict').html(response.region.city + "/" + district);
                $('#cityDistrict').html(response.region.city + "/" + district);
                $('#neighborhood').html(response.region.neighborhood);
                $('#adress').html(response.region.adress);
                $('#phone').html(response.region.phone);
                $('#regDate').html(dateFormat(response.region.created_at));
                $('#updatedDate').html(dateFormat(response.region.updated_at));

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
            $('#ModalAgencyDetail').modal()
        }

        $('button.region-detail').click(function () {
            let region_id = $(this).attr('region_id');
            agencyPost(region_id);
        });

        $(document).on('click', '.region-detail', function () {
            let region_id = $(this).attr('region_id');
            agencyPost(region_id);
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
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Bölge Müdürlüğü Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

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
                                            <h5 class="menu-header-title RegionName">###</h5>
                                            <h6 id="regionCityDistrict" class="menu-header-subtitle">###/###</h6>
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
                                                    <th class="text-center RegionName" colspan="2">Genel Merkez Acente
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
                                                    <td class="static">Kapsadığı İlçe Sayısı</td>
                                                    <td id="districtsItCovers">98</td>
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

                                        <h4 class="mt-3">Bölge Sorumluları</h4>

                                        <div style="overflow-x: scroll" class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees"
                                                   class="TableNoPadding table table-striped mt-3">
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
                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-12">
                                                <div class="p-1">
                                                    <button
                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                                        <i class="lnr-pie-chart text-dark opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Bölge Raporu
                                                    </button>
                                                </div>
                                            </div>
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
