$(document).on('change', '#receiverCity', function () {
    getDistricts('#receiverCity', '#receiverDistrict');
});

$(document).on('change', '#senderCity', function () {
    getDistricts('#senderCity', '#senderDistrict');
});

var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {
    $('#agency').select2();
    $('#creatorUser').select2();

    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 10,
        lengthMenu: dtLengthMenu,
        order: [19, 'desc'],
        language: dtLanguage,
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',

        buttons: [
            {
                text: 'Yenile',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                },
                attr: {
                    class: 'btn btn-primary'
                }
            },

        ],
        responsive: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/MainCargo/GetCancelledCargoes',
            data: function (d) {
                d.invoiceNumber = $('#invoice_number').val();
                d.trackingNo = $('#trackingNo').val();
                d.cargoType = $('#cargoType').val();
                d.receiverCurrentCode = $('#receiverCurrentCode').val();
                d.senderCurrentCode = $('#senderCurrentCode').val();
                d.startDate = $('#startDate').val();
                d.finishDate = $('#finishDate').val();
                d.receiverName = $('#receiverName').val();
                d.receiverCity = $('option:selected', '#receiverCity').attr('data') == undefined ? '' : $('option:selected', '#receiverCity').attr('data');
                d.receiverDistrict = $('#receiverDistrict').val();
                d.receiverPhone = $('#receiverPhone').val();
                d.senderName = $('#senderName').val();
                d.senderCity = $('option:selected', '#senderCity').attr('data') == undefined ? '' : $('option:selected', '#senderCity').attr('data');
                d.senderDistrict = $('#senderDistrict').val();
                d.senderPhone = $('#senderPhone').val();
                d.filterByDAte = $('#filterByDate').prop('checked');
            },
            error: function (xhr, error, code) {

                if (xhr.status == 429) {
                    ToastMessage('error', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                } else if (xhr.status == 509) {
                    ToastMessage('error', 'Tarih aralığı en fazla 30 gün olabilir!', 'Hata');
                }
            },
            complete: function () {
                SnackMessage('Tamamlandı!', 'info', 'bl');
            }
        },
        columns: [
            {data: 'invoice_number', name: 'invoice_number'},
            {data: 'tracking_no', name: 'tracking_no'},
            {data: 'agency_name', name: 'agency_name'},
            {data: 'sender_name', name: 'sender_name'},
            {data: 'sender_city', name: 'sender_city'},
            {data: 'receiver_name', name: 'receiver_name'},
            {data: 'receiver_city', name: 'receiver_city'},
            {data: 'receiver_district', name: 'receiver_district'},
            {data: 'receiver_address', name: 'receiver_address'},
            {data: 'cargo_type', name: 'cargo_type'},
            {data: 'payment_type', name: 'payment_type'},
            {data: 'total_price', name: 'total_price'},
            {data: 'collectible', name: 'collectible'},
            {data: 'collectible', name: 'collectible'},
            {data: 'collection_fee', name: 'collection_fee'},
            {data: 'status', name: 'status'},
            {data: 'status_for_human', name: 'status_for_human'},
            {data: 'transporter', name: 'transporter'},
            {data: 'system', name: 'system'},
            {data: 'created_at', name: 'created_at'},
        ],
        scrollX: true,

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
