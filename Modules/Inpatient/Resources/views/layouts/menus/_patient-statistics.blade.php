 <div id="patient_statistics" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="row">
        @if(isset($enpatient))
        <div class="col-md-6">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Essential Examination</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="patient-statistics-bar-graph-container">
                        <div class="mb-3 mt-3" id="patient-statistics-bar-graph"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Input/Output Chart</h4>
                    </div>
                    <select name="" class="inOut-chart-change float-right form-control col-4" onchange="changeInOutChart()">
                        <option value="">Select</option>
                        <option value="Day">Day</option>
                        <option value="Month">Month</option>
                        <option value="Year">Year</option>
                    </select>
                </div>
                <div class="iq-card-body">
                    <div class="male-female-pie-chart-container">
                        <div class="mb-3 mt-3" id="total-inOut-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Blood Pressure</h4>
                        @if($bloodPressureData['avg_systolic'] != null || $bloodPressureData['avg_diastolic'] != null)
                            <p class="mb-0">Today Avg: @if($bloodPressureData['avg_systolic'] != null) {{ $bloodPressureData['avg_systolic'] }} mmHg @endif / @if($bloodPressureData['avg_diastolic'] != null) {{ $bloodPressureData['avg_diastolic'] }} mmHg @endif</p>
                        @else 
                            <p class="mb-0">Today Avg: No Data</p>
                        @endif
                    </div>
                    <select name="" class="blood-pressure-chart-change float-right form-control col-4" onchange="changeBloodPressureChart()">
                        <option value="">Select</option>
                        <option value="Day">Day</option>
                        <option value="Month">Month</option>
                        <option value="Year">Year</option>
                    </select>
                </div>
                <div class="iq-card-body">
                    <div class="patient-blood-pressure-graph-container">
                        <div class="mb-3 mt-3" id="patient-blood-pressure-graph"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>


