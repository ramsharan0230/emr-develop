<div id="curve_chart" style="width: 900px; height: 500px"></div>
<script type="text/javascript" src="{{asset('assets/js/gstatic-loader.js')}}"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    var obj = <?php echo json_encode($chartData); ?>;
    function drawChart() {
        var data = google.visualization.arrayToDataTable(obj);

        var options = {
            title: 'Company Performance',
            curveType: 'function',
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
    }
</script>
