<div class="male-female-pie-chart-container">
    <div id="male-female-pie-chart"></div>
</div>
<script src="{{ asset('new/js/apexcharts.js') }}"></script>
<script>
    var chartData = <?php echo json_encode($genderChart) ?>;
    $(document).ready(function () {
        if (jQuery('#male-female-pie-chart').length) {
            var options = {
                chart: {
                    width: 380,
                    type: 'pie',
                },
                labels: ['Male', 'Female'],
                series: [chartData.Male, chartData.Female],
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

            var chart = new ApexCharts(
                document.querySelector("#male-female-pie-chart"),
                options
            );

            chart.render();
        }
    })
</script>
