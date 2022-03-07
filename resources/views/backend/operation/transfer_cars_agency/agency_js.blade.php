
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
    <script src="/backend/assets/scripts/jquery.json-viewer.js"></script>
    <script src="/backend/assets/scripts/select2.js"></script>
    <script src="/backend/assets/scripts/delete-method.js"></script>
    <link rel="stylesheet" href="/backend/assets/css/jquery.json-viewer.css">
    <script src="/backend/assets/scripts/city-districts-point.js"></script>

    <script>
        $(document).on('change', '#receiverCity', function () {
            getDistricts('#receiverCity', '#receiverDistrict');
        });

        $(document).on('change', '#senderCity', function () {
            getDistricts('#senderCity', '#senderDistrict');
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
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
                ],
                order: [
                    [7, 'desc']
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

                },
                dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',

                buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        },
                        title: "CK - Aktarma Araçları"
                    },
                    {
                        text: 'Yenile',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    },
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('agency.transfer.car.all') !!}',
                    data: function (d) {
                        d.marka = $('#filter_marka').val();
                        d.model = $('#filter_model').val();
                        d.plaka = $('#filter_plaka').val();
                        d.soforAd = $('#filter_soforAd').val();
                    },
                    error: function (xhr, error, code) {

                        if (xhr.status == 429) {
                            ToastMessage('error', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                        } else if (xhr.status == 509) {
                            ToastMessage('error', 'Tarih aralığı en fazla 60 gün olabilir!', 'Hata');
                        }
                    },
                    complete: function () {
                        ToastMessage('info', 'Tamamlandı!', 'Bilgi');
                    }
                },
                columns: [
                    {data: 'marka', name: 'marka'},
                    {data: 'model', name: 'model'},
                    {data: 'plaka', name: 'plaka'},
                    {data: 'branch', name: 'branch'},
                    {data: 'sofor_ad', name: 'sofor_ad'},
                    {data: 'creator', name: 'creator'},
                    {data: 'car_type', name: 'car_type'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'confirmation_status', name: 'confirmation_status'},
                    {data: 'details', name: 'details'},
                ],
                scrollY:false,
                scrollX:true,
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


        $(document).on('click', '.btn_car_details', function () {
            detailsID = $(this).prop('id');
            carInfo($(this).prop('id'));
        });

        var array = new Array();

        function carInfo(id) {

            $('#ModalCarDetails').modal();

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

            $.ajax('{{route('getAgencyTransferCar')}}', {
                method: 'POST',
                data: {
                    _token: token,
                    carID: id
                },
                cache: false
            }).done(function (response) {

                let cars = response.cars;

                $('#tdPlaka').html(cars.plaka);
                $('#branch').html(cars.branch ? cars.branch.agency_name : '');
                $('#creator-agency-name').html(cars.creator ? cars.creator.get_agency.agency_name : '');
                $('#car_type').html(cars.car_type);
                $('#created_at').html(cars.created_at);
                $('#confirmer').html(cars.confirmer ? cars.confirmer.name_surname : '');
                $('#soforAdi').html(cars.sofor_ad);
                $('#soforIletisim').html(cars.sofor_telefon);
                $('#soforAders').html(cars.sofor_adres);
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
        $(document).on('click', '#btnPrintModal', function () {
            printWindow('#ModalBodyUserDetail', "CKG-Sis - Aktarma Araçları");
        });

    </script>
