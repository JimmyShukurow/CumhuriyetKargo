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
                            üzerinden görüntüleyebilir, düzenleyenilirsiniz.
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
                <form method="POST" id="search-form">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="position-relative form-control-sm form-group">
                                <label for="link" class="">İl:</label>
                                <select name="city" id="city" required
                                        class="form-control niko-select-filter form-control-sm">
                                    <option value="">İl Seçiniz</option>
                                    @foreach($data['cities'] as $city)
                                        <option {{ old('city') == $city->id ? 'selected' : ''  }} id="{{$city->id}}"
                                                value="{{$city->id}}">{{$city->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="district" class="">İlçe*</label>
                                <select name="district" id="district" required
                                        class="form-control niko-select-filter form-control-sm">
                                    <option value="">İlçe Seçiniz</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="agency">Acente</label>
                            <select name="agency"
                                    class="form-control form-control-sm niko-select-filter">
                                <option value="">Seçiniz</option>
                                @foreach($data['agencies'] as $key)
                                    <option
                                        value="{{$key->id}}">{{ $key->city . '/' . $key->district . '-' . $key->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button style="width: 100%;" id="btnGiveNeighborhood"
                                    class="mt-4 btn-icon btn-shadow btn-outline-2x btn btn-outline-light">
                                <i class="lnr-plus-circle btn-icon-wrapper"></i>Bölgeye Bağlanacak İlçeleri Seçin
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
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <script src="/backend/assets/scripts/city-districts-point.js"></script>

    <script>
        $(document).on('change', '#city', function () {
            getDistricts('#city', '#district');
        });

        $(document).on('change', '#district', function () {
            getNeigborhoods('#district', '#neighborhood');
        });

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

        // parse a date in yyyy-mm-dd format
        function dateFormat(date) {
            date = String(date);
            let text = date.substring(0, 10);
            let time = date.substring(19, 8);
            time = time.substring(3, 11);
            let datetime = text + " " + time;
            return datetime;
        }


        $(document).on('click', '.user-detail', function () {
            $('#ModalUserDetail').modal();

            $('#ModalBodyUserDetail.modal-body').block({
                message: $('<div class="loader mx-auto">\n' +
                    '                            <div class="ball-grid-pulse">\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                                <div class="bg-white"></div>\n' +
                    '                            </div>\n' +
                    '                        </div>')
            });
            $('.blockUI.blockMsg.blockElement').css('width', '100%');
            $('.blockUI.blockMsg.blockElement').css('border', '0px');
            $('.blockUI.blockMsg.blockElement').css('background-color', '');

            detailsID = $(this).prop('id');
            userInfo($(this).prop('id'));
        });

        var array = new Array();

        function userInfo(user) {
            $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: user
                },
                cache: false
            }).done(function (response) {
                let currentStatus, currentConfirmed;
                let creatorDisplayName = '<span class="text-primary font-weight-bold">(' + response.current.creator_display_name + ')</span>';

                if (response.current.status == "1")
                    currentStatus = '<span class="text-success">(Aktif Hesap)</span>';
                else
                    currentStatus = '<span class="text-danger">(Pasif Hesap)</span>';

                if (response.current.confirmed == "1") {
                    currentConfirmed = '<span class="text-success font-weight-bold">Onaylandı</span>';
                    $('#divConfirmCurrent').hide();
                } else {
                    currentConfirmed = '<span class="text-danger font-weight-bold">Onay Bekliyor</span>';
                    $('#divConfirmCurrent').show();
                }

                let city = response.current.city + "/",
                    district = response.current.district + " ",
                    neighborhood = response.current.neighborhood + " ",
                    street = response.current.street != '' && response.current.street != null ? response.current.street + " CAD. " : "",
                    street2 = response.current.street2 != '' && response.current.street2 != null ? response.current.street2 + " SK. " : "",
                    buildingNo = "NO:" + response.current.building_no + " ",
                    door = "D:" + response.current.door_no + " ",
                    floor = "KAT:" + response.current.floor + " ",
                    addressNote = "(" + response.current.address_note + ")";

                let fullAddress = neighborhood + street + street2 + buildingNo + floor + door + addressNote;


                $('#agencyName').html(response.current.name);
                $('#agencyCityDistrict').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
                $('#titleBranch').html(response.current.name + ' - ÖZET ' + currentStatus);

                $('#currentCategory').html(response.current.category);
                $('#modalCurrentCode').html(response.current.current_code);
                $('#nameSurnameCompany').html(response.current.name);
                $('#currentAgency').html(response.current.agency_city + "/" + response.current.agency_district + " - " + response.current.agency_name + " Acente " + "(" + response.current.agency_code + ")");
                $('#taxOffice').html(response.current.tax_administration);
                $('#tcknVkn').html(response.current.tckn);
                $('#phone').html(response.current.phone);
                $('#cityDistrict').html(response.current.city + "/" + response.current.district);
                $('#address').html(fullAddress);
                $('#gsm').html(response.current.gsm);
                $('#gsm2').html(response.current.gsm2);
                $('#phone2').html(response.current.phone2);
                $('#email').html(response.current.email);
                $('#website').html(response.current.website);
                $('#regDate').html(response.current.created_at);
                $('#dispatchCityDistrict').html(response.current.dispatch_city + "/" + response.current.dispatch_district);
                $('#dispatchAddress').html(response.current.dispatch_adress);
                $('#iban').html(response.current.iban);
                $('#bankOwner').html(response.current.bank_owner_name);
                $('#contractStartDate').html(response.current.contract_start_date);
                $('#contractEndDate').html(response.current.contract_end_date);
                $('#reference').html(response.current.reference);
                $('#currentCreatorUser').html(response.current.creator_user_name + " " + creatorDisplayName);
                $('#currentFilePrice').html(response.price.file_price + "₺");
                $('#current1_5Desi').html(response.price.d_1_5 + "₺");
                $('#current6_10Desi').html(response.price.d_6_10 + "₺");
                $('#current11_15Desi').html(response.price.d_11_15 + "₺");
                $('#current16_20Desi').html(response.price.d_16_20 + "₺");
                $('#current21_25Desi').html(response.price.d_21_25 + "₺");
                $('#current26_30Desi').html(response.price.d_26_30 + "₺");
                $('#currentAmountOfIncrease').html(response.price.amount_of_increase + "₺");
                $('#currentCollectPrice').html(response.price.collect_price + "₺");
                $('#collectAmountOfIncrease').html("%" + response.price.collect_amount_of_increase);
                $('#currentConfirmed').html(currentConfirmed);

                $('.modal-body').unblock();
                return false;
            });

            $('#ModalAgencyDetail').modal();
        }

        $(document).on('click', '#btnConfirmCurrent', function () {
            $('#btnConfirmCurrent').prop('disabled', true);

            $.ajax('/SenderCurrents/AjaxTransaction/ConfirmCurrent', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID
                }
            }).done(function (response) {

                if (response.status == -1)
                    ToastMessage('error', response.message, '');
                else if (response.status == 1) {
                    ToastMessage('success', 'İşlem başarılı, cari hesabı onaylandı!', 'İşlem Başarılı!');
                    userInfo(detailsID);
                    $('#divConfirmCurrent').hide();
                }


                $('.modalEnabledDisabled.modal-body').unblock();
            }).error(function (jqXHR, response) {
                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
            }).always(function () {
                $('#btnConfirmCurrent').prop('disabled', false);
            });

        });
        $(document).on('click', '#btnEnabledDisabled', function () {
            // alert(detailsID);
            $('#modalEnabledDisabled').modal();

            $('#modalBodyEnabledDisabled.modalEnabledDisabled.modal-body').block({
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


            $.ajax('/SenderCurrents/AjaxTransaction/GetCurrentInfo', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID
                }
            }).done(function (response) {

                $('#userNameSurname').val(response.current.name);
                $('#accountStatus').val(response.current.status);

                $('.modalEnabledDisabled.modal-body').unblock();
            });
        });
        $(document).on('click', '#btnSaveStatus', function () {

            ToastMessage('warning', 'İstek alındı, lütfen bekleyiniz.', 'Dikkat!');
            $.ajax('/SenderCurrents/AjaxTransaction/ChangeStatus', {
                method: 'POST',
                data: {
                    _token: token,
                    currentID: detailsID,
                    status: $('#accountStatus').val(),
                }
            }).done(function (response) {
                if (response.status == 1) {

                    $('#ModalBodyUserDetail.modal-body').block({
                        message: $('<div class="loader mx-auto">\n' +
                            '                            <div class="ball-grid-pulse">\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                                <div class="bg-white"></div>\n' +
                            '                            </div>\n' +
                            '                        </div>')
                    });
                    $('.blockUI.blockMsg.blockElement').css('width', '100%');
                    $('.blockUI.blockMsg.blockElement').css('border', '0px');
                    $('.blockUI.blockMsg.blockElement').css('background-color', '');

                    userInfo(detailsID);
                    ToastMessage('success', 'Değişiklikler başarıyla kaydedildi.', 'İşlem Başarılı!');
                    $('#modalEnabledDisabled').modal('toggle');
                } else if (response.status == 0) {
                    ToastMessage('error', response.description, 'Hata!');
                } else if (response.status == -1) {
                    response.errors.status.forEach(key =>
                        ToastMessage('error', key, 'Hata!')
                    );
                }

                return false;
            }).error(function (jqXHR, response) {

                ToastMessage('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!', 'Hata!');
            });
        });
        $(document).on('click', '#btnCurrentPerformanceReport', function () {
            ToastMessage('warning', 'Cari performans raporu çok yakında!', 'Bilgi');
        });
        $(document).on('click', '#btnPrintModal', function () {
            printWindow('#ModalBodyUserDetail', "CK - " + $('#agencyName').text());
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
                    <h5 class="modal-title" id="ModalGiveRolePermissionLabel">Cari Detayları</h5>
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
                                         style="background-image: url('/backend/assets/images/dropdown-header/abstract5.jpg');">
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
                                <li class="p-0 list-group-item">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnEnabledDisabled"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                        <i class="lnr-construction text-danger opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Hesabı Aktif/Pasif Yap
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnPrintModal"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-primary">
                                                        <i class="lnr-printer text-primary opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Yazdır
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="p-1">
                                                    <button id="btnCurrentPerformanceReport"
                                                            class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-alternate">
                                                        <i class="pe-7s-timer text-alternate opacity-7 btn-icon-wrapper mb-2">
                                                        </i>
                                                        Cari Performans Raporu
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="widget-content pt-4 pb-4 pr-1 pl-1">

                                        <div style="overflow-x: scroll" class="cont">
                                            <table style="white-space: nowrap" id="InfoCard"
                                                   class="TableNoPadding table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" id="titleBranch" colspan="2">Özet</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td class="static">Onay</td>
                                                    <td id="currentConfirmed"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Kategori</td>
                                                    <td id="currentCategory"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Cari Kodu</td>
                                                    <td id="modalCurrentCode"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Ad Soyad/Firma</td>
                                                    <td id="nameSurnameCompany"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Bağlı Şube</td>
                                                    <td id="currentAgency"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Vergi Dairesi</td>
                                                    <td id="taxOffice"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">TCKN/VKN</td>
                                                    <td id="tcknVkn"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Telefon</td>
                                                    <td id="phone"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">İl/İlçe</td>
                                                    <td id="cityDistrict"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Adress</td>
                                                    <td id="address"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">GSM</td>
                                                    <td id="gsm"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">GSM (2)</td>
                                                    <td id="gsm2"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Telefon (2)</td>
                                                    <td id="phone2"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">E-Posta</td>
                                                    <td id="email"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Website</td>
                                                    <td id="website"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Sevk İl/İlce</td>
                                                    <td id="dispatchCityDistrict"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Sevk Adres</td>
                                                    <td id="dispatchAddress"></td>
                                                </tr>


                                                <tr>
                                                    <td class="static">IBAN</td>
                                                    <td id="iban"></td>
                                                </tr>


                                                <tr>
                                                    <td class="static">Hesap Sahibi</td>
                                                    <td id="bankOwner"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Sözleşme Başlangıç Tarihi</td>
                                                    <td id="contractStartDate"></td>
                                                </tr>
                                                <tr>
                                                    <td class="static">Sözleşme Bitiş Tarihi</td>
                                                    <td id="contractEndDate"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Referans</td>
                                                    <td id="reference"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Oluşturan Kullanıcı</td>
                                                    <td id="currentCreatorUser"></td>
                                                </tr>

                                                <tr>
                                                    <td class="static">Kayıt Tarihi</td>
                                                    <td id="regDate"></td>
                                                </tr>


                                                </tbody>
                                            </table>
                                        </div>

                                        <table style="white-space: nowrap" id="InfoCard"
                                               class="TableNoPadding table table-bordered table-striped mt-3 InfoCard">
                                            <thead>
                                            <tr>
                                                <th class="text-center" id="" colspan="2">Anlaşmalı Fiyatlar</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr>
                                                <td class="static">Dosya</td>
                                                <td id="currentFilePrice"></td>
                                            </tr>

                                            <tr>
                                                <td class="static">1-5 Desi</td>
                                                <td id="current1_5Desi"></td>
                                            </tr>

                                            <tr>
                                                <td class="static">6-10 Desi</td>
                                                <td id="current6_10Desi"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">11-15 Desi</td>
                                                <td id="current11_15Desi"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">16-20 Desi</td>
                                                <td id="current16_20Desi"></td>
                                            </tr>
                                            <tr>
                                                <td class="static">21-25 Desi</td>
                                                <td id="current21_25Desi"></td>
                                            </tr>

                                            <tr>
                                                <td class="static">26-30 Desi</td>
                                                <td id="current26_30Desi"></td>
                                            </tr>

                                            <tr>
                                                <td class="static">Üstü Desi</td>
                                                <td id="currentAmountOfIncrease"></td>
                                            </tr>

                                            <tr>
                                                <td class="static">Tahsilat Bedeli (0-200₺)</td>
                                                <td id="currentCollectPrice"></td>
                                            </tr>

                                            <tr>
                                                <td class="static">Tahsilat Bedeli Oranı (200₺ Üstü)</td>
                                                <td id="collectAmountOfIncrease"></td>
                                            </tr>

                                            </tbody>
                                        </table>

                                        <div id="divConfirmCurrent" class="grid-menu grid-menu-2col">
                                            <div class="no-gutters row">

                                                <div class="col-sm-12">
                                                    <div class="p-1">
                                                        <button id="btnConfirmCurrent"
                                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                                            <i class="lnr-checkmark-circle text-success opacity-7 btn-icon-wrapper mb-2">
                                                            </i>
                                                            Cari Hesabını Onayla
                                                        </button>
                                                    </div>
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
                                <label for="userNameSurname">Ad Soyad/Firma</label>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="btnSaveStatus" type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>
@endsection
