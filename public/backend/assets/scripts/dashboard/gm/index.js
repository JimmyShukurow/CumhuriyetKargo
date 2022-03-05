let countCargo, categories, endorsement, agencyCount;

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
                name: 'Acente Sayısı',
                type: 'line',
                data: agencyCount
            },
            {
                name: 'Kargo Adeti',
                type: 'area',
                data: countCargo,
            }, {
                name: 'Ciro',
                type: 'column',
                data: endorsement
            }],
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

let tabTableInit = false
$('#tabTable').click(function () {

    if (tabTableInit == false) {
        tabTableInit = true
        oTable = $('#graphTable').DataTable({
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, 100, 250, 500, -1],
                ["10 Adet", "25 Adet", "50 Adet", "100 Adet", "250 Adet", "500 Adet", "Tümü"]
            ],
            order: [
                3, 'desc'
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
                {
                    extend: 'excelHtml5',
                    attr: {
                        class: 'btn btn-success'
                    }
                },
            ],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/Dashboard/GM/AjaxTransactions/GetRegionAnalysis',
                data:{
                    firstDate: $('#firstDate').val(),
                    lastDate: $('#lastDate').val(),
                },
                error: function (xhr, error, code) {
                    if (code == "Too Many Requests") {
                        ToastMessage('info', 'Aşırı istekte bulundunuz, Lütfen bir süre sonra tekrar deneyin!', 'Hata');
                    }
                }
            },
            columns: [
                {data: 'region', name: 'region'},
                {data: 'region', name: 'region'},
                {data: 'region', name: 'region'},
                {data: 'region', name: 'region'},
            ],
            scrollY: "400px",
        });
    }

});



