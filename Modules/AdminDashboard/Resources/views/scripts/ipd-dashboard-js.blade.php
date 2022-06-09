<script type="text/javascript">
    @if ($ipd_permission)
        if (jQuery('#paitient-by-IPD').length) {
            // am4core.ready(function () {

            //     // Themes begin
            //     am4core.useTheme(am4themes_animated);
            //     // Themes end

            //     patientByIPD = am4core.create("paitient-by-IPD", am4charts.PieChart);
            //     patientByIPD.hiddenState.properties.opacity = 0;

            //     patientByIPD.data = <?php echo json_encode($patientByIPD) ?>;
            //     patientByIPD.innerRadius = am4core.percent(50);
            //     patientByIPD.startAngle = 0;
            //     patientByIPD.endAngle = 360;

            //     var series = patientByIPD.series.push(new am4charts.PieSeries());
            //     series.dataFields.value = "Count";
            //     series.dataFields.category = "IPD";
            //     series.colors.list = [am4core.color("#faa264"),
            //         am4core.color("#1524a5"), am4core.color("#2ca5b2"), am4core.color("#50a71b"), am4core.color("#b3900e"),];

            //     series.slices.template.cornerRadius = 0;
            //     series.slices.template.innerCornerRadius = 0;
            //     series.slices.template.draggable = true;
            //     series.slices.template.inert = true;
            //     series.alignLabels = true;

            //     series.hiddenState.properties.startAngle = 90;
            //     series.hiddenState.properties.endAngle = 90;

            //     patientByIPD.legend = new am4charts.Legend();
            //     patientByIPD.legend.position = "right";
            //     patientByIPD.legend.maxHeight = 220;
            //     patientByIPD.legend.scrollable = true;

            //     let marker = patientByIPD.legend.markers.template.children.getIndex(0);
            //     marker.cornerRadius(12, 12, 12, 12);
            //     marker.strokeWidth = 2;
            //     marker.strokeOpacity = 1;
            // });

            var options = {
                chart: {
                    height: 400,
                    type: 'donut',
                },
                noData: {
                    text: 'No Data Available'
                },
                colors: shuffle(colorForAll),
                labels: <?php echo json_encode($patientByIPDTitle) ?>,
                series: <?php echo json_encode($patientIPD) ?>,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            }

            chartByDepartmentIPD = new ApexCharts(
                document.querySelector("#paitient-by-IPD"),
                options
            );

            chartByDepartmentIPD.render();
        }

        if (jQuery('#paitient-by-bed-occupacy').length) {
            // am4core.ready(function () {

            //     // Themes begin
            //     am4core.useTheme(am4themes_animated);
            //     // Themes end

            //     patientByBedOccupacy = am4core.create("paitient-by-bed-occupacy", am4charts.PieChart);
            //     // patientByBedOccupacy.height = am4core.percent(200);
            //     patientByBedOccupacy.hiddenState.properties.opacity = 0;

            //     patientByBedOccupacy.data = <?php echo json_encode($patientByBedOccupacy) ?>;
            //     patientByBedOccupacy.innerRadius = am4core.percent(50);
            //     patientByBedOccupacy.startAngle = 180;
            //     patientByBedOccupacy.endAngle = 360;

            //     var series = patientByBedOccupacy.series.push(new am4charts.PieSeries());
            //     series.dataFields.value = "Count";
            //     series.dataFields.category = "IPD";
            //     series.colors.list = [am4core.color("#faa264"),
            //         am4core.color("#1524a5"), am4core.color("#2ca5b2"), am4core.color("#50a71b"), am4core.color("#b3900e"),];

            //     series.slices.template.cornerRadius = 0;
            //     series.slices.template.innerCornerRadius = 0;
            //     series.slices.template.draggable = true;
            //     series.slices.template.inert = true;
            //     series.alignLabels = false;

            //     series.hiddenState.properties.startAngle = 90;
            //     series.hiddenState.properties.endAngle = 90;

            //     patientByBedOccupacy.legend = new am4charts.Legend();
            //     patientByBedOccupacy.legend.position = "right";
            //     patientByBedOccupacy.legend.maxHeight = 220;
            //     patientByBedOccupacy.legend.scrollable = true;
                
            //     let marker = patientByBedOccupacy.legend.markers.template.children.getIndex(0);
            //     marker.cornerRadius(12, 12, 12, 12);
            //     marker.strokeWidth = 2;
            //     marker.strokeOpacity = 1;
            // });

            var options = {
                chart: {
                    height: 400,
                    type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                noData: {
                    text: 'No Data Available'
                },
                dataLabels: {
                  enabled: true,
                },
                stroke: {
                    curve: 'smooth'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                markers: {
                    size: 1
                },
                xaxis: {
                    categories: <?php echo json_encode($patientBedOccupacyTitle) ?>,
                },
                yaxis: {
                    title: {
                        text: 'No. of Patients'
                    }
                },
                colors: shuffle(colorForAll),
                // labels: <?php echo json_encode($patientBedOccupacyTitle) ?>,
                series: [{
                    data: <?php echo json_encode($patientBedOccupacy) ?>
                }],
                // responsive: [{
                //     breakpoint: 480,
                //     options: {
                //         chart: {
                //             width: 200
                //         },
                //         legend: {
                //             position: 'bottom'
                //         }
                //     }
                // }]
            }

            chartByBedOccupacy = new ApexCharts(
                document.querySelector("#paitient-by-bed-occupacy"),
                options
            );

            chartByBedOccupacy.render();
        }
    @endif

    function changePatientByIPD() {
        chartByDepartmentIPD.destroy();
        $.ajax({
            url: '{{ route("admin.dashboard.patient.ipd.chart") }}',
            type: "POST",
            data: {"chartParam": $('.paitient-by-IPD-count-change').val(), "_token": "{{ csrf_token() }}"},
            success: function (response, status, xhr) {
                // am4core.ready(function () {

                //     // Themes begin
                //     am4core.useTheme(am4themes_animated);
                //     // Themes end

                //     patientByIPD = am4core.create("paitient-by-IPD", am4charts.PieChart);
                //     patientByIPD.hiddenState.properties.opacity = 0;

                //     patientByIPD.data = response.patientByIPD;
                //     patientByIPD.innerRadius = am4core.percent(40);
                //     patientByIPD.startAngle = 180;
                //     patientByIPD.endAngle = 360;

                //     var series = patientByIPD.series.push(new am4charts.PieSeries());
                //     series.dataFields.value = "Count";
                //     series.dataFields.category = "IPD";
                //     series.colors.list = [am4core.color("#faa264"),
                //         am4core.color("#a92e2e"), am4core.color("#2ca5b2"), am4core.color("#50a71b"), am4core.color("#b3900e"),];
                //     series.slices.template.cornerRadius = 0;
                //     series.slices.template.innerCornerRadius = 0;
                //     series.slices.template.draggable = true;
                //     series.slices.template.inert = true;
                //     series.alignLabels = false;

                //     series.hiddenState.properties.startAngle = 90;
                //     series.hiddenState.properties.endAngle = 90;

                //     patientByIPD.legend = new am4charts.Legend();
                //     patientByIPD.legend.position = "right";
                //     patientByIPD.legend.maxHeight = 150;
                //     patientByIPD.legend.scrollable = true;

                //     let marker = patientByIPD.legend.markers.template.children.getIndex(0);
                //     marker.cornerRadius(12, 12, 12, 12);
                //     marker.strokeWidth = 2;
                //     marker.strokeOpacity = 1;

                // });

                var options = {
                chart: {
                    height: 400,
                    type: 'donut',
                },
                noData: {
                    text: 'No Data Available'
                },
                colors: shuffle(colorForAll),
                labels: response.patientByIPDTitle,
                series: response.patientIPD,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            }

            chartByDepartmentIPD = new ApexCharts(
                document.querySelector("#paitient-by-IPD"),
                options
            );

            chartByDepartmentIPD.render();

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

</script>