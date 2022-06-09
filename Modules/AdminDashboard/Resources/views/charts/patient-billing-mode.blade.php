<div class="chart-dropdown">
    <select name="" class="paitient-by-billing-mode-count-change float-right" onchange="changePatientByBillingMode()">
        <option value="">Select</option>
        <option value="Day">Day</option>
        <option value="Month">Month</option>
        <option value="Year">Year</option>
    </select>
    <div class="clearfix"></div>
    <div class="mb-3 mt-3" id="paitient-by-billing-mode-count"></div>
</div>
<script type="text/javascript" src="{{asset('assets/js/gstatic-loader.js')}}"></script>
<script>
    var patientByBillingMode1 = <?php echo json_encode($patientByBillingMode) ?>;
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawChartByBillingMode);

    function drawChartByBillingMode() {
        var data = google.visualization.arrayToDataTable(patientByBillingMode1);

        var options = {
            title: 'Count of patient by billing mode',
            chartArea: {width: '70%'},
            hAxis: {
                title: 'Patient Count'
            },
            vAxis: {
                title: ''
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('paitient-by-billing-mode-count'));
        chart.draw(data, options);
    }
</script>
