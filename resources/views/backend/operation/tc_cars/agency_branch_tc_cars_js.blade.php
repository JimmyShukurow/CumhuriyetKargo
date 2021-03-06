<script>

let tablePangdingConfirm = false;
let detailsID = null;
    let car_type = null;

$('#tabPandingConfirmCars').click(function () {

    if(tablePangdingConfirm == false){
        tablePangdingConfirm = true;
       tableAgencyPaymentApps = $('#agencyBranchTransferCarsTable').DataTable({
        pageLength: 50,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [8, 'desc'],
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
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                },
                title: "CK - Aktarma Araçları"
            },
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                },
                attr: {
                    id: 'datatableRefreshBtn'
                }
            },
        ],
        responsive: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: 'AjaxTCCars/AgencyBranchGetTransferCars',
            data: function (d) {
                d.marka = $('#markaFilterAgency').val();
                d.model = $('#modelFilterAgency').val();
                d.plaka = $('#plakaFilterAgency').val();
                d.soforAd = $('#soforAdiFilterAgency').val();
                d.creator = $('#creatorFilterAgency').val();
                d.confirmation = $('#confirmationFilterAgency').val();
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
            {data: 'doors_to_be_sealed', name: 'doors_to_be_sealed'},
            {data: 'sofor_ad', name: 'sofor_ad'},
            {data: 'sofor_telefon', name: 'sofor_telefon'},
            {data: 'car_status', name: 'car_status'},
            {data: 'creator', name: 'creator'},
            {data: 'creator_agency', name: 'creator_agency'},
            {data: 'created_at', name: 'created_at'},
            {data: 'confirmation_status', name: 'confirmation_status'},
            {data: 'details', name: 'details'},

        ],
        scrollY: '500px',
        scrollX: true,
    });
    }

    $('#confirmation').show();

    $('#carConfirmSuccess').on('click', function (){
        $.ajax({
            url:"AjaxTCCars/CarConfirmSuccess",
            data:{
                id:detailsID,
            }
        }).done(function (response){
            tableAgencyPaymentApps.draw();
            ToastMessage('success', response.message, 'İşlem Başarılı!');
            carInfo(detailsID);

        })
    })
    $('#carRejectSuccess').on('click', function (){
        $.ajax({
            url:"AjaxTCCars/CarRejectSuccess",
            data:{
                id:detailsID,
            }
        }).done(function (response){
            tableAgencyPaymentApps.draw();
            ToastMessage('success', response.message, 'İşlem Başarılı!');
            carInfo(detailsID);

        })
    })
});





</script>
