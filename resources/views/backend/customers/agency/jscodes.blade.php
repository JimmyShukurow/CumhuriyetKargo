<script src="/backend/assets/scripts/backend-modules.js"></script>
    <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
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
                    10, 'desc'
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
                    {data: 'free', name: 'free'},
                    {data: 'current_code', name: 'current_code'},
                    {data: 'current_type', name: 'current_type'},
                    {data: 'name', name: 'name'},
                    {data: 'category', name: 'category'},
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
            $.each(cargo, function (index, value) {
                    $(mytbodyId).append(
                        '<tr>' +
                        '<td>' + arrangeCargoTrackingNumber(String(value.tracking_no)) + '</td>' +
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
            ToastMessage('warning', '', 'Yükleniyor');
            detailsID = $(this).prop('id');
            getCustomerDetails(detailsID);
        });

        var array = new Array();

        function getCustomerDetails(user) {
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
                let cargo = response.cargo;

                if (current_type == 'Gönderici' && category == 'Bireysel') {
                    fillCargo('tbodyUserTopTenSenderPersonal', cargo);

                    $('#ModalCustomerDetails').modal();
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
                    console.log(current);

                    fillCargo('tbodyUserTopTenSenderCorporate', cargo);

                    $('#ModalCustomerDetails').modal();

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
                    $('#senderCustomerType-1').html(category);

                } else if (current_type == 'Alıcı') {

                    fillCargo('tbodyUserTopTen', cargo);
                    $('taker-table').css("display":"block");
                    $('#ModalCustomerDetails').modal();
                    $('#tcknTaker').html(current.tckn)
                    $('#nameSurnameTaker').html(current.name);
                    $('#emailTaker').html(current.email);

                    $('#cityTaker').html(current.city);
                    $('#districtTaker').html(current.district);
                    $('#neighborhoodTaker').html(current.neighborhood);

                    $('#phoneTaker').html(current.phone);
                    $('#phone-2-taker').html(current.phone2);
                    $('#adressTaker').html(addressNote);

                    $('#vknTaker').html(current.vkn);

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