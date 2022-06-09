@extends('frontend.layouts.master')

@section('content')
    <style type="text/css">
        .fa-arrow-circle-right {
            font-size: 25px;
        }
        .modal-body p {
            margin-bottom: 0;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Radio Verification</h4>
                            <input type="hidden" id="js-sampling-current-userid" value="{{ \Auth::guard('admin_frontend')->user()->username }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 ">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="js-xray-form">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">Form:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="from_date" class="form-control form-control-sm nepaliDatePicker" id="from_date" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="to_date" class="form-control form-control-sm nepaliDatePicker" id="to_date" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-lg-4 col-sm-4">Encounter</label>
                                        <div class="col-lg-8 col-sm-8">
                                            <input type="text" name="encounter_id" class="form-control" id="js-sampling-encounterid-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-lg-4 col-sm-4">Category</label>
                                        <div class="col-lg-8 col-sm-8">
                                            <select name="category" class="form-control" id="js-sampling-category-select">
                                                <option value="">--Select--</option>
                                                @foreach ($categories as $category)
                                                <option {{ (request()->get('category') == $category->flclass) ? 'selected="selected"' : '' }} value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-lg-3 col-sm-5">Name</label>
                                        <div class="col-lg-9 col-sm-7">
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <label class="col-lg-4 col-sm-5">Unit</label>
                                        <div class="col-lg-8 col-sm-7">
                                            <input type="text" class="form-control" name="unit">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-lg-3 col-sm-5">Status</label>
                                        <div class="col-lg-9 col-sm-7">
                                            <select name="status" class="form-control" id="js-sampling-status-select">
                                                <option value="">---Select---</option>
                                                <option value="Reported">Pending to verify</option>
                                                <option value="Verified">Verified</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-row">
                                        <div class="col-lg-12">
                                            <button class="btn btn-action float-right btn-primary" id="js-sampling-encounter-show-btn"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;View</button>&nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 ">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="res-table table-sticky-th">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th class="tittle-th">SN</th>
                                    <th class="tittle-th">Encounter ID</th>
                                    <th class="tittle-th" width="250px">Patient Detail</th>
                                    <th class="tittle-th" width="200px">Doctor Details</th>
                                    <th class="tittle-th">Test</th>
                                    <th class="tittle-th">Comment</th>
                                    <th class="tittle-th">History/PACS</th>
                                    <th class="tittle-th">Report</th>
                                </tr>
                                </thead>
                                <tbody id="js-sampling-labtest-tbody">
                                    @if($tests)
                                        @foreach($tests as $test)
                                            <tr
                                                data-fldid="{{ $test->fldid }}"
                                                data-encounterid="{{ $test->fldencounterval }}"
                                                data-patientid="{{ $test->encounter ? $test->encounter->fldpatientval : '' }}"
                                                data-fldcategory="{{ $test->radio ? $test->radio->fldcategory : '' }}"
                                                data-flduseridreport="{{ $test->flduserid_report ? $test->flduserid_report : '' }}"
                                                fldsample="{{ $test->fldstatus }}"
                                                data-fldfullname="{{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldrankfullname : '' }}"
                                            >
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $test->fldencounterval }}</td>
                                                <td>
                                                    {{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldrankfullname : '' }} <br>
                                                    {{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldagestyle . ' years' : '' }}/{{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldptsex : '' }} {{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldptcontact : '' }}<br>
                                                    <i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldptaddvill : '' }}, {{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldptadddist : '' }}
                                                </td>
                                                <td>
                                                    {{ ($test->encounter && $test->encounter->consultant && $test->encounter->consultant->user ) ? $test->encounter->consultant->user->fldfullname : '' }}<br>
                                                    {{ ($test->encounter && $test->encounter->consultant ) ? $test->encounter->consultant->fldconsultname : '' }}
                                                </td>
                                                <td>{{ $test->fldtestid }}</td>
                                                <td>{{ $test->fldcomment ?: $test->fldreason }}</td>
                                                <td>
                                                    <button class="btn btn-primary js-sampling-history-btn">History</button>
                                                    <button class="btn btn-primary js-sampling-pacs-btn">PACS</button>
                                                </td>
                                                <td>{!! $test->fldreportquali !!}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="js-radiotemplate-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <div class="head-content">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                    </div>
                    <h6 class="modal-title">Select Radio comment</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p>Name: <strong id="js-radiotemplate-name-modal">Name</strong></p>
                            <p>EncId: <strong id="js-radiotemplate-encid-modal">EncId</strong></p>
                            <p>Test: <strong id="js-radiotemplate-test-modal">Test</strong></p>
                            <div id="js-radiotemplate-obseravtion-modal"></div>
                            <button class="btn btn-primary" id="js-radiotemplate-save-modal">Verify</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
<script src="{{asset('js/verification_form.js')}}"></script>
@endpush
