let countCargo, categories, endorsement, agencyCount, oTable, oAgenciesTable;
let tabTableInit = false, agenciesTableInit = false, dataSet = null, dataSetAgencies = null;

$(document).ready(function () {
    reloadDashboard()
})

function reloadDashboard() {
    $('.app-main__inner').block({
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
    $('.blockUI.blockMsg.blockElement').css('border', '0px')
    $('.blockUI.blockMsg.blockElement').css('background-color', '')

    $.ajax('/Dashboard/GM/AjaxTransactions/GetSummery', {
        method: 'POST',
        data: {
            _token: token,
            firstDate: $('#firstDate').val(),
            lastDate: $('#lastDate').val(),
        }
    }).done(function (response) {
        if (response.status == 1) {
            let data = response.data
            $('#endorsementCurrentDate').html(data.endorsementCurrentDate)
            $('#totalCargosCurrentDate').html(data.totalCargosCurrentDate)
            $('#cargoFileCurrentDate').html(data.cargoCountCurrentDate + " / " + data.fileCountCurrentDate)
            $('#totalDesiCurrentDate').html(data.totalDesiCurrentDate)
            $('#endorsementAllTime').html(data.endorsementAllTime)
            $('#inSafeAllTime').html(data.inSafeAllTime)
            ToastMessage('success', '', 'Dashboard Hazır!')

            countCargo = data.regionCargoCount;
            categories = data.regions;
            endorsement = data.regionEndorsements;
            agencyCount = data.agencyCount;

            $('#chart-regions').html('')
            makeChart()
            dataSet = data.data_full
            initGraphTable()

            dataSetAgencies = data.agencies
            initAgenciesTable()

        } else if (response.status == 0) {
            ToastMessage('error', response.message, 'Hata!')
        }
    }).error(function (jqXHR, response) {
        ajaxError(jqXHR.status, JSON.parse(jqXHR.responseText))
    }).always(function () {
        $('.app-main__inner').unblock()
    });
}

$('#btnReloadDashboard').click(function () {
    reloadDashboard()
})


function makeChart() {
    var options = {
        series: [
            {
                name: 'Kargo Adeti',
                type: 'area',
                data: countCargo,
            },
            {
                name: 'Acente Sayısı',
                type: 'line',
                data: agencyCount
            },
            {
                name: 'Ciro',
                type: 'column',
                data: endorsement
            }
        ],
        chart: {
            height: 500,
            type: 'line',
            stacked: true
        },
        dataLabels: {
            enabled: true
        },
        stroke: {
            width: [1, 1, 4]
        },
        title: {
            text: 'CKG-Sis Türkiye Geneli Bölgesel Ciro Analiz (Belirtilen Tarih)',
            align: 'left',
            offsetX: 110
        },
        xaxis: {
            categories: categories,
        },
        yaxis: [{
            axisTicks: {
                show: true,
            },
            axisBorder: {
                show: true,
                color: '#008FFB'
            },
            labels: {
                style: {
                    colors: '#008FFB',
                }
            },
            title: {
                text: "Kargo Adeti",
                style: {
                    color: '#008FFB',
                }
            },
            tooltip: {
                enabled: true
            }
        }, {
            seriesName: 'Kargo Adeti',
            opposite: true,
            axisTicks: {
                show: true,
            },
            axisBorder: {
                show: true,
                color: '#00E396'
            },
            labels: {
                style: {
                    colors: '#00E396',
                }
            },
            title: {
                text: "CİRO",
                style: {
                    color: '#00E396',
                }
            },
        }],
        tooltip: {
            fixed: {
                enabled: true,
                position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
                offsetY: 30,
                offsetX: 60
            },
        },
        legend: {
            horizontalAlign: 'left',
            offsetX: 40
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-regions"), options);
    chart.render();
}


function initGraphTable() {
    if (tabTableInit == false) {
        tabTableInit = true;
        oTable = $('#graphTable').DataTable({
            pageLength: 25,
            lengthMenu: dtLengthMenu,
            order: [3, 'desc'],
            language: dtLanguage,
            dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>frtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'CKG-SİS TÜRKİYE GENELİ BÖLGESEL CİRO ANALİZ (' + $('#firstDate').val() + '-' + $('#lastDate').val() + ')',
                    attr: {
                        class: 'btn btn-success'
                    }
                },
            ],
            data: dataSet,
            search: {"regex": true},
            columns: [
                {data: 'region'},
                {data: 'agencyCount'},
                {data: 'cargoCount'},
                {data: 'regionEndorsements'},
            ],
            scrollY: "400px",
        });
    } else {
        oTable.destroy()
        tabTableInit = false
        initGraphTable()
    }
}

function initAgenciesTable() {
    if (agenciesTableInit == false) {
        agenciesTableInit = true;
        oAgenciesTable = $('#tableAgencies').DataTable({
            pageLength: 10,
            lengthMenu: dtLengthMenu,
            order: [7, 'desc'],
            language: dtLanguage,
            dom: '<"top"<"left-col"l><"center-col text-center"B><"right-col">>frtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'CKG-SİS TÜRKİYE GENELİ BÖLGESEL CİRO ANALİZ (' + $('#firstDate').val() + '-' + $('#lastDate').val() + ')',
                    attr: {
                        class: 'btn btn-success'
                    }
                },
            ],
            search: {"regex": true},
            data: dataSetAgencies,
            columns: [
                {data: 'agency_name'},
                {data: 'region'},
                {data: 'personel_count'},
                {data: 'cargo_count'},
                {data: 'cargo_cargo_count'},
                {data: 'cargo_desi_amount'},
                {data: 'cargo_file_count'},
                {data: 'endorsement'},
            ],
            scrollY: "400px",
        });
    } else {
        oAgenciesTable.destroy()
        agenciesTableInit = false
        initAgenciesTable()
    }
}

$('#tabTable').click(function () {
    initGraphTable()
    setTimeout(function () {
        initGraphTable()
    }, 50)
})







