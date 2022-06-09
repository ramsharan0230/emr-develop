<script type="text/javascript">
    /*chart by billing mode*/
    @if ($account_permission)
        if (jQuery('#lab-status').length) {
            var options = {
                chart: {
                    type: 'area',
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
                    data:  <?php echo json_encode($labStatus) ?>
                }],
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: <?php echo json_encode($labStatusTitle) ?>,
                }
            }

            revenueStatistics = new ApexCharts(
                document.querySelector("#revenue-statistics"),
                options
            );

            revenueStatistics.render();
        }
    @endif
</script>
