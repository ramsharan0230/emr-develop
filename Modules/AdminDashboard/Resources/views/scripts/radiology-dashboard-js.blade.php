<script type="text/javascript">
    @if ($radio_permission)
        if (jQuery('#radiology-status').length) {
            var options = {
                chart: {
                    type: 'bar',
                    height: '350px'
                },
                colors: shuffle(colorForAll),
                plotOptions: {
                    bar: {
                        distributed: true,
                        horizontal: true,
                        columnWidth: '15%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                grid: {
                    row: {
                        colors: ['#fff', '#f2f2f2']
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "vertical",
                        shadeIntensity: 0.25,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 0.85,
                        stops: [50, 0, 100]
                    },
                },
                series: [{
                    data:  <?php echo json_encode($radiologyStatus) ?>
                }],
                xaxis: {
                    categories: <?php echo json_encode($radiologyStatusTitle) ?>,
                    title: {
                        text: 'No. of Tests'
                    }
                }
            }

            radiologyStatus = new ApexCharts(
                document.querySelector("#radiology-status"),
                options
            );

            radiologyStatus.render();
        }

        if (jQuery('#radiology-order').length) {
            var options = {
                chart: {
                    type: 'bar',
                    height: '350px'
                },
                colors: shuffle(colorForAll),
                plotOptions: {
                    bar: {
                        distributed: true,
                        horizontal: false,
                        columnWidth: '15%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                grid: {
                    row: {
                        colors: ['#fff', '#f2f2f2']
                    }
                },
                yaxis: {
                    title: {
                        text: 'No. of Orders'
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.25,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 0.85,
                        stops: [50, 0, 100]
                    },
                },
                series: [{
                    data:  <?php echo json_encode($radiologyOrder) ?>
                }],
                xaxis: {
                    categories: <?php echo json_encode($radiologyOrderTitle) ?>,
                }
            }

            radiologyOrder = new ApexCharts(
                document.querySelector("#radiology-order"),
                options
            );

            radiologyOrder.render();
        }
    @endif

    function changeRadiologyStatus() {
        radiologyStatus.destroy();
        $.ajax({
            url: '{{ route("admin.dashboard.radiology.status.chart") }}',
            type: "POST",
            data: {"chartParam": $('.radiology-status-count-change').val(), "_token": "{{ csrf_token() }}"},
            success: function (response, status, xhr) {
                var options = {
                    chart: {
                        type: 'bar',
                        height: '250px'
                    },
                    colors: shuffle(colorForAll),
                    plotOptions: {
                        bar: {
                            distributed: true,
                            horizontal: true,
                            columnWidth: '15%'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    grid: {
                        row: {
                            colors: ['#fff', '#f2f2f2']
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: "vertical",
                            shadeIntensity: 0.25,
                            gradientToColors: undefined,
                            inverseColors: true,
                            opacityFrom: 0.85,
                            opacityTo: 0.85,
                            stops: [50, 0, 100]
                        },
                    },
                    series: [{
                        data: response.radiologyStatus
                    }],
                    xaxis: {
                        categories: response.radiologyStatusTitle,
                        title: {
                            text: 'No. of Tests'
                        }
                    }
                }

                radiologyStatus = new ApexCharts(
                    document.querySelector("#radiology-status"),
                    options
                );

                radiologyStatus.render();

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function changeRadiologyOrder() {
        radiologyOrder.destroy();
        $.ajax({
            url: '{{ route("admin.dashboard.radiology.order-status.chart") }}',
            type: "POST",
            data: {"chartParam": $('.radiology-order-count-change').val(), "_token": "{{ csrf_token() }}"},
            success: function (response, status, xhr) {
                var options = {
                    chart: {
                        type: 'bar',
                        height: '250px'
                    },
                    colors: shuffle(colorForAll),
                    plotOptions: {
                        bar: {
                            distributed: true,
                            horizontal: false,
                            columnWidth: '15%',
                            endingShape: 'rounded'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    grid: {
                        row: {
                            colors: ['#fff', '#f2f2f2']
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'No. of Orders'
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: "horizontal",
                            shadeIntensity: 0.25,
                            gradientToColors: undefined,
                            inverseColors: true,
                            opacityFrom: 0.85,
                            opacityTo: 0.85,
                            stops: [50, 0, 100]
                        },
                    },
                    series: [{
                        data: response.radiologyOrder
                    }],
                    xaxis: {
                        categories: response.radiologyOrderTitle,
                    }
                }

                radiologyOrder = new ApexCharts(
                    document.querySelector("#radiology-order"),
                    options
                );

                radiologyOrder.render();

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }


    $(document).on("change","#change-view-radiology",function(){
        radiologyStatus.destroy();
        var url = "";
        if($('#change-view-radiology').val() == "order_type"){
            url = "{{ route("admin.dashboard.radiology.order-status.chart") }}";
        }else if($('#change-view-radiology').val() == "radio_newold_patient"){
            url = "{{ route("admin.dashboard.radio.newold-patient.chart") }}";
        }else if($('#change-view-radiology').val() == "radio_inpatient_outpatient"){
            url = "{{ route("admin.dashboard.radio.inpatient-outpatient.chart") }}";
        }else{
            url = "{{ route("admin.dashboard.radiology.status.chart") }}";
        }
        $.ajax({
            url: url,
            type: "POST",
            data: {"chartParam": $('.radiology-status-count-change').val(), "_token": "{{ csrf_token() }}"},
            success: function (response, status, xhr) {
                var options = {
                    chart: {
                        type: 'bar',
                        height: '350px'
                    },
                    colors: shuffle(colorForAll),
                    plotOptions: {
                        bar: {
                            distributed: true,
                            horizontal: false,
                            columnWidth: '20%',
                            endingShape: 'rounded'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        data: response.radiologyStatus
                    }],
                    xaxis: {
                        categories: response.radiologyStatusTitle,
                    }
                }

                radiologyStatus = new ApexCharts(
                    document.querySelector("#radiology-status"),
                    options
                );

                radiologyStatus.render();

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });
</script>