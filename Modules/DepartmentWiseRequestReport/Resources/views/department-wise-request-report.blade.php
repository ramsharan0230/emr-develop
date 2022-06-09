<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Cogent EMR</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('new/images/favicon.ico') }}"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/bootstrap.min.css') }}"/>
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/theme-responsive.css') }}"/>
    <link rel="stylesheet" href="{{ asset('new/css/responsive.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui-timepicker.css')}}">
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui-timepicker.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}"/>
    <script src="{{asset('js/select2.min.js')}}"></script>
    <style>
        .loader-ajax-start-stop {
            position: absolute;
            left: 45%;
            top: 35%;
        }

        .loader-ajax-start-stop-container {
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            background: black;
            opacity: .5;
            z-index: 1051;
        }

        td {
            padding: 10px; border: 1px solid;
        }
    </style>
</head>
<body>
<div class="loader-ajax-start-stop-container">
    <div class="loader-ajax-start-stop">
        <img src="{{ asset('images/loader-rolling.svg') }}">
    </div>
</div>
<div class="container-fluid">
    <div class="pdf-container">
        <form action="{{ route('departmentwise.request.report') }}" method="GET" id="deptReportForm">
            <div class="form-group form-row align-items-center">
                <div class="col-sm-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="patientType" id="outpatientRadio" value="outpatient" @if($patientType == "outpatient") checked @endif>
                        <label class="form-check-label" for="outpatientRadio">Outpatient
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="patientType" id="inpatientRadio" value="inpatient" @if($patientType == "inpatient") checked @endif>
                        <label class="form-check-label" for="inpatientRadio">Inpatient</label>
                    </div>
                </div>
                <div class="col-sm-2">
                    <select id="departmentLists" name="dept" class="form-control select2">
                        <option value="%">%</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->flddept }}" @if($request_dept == $department->flddept) selected @endif>{{ $department->flddept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select name="billing" class="form-control select2 billing_mode">
                        <option value="">--Select Billing Mode--</option>
                        @foreach ($billing_mode as $mode)
                            <option value="{{ $mode->fldsetname }}" @if($billing == $mode->fldsetname) selected @endif>{{ $mode->fldsetname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="date" value="{{ $request_date }}" name="request_date" id="request_date" class="form-control">
                </div>
                <input type="hidden" name="filter" id="submitType">
                <div class="col-sm-2">
                    <button type="submit" name="submitType" class="btn btn-primary" value="filter" id="filterReport">Filter</button>
                    <button type="submit" name="submitType" class="btn btn-primary" value="pdf" id="pdfReport">Pdf</button>
                    <button type="submit" name="submitType" class="btn btn-primary" value="export" id="exportReport">Export</button>
                </div>
            </div>
        </form>
        <div class="" style="width: 100%;">
            @php
                $malePharmacy = 0;
                $femalePharmacy = 0;
                $maleLaboratory = 0;
                $femaleLaboratory = 0;
                $maleRadiology = 0;
                $femaleRadiology = 0;
                $malePlannedConsult = 0;
                $femalePlannedConsult = 0;
                $maleDoneConsult = 0;
                $femaleDoneConsult = 0;
            @endphp
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20px;">
                <tbody>
                <tr>
                    <td rowspan="2">Department</td>
                    <td rowspan="2">User</td>
                    <td colspan="2">Pharmacy Order</td>
                    <td colspan="2">Laboratory Order</td>
                    <td colspan="2">Radiology Order</td>
                    <td colspan="2">Consult Planned</td>
                    <td colspan="2">Consult Done</td>
                </tr>
                <tr>
                    <td>Male</td>
                    <td>Female</td>
                    <td>Male</td>
                    <td>Female</td>
                    <td>Male</td>
                    <td>Female</td>
                    <td>Male</td>
                    <td>Female</td>
                    <td>Male</td>
                    <td>Female</td>
                </tr>
                @if($filter)
                    @foreach ($dept as $dept)
                        @php
                            $hasUserData = false;
                        @endphp
                        @if(count($dept->users) > 0)
                            @php
                                $isFirstUser = true;
                                $chkDeptNoData = 0;
                                $usersArray = [];
                                foreach ($dept->users as $user){
                                    array_push($usersArray,$user->flduserid);
                                }
                            @endphp
                            @foreach ($dept->users as $key => $user)
                                @php
                                    $hasUserData = false;
                                    if(array_key_exists($dept->flddept, $departmentWisePharmacyData)){
                                        if(array_key_exists($user->flduserid, $departmentWisePharmacyData[$dept->flddept])){
                                            $hasUserData = true;
                                        }
                                    }
                                    if(array_key_exists($dept->flddept, $departmentWiseData)){
                                        if(array_key_exists($user->flduserid, $departmentWiseData[$dept->flddept])){
                                            $hasUserData = true;
                                        }
                                    }
                                    if(array_key_exists($dept->flddept, $departmentWiseConsultData)){
                                        if(array_key_exists($user->flduserid, $departmentWiseConsultData[$dept->flddept])){
                                            $hasUserData = true;
                                        }
                                    }
                                @endphp
                                @if($hasUserData)
                                @php
                                    $chkDeptNoData = $chkDeptNoData + 1;
                                @endphp
                                <tr>
                                    @php
                                        $maxrowspan = 0;
                                        if(array_key_exists($dept->flddept, $departmentWisePharmacyData)){
                                            $rowspan = count($departmentWisePharmacyData[$dept->flddept]);
                                            $arrayDiff = count(array_diff(array_keys($departmentWisePharmacyData[$dept->flddept]), $usersArray));
                                            $maxrowspan += ($rowspan - $arrayDiff);
                                        }
                                        if(array_key_exists($dept->flddept, $departmentWiseData)){
                                            $rowspan = count($departmentWiseData[$dept->flddept]);
                                            $arrayDiff = count(array_diff(array_keys($departmentWiseData[$dept->flddept]), $usersArray));
                                            $maxrowspan += ($rowspan - $arrayDiff);
                                        }
                                        if(array_key_exists($dept->flddept, $departmentWiseConsultData)){
                                            $rowspan = count($departmentWiseConsultData[$dept->flddept]);
                                            $arrayDiff = count(array_diff(array_keys($departmentWiseConsultData[$dept->flddept]), $usersArray));
                                            $maxrowspan += ($rowspan - $arrayDiff);
                                        }
                                        if($maxrowspan < 1){
                                            $maxrowspan = 1;
                                        }
                                    @endphp
                                    @if($isFirstUser)
                                        <td rowspan="{{ $maxrowspan }}">{{ $dept->flddept }}</td>
                                        @php
                                            if($hasUserData){
                                                $isFirstUser = false;
                                            }
                                        @endphp
                                        @if($isFirstUser)
                                            <td rowspan="{{ $maxrowspan }}">{{ $dept->flddept }}</td>
                                            @php
                                                if($hasUserData){
                                                    $isFirstUser = false;
                                                }
                                            @endphp
                                        @endif
                                        @if($hasUserData)
                                            <td>{{ $user->fldfullname }}</td>
                                        @endif
                                        @if(count($departmentWisePharmacyData) > 0)
                                            @if(array_key_exists($dept->flddept, $departmentWisePharmacyData))
                                                @if(array_key_exists($user->flduserid, $departmentWisePharmacyData[$dept->flddept]))
                                                    @if(array_key_exists('Male', $departmentWisePharmacyData[$dept->flddept][$user->flduserid]))
                                                        <td>{{ count($departmentWisePharmacyData[$dept->flddept][$user->flduserid]['Male']) }}</td>
                                                        @php
                                                            $malePharmacy += count($departmentWisePharmacyData[$dept->flddept][$user->flduserid]['Male']);
                                                        @endphp
                                                    @else
                                                        <td>0</td>
                                                    @endif
                                                    @if(array_key_exists('Female', $departmentWisePharmacyData[$dept->flddept][$user->flduserid]))
                                                        <td>{{ count($departmentWisePharmacyData[$dept->flddept][$user->flduserid]['Female']) }}</td>
                                                        @php
                                                            $femalePharmacy += count($departmentWisePharmacyData[$dept->flddept][$user->flduserid]['Female']);
                                                        @endphp
                                                    @else
                                                        <td>0</td>
                                                    @endif
                                                @else
                                                    @if($hasUserData)
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                @endif
                                            @else
                                                <td>0</td>
                                                <td>0</td>
                                            @endif
                                        @else
                                            <td>0</td>
                                            <td>0</td>
                                        @endif
                                        @if(count($departmentWiseData) > 0)
                                            @if(array_key_exists($dept->flddept, $departmentWiseData))
                                                @if(array_key_exists($user->flduserid, $departmentWiseData[$dept->flddept]))
                                                    @if(array_key_exists('Diagnostic Tests', $departmentWiseData[$dept->flddept][$user->flduserid]))
                                                        @if(count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']) > 0)
                                                            @if(array_key_exists('Male', $departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']))
                                                                <td>{{ count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']['Male']) }}</td>
                                                                @php
                                                                    $maleLaboratory += count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']['Male']);
                                                                @endphp
                                                            @else
                                                                <td>0</td>
                                                            @endif
                                                            @if(array_key_exists('Female', $departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']))
                                                                <td>{{ count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']['Female']) }}</td>
                                                                @php
                                                                    $femaleLaboratory += count($departmentWiseData[$dept->flddept][$user->flduserid]['Diagnostic Tests']['Female']);
                                                                @endphp
                                                            @else
                                                                <td>0</td>
                                                            @endif
                                                        @else
                                                            <td>0</td>
                                                            <td>0</td>
                                                        @endif
                                                    @else
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                    @if(array_key_exists('Radio Diagnostics', $departmentWiseData[$dept->flddept][$user->flduserid]))
                                                        @if(count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']) > 0)
                                                            @if(array_key_exists('Male', $departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']))
                                                                <td>{{ count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']['Male']) }}</td>
                                                                @php
                                                                    $maleRadiology += count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']['Male']);
                                                                @endphp
                                                            @else
                                                                <td>0</td>
                                                            @endif
                                                            @if(array_key_exists('Female', $departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']))
                                                                <td>{{ count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']['Female']) }}</td>
                                                                @php
                                                                    $femaleRadiology += count($departmentWiseData[$dept->flddept][$user->flduserid]['Radio Diagnostics']['Female']);
                                                                @endphp
                                                            @else
                                                                <td>0</td>
                                                            @endif
                                                        @else
                                                            <td>0</td>
                                                            <td>0</td>
                                                        @endif
                                                    @else
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                @else
                                                    @if($hasUserData)
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                @endif
                                            @else
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                            @endif
                                        @else
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                        @endif

                                        @if(count($departmentWiseConsultData) > 0)
                                            @if(array_key_exists($dept->flddept, $departmentWiseConsultData))
                                                @if(array_key_exists($user->flduserid, $departmentWiseConsultData[$dept->flddept]))
                                                    @if(array_key_exists('Planned', $departmentWiseConsultData[$dept->flddept][$user->flduserid]))
                                                        @if(count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']) > 0)
                                                            @if(array_key_exists('Male', $departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']))
                                                                <td>{{ count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']['Male']) }}</td>
                                                                @php
                                                                    $malePlannedConsult += count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']['Male']);
                                                                @endphp
                                                            @else
                                                                <td>0</td>
                                                            @endif
                                                            @if(array_key_exists('Female', $departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']))
                                                                <td>{{ count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']['Female']) }}</td>
                                                                @php
                                                                    $femalePlannedConsult += count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Planned']['Female']);
                                                                @endphp
                                                            @else
                                                                <td>0</td>
                                                            @endif
                                                        @else
                                                            <td>0</td>
                                                            <td>0</td>
                                                        @endif
                                                    @else
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                    @if(array_key_exists('Done', $departmentWiseConsultData[$dept->flddept][$user->flduserid]))
                                                        @if(count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']) > 0)
                                                            @if(array_key_exists('Male', $departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']))
                                                                <td>{{ count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']['Male']) }}</td>
                                                                @php
                                                                    $maleDoneConsult += count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']['Male']);
                                                                @endphp
                                                            @else
                                                                <td>0</td>
                                                            @endif
                                                            @if(array_key_exists('Female', $departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']))
                                                                <td>{{ count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']['Female']) }}</td>
                                                                @php
                                                                    $femaleDoneConsult += count($departmentWiseConsultData[$dept->flddept][$user->flduserid]['Done']['Female']);
                                                                @endphp
                                                            @else
                                                                <td>0</td>
                                                            @endif
                                                        @else
                                                            <td>0</td>
                                                            <td>0</td>
                                                        @endif
                                                    @else
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                @else
                                                    @if($hasUserData)
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                @endif
                                            @else
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                            @endif
                                        @else
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                        @endif
                                    @endif
                                </tr>
                                @endif
                            @endforeach
                            @if($chkDeptNoData < 1)
                                <tr>
                                    <td>{{ $dept->flddept }}</td>
                                    <td>N/A</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td>{{ $dept->flddept }}</td>
                                <td>N/A</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                <tr>
                    <td rowspan="2" colspan="2">Total</td>
                    <td>{{ $malePharmacy }}</td>
                    <td>{{ $femalePharmacy }}</td>
                    <td>{{ $maleLaboratory }}</td>
                    <td>{{ $femaleLaboratory }}</td>
                    <td>{{ $maleRadiology }}</td>
                    <td>{{ $femaleRadiology }}</td>
                    <td>{{ $malePlannedConsult }}</td>
                    <td>{{ $femalePlannedConsult }}</td>
                    <td>{{ $maleDoneConsult }}</td>
                    <td>{{ $femaleDoneConsult }}</td>
                </tr>
                <tr>
                    <td colspan="2">{{ $malePharmacy + $femalePharmacy }}</td>
                    <td colspan="2">{{ $maleLaboratory + $femaleLaboratory }}</td>
                    <td colspan="2">{{ $maleRadiology + $femaleRadiology }}</td>
                    <td colspan="2">{{ $malePlannedConsult + $femalePlannedConsult }}</td>
                    <td colspan="2">{{ $maleDoneConsult + $femaleDoneConsult }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    /*ajax loader*/
    var $loadingContainer = $('.loader-ajax-start-stop-container').hide();

    $('#loader-ajax-start-stop').show();
    $loadingContainer.show();
    $(document).ready(function () {
        $('#loader-ajax-start-stop').hide();
        $loadingContainer.hide();
    })

    $(document)
        .ajaxStart(function () {
            $('#loader-ajax-start-stop').show();
            $loadingContainer.show();
        })
        .ajaxStop(function () {
            $('#loader-ajax-start-stop').hide();
            $loadingContainer.hide();
        });

    $(document).on('change','input[type=radio][name=patientType]',function(){
        $.ajax({
            url: '{{ route("department-list.request") }}',
            type: "GET",
            data: {
                patientType: $(this).val()
            },
            success: function (response) {
                if(response.success.status){
                    $('#departmentLists').empty().append(response.success.options);
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });

    $("#filterReport").click(function(e) {
        e.preventDefault();
        $('#deptReportForm').attr("action","{{ route('departmentwise.request.report') }}");
        $('#submitType').val('filter');
        $('#deptReportForm').submit();
    });

    $("#pdfReport").click(function(e) {
        e.preventDefault();
        $('#deptReportForm').attr("action","{{ route('departmentwise.request.report') }}");
        $('#submitType').val('pdf');
        $('#deptReportForm').submit();
    });

    $("#exportReport").click(function(e) {
        e.preventDefault();
        $('#deptReportForm').attr("action","{{ route('report.dept-wise-request.export') }}");
        $('#submitType').val('export');
        $('#deptReportForm').submit();
    });
</script>
</body>

</html>
