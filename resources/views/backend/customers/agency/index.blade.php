@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Tüm Müşterileriniz')

@section('content')

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-ripe-malin">
                        </i>
                    </div>
                    <div>Tüm Müşterileriniz
                        <div class="page-title-subheading">
                            Oluşturduğunuz gönderici ve alıcıları buradan listeleyebilirsiniz.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ route('user.gm.AddUser') }}">
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

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" id="search-form">
                    <div class="row">

                        <div class="col-md-2">
                            <label for="customer_type">Müşteri Tipi</label>
                            <select name="customer_type" id="customer_type" class="form-control form-control-sm ">
                                <option value="">Tümü</option>
                                <option value="Alıcı">Alıcı</option>
                                <option value="Gönderici">Gönderici</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="category">Kategori</label>
                            <select name="category" id="category" class="form-control form-control-sm ">
                                <option value="">Tümü</option>
                                <option value="Bireysel">Bireysel</option>
                                <option value="Kurumsal">Kurumsal</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="currentCode">Cari Kod</label>
                            <input name="currentCode" id="currentCode" data-inputmask="'mask': '999 999 999'"
                                   placeholder="___ ___ ___" type="text"
                                   class="form-control input-mask-trigger form-control-sm niko-filter" im-insert="true">
                        </div>

                        <div class="col-md-2">
                            <label for="customer_name_surname">Müşteri Adı</label>
                            <input type="text" class="form-control form-control-sm " name="customer_name_surname"
                                   id="customer_name_surname">
                        </div>

                        <div class="col-md-2 ">
                            <div class="position-relative form-group">
                                <label for="phone" class="">Telefon </label>
                                <input name="phone" id="phone" data-inputmask="'mask': '(999) 999 99 99'"
                                       placeholder="(___) ___ __ __" type="text" value=""
                                       class="form-control form-control-sm input-mask-trigger" im-insert="true">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="city">Şehir</label>
                            <select name="city" id="city" class="form-control form-control-sm ">
                                <option value="">Seçiniz</option>
                                @foreach($data['cities'] as $key)
                                    <option
                                        value="{{$key->city_name}}">{{ $key->city_name }}</option>
                                @endforeach
                            </select>
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
                        class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>Tüm Kullanıcılar
                </div>

            </div>
            <div class="card-body">

                <table style="white-space: nowrap;" id="AgenciesTable"
                       class="align-middle mb-0 table Table20Padding table-borderless table-striped table-hover NikolasDataTable">
                    <thead>
                    <tr>
                        <th>Cari Kodu</th>
                        <th>Müşteri Tipi</th>
                        <th>Ad</th>
                        <th>City</th>
                        <th>District</th>
                        <th>Neighborhood</th>
                        <th>Tel</th>
                        <th>Tel 2</th>
                        <th>Kayıt Yapan</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Cari Kodu</th>
                        <th>Müşteri Tipi</th>
                        <th>Ad</th>
                        <th>City</th>
                        <th>District</th>
                        <th>Neighborhood</th>
                        <th>Tel</th>
                        <th>Tel 2</th>
                        <th>Kayıt Yapan</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.blockUI.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <style type="text/css">
        pre#json-renderer {
            border: 1px solid #aaa;
        }
    </style>

    <script>
        var oTable;
        var detailsID = null;
        // and The Last Part: NikoStyle
        $(document).ready(function () {
            oTable = $('.NikolasDataTable').DataTable({
                pageLength: 25,
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
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',
                buttons: [
                    'pdf',
                    'print',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        },
                        title: "CK - Sistem Kullanıcıları"
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
                    url: '{!! route('customer.gm.getAllCustomers') !!}',
                    data: function (d) {
                        d.customer_type = $('#customer_type').val();
                        d.category = $('#category').val();
                        d.currentCode = $('#currentCode').val();
                        d.customer_name_surname = $('#customer_name_surname').val();
                        d.phone = $('#phone').val();
                        d.city = $('#city').val();
                    },
                    error: function (xhr, error, code) {
                        if (code == "Too Many Requests") {
                            ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        }
                    }
                },
                columns: [
                    {data: 'current_code', name: 'current_code'},
                    {data: 'current_type', name: 'current_type'},
                    {data: 'name', name: 'name'},
                    {data: 'city', name: 'current_type'},
                    {data: 'district', name: 'current_type'},
                    {data: 'neighborhood', name: 'current_type'},
                    {data: 'phone', name: 'current_type'},
                    {data: 'phone2', name: 'current_type'},
                    {data: 'name_surname', name: 'name_surname'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'edit', name: 'edit'},

                ],
                scrollY: "400px",
            });
        });

        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });

        // parse a date in yyyy-mm-dd format
        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }

        function fillCargo(tbodyId, cargo) {
            var mytbodyId = "#" + tbodyId;
            console.log(mytbodyId);
            $.each(cargo, function (index, value) {
                    $(mytbodyId).append(
                        '<tr>' +
                            '<td>' + arrangeCargoTrackingNumber(String(value.tracking_no ))+ '</td>' +
                            '<td class="font-weight-bold">' + value.sender_name + '</td>' +
                            '<td class="font-weight-bold">' + value.receiver_name + '</td>' +
                            '<td class="font-weight-bold text-success">' + value.status + '</td>' +
                            '<td class="text-primary">' + value.cargo_type + '</td>' +
                            '<td class="font-weight-bold text-primary">' + value.total_price + '₺' + '</td>' +
                            '<td>' + '<button type="button" class="btn btn-sm btn-primary">Detay</button>' + '</td>' +
                        '</tr>'
                    )
                }
            )
        }


        $(document).on('click', '.user-detail', function () {
            console.log(arrangeCargoTrackingNumber('340212342895925'));
            ToastMessage('warning', '', 'Yükleniyor');
            detailsID = $(this).prop('id');
            userInfo(detailsID);
        });

        var array = new Array();

        function userInfo(user) {
            $.ajax('/Users/GetCustomerInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    user: user
                },
                cache: false
            }).done(function (response) {

                var current = response.data[0];
                var category = current.category;
                var current_type = current.current_type;
                let addressNote = adresMaker(current.city, current.district, current.neighborhood, current.street, current.street2, current.building_no, current.door_no, current.floor, current.address_note);
                let branch_office = current.agencies_city + " / " + current.agencies_district + " / " + current.agency_name + " Acente";

                console.log(response.cargo)
                let cargo = response.cargo;

                if (current_type == 'Gönderici' && category == 'Bireysel') {
                    fillCargo('tbodyUserTopTenSenderPersonal', cargo);

                    $('#ModalSenderPersonal').modal();
                    $('#senderPersonalCustomerType-1').html(category);
                    $('#senderPersonalCustomerTckn').html(current.tckn)
                    $('#senderPersonalNameSurname').html(current.name);
                    $('#senderPersonalCustomerEmail').html(current.email);

                    $('#senderPersonalCustomerCity').html(current.city);
                    $('#senderPersonalCustomerDistrict').html(current.district);
                    $('#senderPersonalCustomerNeighborhood').html(current.neighborhood);

                    $('#senderPersonalCustomerPhone').html(current.phone);
                    $('#senderPersonalCustomer-phone-2').html(current.phone2);
                    $('#senderPersonalCustomerAdress').html(addressNote);

                    $('#senderPersonalCustomerVknCustomerVkn').html(current.vkn);

                    $('#senderPersonalCustomerName').html(current.name);
                    $('#senderPersonalCustomerType').html(current_type);

                } else if (current_type == 'Gönderici' && category == 'Kurumsal') {

                    fillCargo('tbodyUserTopTenSenderCorporate', cargo);

                    $('#ModalSenderCorporate').modal();

                    $('#senderCorporateCustomerType-1').html(category);

                    $('#senderCustomerTckn').html(current.tckn)
                    $('#senderCustomerNameSurname').html(current.name);
                    $('#senderCustomerEmail').html(current.email);

                    $('#senderCustomerCity').html(current.city);
                    $('#senderCustomerDistrict').html(current.district);
                    $('#senderCustomerNeighborhood').html(current.neighborhood);
                    $('#senderCustomerStreet').html(current.street);
                    $('#senderCustomerStreet2').html(current.street2);
                    $('#senderCustomerBuildingNo').html(current.building_no);
                    $('#senderCustomerFloor').html(current.floor);
                    $('#senderCustomerDaireNo').html(current.door_no);
                    $('#senderCustomerAdressNote').html(current.address_note);
                    $('#senderCustomerAdress').html(addressNote);


                    $('#senderCustomerPhone').html(current.phone);
                    $('#senderCustomer-phone-2').html(current.phone2);
                    $('#senderCustomerGsm').html(current.gsm);
                    $('#senderCustomerGsm-2').html(current.gsm2);
                    $('#senderCustomerWebSite').html(current.web_site);

                    $('#senderCustomerDispatchCity').html(current.dispatch_city);
                    $('#senderCustomerDispatchDistrict').html(current.dispatch_district);
                    $('#senderCustomerDispatchAdress').html(current.dispatch_adress);
                    $('#senderCustomerTaxAdmin').html(current.tax_administration);
                    $('#senderCustomerDispatchPastCode').html(current.dispatch_post_code);
                    $('#senderCustomerBranchOffice').html(branch_office);

                    $('#senderCustomerDiscount').html(current.discount);
                    $('#senderCustomerContractEnd').html(current.contract_end_date);
                    $('#senderCustomerCotractStart').html(current.contract_start_date);
                    $('#senderCustomerRefference').html(current.reference);


                    $('#senderCustomerVkn').html(current.vkn);

                    $('#senderCustomerName').html(current.name);
                    $('#senderCustomerType').html(current_type);

                } else if (current_type == 'Alıcı') {
                    fillCargo('tbodyUserTopTen', cargo);

                    $('#ModalUserDetail').modal();

                    $('#tckn').html(current.tckn)
                    $('#nameSurname').html(current.name);
                    $('#email').html(current.email);

                    $('#city').html(current.city);
                    $('#district').html(current.district);
                    $('#neighborhood').html(current.neighborhood);

                    $('#phone').html(current.phone);
                    $('#phone-2').html(current.phone2);
                    $('#adress').html(addressNote);

                    $('#vkn').html(current.vkn);

                    $('#customerName').html(current.name);
                    $('#customerType').html(current_type);
                }


            });
        }

        $(document).on('click', '.properties-log', function () {
            var properties = $(this).attr('properties');
            var log_id = $(this).attr('id');
            var array_no = $(this).attr('array-no');
            $('#json-renderer').text(JSON.parse(array[array_no]));
            $('#json-renderer').jsonViewer(JSON.parse(array[array_no]), {
                collapsed: false,
                rootCollapsable: false,
                withQuotes: false,
                withLinks: true
            });
            $('#ModalLogProperties').modal();
        });

    </script>
