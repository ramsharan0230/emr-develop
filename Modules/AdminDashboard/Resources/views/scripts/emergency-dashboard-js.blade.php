<script type="text/javascript">
    @if ($emergency_permission)
        if (jQuery('#paitient-by-Emergency').length) {

            var options = {
                chart: {
                    height: 350,
                    type: 'pie',
                },
                noData: {
                    text: 'No Data Available'
                },
                colors: shuffle(colorForAll),
                labels: <?php echo json_encode($patientByEmergencyTitle) ?>,
                series: <?php echo json_encode($patientByEmergency) ?>,
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

            chartByDepartmentEmergency = new ApexCharts(
                document.querySelector("#paitient-by-Emergency"),
                options
            );

            chartByDepartmentEmergency.render();
        }
    @endif

    function changePatientByEmergency() {
        chartByDepartmentEmergency.destroy();
        $.ajax({
            url: '{{ route("admin.dashboard.patient.emergency.chart") }}',
            type: "POST",
            data: {"chartParam": $('.paitient-by-emergency-count-change').val(), "_token": "{{ csrf_token() }}"},
            success: function (response, status, xhr) {
                // console.log(response)
                var options = {
                    chart: {
                        width: 380,
                        type: 'pie',
                    },
                    noData: {
                        text: 'No Data Available'
                    },
                    labels: response.patientByEmergencyTitle,
                    series: response.patientByEmergency,
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

                chartByDepartmentEmergency = new ApexCharts(
                    document.querySelector("#paitient-by-Emergency"),
                    options
                );

                chartByDepartmentEmergency.render();

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
</script>