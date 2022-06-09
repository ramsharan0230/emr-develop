@extends('frontend.layouts.master')

@push('after-styles')
    <style>
        .tr-orange td {
            color: #905d00;
        }
        .tr-yellow td {
            color: #a5a500;
        }
        .tr-blue td {
            color: #7777ff;
        }
        .tr-red td {
            color: #ff4e4e;
        }
        .tr-green td {
            color: #427f42;
        }
    </style>
@endpush

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Diagnostic Report/Laboratory Discharge Report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <form id="laboratoryForm">
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-4 col-lg-3">Status:</label>
                                    <div class="col-sm-8 col-lg-9">
                                        <select name="status" class="form-control form-control-sm" id="lab_status">
                                            <option value="%">%</option>
                                            <option value="Waiting">Waiting</option>
                                            <option value="Sampled">Sampled</option>
                                            <option value="Reported">Reported</option>
                                            <option value="Verified">Verified</option>
                                            <option value="Not Done">Not Done</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-4 col-lg-3">From:</label>
                                    <div class="col-sm-8 col-lg-9">
                                        <input type="text" name="from_date" class="form-control" id="lab_from_date" value="{{isset($date) ? $date : ''}}" autocomplete="off" />
                                    </div>

                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-4 col-lg-3">To:</label>
                                    <div class="col-sm-8 col-lg-9">
                                        <input type="text" class="form-control" name="to_date" id="lab_to_date" value="{{isset($date) ? $date : ''}}" />
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-4 col-lg-3">Gender:</label>
                                    <div class="col-sm-8 col-lg-9">
                                        <select name="gender" class="form-control form-control-sm" id="lab_gender">
                                            <option value="">%</option>
                                            <option value="Female">Female</option>
                                            <option value="Male">Male</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5 p-0">Section:</label>
                                    <div class="col-sm-7">
                                        <select name="section" id="lab_section" class="form-control form-control-sm">
                                            <option value="">%</option>
                                            @if(isset($sectionlist) and count($sectionlist) > 0)
                                            @foreach($sectionlist as $d)
                                            <option value="{{$d->flclass}}">{{$d->flclass}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5 p-0">Patient name:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="patient_name" id="patient_name">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5 p-0">Patient no:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="patient_no" id="patient_no">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5 p-0">Age (yr):</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="from_age" id="lab_age_from" class="form-control form-control-sm" placeholder="from" />
                                    </div>
                                    &nbsp;
                                    <div class="col-sm-2 p-0">
                                        <input type="text" name="to_age" id="lab_age_to" class="form-control form-control-sm" placeholder="to" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5 p-0">Encounter no:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="encounter_no" id="encounter_no">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5 p-0">Sample no:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="sample_no" id="sample_no">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5 p-0">Bed no:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="bed_no" id="bed_no">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5 p-0">Department</label>
                                    <div class="col-sm-7">
                                        <select name="department" id="department" class="form-control form-control-sm">
                                            <option value="">%</option>
                                            @if(isset($departments) and count($departments) > 0)
                                            @foreach($departments as $t)
                                            <option value="{{$t->flddept}}">{{$t->flddept}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-5 p-0">Bill no:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="bill_no" id="bill_no">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <button class="btn btn-primary rounded-pill" type="button" onclick="showLaboratoryResult()"><i class="fa fa-sync"></i>&nbsp;Refresh</button>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <button class="btn btn-warning rounded-pill" type="button" onclick="exportLaboratoryReport()"><i class="fas fa-external-link-square-alt"></i>&nbsp;&nbsp;Export</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Gridview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Chartview</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent-2">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="form-group form-row float-right">
                                <div class="ml-2"><button class="btn" style="background-color: #427f42;">&nbsp;</button>&nbsp;Waiting</div>
                                <div class="ml-2"><button class="btn" style="background-color: #ff4e4e;">&nbsp;</button>&nbsp;Sampled</div>
                                <div class="ml-2"><button class="btn" style="background-color: #a5a500;">&nbsp;</button>&nbsp;Reported</div>
                                <div class="ml-2"><button class="btn" style="background-color: #7777ff;">&nbsp;</button>&nbsp;Not Done</div>
                                <div class="ml-2"><button class="btn" style="background-color: #905d00;">&nbsp;</button>&nbsp;Verified</div>
                            </div>
                            <div class="table-responsive table-container res-table reporting-table table-sticky-th border">
                                <table class="table table-striped table-hover table-bordered ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="tittle-th">S.N</th>
                                            <th class="tittle-th" width="300">Patient Detal</th>
                                            <th class="tittle-th">Department/Bed No</th>
                                            <th class="tittle-th">Test Name</th>
                                            <th class="tittle-th">SampleID</th>
                                            <th class="tittle-th">BillNo</th>
                                            <th class="tittle-th">BillDate</th>
                                            <th class="tittle-th">Is Printed</th>
                                            <th class="tittle-th">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="diagnostic_laboratory_data">
                                        {!! $allLabData !!}
                                    </tbody>
                                </table>
                                <div id="bottom_anchor"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('after-script')
<script type="text/javascript">
    $('#lab_from_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
    });

    $('#lab_to_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
    });

    $('#lab_section').on('change', function () {
        var section = $(this).val();
        if (section != '') {
            $.ajax({
                url: '{{ route('list.test.form.diagnostic.consultant') }}',
                type: "POST",
                data: { section: section, "_token": "{{ csrf_token() }}" },
                success: function (response) {
                    $('#lab_test').empty().html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    });

    $('#lab_test').on('change', function () {
        var test = $(this).val();
        if (test != '') {
            $.ajax({
                url: '{{ route('list.subtests.form.diagnostic.consultant') }}',
                type: "POST",
                data: { test: test, "_token": "{{ csrf_token() }}" },
                success: function (response) {
    
                    $('#lab_sub_test').empty().html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    });
    
    function showLaboratoryResult() {
        $.ajax({
            url: "{{ route('consultant.diagnostic.searchLaboratoryDischarge') }}",
            type: "POST",
            data: $('#laboratoryForm').serialize(),
            dataType: "json",
            success: function (response) {
                $('#diagnostic_laboratory_data').html(response.html);
            }
        });
    }
    
    function exportLaboratoryReport() {
        $('form').submit(false);
        data = $('#laboratoryForm').serialize();
        var urlReport = baseUrl + "/consultation/export-laboratory-discharge-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
        window.open(urlReport, '_blank');
    }
    
    $('#lab_from_date_bs').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        npdYearCount: 100,
        onChange: function () {
            var datebs = $('#lab_from_date_bs').val();
            $.ajax({
                type: 'post',
                url: '{{ route("patient.request.menu.nepalitoenglish") }}',
                data: { date: datebs, },
                success: function (response) {
                    $('#lab_from_date').val(response);
                }
            });
        }
    });
    
    $('#lab_to_date_bs').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        npdYearCount: 100,
        onChange: function () {
            var datebs = $('#lab_to_date_bs').val();
            $.ajax({
                type: 'post',
                url: '{{ route("patient.request.menu.nepalitoenglish") }}',
                data: { date: datebs, },
                success: function (response) {
                    $('#lab_to_date').val(response);
                }
            });
        }
    });
</script>
@endpush
