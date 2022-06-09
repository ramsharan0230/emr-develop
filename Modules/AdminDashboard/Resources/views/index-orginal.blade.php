@extends('frontend.layouts.master')

@push('after-styles')
    <style>
        .iq-card {
            /*background-color: rgba(255, 255, 255, 0.1);*/
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="mb-2">Fiscal Year: {{ $fiscal_year->fldname }}</h4>
                </div>
                @if($lab_permission || $radio_permission || $opd_permission || $ipd_permission || $account_permission)
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-primary">
                                    <i class="ri-user-fill"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $totalNewPatient }}/{{ $totalNOldPatient }}</span>
                                    </h4>
                                    <p>Total New/Old Patient</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if($ipd_permission || $emergency_permission)
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-info">
                                    <i class="ri-group-fill"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $totalPatientAdmitted }}</span>
                                    </h4>
                                    <p>Currently Admitted Patients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-success">
                                    <i class="ri-hospital-line"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $totalPatientDischarged }}</span>
                                    </h4>
                                    <p>Total Discharged</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{-- @if($opd_permission)
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-primary">
                                    <i class="ri-user-fill"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $followupCount  }}</span>
                                    </h4>
                                    <h4>Total Follow Up</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif --}}
                @if($radio_permission)
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-success">
                                    <i class="ri-group-fill"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $inpatientCount }} / {{ $outpatientCount }}</span>
                                    </h4>
                                    <p>Total Inpatient/Outpatient</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if($lab_permission)
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-primary">
                                    <i class="ri-microscope-line"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $normalCount }} / {{ $abnormalCount }}</span>
                                    </h4>
                                    <p>Lab Normal/Abnormal Test</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-info">
                                    <i class="ri-microscope-line"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $todayLabSampled }} / {{ $todayLabReported }} / {{ $todayLabVerified }}</span>
                                    </h4>
                                    <p>Lab Sampled/Reported/Verified Today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-info">
                                    <i class="ri-microscope-line"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $todayLabWaiting }}</span>
                                    </h4>
                                    <p>Remaining to Sample Lab Today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if($radio_permission)
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-success">
                                    <i class="ri-stethoscope-fill"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $todayRadioCheckin }} / {{ $todayRadioReported }} / {{ $todayRadioVerified }}</span>
                                    </h4>
                                    <p>Radio CheckIn/Reported/Verified Today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-primary">
                                    <i class="ri-stethoscope-fill"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ $todayRadioWaiting }}</span>
                                    </h4>
                                    <p>Remaining to Sample Radio Today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{--<div class="col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle iq-card-icon bg-warning">
                                    <i class="fas fa-people-arrows"></i>
                                </div>
                                <div class="text-right">
                                    <h4 class="mb-0">
                                        <span class="counter">{{ implode('/',$genderChartTitle)  }}</span>
                                    </h4>
                                    <h4>{{ implode('/',$genderChart) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
            </div>
        </div>
        @if (\App\Utils\Helpers::getPermissionsByModuleName("opd"))
            @include('admindashboard::partials.opd-dashboard-partial')
        @endif
        @if (\App\Utils\Helpers::getPermissionsByModuleName("ipd"))
            @include('admindashboard::partials.ipd-dashboard-partial')
        @endif
        @if (\App\Utils\Helpers::getPermissionsByModuleName("emergency"))
            @include('admindashboard::partials.emergency-dashboard-partial')
        @endif
        @if (\App\Utils\Helpers::getPermissionsByModuleName("billing"))
            @include('admindashboard::partials.billing-dashboard-partial')
        @endif
        @if (\App\Utils\Helpers::getPermissionsByModuleName("lab_status"))
            @include('admindashboard::partials.laboratory-dashboard-partial')
        @endif
        @if (\App\Utils\Helpers::getPermissionsByModuleName("radiology"))
            @include('admindashboard::partials.radiology-dashboard-partial')
        @endif
        {{-- @if (\App\Utils\Helpers::getPermissionsByModuleName("account"))
            @include('admindashboard::partials.account-dashboard-partial')
        @endif --}}
        @if (\App\Utils\Helpers::getPermissionsByModuleName("pharmacy"))
            @include('admindashboard::partials.pharmacy-dashboard-partial')
        @endif

        {{-- <div class="col-md-12">

            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Patient by Department</h4>
                    </div>
                    <select name="" class="paitient-by-department-count-change float-right form-control col-4" onchange="changePatientByDepartmentLine()">
                        <option value="">Select</option>
                        <option value="Day">Day</option>
                        <option value="Month">Month</option>
                        <option value="Year">Year</option>
                    </select>
                </div>
                <div class="iq-card-body">
                    <div class="male-female-pie-chart-container">
                        <div class="mb-3 mt-3" id="paitient-by-department-count"></div>
                    </div>
                </div>
            </div>
        </div> --}}
        {{--<div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Male/Female Chart</h4>
                </div>
            </div>
            <div class="iq-card-body piechart-male-female-add">
                <div class="chart-dropdown">
                    <select name="" class="piechart-male-female-change float-right" onchange="changeMaleFemalePie()">
                        <option value="">Select</option>
                        <option value="Day">Day</option>
                        <option value="Month">Month</option>
                        <option value="Year">Year</option>
                    </select>
                    <div class="clearfix"></div>
                    <div class="mb-3 mt-3" id="piechart-male-female"></div>
                </div>
            </div>
        </div>--}}{{--
    </div>--}}

    </div>
@endsection
@push('after-script')
    <!-- am core JavaScript -->
    <script src="{{ asset('new/js/core.js') }}"></script>
    <!-- am charts JavaScript -->
    <script src="{{ asset('new/js/charts.js') }}"></script>
    {{-- Apex Charts --}}
    <script src="{{ asset('js/apex-chart.min.js') }}"></script>
    <!-- am animated JavaScript -->
    <script src="{{ asset('new/js/animated.js') }}"></script>
    <!-- am kelly JavaScript -->
    <script src="{{ asset('new/js/kelly.js') }}"></script>
    <script type="text/javascript">
        var maleFemale;
        var chartByDepartment;
        var chartByBillingMode;
        var chartByOPD;
        var patientByIPD;
        var colorForAll = ['#FFA500', '#B8860B', '#BDB76B', '#F0E68C', '#9ACD32'
            , '#ADFF2F', '#008000', '#66CDAA', '#8FBC8F', '#008080', '#00CED1', '#7FFFD4', '#4682B4'
            , '#1E90FF', '#00008B', '#4169E1', '#9370DB'
            , '#9932CC', '#EE82EE', '#C71585', '#644e35', '#FFFACD', '#A0522D'
            , '#808000', '#778899', '#0a6258', '#A9A9A9'];

        jQuery(document).ready(function () {
            /*if (jQuery('#male-female-pie-chart').length) {
                var options = {
                    chart: {
                        width: 380,
                        type: 'pie',
                    },
                    noData: {
                        text: 'Loading...'
                    },
                    labels: <?php //echo json_encode($genderChart) ?>,
                    series: <?php //echo json_encode($genderChartTitle) ?>,
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

                maleFemale = new ApexCharts(
                    document.querySelector("#male-female-pie-chart"),
                    options
                );

                maleFemale.render();
            }*/
            /*chart by department*/
            {{--if (jQuery('#paitient-by-department-count').length) {
                var options = {
                    chart: {
                        type: 'bar',
                    },
                    colors: shuffle(colorForAll),
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            distributed: true
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        data:  {{ json_encode($patientByDepartment) }}
                    }],
                    xaxis: {
                        categories: {{ json_encode($patientByDepartmentTitle) }},
                    }
                }

                chartByDepartment = new ApexCharts(
                    document.querySelector("#paitient-by-department-count"),
                    options
                );

                chartByDepartment.render();
            }
            --}}


        });

        /*function changeMaleFemalePie() {
            $.ajax({
                url: '{{ route("admin.dashboard.male.female.chart") }}',
                type: "POST",
                data: {"chartParam": $('.piechart-male-female-change').val(), "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // $('#male-female-pie-chart').empty();
                    maleFemale.destroy();
                    var options = {
                        chart: {
                            width: 380,
                            type: 'pie',
                        },
                        noData: {
                            text: 'Loading...'
                        },
                        labels: response.genderChart,
                        series: response.genderChartTitle,
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

                    maleFemale1 = new ApexCharts(
                        document.querySelector("#male-female-pie-chart"),
                        options
                    );

                    maleFemale1.render();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(errorMessage);
                }
            });
        }*/

        {{--function changePatientByDepartmentLine() {
            $.ajax({
                url: '{{ route("admin.dashboard.patient.department.chart") }}',
                type: "POST",
                data: {"chartParam": $('.paitient-by-department-count-change').val(), "_token": "{{ csrf_token() }}"},
                success: function (response, status, xhr) {
                    chartByDepartment.destroy();
                    var options = {
                        chart: {
                            type: 'bar',
                        },
                        colors: shuffle(colorForAll),
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                distributed: true
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        series: [{
                            data: response.patientByDepartment
                        }],
                        xaxis: {
                            categories: response.patientByDepartmentTitle,
                        }
                    }

                    chartByDepartment = new ApexCharts(
                        document.querySelector("#paitient-by-department-count"),
                        options
                    );

                    chartByDepartment.render();

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }--}}

        function shuffle(array) {
            var currentIndex = array.length, temporaryValue, randomIndex;

            // While there remain elements to shuffle...
            while (0 !== currentIndex) {

                // Pick a remaining element...
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;

                // And swap it with the current element.
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
            }

            return array;
        }

    </script>
    @include('admindashboard::scripts.billing-dashboard-js')
    @include('admindashboard::scripts.opd-dashboard-js')
    @include('admindashboard::scripts.ipd-dashboard-js')
    @include('admindashboard::scripts.emergency-dashboard-js')
    @include('admindashboard::scripts.laboratory-dashboard-js')
    @include('admindashboard::scripts.radiology-dashboard-js')
    @include('admindashboard::scripts.account-dashboard-js')
    @include('admindashboard::scripts.pharmacy-dashboard-js')
@endpush