@endsection

@section('modals')
    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalUserDetail" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Müşteri Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyUserDetail" class="modal-body">

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
                                            <h5 id="customerName" class="menu-header-title">###</h5>
                                            <h6 id="customerType" class="menu-header-subtitle">###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: scroll" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="2">Müşteri Özeti
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">TCKN</td>
                                                    <td id="tckn"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Ad Soyad</td>
                                                    <td id="nameSurname"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Email</td>
                                                    <td id="email"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">İlçe</td>
                                                    <td id="district"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">İl</td>
                                                    <td id="city"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Mahalle</td>
                                                    <td id="neighborhood"></td>
                                                </tr>


                                                <tr>
                                                    <td class="static">Telefon</td>
                                                    <td id="phone">Aktif</td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Cep Telefonu</td>
                                                    <td id="phone-2">Aktif</td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Adres</td>
                                                    <td id="adress"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">VKN</td>
                                                    <td id="vkn"></td>
                                                </tr>


                                                </tbody>
                                            </table>
                                        </div>
                                        <h4 class="mt-3">Kargo Geçmişi</h4>

                                        <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                             class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees"
                                                   class="Table30Padding table table-striped mt-3">
                                                <thead>
                                                <tr>
                                                    <th class="font-weight-bold">KTNO</th>
                                                    <th class="font-weight-bold">Gönderici Ad Soyad</th>
                                                    <th class="font-weight-bold">Alıcı Ad Soyad</th>
                                                    <th class="font-weight-bold">Durum</th>
                                                    <th class="font-weight-bold">Kargo Tipi</th>
                                                    <th class="font-weight-bold">Tutar</th>
                                                    <th class="font-weight-bold">Detay</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyUserTopTen">

                                                </tbody>
                                            </table>
                                            <ul class="list-group list-group-flush">
                                                <li class="p-0 list-group-item">
                                                    <div class="grid-menu grid-menu-2col">
                                                        <div class="no-gutters row">
                                                            <div class="col-sm-12">
                                                                <div class="p-1">
                                                                    <button
                                                                        id="passwordResetBtn"
                                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                                                        <i class="lnr-redo text-dark opacity-7 btn-icon-wrapper mb-2"></i>
                                                                        Tümünü Gör
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>

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


    <div class="modal fade bd-example-modal-lg" id="ModalSenderCorporate" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Müşteri Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyUserDetail" class="modal-body">

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
                                            <h5 id="senderCustomerName" class="menu-header-title">###</h5>
                                            <h6 id="senderCustomerType" class="menu-header-subtitle">###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: scroll" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="2">Müşteri Özeti
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">TCKN</td>
                                                    <td id="tckn"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Ad Soyad</td>
                                                    <td id="nameSurname"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Email</td>
                                                    <td id="email"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">İlçe</td>
                                                    <td id="district"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">İl</td>
                                                    <td id="city"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Mahalle</td>
                                                    <td id="neighborhood"></td>
                                                </tr>


                                                <tr>
                                                    <td class="static">Telefon</td>
                                                    <td id="phone">Aktif</td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Cep Telefonu</td>
                                                    <td id="phone-2">Aktif</td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Adres</td>
                                                    <td id="adress"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">VKN</td>
                                                    <td id="vkn"></td>
                                                </tr>


                                                </tbody>
                                            </table>
                                        </div>
                                        <h4 class="mt-3">Kargo Geçmişi</h4>

                                        <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                             class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees"
                                                   class="Table30Padding table table-striped mt-3">
                                                <thead>
                                                <tr>
                                                    <th class="font-weight-bold">KTNO</th>
                                                    <th class="font-weight-bold">Gönderici Ad Soyad</th>
                                                    <th class="font-weight-bold">Alıcı Ad Soyad</th>
                                                    <th class="font-weight-bold">Durum</th>
                                                    <th class="font-weight-bold">Kargo Tipi</th>
                                                    <th class="font-weight-bold">Tutar</th>
                                                    <th class="font-weight-bold">Detay</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyUserTopTenSenderCorporate">

                                                </tbody>
                                            </table>
                                            <ul class="list-group list-group-flush">
                                                <li class="p-0 list-group-item">
                                                    <div class="grid-menu grid-menu-2col">
                                                        <div class="no-gutters row">
                                                            <div class="col-sm-12">
                                                                <div class="p-1">
                                                                    <button
                                                                        id="passwordResetBtn"
                                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                                                        <i class="lnr-redo text-dark opacity-7 btn-icon-wrapper mb-2"></i>
                                                                        Tümünü Gör
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>

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

    <div class="modal fade bd-example-modal-lg" id="ModalSenderPersonal" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Müşteri Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="overflow-y: auto; max-height: 75vh;" id="ModalBodyUserDetail" class="modal-body">

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
                                            <h5 id="senderPersonalCustomerName" class="menu-header-title">###</h5>
                                            <h6 id="senderPersonalCustomerType" class="menu-header-subtitle">
                                                ###/###</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: scroll" class="cont">
                                            <table style="white-space: nowrap" id="AgencyCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="2">Müşteri Özeti
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="static">Müşteri Tipi</td>
                                                    <td id="senderPersonalCustomerType-1" class="text-success"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">TCKN</td>
                                                    <td id="senderPersonalCustomerTckn"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Ad Soyad</td>
                                                    <td id="senderPersonalNameSurname"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Email</td>
                                                    <td id="senderPersonalCustomerEmail"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">İlçe</td>
                                                    <td id="senderPersonalCustomerDistrict"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">İl</td>
                                                    <td id="senderPersonalCustomerCity"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Mahalle</td>
                                                    <td id="senderPersonalCustomerNeighborhood"></td>
                                                </tr>


                                                <tr>
                                                    <td class="static">Telefon</td>
                                                    <td id="senderPersonalCustomerPhone">Aktif</td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Cep Telefonu</td>
                                                    <td id="senderPersonalCustomer-phone-2">Aktif</td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Adres</td>
                                                    <td id="senderPersonalCustomerAdress"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">VKN</td>
                                                    <td id="senderPersonalCustomerVkn"></td>
                                                </tr>


                                                </tbody>
                                            </table>
                                        </div>
                                        <h4 class="mt-3">Kargo Geçmişi</h4>

                                        <div style="overflow-x: auto; white-space: nowrap; max-height: 300px;"
                                             class="cont">
                                            <table style="white-space: nowrap" id="TableEmployees"
                                                   class="Table30Padding table table-striped mt-3">
                                                <thead>
                                                <tr>
                                                    <th class="font-weight-bold">KTNO</th>
                                                    <th class="font-weight-bold">Gönderici Ad Soyad</th>
                                                    <th class="font-weight-bold">Alıcı Ad Soyad</th>
                                                    <th class="font-weight-bold">Durum</th>
                                                    <th class="font-weight-bold">Kargo Tipi</th>
                                                    <th class="font-weight-bold">Tutar</th>
                                                    <th class="font-weight-bold">Detay</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyUserTopTenSenderPersonal">

                                                </tbody>
                                            </table>

                                            <ul class="list-group list-group-flush">
                                                <li class="p-0 list-group-item">
                                                    <div class="grid-menu grid-menu-2col">
                                                        <div class="no-gutters row">
                                                            <div class="col-sm-12">
                                                                <div class="p-1">
                                                                    <button
                                                                        id="passwordResetBtn"
                                                                        class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                                                        <i class="lnr-redo text-dark opacity-7 btn-icon-wrapper mb-2"></i>
                                                                        Tümünü Gör
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
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

    <!-- Large modal -->
    <div class="modal fade bd-example-modal-lg" id="ModalLogProperties" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Log Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- CARD START --}}
                    <div class="col-md-12">
                        <div class="mb-3 profile-responsive card">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <h3 class="text-center text-primary">Özellikler</h3>
                                    <pre id="json-renderer"></pre>
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

    {{-- Standart Modal - Enabled/Disabled User --}}
    <div class="modal fade" id="modalEnabledDisabled" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hesabı Aktif Pasif Yap</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalBodyEnabledDisabled" class="modalEnabledDisabled modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="userNameSurname">Ad Soyad</label>
                                <input id="userNameSurname" class="form-control" type="text" readonly
                                       value="Nurullah Güç">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="accountStatus">Hesap Durumu</label>
                                <select class="form-control" name="" id="accountStatus">
                                    <option value="0">Pasif</option>
                                    <option value="1">Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="statusDesc">Statü Açıklaması</label>
                                <textarea name="" id="statusDesc" cols="30" rows="10" class="form-control"></textarea>
                                <em class="text-danger">Kullanıcı hesabı pasif edilmesinden dolayı sisteme giriş
                                    yapamadığında karşısına çıkacak uyarıyı belirtin.</em>
                                <em class="text-success"> <br>
                                    <b>Default Message: Hesabınız pasif edilmiştir. Detaylı bilgi için sistem destek
                                        ekibine ulaşın.</b>
                                </em>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnSaveStatus" type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Standart Modal - Virtual Login --}}
    <div class="modal fade" id="ModalVirtualLogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sanal Giriş</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modalEnabledDisabled">
                    <em class="text-dark">Kullanıcının hesabına neden sanal giriş yaptığınızı
                        belirtin.</em>
                    <em class="text-dark"><br>
                        Not: Hesap sahibine bununla ilgili gerekçesi ile birlikte bir bildirim
                        göndereceğiz.
                    </em>
                    <em class="text-dark"><br>
                        Not: Yetkisi <b>Genel Yönetici</b> olanlar ve <b>kendi hesabınız</b> hariç tüm hesaplara sanal
                        giriş
                        yapabilirsiniz.
                    </em>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="reason">Gerekçe Belirtin (<label for="reason"
                                                                             class="text-danger">Zorunlu*</label>)</label>
                                <textarea name="" id="reason" cols="30" rows="4" class="form-control"></textarea>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnGoToVirtualLogin" type="button" class="btn btn-primary">Hesaba Sanal Giriş Yap
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
