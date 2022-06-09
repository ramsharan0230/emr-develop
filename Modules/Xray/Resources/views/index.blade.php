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
                            <h4 class="card-title">Radio Reporting</h4>
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
                                                <option value="Waiting">Waiting</option>
                                                <option value="Appointment">Appointment</option>
                                                <option value="CheckIn">CheckIn</option>
                                                <option value="Reported">Reported</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="showall">
                                            <label class="custom-control-label">Show All</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="form-group form-row">
                                        <div class="col-lg-12">
                                            <button class="btn btn-primary btn-action float-right" id="js-sampling-encounter-show-btn"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;View</button>&nbsp;
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
                        <div class="form-group form-row float-right">
                            <div class="ml-2"><button class="btn" style="background-color: green;">&nbsp;</button>&nbsp;Waiting</div>
                            <div class="ml-2"><button class="btn" style="background-color: red;">&nbsp;</button>&nbsp;Appointment</div>
                            <div class="ml-2"><button class="btn" style="background-color: blue;">&nbsp;</button>&nbsp;CheckIn</div>
                            <div class="ml-2"><button class="btn" style="background-color: yellow;">&nbsp;</button>&nbsp;Reported</div>
                            <div class="ml-2"><button class="btn" style="background-color: orange;">&nbsp;</button>&nbsp;Verified</div>
                        </div>
                        <div class="res-table table-sticky-th">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th class="tittle-th">SN</th>
                                    <th class="tittle-th">&nbsp;</th>
                                    <th class="tittle-th">Encounter ID</th>
                                    <th class="tittle-th" width="250px">Patient Detail</th>
                                    <!-- <th class="tittle-th">Address</th> -->
                                    <!-- <th class="tittle-th">Mobile</th> -->
                                    <!-- <th class="tittle-th">Age/Sex</th> -->
                                    <th class="tittle-th" width="200px">Doctor Details</th>
                                    <th class="tittle-th">Payable/Reporting</th>
                                    <th class="tittle-th">Test</th>
                                    <th class="tittle-th">Comment</th>
                                    <th class="tittle-th">History/PACS</th>
                                    <th class="tittle-th">Report</th>
                                    <th class="tittle-th">Check In</th>
                                </tr>
                                </thead>
                                <tbody id="js-sampling-labtest-tbody">
                                    @if($tests)
                                        @foreach($tests as $test)
                                            @php
                                                $color = 'green';
                                                if ($test->fldsample == 'Appointment')
                                                    $color = 'red';
                                                elseif ($test->fldsample == 'CheckIn')
                                                    $color = 'blue';
                                                elseif ($test->fldsample == 'Reported')
                                                    $color = 'yellow';
                                                elseif ($test->fldsample == 'Verified')
                                                    $color = 'orange';
                                            @endphp
                                            <tr
                                                data-fldid="{{ $test->fldid }}"
                                                tblpatradiotestid="{{ $test->tblpatradiotestid }}"
                                                data-encounterid="{{ $test->fldencounterval }}"
                                                data-patientid="{{ $test->encounter ? $test->encounter->fldpatientval : '' }}"
                                                data-fldcategory="{{ $test->fldcategory ? $test->fldcategory : '' }}"
                                                flduseridreport="{{ $test->flduserid_report ? $test->flduserid_report : '' }}"
                                                fldsample="{{ $test->fldsample }}"
                                                data-fldfullname="{{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldrank . ' ' . $test->encounter->patientInfo->fldfullname : '' }}"
                                            >
                                                <td>{{ $loop->iteration }}</td>
                                                <td><button class="btn" style="background: {{ $color  }};">&nbsp;</button></td>
                                                <td>{{ $test->fldencounterval }}</td>
                                                <td>
                                                    {{ ($test->encounter) ? $test->encounter->fldrank : '' }} {{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldfullname : '' }} <br>
                                                    {{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldagestyle : '' }}/{{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldptsex : '' }} {{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldptcontact : '' }}<br>
                                                    <i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;{{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldptaddvill : '' }}, {{ ($test->encounter && $test->encounter->patientInfo) ? $test->encounter->patientInfo->fldptadddist : '' }}
                                                </td>
                                                <td>
                                                    {{ ($test->encounter && $test->encounter->consultant && $test->encounter->consultant->user ) ? $test->encounter->consultant->user->fldfullname : '' }}<br>
                                                    {{ ($test->encounter && $test->encounter->consultant ) ? $test->encounter->consultant->fldconsultname : '' }}
                                                </td>
                                                <td>
                                                    @if(isset($test->pat_billing_shares->user))
                                                    @foreach($test->pat_billing_shares->user as $user)
                                                        {{$user->fldfullname}}
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>{{ $test->fldtestid }}</td>
                                                <td>{{ $test->comment ?: $test->comment }}</td>
                                                <td>
                                                    <button class="btn btn-primary js-sampling-history-btn">History</button>
                                                    <button class="btn btn-primary js-sampling-pacs-btn">PACS</button>
                                                    <a href="javascript:void(0)" onclick="radiology.displayModal('{{ $test->fldencounterval}}')" class="btn btn-primary">Laboratory</a>
                                                    <a href="javascript:void(0)" onclick="pharmacy.displayModal('{{ $test->fldencounterval}}')" class="btn btn-primary">Pharmacy</a>
                                                </td>
                                                <td>{!! $test->fldreportquali !!}</td>
                                                <td><i class="fa fa-arrow-circle-right" aria-hidden="true" {{ ($test->fldsample == 'CheckIn') ? 'style="color: #b3b9bf;"' : '' }}></i></td>
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

    <div id="js-radiotemplate-modal" class="modal fade fade bd-example-modal-xl" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Radio Content</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="res-table table-sticky-th">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>
                                                TestName
                                                <input type="text" class="form-control" id="find-test-name" placeholder="search test name"/>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-radiotemplate-tbody-modal">
                                        @foreach($radiotemplates as $radiotemplate)
                                        <tr data-flddescription="{{ $radiotemplate->flddescription }}" data-fldtestid="{{ $radiotemplate->fldtestid }}">
                                            <td>{{ $radiotemplate->fldtestid }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <p>Name: <strong id="js-radiotemplate-name-modal">Name</strong></p>
                                <p>EncId: <strong id="js-radiotemplate-encid-modal">EncId</strong></p>
                                <p>Test: <strong id="js-radiotemplate-test-modal">Test</strong></p>
                                <textarea rows="10" style="width: 100%;" id="js-radiotemplate-textarea-modal"></textarea>
                            </div>
                            <div class="form-group text-right">
                                @if(\App\Utils\Permission::checkPermissionFrontendAdmin('radiology-report-save'))
                                <button class="btn btn-primary" id="js-radiotemplate-save-modal">Save</button>
                                @endif
                                @if(\App\Utils\Permission::checkPermissionFrontendAdmin('radiology-report-save-and-verify'))
                                <button class="btn btn-primary" id="js-radiotemplate-saveverify-modal">Save and verify</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="js-appointment-modal" class="modal fade" role="dialog">
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
                        <div class="col-sm-8">
                            <p>Name: <strong id="js-appointment-name-modal">Name</strong></p>
                            <p>EncId: <strong id="js-appointment-encid-modal">EncId</strong></p>
                            <p>Test: <strong id="js-appointment-test-modal">Test</strong></p>
                            <input type="text" id="js-appointment-date-modal" class="form-control nepaliDatePicker">
                            <button class="btn btn-primary" id="js-appointment-save-modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="radiology-observation-modal"></div>
@endsection

@push('after-script')
<script src="{{asset('js/xray_form.js')}}"></script>
@endpush
