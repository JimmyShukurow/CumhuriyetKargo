@extends('backend.layout')

@push('css')
    <link rel="stylesheet" href="/backend/assets/css/app-main-block.css">
@endpush()

@section('title', 'Bölgesel Rapor')

@section('content')

    <div class="app-main__inner">

        <div class="app-inner-layout">
            <div class="app-inner-layout__header-boxed p-0">
                <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fa fa-university icon-gradient bg-slick-carbon"> </i>
                                </div>
                                <div>
                                    Bölgesel Rapor (Operasyonel)
                                    <div class="page-title-subheading">Cumhuriyet Kargo Türkiye Geneli Operasyonel
                                        Raporu.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Toplam Acente</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            <span class="opacity-10 text-primary pr-2"><i
                                                    class="lnr-store"></i></span>
                                            {{$data['agency_quantity']}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-agency d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-danger card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Toplam Bölge Müdürlüğü</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                                    <span class="opacity-10 text-danger pr-2">
                                                        <i class="fa fa-university"></i>
                                                    </span>
                                            {{$data['region_quantity']}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-region d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-warning card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Toplam Transfer Merkezi</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                        <span class="opacity-10 text-warning pr-2">
                                                        <i class="fa fa-truck"></i>
                                                    </span>
                                            {{$data['tc_quantity']}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-tc d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div
                        class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-success card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Boşta Kalan İlçeler</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                             <span class="opacity-10 text-success pr-2">
                                                        <i class="fa fa-unlink"></i>
                                                    </span>
                                            {{ $data['idle_districts_quantity']}}
                                            <small class="opacity-5 pl-1">adet</small>
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-idle-district d-inline-block">
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-7 col-lg-8">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">En Çok
                                Acenteye
                                Sahip Olan Bölgeler
                            </div>
                            <div class="btn-actions-pane-right text-capitalize">
                            </div>
                        </div>
                        <div class="pt-0 card-body">
                            <div id="chart-regions"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 col-lg-4">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">Verilen
                                İlçeler
                            </div>

                        </div>
                        <div class="p-0 card-body">
                            <div id="chart-idle"></div>

                            <div class="col-md-12">
                                <div
                                    class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-alternate border-alternate card">
                                    <div class="widget-chat-wrapper-outer">
                                        <div class="widget-chart-content">
                                            <div class="widget-title opacity-5 ">Toplam İlçe/Verilen İlçe</div>
                                            <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                                <div class="widget-chart-flex align-items-center">
                                                    <div>
                                        <span class="opacity-10 text-alternate pr-2">
                                                        <i class="fa fa-flag"></i>
                                                    </span>
                                                        {{$data['total_districts'] . '/' . $data['regional_districts'] }}
                                                    </div>
                                                    <div
                                                        class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                                        <div
                                                            class="circle-progress circle-idle-districts d-inline-block">
                                                            <small></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3 card">
                        <div class="tabs-lg-alternate card-header">
                            <ul class="nav nav-justified">
                                <li class="nav-item">

                                    <a data-toggle="tab" href="#idle-districts" class="nav-link show active">
                                        <div style="font-size: 1.4rem;" class="widget-number">Bölgesi Olmayan İlçeler
                                        </div>
                                        <div class="tab-subheading">
                                            <span class="pr-2 opactiy-6">
                                                <i class="fa fa-comment-dots"></i>
                                            </span>Bölge müdürlüğüne verilmeyen boşta kalan ilçeler.
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#idle-agiencies-region" class="nav-link show">
                                        <div style="font-size: 1.4rem;" class="widget-number text-warning">Bölgesi
                                            Olmayan Acenteler
                                        </div>
                                        <div class="tab-subheading">Herhangi bir bölge müdürlüğüne bağlı olmayan
                                            acenteler
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#idle-agencies-tc" class="nav-link show">
                                        <div style="font-size: 1.4rem;" class="widget-number text-danger">Aktarması
                                            Olmayan Acenteler
                                        </div>
                                        <div class="tab-subheading">
                                        <span class="pr-2 opactiy-6">
                                        <i class="fa fa-bullhorn"></i>
                                         </span>Herhangi bir transfer merkezine bağlı olmayan acenteler.
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="idle-districts" role="tabpanel">
                                <div class="card-body">
                                    <div class="card-body">
                                        <table id="TableRolePermissions"
                                               style="white-space: nowrap; width: 100% !important;"
                                               class="table table-hover table-striped  table-bordered TableNoPadding NikolasDataTable IdleDistricts table-hover">
                                            <thead>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Bölge</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Bölge</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show" id="idle-agiencies-region" role="tabpanel">
                                <div class="card-body">
                                    <div class="card-body">
                                        <table id="TableRolePermissions"
                                               style="white-space: nowrap; width: 100% !important;"
                                               class="table table-hover table-striped  table-bordered TableNoPadding NikolasDataTable IdleAgenciesRegion table-hover">
                                            <thead>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Acente İsmi</th>
                                                <th>Acente Sahibi</th>
                                                <th>Bölge</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>İl</th>
                                                <th>İlçe</th>
                                                <th>Acente İsmi</th>
                                                <th>Acente Sahibi</th>
                                                <th>Bölge</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show" id="idle-agencies-tc" role="tabpanel">
                                <div class="card-body">
                                    <div class="card-body">
                                        <div class="card-body">
                                            <table id="TableRolePermissions"
                                                   style="white-space: nowrap; width: 100%;"
                                                   class="table table-hover table-striped  table-bordered TableNoPadding NikolasDataTable IdleAgenciesTC"
                                                   role="grid">
                                                <thead>

                                                <tr>
                                                    <th>İl</th>
                                                    <th>İlçe</th>
                                                    <th>Acente İsmi</th>
                                                    <th>Acente Sahibi</th>
                                                    <th>Bağlı Old. Aktarma</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>İl</th>
                                                    <th>İlçe</th>
                                                    <th>Acente İsmi</th>
                                                    <th>Acente Sahibi</th>
                                                    <th>Bağlı Old. Aktarma</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        @endsection


        @section('js')
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script src="/backend/assets/scripts/circle-progress.min.js"></script>
            <script src="/backend/assets/scripts/NikoStyleDataTable.js"></script>
            <script src="/backend/assets/scripts/regional-directorates/report.js"></script>

            <script>

                let bagliIlceData = [];

                @foreach($data['regions'] as $key)
                bagliIlceData.push({{ $key->district_covered_quantity  }})
                @endforeach

                console.log(bagliIlceData)
                var options = {
                    series: [{
                        data: [4, 3, 10, 9, 29, 19, 22]
                    }],
                    chart: {
                        type: "histogram",
                        height: 380,
                        foreColor: "#999",
                        events: {
                            dataPointSelection: function (e, chart, opts) {
                                var arraySelected = []
                                opts.selectedDataPoints.map(function (selectedIndex) {
                                    return selectedIndex.map(function (s) {
                                        return arraySelected.push(chart.w.globals.series[0][s])
                                    })

                                });
                                arraySelected = arraySelected.reduce(function (acc, curr) {
                                    return acc + curr;
                                }, 0)

                                document.querySelector("#selected-count").innerHTML = arraySelected
                            }
                        }
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: false
                            }
                        }
                    },
                    states: {
                        active: {
                            allowMultipleDataPointsSelection: true
                        }
                    },
                    xaxis: {
                        categories: [10, 20, 30, 40, 50, 60, 70],
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        tickAmount: 4,
                        labels: {
                            offsetX: -5,
                            offsetY: -5
                        },
                    },
                    tooltip: {
                        x: {
                            format: "dd MMM yyyy"
                        },
                    },
                };

                var options777 = {
                        chart: {
                            height: 397,
                            type: 'line',
                            toolbar: {
                                show: true,
                            }
                        },
                        series: [{
                            name: 'Bağlı İlçe',
                            type: 'column',
                            data: []
                        }, {
                            name: 'Bağlı Acente',
                            type: 'line',
                            data: bagliIlceData
                        }],

                        labels: [
                            @foreach($data['regions'] as $key)
                                '{{ $key->name  . ' B.M.'}}',
                            @endforeach
                        ],

                        yaxis:
                            [{
                                title: {
                                    text: 'Bölgeye Bağlı İlçe',
                                },

                            }, {
                                opposite: true,
                                title: {
                                    text: 'Bölgeye Bağlı Acente'
                                }
                            }]

                    }
                ;

                var chart = new ApexCharts(document.querySelector("#chart-regions"), options777);
                chart.render();


                chart.addEventListener("dataPointSelection", function (e, opts) {
                    console.log(e, opts)
                })

            </script>

            <script>
                // Combined

                var options777 = {
                    chart: {
                        height: 397,
                        type: 'line',
                        toolbar: {
                            show: false,
                        }
                    },
                    series: [{
                        name: 'Website Blog',
                        type: 'column',
                        data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
                    }, {
                        name: 'Social Media',
                        type: 'line',
                        data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
                    }],
                    stroke: {
                        width: [0, 4]
                    },
                    // labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001', '06 Jan 2001', '07 Jan 2001', '08 Jan 2001', '09 Jan 2001', '10 Jan 2001', '11 Jan 2001', '12 Jan 2001'],
                    xaxis: {
                        type: 'datetime'
                    },
                    yaxis: [{
                        title: {
                            text: 'Website Blog',
                        },

                    }, {
                        opposite: true,
                        title: {
                            text: 'Social Mediaaaa'
                        }
                    }]

                };

                var optionsRadial = {
                    series: [{{ round(($data['regional_districts'] / $data['total_districts']) * 100, 0) }}],
                    chart: {
                        height: 350,
                        type: 'radialBar',
                        toolbar: {
                            show: true
                        }
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 225,
                            hollow: {
                                margin: 0,
                                size: '70%',
                                background: '#fff',
                                image: undefined,
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                                dropShadow: {
                                    enabled: true,
                                    top: 3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.24
                                }
                            },
                            track: {
                                background: '#fff',
                                strokeWidth: '67%',
                                margin: 0, // margin is in pixels
                                dropShadow: {
                                    enabled: true,
                                    top: -3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.35
                                }
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    offsetY: -10,
                                    show: true,
                                    color: '#888',
                                    fontSize: '17px'
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    color: '#111',
                                    fontSize: '36px',
                                    show: true,
                                }
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#ABE5A1'],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                    labels: ['%'],
                };

                var chart = new ApexCharts(document.querySelector("#chart-idle"), optionsRadial);
                chart.render();

                $(document).ready(function () {
                    var chart777 = new ApexCharts(
                        document.querySelector("#chart-regions"),
                        options777
                    );

                    $('.circle-agency').circleProgress({
                        value: 100,
                        size: 46,
                        lineCap: 'round',
                        fill: {gradient: ['#007bff', '#16aaff']}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(0) + '<span>');
                    });

                    $('.circle-region').circleProgress({
                        value: 100,
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#d92550'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(0) + '<span>');
                    });

                    $('.circle-tc').circleProgress({
                        value: 100,
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#fd7e14'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(0) + '<span>');
                    });

                    $('.circle-idle-district').circleProgress({
                        value: {{ '.'.round(($data['idle_districts_quantity'] / $data['total_districts']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#3ac47d'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(2).substring(2) + '<span>');
                    });

                    $('.circle-idle-districts').circleProgress({
                        value: {{ '.'.round(($data['regional_districts'] / $data['total_districts']) * 100, 0) }},
                        size: 46,
                        lineCap: 'round',
                        fill: {color: '#794C8A'}

                    }).on('circle-animation-progress', function (event, progress, stepValue) {
                        $(this).find('small').html('<span>%' + stepValue.toFixed(2).substring(2) + '<span>');
                    });

                });
            </script>

@endsection
