var oTable;
var detailsID = null;
// and The Last Part: NikoStyle
$(document).ready(function () {
    $('#agency').select2();
    $('#creatorUser').select2();

    oTable = $('.NikolasDataTable').DataTable({
        pageLength: 10,
        lengthMenu: dtLengthMenu,
        order: [4, 'desc'],
        language: dtLanguage,
        dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>rtip',

        buttons: [
            'excel'
        ],
        responsive: true,
        processing: true,
        serverSide: false,
    });
});
