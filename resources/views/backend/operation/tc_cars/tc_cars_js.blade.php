<script>
$(document).ready(function () {


    tableAgencyPaymentApps = $('#transferCarsTable').DataTable({
        pageLength: 50,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [10, 'desc'],
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
        select: {
            style: 'multi',
            selector: 'td:nth-child(0)'
        },
        buttons: [
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                },
                attr: {
                    id: 'datatableRefreshBtn'
                }
            },
            {
                text: 'Araç Ekle',
                action:function(){
                    window.location.replace('TCCars/Create')
                },
                attr: {
                    class: 'btn btn-primary',
                    id: 'btnAddTcCar'
                }
            }
        ],
        responsive: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: 'AjaxTCCars/GetTransferCars',
            data: function (d) {
                d.marka = $('#markaFilter').val();
                d.model = $('#modelFilter').val();
                d.plaka = $('#plakaFilter').val();
                d.soforAd = $('#soforAdiFilter').val();
                d.creator = $('#creatorFilter').val();
            },

            error: function (xhr, error, code) {

                console.log('error');
            },
            complete: function () {
                // if ($('#datatableRefreshBtn').prop('disabled') == true)
                //     $('#datatableRefreshBtn').prop('disabled', false);

            }
        },
        columns: [
            {data: 'marka', name: 'marka'},
            {data: 'model', name: 'model'},
            {data: 'plaka', name: 'plaka'},
            {data: 'hat', name: 'hat'},
            {data: 'arac_kapasitesi', name: 'arac_kapasitesi'},
            {data: 'cikis_aktarma', name: 'cikis_aktarma'},
            {data: 'varis_aktarma', name: 'varis_aktarma'},
            {data: 'sofor_ad', name: 'sofor_ad'},
            {data: 'sofor_telefon', name: 'sofor_telefon'},
            {data: 'creator', name: 'creator'},
            {data: 'created_at', name: 'created_at'},
            {data: 'details', name: 'details'},
        ],
        scrollY: '500px',
        scrollX: true,
    });
});




    $(document).ready(function () {
        $('#agencyPaymentAppsAgency').select2();

        $('#tabTransferCars').on('click', function(){
            $('#confirmation').hide();
            }
        );
    })

    $(document).on('click', '.btn_car_details', function () {
        detailsID = $(this).prop('id');
        carInfo($(this).prop('id'));
     });

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

            $('#plaka').html(cars.plaka);
            $('#tdPlaka').html(cars.plaka);
            $('#arac_kapasitesi').html(cars.arac_kapasitesi);
            $('#hat').html(cars.hat);
            $('#cikis_aktarma').html(cars.cikish_aktarma ? cars.cikish_aktarma.tc_name : '' );
            $('#varis_aktarma').html(cars.varish_aktarma ? cars.varish_aktarma.tc_name : '');
            $('#branch').html(cars.branch ? cars.branch.agency_name : '');
            $('#creator').html(cars.creator ? cars.creator.name_surname : '');
            $('#agency').html(cars.creator.get_agency ? cars.creator.get_agency.agency_name : '');
            $('#car_type').html(cars.car_type);
            $('#created_at').html(cars.created_at);
            $('#confirmer').html(cars.confirmed_user);
            $('#soforAdi').html(cars.sofor_ad);
            $('#soforIletisim').html(cars.sofor_telefon);
            $('#soforAders').html(cars.sofor_adres);
            $('.modal-body').unblock();
            return false;
        });

        $('#ModalAgencyDetail').modal();
    }


</script>
