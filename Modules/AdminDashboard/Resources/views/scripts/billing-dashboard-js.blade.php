<script type="text/javascript">
    /*chart by billing mode*/
    @if (\App\Utils\Helpers::getPermissionsByModuleName("billing"))
        jQuery("#paitient-by-billing-mode-count").length && am4core.ready(function () {
            am4core.ready(function () {

                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end


                var chart = am4core.create("paitient-by-billing-mode-count", am4charts.RadarChart);

                chart.data = <?php echo json_encode($patientByBillingMode) ?>;

                chart.innerRadius = am4core.percent(40)

                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.dataFields.category = "Billing";
                categoryAxis.renderer.minGridDistance = 60;
                categoryAxis.renderer.inversed = true;
                categoryAxis.renderer.labels.template.location = 0.5;
                categoryAxis.renderer.grid.template.strokeOpacity = 0.08;

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.min = 0;
                valueAxis.extraMax = 0.1;
                valueAxis.renderer.grid.template.strokeOpacity = 0.08;

                chart.seriesContainer.zIndex = -10;


                var series = chart.series.push(new am4charts.RadarColumnSeries());
                series.dataFields.categoryX = "Billing";
                series.dataFields.valueY = "Count";
                series.tooltipText = "{valueY.value}"
                series.columns.template.strokeOpacity = 0;
                series.columns.template.radarColumn.cornerRadius = 5;
                series.columns.template.radarColumn.innerCornerRadius = 0;
                chart.colors.list = [am4core.color("#FFA500"),
                    am4core.color("#B8860B"),
                    am4core.color("#BDB76B"),
                    am4core.color("#F0E68C"),
                    am4core.color("#9ACD32"),
                    am4core.color("#ADFF2F"),
                    am4core.color("#008000"),
                    am4core.color("#4169E1"),
                    am4core.color("#9370DB"),
                    am4core.color("#9932CC"),
                    am4core.color("#778899"),
                    am4core.color("#0a6258")
                ];
                chart.zoomOutButton.disabled = true;

                // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
                series.columns.template.adapter.add("fill", (fill, target) => {
                    return chart.colors.getIndex(target.dataItem.index);
                });

                setInterval(() => {
                    am4core.array.each(chart.data, (item) => {
                        item.visits *= Math.random() * 0.5 + 0.5;
                        item.visits += 10;
                    })
                    chart.invalidateRawData();
                }, 2000)

                categoryAxis.sortBySeries = series;

                chart.cursor = new am4charts.RadarCursor();
                chart.cursor.behavior = "none";
                chart.cursor.lineX.disabled = true;
                chart.cursor.lineY.disabled = true;

            });
        });

        function changePatientByBillingMode() {
            // chartByBillingMode.destroy();
            $.ajax({
                url: '{{ route("admin.dashboard.patient.billing.mode.chart") }}',
                type: "POST",
                data: {"chartParam": $('.paitient-by-billing-mode-count-change').val(), "_token": "{{ csrf_token() }}"},
                success: function (response, status, xhr) {
                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end


                    var chart = am4core.create("paitient-by-billing-mode-count", am4charts.RadarChart);

                    chart.data = <?php echo json_encode($patientByBillingMode) ?>;

                    chart.innerRadius = am4core.percent(40)

                    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.renderer.grid.template.location = 0;
                    categoryAxis.dataFields.category = "Billing";
                    categoryAxis.renderer.minGridDistance = 60;
                    categoryAxis.renderer.inversed = true;
                    categoryAxis.renderer.labels.template.location = 0.5;
                    categoryAxis.renderer.grid.template.strokeOpacity = 0.08;

                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.min = 0;
                    valueAxis.extraMax = 0.1;
                    valueAxis.renderer.grid.template.strokeOpacity = 0.08;

                    chart.seriesContainer.zIndex = -10;


                    var series = chart.series.push(new am4charts.RadarColumnSeries());
                    series.dataFields.categoryX = "Billing";
                    series.dataFields.valueY = "Count";
                    series.tooltipText = "{valueY.value}"
                    series.columns.template.strokeOpacity = 0;
                    series.columns.template.radarColumn.cornerRadius = 5;
                    series.columns.template.radarColumn.innerCornerRadius = 0;
                    chart.colors.list = [am4core.color("#279fac"),
                        am4core.color("#ffb57e"),
                        am4core.color("#279fac"),
                        am4core.color("#ffb57e"),
                        am4core.color("#279fac"),
                        am4core.color("#ffb57e"),
                        am4core.color("#279fac"),
                        am4core.color("#ffb57e"),
                        am4core.color("#279fac"),
                        am4core.color("#ffb57e"),
                        am4core.color("#279fac"),
                        am4core.color("#ffb57e")
                    ];

                    chart.zoomOutButton.disabled = true;

                    // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
                    series.columns.template.adapter.add("fill", (fill, target) => {
                        return chart.colors.getIndex(target.dataItem.index);
                    });

                    setInterval(() => {
                        am4core.array.each(chart.data, (item) => {
                            item.visits *= Math.random() * 0.5 + 0.5;
                            item.visits += 10;
                        })
                        chart.invalidateRawData();
                    }, 2000)

                    categoryAxis.sortBySeries = series;

                    chart.cursor = new am4charts.RadarCursor();
                    chart.cursor.behavior = "none";
                    chart.cursor.lineX.disabled = true;
                    chart.cursor.lineY.disabled = true;

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    @endif
</script>
