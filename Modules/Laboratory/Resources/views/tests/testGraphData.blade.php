@extends('frontend.layouts.master')
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/laboratory-style.css')}}">
    <style>
        .essential-exam-chart {
            width: 95%;
            padding-top: 2rem;
            padding-left: 2rem;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 leftdiv">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Test Observation Chart
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="essential-exam-chart">
                                        <div id="curve_chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('after-script')
    <script src="{{asset('js/laboratory_form.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/gstatic-loader.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajax({
                url: baseUrl + '/admin/laboratory/reporting/getTestGraphData',
                type: "get",
                data: {encounter_id: "{{ request('encounter_id') }}", testid: "{{ request('testid') }}"},
                success: function (response) {
                    document.getElementById('curve_chart').innerHTML = "";
                    if (response.dataCount > 1) {
                        google.charts.load('current', {'packages': ['corechart']});
                        google.charts.setOnLoadCallback(drawChart);
                    }

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable(response.data);
                        var options = {
                            width: "100%",
                            title: response.test_name,
                            curveType: 'function',
                            legend: {position: 'bottom'},
                            hAxis: {slantedText: true}
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                        chart.draw(data, options);

                        $('#js-curve-chart-modal').modal('show');
                    }
                }
            });
        });
    </script>

    <script>
        $('#js-reporting-encounter-input').on('keypress', function (e) {
            if (e.which === 13)
                $('#js-reporting-show-btn').click();
        });
    </script>
@endpush
