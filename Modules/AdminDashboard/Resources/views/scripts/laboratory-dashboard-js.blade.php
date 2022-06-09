<script type="text/javascript">
    @if ($lab_permission)
        if (jQuery('#lab-status').length) {
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
                        columnWidth: '15%',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    data:  <?php if(isset($labStatus)){ echo json_encode($labStatus); } ?>
                }],
                xaxis: {
                    categories: <?php if(isset($labStatusTitle)){  echo json_encode($labStatusTitle); } ?>,
                    title: {
                        text: 'No. of Tests'
                    }
                }
            }

            labStatus = new ApexCharts(
                document.querySelector("#lab-status"),
                options
            );

            labStatus.render();
        }

        if (jQuery('#lab-order').length) {
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
                yaxis: {
                    title: {
                        text: 'No. of Orders'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    data:  <?php if(isset($labStatusTitle)){ echo json_encode($labOrder); } ?>
                }],
                xaxis: {
                    categories: <?php if(isset($labStatusTitle)){ echo json_encode($labOrderTitle); } ?>,
                }
            }

            labOrder = new ApexCharts(
                document.querySelector("#lab-order"),
                options
            );

            labOrder.render();
        }
    @endif

    function changelabStatus() {
        labStatus.destroy();
        $.ajax({
            url: '{{ route("admin.dashboard.lab.status.chart") }}',
            type: "POST",
            data: {"chartParam": $('.lab-status-count-change').val(), "_token": "{{ csrf_token() }}"},
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
                            columnWidth: '15%',
                            endingShape: 'rounded'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        data: response.labStatus
                    }],
                    xaxis: {
                        categories: response.labStatusTitle,
                        title: {
                            text: 'No. of Tests'
                        }
                    }
                }

                labStatus = new ApexCharts(
                    document.querySelector("#lab-status"),
                    options
                );

                labStatus.render();

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function changeLabOrder() {
        labOrder.destroy();
        $.ajax({
            url: '{{ route("admin.dashboard.lab.order-status.chart") }}',
            type: "POST",
            data: {"chartParam": $('.lab-order-count-change').val(), "_token": "{{ csrf_token() }}"},
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
                            columnWidth: '15%',
                            endingShape: 'rounded'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'No. of Orders'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        data: response.labOrder
                    }],
                    xaxis: {
                        categories: response.labOrderTitle,
                    }
                }

                labOrder = new ApexCharts(
                    document.querySelector("#lab-order"),
                    options
                );

                labOrder.render();

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

</script>