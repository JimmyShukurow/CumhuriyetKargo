$('.NikolasDataTable.IdleDistricts').DataTable({
    pageLength: 10,
    lengthMenu: dtLengthMenu,
    language: dtLanguage,
    dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
    buttons: [
        {
            extend: 'excelHtml5',
            exportOptions: {
                columns: [0, 1, 2]
            },
            title: "CK-Bölgesi Olmayan İlçeler " + currentDate,
            attr: {
                class: 'btn btn-success',
            }
        },
        {
            text: 'Yenile',
            action: function (e, dt, node, config) {
                dt.ajax.reload();
            },
            attr: {
                class: 'btn btn-primary',
            }
        }
    ],
    responsive: false,
    processing: true,
    serverSide: true,
    ajax: '/RegionalDirectorates/ListIdleDistricts',
    columnDefs: [
        {targets: [2], orderable: false}
    ],
    columns: [
        {data: 'city_name', name: 'city_name'},
        {data: 'district_name', name: 'district_name'},
        {data: 'delete', name: 'delete'}
    ],
    scrollY: false
});

$('.NikolasDataTable.IdleAgenciesRegion').DataTable({
    pageLength: 10,
    lengthMenu: dtLengthMenu,
    language: dtLanguage,
    dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
    buttons: [
        {
            extend: 'excelHtml5',
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
            },
            title: "CK-Bölge Müdürlüğüne Bağlı Olmayan Acenteler " + currentDate,
            attr: {
                class: 'btn btn-success',
            }
        },
        {
            text: 'Yenile',
            action: function (e, dt, node, config) {
                dt.ajax.reload();
            },
            attr: {
                class: 'btn btn-primary',
            }
        }
    ],
    responsive: false,
    processing: true,
    serverSide: true,
    ajax: '/RegionalDirectorates/ListIdleAgenciesRegion',
    columnDefs: [
        {targets: [4], orderable: false}
    ],
    columns: [
        {data: 'city', name: 'city'},
        {data: 'district', name: 'district'},
        {data: 'district', name: 'district'},
        {data: 'name_surname', name: 'name_surname'},
        {data: 'delete', name: 'delete'}
    ],
    scrollY: false
});

$('.NikolasDataTable.IdleAgenciesTC').DataTable({
    pageLength: 10,
    lengthMenu: dtLengthMenu,
    language: dtLanguage,
    dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col"f>>rtip',
    buttons: [
        {
            extend: 'excelHtml5',
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
            },
            title: "CK-Transfer Merkezine Bağlı Olmayan Acenteler " + currentDate,
            attr: {
                class: 'btn btn-success',
            }
        },
        {
            text: 'Yenile',
            action: function (e, dt, node, config) {
                dt.ajax.reload();
            },
            attr: {
                class: 'btn btn-primary',
            }
        }
    ],
    responsive: false,
    processing: true,
    serverSide: true,
    ajax: '/RegionalDirectorates/ListIdleAgenciesTC',
    columnDefs: [
        {targets: [4], orderable: false}
    ],
    columns: [
        {data: 'city', name: 'city'},
        {data: 'district', name: 'district'},
        {data: 'district', name: 'district'},
        {data: 'name_surname', name: 'name_surname'},
        {data: 'delete', name: 'delete'}
    ],
    scrollY: false
});

