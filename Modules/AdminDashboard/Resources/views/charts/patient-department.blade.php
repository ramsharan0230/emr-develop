<div class="chart-dropdown">
    <select name="" class="paitient-by-department-count-change float-right" onchange="changePatientByDepartmentLine()">
        <option value="">Select</option>
        <option value="Day">Day</option>
        <option value="Month">Month</option>
        <option value="Year">Year</option>
    </select>
    <div class="clearfix"></div>
    <div class="mb-3 mt-3" id="paitient-by-department-count"></div>
</div>
<script type="text/javascript" src="{{asset('assets/js/gstatic-loader.js')}}"></script>
<script>
    var patientByDepartment = <?php echo json_encode($patientByDepartment) ?>;
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawMultSeries);

    function drawMultSeries() {
        var data = google.visualization.arrayToDataTable(patientByDepartment);

        var options = {
            title: 'Count of patient by department',
            chartArea: {width: '70%'},
            hAxis: {
                title: 'Patient Count'
            },
            vAxis: {
                title: ''
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('paitient-by-department-count'));
        chart.draw(data, options);
    }
</script>
