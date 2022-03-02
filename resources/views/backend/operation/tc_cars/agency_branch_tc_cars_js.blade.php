<script>

let tablePangdingConfirm = false;

$('#tabPandingConfirmCars').click(function () {

    if(tablePangdingConfirm == false){
        tablePangdingConfirm = true;
        tableAgencyPaymentApps = $('#agencyBranchTransferCarsTable').DataTable({
        pageLength: 50,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500, -1],
            ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
        ],
        order: [0, 'desc'],
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
            url: 'AjaxTCCars/AgenBranchGetTransferCars',
            data: function (d) {
                d.marka = $('#markaFilter').val();
                d.model = $('#modelFilter').val();
                d.plaka = $('#plakaFilter').val();
                d.soforAd = $('#soforAdiFilter').val();
                d.creator = $('#creatorFilter').val();
                d.confirmation = $('#confirmationFilter').val();
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
            {data: 'confirmation_status', name: 'confirmation_status'},
            {data: 'details', name: 'details'},

        ],
        scrollY: '500px',
        scrollX: true,
    });
    }

    let buttonCancel = "<div class='col-sm-6'> <div class='p-1'> <button id='btnAppConfirmSuccess' class='btn-app-transaction btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success'> <i class='lnr-checkmark-circle text-success opacity-7 btn-icon-wrapper mb-2'> </i> Aracı Onayla </button> </div> </div>";
    let buttonConfirm = "<div class='col-sm-6'>    <div class= 'p-1'> <button id='btnAppConfirmReject' class='btn-app-transaction btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger'> <i class='lnr-cross-circle text-danger opacity-7 btn-icon-wrapper mb-2'> </i> Aracı Reddet </button> </div> </div>";

    $('#confirmation').html("<div class='grid-menu grid-menu-2col'> <div class='no-gutters row'>"+ buttonCancel + buttonConfirm + "</div> </div>");
});

</script>
