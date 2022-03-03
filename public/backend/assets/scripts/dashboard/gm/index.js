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

            countCargo = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140];
            categories = data.regions;
            endersement = [15, 25, 35, 45, 55, 65, 75, 85, 95, 105, 115, 125, 135, 145];
            agencyCount = data.agencyCount;

            makeChart(categories, countCargo, endersement, agencyCount)

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


function makeChart(categories, countCargo, endorsement, agencyCount) {

    var options = {
        series: [{
            name: 'Kargo Adeti',
            type: 'column',
            data: countCargo
        }, {
            name: 'Ciro',
            type: 'column',
            data: endorsement
        }, {
            name: 'Acente Sayısı',
            type: 'line',
            data: agencyCount
        }],
        chart: {
            height: 500,
            type: 'line',
            stacked: false
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
        yaxis: [
            {
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
            },
            {
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
            },
            {
                seriesName: 'Revenue',
                opposite: true,
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    color: '#FEB019'
                },
                labels: {
                    style: {
                        colors: '#FEB019',
                    },
                },
                title: {
                    text: "Acente Sayısı",
                    style: {
                        color: '#FEB019',
                    }
                }
            },
        ],
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
