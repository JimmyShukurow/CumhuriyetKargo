@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Transfer Merkezleri (Aktarmalar)')


@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i style="color:#000" class="fa fa-truck con-gradient">
                        </i>
                    </div>
                    <div> Transfer Merkezleri (Aktarmalar)
                        <div class="page-title-subheading">Bu modül üzerinden transfer merkezlerini
                            listleyebilir, işlem yapablirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('TransshipmentCenters.create') }}">
                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                    class="btn-shadow btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>
                                Yeni Tramsfer Merkezi Ekle
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i
                        class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Tüm Aktarmalar
                </div>

            </div>
            <div class="card-body">

                <table id="AgenciesTable"
                       class="align-middle mb-0 table table-borderless table-striped NikolasDataTable">
                    <thead style="white-space: nowrap">
                    <tr>
                        <th>Aktarma Adı</th>
                        <th>İl/İlçe</th>
                        <th>Tip</th>
                        <th>Bağ. Şube Say.</th>
                        <th>Aktarma Müdürü</th>
                        <th>Aktarma Müdür Yrd.</th>
                        <th>Telefon</th>
                        <th>Kayıt Tarihi</th>
                        <th class="text-center"></th>
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

    <script src="/backend/assets/scripts/delete-method.js"></script>

    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script>
        $("td:contains(ATANMADI)").addClass("text-danger");

        var oTable;

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
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        },
                        title: "CK-Transfer Merkezleri - {{date('d/m/Y H:i')}}"
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
                ajax: '{!! route('TransshipmentCenters.getTC') !!}',
                columns: [
                    // {data: 'district', name: 'district'},
                    {data: 'tc_name', name: 'tc_name'},
                    {data: 'city', name: 'city'},
                    {data: 'type', name: 'type'},
                    {data: 'agency_quantity', name: 'agency_quantity'},
                    {data: 'director_name', name: 'director_name'},
                    {data: 'assistant_director_name', name: 'assistant_director_name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'edit', name: 'edit'},
                ],
                scrollY: "500px",
            });
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
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

        function agencyPost(tc_id) {
            $.post('{{ route('TransshipmentCenters.info') }}', {
                _token: token,
                tc_id: tc_id
            }, function (response) {

                console.log(response);


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
            $('#ModalTCDetail').modal()
        }


        $(document).on('click', '.transshipment-center-detail', function () {
            let tc_id = $(this).attr('tc_id');
            let neighborhood = $(this).attr('neighborhood');
            let adress = $(this).attr('adress');

            $('.tcName').text($("#transshipment_center-item-" + tc_id + " > td:nth-child(1)").text());
            $('.city').text($("#transshipment_center-item-" + tc_id + " > td:nth-child(2)").text());
            $('#neighborhood').text(neighborhood);
            $('#adress').text(adress);
            $('#districtsItCovers').text($("#transshipment_center-item-" + tc_id + " > td:nth-child(3)").text());
            $('#phone').text($("#transshipment_center-item-" + tc_id + " > td:nth-child(6)").text());
            $('#regDate').text($("#transshipment_center-item-" + tc_id + " > td:nth-child(7)").text());

            agencyPost(tc_id);
        });

    </script>
@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalTCDetail" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Transfer Merkezi Detayları</h5>
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
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract4.jpg');">
                                    </div>
                                    <div class="menu-header-content btn-pane-right">
                                        <div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                            <div class="avatar-icon rounded">
                                                <img src="/backend/assets/images/ck-ico-white.png" alt="Avatar 5">
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="menu-header-title tcName">###</h5>
                                            <h6 id="city" class="menu-header-subtitle city">###/###</h6>
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
                                                    <th class="text-center tcName" colspan="2">Genel Merkez Acente
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">İl İlçe</td>
                                                    <td class="city">İstanbul/Bağcılar</td>
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
                                                    <td class="static">Aktarmaya Bağlı Şube Sayısı</td>
                                                    <td id="districtsItCovers">98</td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Kayıt Tarihi</td>
                                                    <td id="regDate">535 427 68 24</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <h4 class="mt-3">Aktarma Yöneticileri</h4>

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
                                                        Transfer Merkezi Raporu
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
