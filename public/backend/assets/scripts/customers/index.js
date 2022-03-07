var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {
    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 25,
        lengthMenu: dtLengthMenu,
        order: [
            11, 'desc'
        ],
        language: dtLanguage,
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
            url: '/Customers/GetAllCustomers',
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