@push('after-script')
    <script src="{{ asset('new/js/core.js') }}"></script>
    <script src="{{ asset('new/js/charts.js') }}"></script>
    <script type="text/javascript">
        var colorForAll = ['#cc9900', '#cd0505', '#6da43c', '#01cd9d', '#cc9900'
                , '#1bd238', '#888ccd', '#8a5d7d', '#e58080', '#cd0505', '#6da43c', '#01cd9d', '#cc9900'
                , '#1bd238', '#545ad9', '#ab61d9', '#ab0f71'];

        function shuffle(array) {
            var currentIndex = array.length, temporaryValue, randomIndex;
            while (0 !== currentIndex) {
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;

                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
            }

            return array;
        }
        jQuery(document).ready(function () {
            
            if (jQuery('#patient-statistics-bar-graph').length) {
                var options = {
                    chart: {
                        type: 'bar',
                    },
                    colors: shuffle(colorForAll),
                    plotOptions: {
                        bar: {
                            distributed: true,
                            columnWidth: '15%',
                            endingShape: 'rounded'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        data: <?= (isset($graphData['values'])) ? json_encode($graphData['values']) : 0 ?>
                    }],
                    xaxis: {
                        categories: <?= (isset($graphData['heads'])) ? json_encode($graphData['heads']) : 0 ?>,
                    }
                }
                chartByBillingMode = new ApexCharts(
                    document.querySelector("#patient-statistics-bar-graph"),
                    options
                );

                chartByBillingMode.render();
            }

            @if(isset($enpatient))
                if (jQuery('#total-inOut-chart').length) {
                    var options = {
                        chart: {
                            type: 'bar',
                            height: '350px'
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '15%',
                                endingShape: 'rounded'
                            },
                        },
                        colors: shuffle(colorForAll),
                        annotations: {
                            yaxis: [{
                                borderColor: '#999',
                                label: {
                                    show: true,
                                    text: 'Support',
                                    style: {
                                        color: "#fff",
                                        background: '#00E396'
                                    }
                                }
                            }],
                            xaxis: [{
                                borderColor: '#999',
                                yAxisIndex: 0,
                                label: {
                                    show: true,
                                    text: 'Rally',
                                    style: {
                                        color: "#fff",
                                        background: '#775DD0'
                                    }
                                }
                            }]
                        },
                        dataLabels: {
                            enabled: false
                        },
                        markers: {
                            size: 0,
                            style: 'hollow',
                        },
                        tooltip: {
                            x: {
                                format: 'dd MMM yyyy'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Input in ML'
                            }
                        },
                        series: [
                            {
                                name: 'Input',
                                data: <?php echo json_encode($inOutGraphData['inputMlStatus']) ?>
                            }, {
                                name: 'Output',
                                data: <?php echo json_encode($inOutGraphData['outputMlStatus']) ?>
                            }
                        ],
                        xaxis: {
                            categories: <?php echo json_encode($inOutGraphData['statusTitle']) ?>,
                            type: 'datetime',
                            tickAmount: 10,
                        }
                    }

                    totalInOutStatistics = new ApexCharts(
                        document.querySelector("#total-inOut-chart"),
                        options
                    );

                    totalInOutStatistics.render();
                }

                if(jQuery('#patient-blood-pressure-graph').length){
                    var options = {
                        chart: {
                            type: 'bar',
                            height: '350px'
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '15px',
                                endingShape: 'rounded'
                            },
                        },
                        colors: shuffle(colorForAll),
                        annotations: {
                            yaxis: [{
                                borderColor: '#999',
                                label: {
                                    show: true,
                                    text: 'Support',
                                    style: {
                                        color: "#fff",
                                        background: '#00E396'
                                    }
                                }
                            }],
                            xaxis: [{
                                borderColor: '#999',
                                yAxisIndex: 0,
                                label: {
                                    show: true,
                                    text: 'Rally',
                                    style: {
                                        color: "#fff",
                                        background: '#775DD0'
                                    }
                                }
                            }]
                        },
                        dataLabels: {
                            enabled: false
                        },
                        markers: {
                            size: 0,
                            style: 'hollow',
                        },
                        tooltip: {
                            x: {
                                format: 'dd MMM yyyy'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Data in mmHg'
                            }
                        },
                        series: [
                            {
                                name: 'Systolic BP',
                                data: <?php echo json_encode($bloodPressureData['systolicData']) ?>
                            }, {
                                name: 'Diastolic BP',
                                data: <?php echo json_encode($bloodPressureData['diastolicData']) ?>
                            }
                        ],
                        xaxis: {
                            categories: <?php echo json_encode($bloodPressureData['testDateData']) ?>,
                            type: 'datetime',
                            tickAmount: 10,
                        }
                    }

                    bloodPressureStatistics = new ApexCharts(
                        document.querySelector("#patient-blood-pressure-graph"),
                        options
                    );

                    bloodPressureStatistics.render();
                }
            @endif
        });

        @if(isset($enpatient))
            function changeInOutChart(){
                totalInOutStatistics.destroy();
                $.ajax({
                    url: '{{ route("change.inOut.chart") }}',
                    type: "POST",
                    data: {"chartParam": $('.inOut-chart-change').val(), "_token": "{{ csrf_token() }}"},
                    success: function (response, status, xhr) {
                        var options = {
                            chart: {
                                type: 'bar',
                                height: '350px'
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '15%',
                                    endingShape: 'rounded'
                                },
                            },
                            colors: shuffle(colorForAll),
                            annotations: {
                                yaxis: [{
                                    borderColor: '#999',
                                    label: {
                                        show: true,
                                        text: 'Support',
                                        style: {
                                            color: "#fff",
                                            background: '#00E396'
                                        }
                                    }
                                }],
                                xaxis: [{
                                    borderColor: '#999',
                                    yAxisIndex: 0,
                                    label: {
                                        show: true,
                                        text: 'Rally',
                                        style: {
                                            color: "#fff",
                                            background: '#775DD0'
                                        }
                                    }
                                }]
                            },
                            dataLabels: {
                                enabled: false
                            },
                            markers: {
                                size: 0,
                                style: 'hollow',
                            },
                            tooltip: {
                                x: {
                                    format: 'dd MMM yyyy'
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Input in ML'
                                }
                            },
                            series: [
                                {
                                    name: 'Input',
                                    data: response.inputMlStatus
                                }, {
                                    name: 'Output',
                                    data: response.outputMlStatus
                                }
                            ],
                            xaxis: {
                                categories: response.statusTitle,
                                type: 'datetime',
                                tickAmount: 10,
                            }
                        }

                        totalInOutStatistics = new ApexCharts(
                            document.querySelector("#total-inOut-chart"),
                            options
                        );

                        totalInOutStatistics.render();

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }

            function changeBloodPressureChart(){
                bloodPressureStatistics.destroy();
                $.ajax({
                    url: '{{ route("change.blood-pressure.chart") }}',
                    type: "POST",
                    data: {"chartParam": $('.blood-pressure-chart-change').val(), "_token": "{{ csrf_token() }}"},
                    success: function (response, status, xhr) {
                        var options = {
                            chart: {
                                type: 'bar',
                                height: '350px'
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '15px',
                                    endingShape: 'rounded'
                                },
                            },
                            colors: shuffle(colorForAll),
                            annotations: {
                                yaxis: [{
                                    borderColor: '#999',
                                    label: {
                                        show: true,
                                        text: 'Support',
                                        style: {
                                            color: "#fff",
                                            background: '#00E396'
                                        }
                                    }
                                }],
                                xaxis: [{
                                    borderColor: '#999',
                                    yAxisIndex: 0,
                                    label: {
                                        show: true,
                                        text: 'Rally',
                                        style: {
                                            color: "#fff",
                                            background: '#775DD0'
                                        }
                                    }
                                }]
                            },
                            dataLabels: {
                                enabled: false
                            },
                            markers: {
                                size: 0,
                                style: 'hollow',
                            },
                            tooltip: {
                                x: {
                                    format: 'dd MMM yyyy'
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Data in mmHg'
                                }
                            },
                            series: [
                                {
                                    name: 'Systolic BP',
                                    data: response.systolicData
                                }, {
                                    name: 'Diastolic BP',
                                    data: response.diastolicData
                                }
                            ],
                            xaxis: {
                                categories: response.testDateData,
                                type: 'datetime',
                                tickAmount: 10,
                            }
                        }

                        bloodPressureStatistics = new ApexCharts(
                            document.querySelector("#patient-blood-pressure-graph"),
                            options
                        );

                        bloodPressureStatistics.render();

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        @endif

    </script>
@endpush
