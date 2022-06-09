<script type="text/javascript">
    @if ($pharmacy_permission)
        if (jQuery('#pharmacy-op-sales').length) {
            var options = {
                chart: {
                    type: 'area',
                    height: '350px'
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
                        text: 'In Nepalese Rupee (NPR)'
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 100]
                    }
                },
                series: [{
                    data:  <?php echo json_encode($pharmacyOpStatus) ?>
                }],
                xaxis: {
                    categories: <?php echo json_encode($pharmacyOpStatusTitle) ?>,
                    type: 'datetime',
                    tickAmount: 10,
                }
            }

            pharmacyOpStatistics = new ApexCharts(
                document.querySelector("#pharmacy-op-sales"),
                options
            );

            pharmacyOpStatistics.render();
        }

        if (jQuery('#pharmacy-ip-sales').length) {
            var options = {
                chart: {
                    type: 'area',
                    height: '350px'
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
                        text: 'In Nepalese Rupee (NPR)'
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 100]
                    }
                },
                series: [{
                    data:  <?php echo json_encode($pharmacyIpStatus) ?>
                }],
                xaxis: {
                    categories: <?php echo json_encode($pharmacyIpStatusTitle) ?>,
                    type: 'datetime',
                    tickAmount: 10,
                }
            }

            pharmacyIpStatistics = new ApexCharts(
                document.querySelector("#pharmacy-ip-sales"),
                options
            );

            pharmacyIpStatistics.render();
        }
    @endif
    
    function opSalesByBilling(){
        pharmacyOpStatistics.destroy();
        $.ajax({
            url: "{{ route("admin.dashboard.pharmacy.op-sales.chart") }}",
            type: "POST",
            data: {"billingSet": $('.opsales-billing-set-change').val(),"_token": "{{ csrf_token() }}"},
            success: function (response, status, xhr) {
                    var options = {
                        chart: {
                        type: 'area',
                        height: '350px'
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
                            text: 'In Nepalese Rupee (NPR)'
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.9,
                            stops: [0, 100]
                        }
                    },
                    series: [{
                        data:  response.pharmacyOpStatus
                    }],
                    xaxis: {
                        categories: response.pharmacyOpStatusTitle,
                        type: 'datetime',
                        tickAmount: 10,
                    }
                }

                pharmacyOpStatistics = new ApexCharts(
                    document.querySelector("#pharmacy-op-sales"),
                    options
                );

                pharmacyOpStatistics.render();
                
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function ipSalesByBilling(){
        pharmacyIpStatistics.destroy();
        $.ajax({
            url: "{{ route("admin.dashboard.pharmacy.ip-sales.chart") }}",
            type: "POST",
            data: {"billingSet": $('.ipsales-billing-set-change').val(),"_token": "{{ csrf_token() }}"},
            success: function (response, status, xhr) {
                    var options = {
                        chart: {
                        type: 'area',
                        height: '350px'
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
                            text: 'In Nepalese Rupee (NPR)'
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.9,
                            stops: [0, 100]
                        }
                    },
                    series: [{
                        data:  response.pharmacyIpStatus
                    }],
                    xaxis: {
                        categories: response.pharmacyIpStatusTitle,
                        type: 'datetime',
                        tickAmount: 10,
                    }
                }

                pharmacyIpStatistics = new ApexCharts(
                    document.querySelector("#pharmacy-ip-sales"),
                    options
                );

                pharmacyIpStatistics.render();
                
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

</script>
