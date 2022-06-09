@extends('frontend.layouts.master')

@section('content')

    @php
        $report_segment = Request::segment(3)
    @endphp
    <div class="container-fluid">
        <form method="POST" id="js-printing-hform">
            <div class="row">

                @csrf
                <input type="hidden" name="encounter_id" id="js-printing-hform-encounter">
                <input type="hidden" name="sample_id" id="js-printing-hform-sample">
                <input type="hidden" name="category_id" id="js-printing-hform-category">
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">
                                    Test {{ ucwords($report_segment) }}
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group er-input">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input {{ (request('sample_id') == NULL || request('sample_id') == '') ? 'checked="checked' : '' }} type="radio" name="type" checked="checked" id="encounter" value="encounter" class="custom-control-input"/>
                                            <label class="custom-control-label" for="encounter"> Encounter </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input {{ request('sample_id') ? 'checked="checked' : '' }} type="radio" name="type" id="sample" value="sample" class="custom-control-input"/>
                                            <label class="custom-control-label" for="sample"> Sample </label>
                                        </div>
                                        <div class="custom-control padding-none">
                                            <input type="text" id="js-printing-encounter-input" name="encounter_sample" value="{{ request('sample_id') ?: request('encounter_id') }}" class="form-control col-11"/>
                                        </div>
                                    </div>
                                    <div class="form-group er-input">
                                        <label class="col-3">Full Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="js-printing-name-input" readonly value='{{ Options::get('system_patient_rank')  == 1 && (isset($encounter_data)) && (isset($encounter_data->fldrank) ) ?$encounter_data->fldrank:''}} {{ isset($encounter_data) ? "{$encounter_data->patientInfo->fldptnamefir} {$encounter_data->patientInfo->fldmidname} {$encounter_data->patientInfo->fldptnamelast}" : "" }}' class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group er-input">
                                        <label class="col-3">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="js-printing-address-input" readonly value='{{ isset($encounter_data) ? $encounter_data->patientInfo->fldptadddist : "" }}' class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group er-input">
                                        <label class="col-3 padding-none">Section</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="js-printing-category-select">
                                                <option value="">%</option>
                                                @foreach ($categories as $category)
                                                    <option {{ (request('category_id') == $category->flclass) ? 'selected="selected"' : '' }} value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group er-input">
                                        <label class="col-3 padding-none">Age/Sex</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="js-printing-agesex-input" readonly value='{{ isset($encounter_data) ? $encounter_data->patientInfo->fldptsex : "" }}' class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group er-input">
                                        <label class="col-3 padding-none">Location</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="js-printing-location-input" readonly value='{{ isset($encounter_data) ? $encounter_data->fldcurrlocat : "" }}' class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group-lab">
                                        <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline col-5">
                                            <input type="radio" name="t1" id="text" value="text" checked="checked" class="custom-control-input bg-primary"/>
                                            <label class="custom-control-label" for="text"> Test </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline col-5">
                                            <input type="radio" name="t1" id="image" value="image" class="custom-control-input bg-primary"/>
                                            <label class="custom-control-label" for="image"> Image </label>
                                        </div>
                                    </div>
                                    <div class="form-group-lab">
                                        <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline col-5">
                                            <input type="radio" name="t2" value="reported" id="reported" {{ $is_verified ? '' : 'checked="checked"' }} class="custom-control-input bg-primary"/>
                                            <label class="custom-control-label" for="reported"> Reported </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline col-5">
                                            <input type="radio" name="t2" value="verified" id="verified" {{ $is_verified ? 'checked="checked"' : '' }} class="custom-control-input bg-primary"/>
                                            <label class="custom-control-label" for="verified"> Verfied </label>
                                        </div>
                                    </div>
                                    <div class="form-group-lab">
                                        <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline col-5">
                                            <input type="radio" name="t3" value="si_unit" id="si_unit" checked="checked" class="custom-control-input bg-primary"/>
                                            <label class="custom-control-label" for="si_unit"> SI unit</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline col-5">
                                            <input type="radio" name="t3" value="metric" id="metric" class="custom-control-input bg-primary"/>
                                            <label class="custom-control-label" for="metric"> Metric</label>
                                        </div>
                                    </div>
                                    <div class="form-group-lab">
                                        <button type="button" id="js-printing-show-btn" class="btn btn-primary"><i class="fas fa-play"></i>&nbsp;Show</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#js-printing-patient-search-modal"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="lab-table table-responsive mt-2">
                                <table class="table table-hovered table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th><input type="checkbox" id="js-printing-select-all-checkbox"/></th>
                                        <th>Test Name</th>
                                        <th>Specimen</th>
                                        <th>Sample</th>
                                        <th>Sample Date</th>
                                        <th>&nbsp;</th>
                                        <th>Observation</th>
                                        <th>Reference</th>
                                        <th>ReportDate</th>
                                        <th>&nbsp;</th>
                                        <th>Qua</th>
                                        @if($report_segment === 'verify')
                                            <th class="tittle-th">Action</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody id="js-printing-samples-tbody">
                                    @if(isset($samples))
                                        @foreach($samples as $key => $sample)
                                            @php $selects[] = $sample->fldtestid @endphp
                                            <tr data-subtest="{{ json_encode($sample->subTest) }}">
                                                <td>{{ $key+1 }}</td>
                                                <td><input type="checkbox" name="test[]" value="{{ $sample->fldtestid }}" class="js-printing-labtest-checkbox" checked></td>
                                                <td>{{ $sample->fldtestid }}</td>
                                                <td>{{ $sample->fldsampletype }}</td>
                                                <td>{{ $sample->fldsampleid }}</td>
                                                <td>{{ $sample->fldtime_sample }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm {{ $sample->fldabnormal=='0' ? 'btn-success' : 'btn-danger' }}"></button>
                                                </td>
                                                <td>
                                                    @if($sample->fldreportquali !== NULL)
                                                        {{ $sample->fldreportquali }}
                                                        @if($sample->testLimit->isNotEmpty())
                                                            @foreach($sample->testLimit as $testLimit)
                                                                {{ $testLimit->fldsiunit }}
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($sample->testLimit->isNotEmpty())
                                                        @foreach($sample->testLimit as $testLimit)
                                                            {{ $testLimit->fldsilow }} - {{ $testLimit->fldsihigh }} {{ $testLimit->fldsiunit }}
                                                        @endforeach
                                                    @endif                                                    </td>
                                                <td>{{ $sample->fldtime_report }}</td>
                                                <td><input type="checkbox" {{ $sample->fldstatus == 'Verified' ? 'checked="checked"' : '' }}></td>
                                                <td>0</td>
                                                @if($report_segment === 'verify')
                                                    <td>
                                                        @if($sample->fldstatus == 'Verified')
                                                            &nbsp;
                                                        @else
                                                            <button type="button" class="btn btn-secondary js-printing-verify-btn" data-fldid="{{ $sample->fldid }}">Verify</button>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6" style="float: left;">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="table-responsive major-table">
                                <table class="table table-hovered table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">Subtest</th>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">Observation</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-printing-samples-subtest-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6" style="float: left;">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                    <input type="checkbox" class="custom-control-input bg-primary" id="input-check-new" {{ $has_saved_report ? 'checked="checked"' : '' }}>
                                    <label class="custom-control-label" for="input-check-new"> New</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                    <input type="checkbox" class="custom-control-input bg-primary" id="input-check-printed" {{ $has_saved_report ? 'checked="checked"' : '' }}>
                                    <label class="custom-control-label" for="input-check-printed"> Printed</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                    <input type="checkbox" class="custom-control-input bg-primary" id="input-check-mark-printed" {{ $has_saved_report ? 'checked="checked"' : '' }}>
                                    <label class="custom-control-label" for="input-check-mark-printed"> Mark Printed</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                    <input type="checkbox" class="custom-control-input bg-primary" id="email_report" name="email_report">
                                    <label class="custom-control-label" for="email_report"> Email Report</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <select name="" class="form-control">
                                    <option>-- Select -</option>
                                    @foreach($selects as $select)
                                        <option>{{ $select }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea name="" id="" cols="" rows="" class="form-control" placeholder="Comment"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="diagnosis-btn">
                                    <button class="btn rounded-pill btn-info" type="button" data-toggle="modal" data-target="#js-printing-save-report-modal">
                                        <i class="fas fa-plus"></i>&nbsp;Save
                                    </button>
                                    <button class="btn rounded-pill btn-warning" type="button">
                                        <i class="fas fa-file-download"></i>&nbsp;SMS
                                    </button>
                                    <button class="btn rounded-pill btn-primary" id="genereate-report" type="button">
                                        <i class="fas fa-code"></i>&nbsp; Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <div class="modal fade body-modal" id="js-printing-patient-search-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Search Patient</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div>
                        <form id="js-printing-search-patient-form" class="row">
                            <div class="col-md-6">
                                <div class="form-row form-group">
                                    <div class="col-md-4">
                                        <label>Name</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptnamefir" id="js-printing-modal-name-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>District</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptadddist" id="js-printing-modal-district-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>Contact</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptcontact" id="js-printing-modal-contact-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>NHSI No.</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptcode" id="js-printing-modal-nhsi-input">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>SurName</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptnamelast" id="js-printing-modal-surname-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>Address</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="fldptaddvill" id="js-printing-modal-address-input">
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label>Gender</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="fldptsex" id="js-printing-modal-gender-select" class="select-3 form-input">
                                            <option value="">-- Select --</option>
                                            <option value="">Male</option>
                                            <option value="">Female</option>
                                            <option value="">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="justify-content: center;width: 100%;margin-top: 5px;">
                                <button type="button" id="js-printing-search-patient-btn-modal" class="btn btn-default btn-sm"><i class="fas fa-search"></i>&nbsp;&nbsp;Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive table-scroll-test" style="height: 300px; min-height: 300px;">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th class="tittle-th">&nbsp;</th>
                                        <th class="tittle-th">PatientNo</th>
                                        <th class="tittle-th">Name</th>
                                        <th class="tittle-th">SurName</th>
                                        <th class="tittle-th">Gender</th>
                                        <th class="tittle-th">Address</th>
                                        <th class="tittle-th">District</th>
                                        <th class="tittle-th">Contact</th>
                                        <th class="tittle-th">CurrAge</th>
                                        <th class="tittle-th">PatientCode</th>
                                    </tr>
                                    </thead>
                                    <tbody id="js-printing-modal-patient-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="js-printing-save-report-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;">Save Report</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label>Title</label>
                            <input type="text" id="js-printing-title-modal-input" class="form-control" value="%">
                        </div>
                        <div class="col-md-4">
                            <button style="width: 100%;margin-bottom: 5px;" class="btn" id="js-printing-add-btn-modal">Ok</button>
                            <button style="width: 100%;" type="button" class="btn onclose" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script src="{{asset('js/laboratory_form.js')}}"></script>
    <script>
        $('#genereate-report').click(function (e) {
            e.preventDefault();
            $.ajax({
                url: baseUrl + '/admin/laboratory/printing/printReport',
                type: "POST",
                data: $('#js-printing-hform').serialize(),
                success: function (response, status, xhr) {
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        });
    </script>
@endpush
