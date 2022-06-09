<script type="text/javascript">
    @if ($opd_permission)
        if (jQuery('#paitient-by-OPD').length) {

            var options = {
                chart: {
                    height: 400,
                    type: 'pie',
                },
                noData: {
                    text: 'Loading...'
                },
                colors: shuffle(colorForAll),
                labels: <?php echo json_encode($patientByOPDTitle) ?>,
                series: <?php echo json_encode($patientByOPD) ?>,
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

            chartByDepartmentOPD = new ApexCharts(
                document.querySelector("#paitient-by-OPD"),
                options
            );

            chartByDepartmentOPD.render();
        }
    @endif

    function changePatientByOPD() {
        chartByDepartmentOPD.destroy();
        $.ajax({
            url: '{{ route("admin.dashboard.patient.opd.chart") }}',
            type: "POST",
            data: {"chartParam": $('.paitient-by-opd-count-change').val(), "billingSet": $('.paitient-by-opd-billing-set-change').val(), "_token": "{{ csrf_token() }}"},
            success: function (response, status, xhr) {
                // console.log(response)
                var options = {
                    chart: {
                        width: 400,
                        type: 'pie',
                    },
                    noData: {
                        text: 'No Data Available'
                    },
                    labels: response.patientByOPDTitle,
                    series: response.patientByOPD,
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

                chartByDepartmentOPD = new ApexCharts(
                    document.querySelector("#paitient-by-OPD"),
                    options
                );

                chartByDepartmentOPD.render();

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
</script>