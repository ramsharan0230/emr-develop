@extends('frontend.layouts.master')

@section('content')
@php
$moduleName = Request::segment(2) ?: 'addition';
@endphp

@if($moduleName == 'addition')
@include('menu::common.laboratory-nav-bar')
@endif
<div class="container-fluid">
    @include('menu::toggleButton')
    <div class="row">
       <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                 <h4 class="card-title">Radio {{ ucwords($moduleName) }}</h4>
                 <input type="hidden" id="js-sampling-current-userid" value="{{ \Auth::guard('admin_frontend')->user()->username }}">
             </div>
             <a type="button" id="btn" class="btn btn-primary text-white" onclick="toggleSideBar(this)" title="Hide"><i class="fa fa-bars" aria-hidden="true"></i></a>
         </div>
     </div>
 </div>
 <div class="col-sm-12 col-lg-4 leftdiv">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <form method="post" id="js-sampling-search-form" action="{{ url()->full() }}">
                @csrf


                <div class="form-group form-row align-items-center form-row">
                    <div class="col-sm-10">
                        <select name="category" class="form-control" id="js-sampling-category-select">
                            <option value="">--Select--</option>
                            @foreach ($categories as $category)
                            <option {{ (request()->get('category') == $category->flclass) ? 'selected="selected"' : '' }} value="{{ $category->flclass }}">{{ $category->flclass }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary" id="js-sampling-export-left"><i class="fa fa-code"></i></button>
                    </div>
                </div>
                <div class="form-group form-row align-items-center form-row">
                    <div class="col-sm-4"><label>Name</label></div>
                    <div class="col-sm-8">
                        <input type="text" name="name" id="js-sampling-search-name-input" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center form-row">
                    <div class="col-sm-4"><label>Encounter</label></div>
                    <div class="col-sm-8">
                        <input type="text" name="encounterId" id="js-sampling-search-encounter-input" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center pl-2">
                    <div class="col-sm-5">
                        <input type="text" name="fromdate" value="{{ request()->get('fromdate') ?: $date }}" class="form-control nepaliDatePicker" id="js-fromdate-input-nepaliDatePicker">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" name="todate" value="{{ request()->get('todate') ?: $date }}" class="form-control nepaliDatePicker" id="js-todate-input-nepaliDatePicker">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary" id="js-sampling-search-submit-btn"><i class="fa fa-sync"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-sm-12 col-lg-8 rightdiv">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label class="col-lg-3 col-sm-4">Encounter</label>
                        <div class="col-lg-9 col-sm-8">
                            <input type="text" class="form-control" id="js-sampling-encounterid-input">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group form-row">
                        <label class="col-lg-3 col-sm-5">Full Name</label>
                        <div class="col-lg-9 col-sm-7">
                            <input type="text" class="form-control" id="js-sampling-fullname-input" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                   <div class="form-group form-row">
                    <label class="col-lg-4 col-sm-5 ">Address</label>
                    <div class="col-lg-8 col-sm-7">
                        <input type="text" class="form-control" id="js-sampling-address-input" readonly>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group form-row">
                    <label class="col-lg-4 col-sm-5">Age/Sex</label>
                    <div class="col-lg-8 col-sm-7">
                        <input type="text" class="form-control" id="js-sampling-agesex-input" readonly>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group form-row">
                    <label class="col-lg-4 col-sm-5">Location</label>
                    <div class="col-lg-8 col-sm-7">
                        <input type="text" class="form-control" id="js-sampling-location-input" readonly>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group form-row">
                  @if($moduleName == 'reporting')
                  <div class="col-lg-3 col-sm-4 align-items-center">
                    <button type="button" class="btn btn-primary" id="js-reporting-history-btn">&nbsp;History</button>&nbsp;
                    <button type="button" class="btn btn-primary" id="js-reporting-pacs-btn">&nbsp;PACS</button>
                </div>
                @endif
                <div class="col-lg-3 col-sm-4">
                    <button class="btn btn-primary" id="js-sampling-encounter-show-btn"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;View</button>&nbsp;
                    <button class="btn btn-primary" id="js-sampling-export-right">Export</button>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input">
                    <label class="custom-control-label">Show All</label>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div class="col-sm-12 col-lg-4 leftdiv">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="res-table table-sticky-th reporting-table" style="max-height: 609px;">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>EncID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Userid</th>
                            <th>Datetime</th>
                        </tr>
                    </thead>
                    <tbody class="js-sampling-patient-tbody">
                        @if(isset($patients))
                        @foreach($patients as $pat)
                        <tr data-encounterid="{{ $pat->fldencounterval }}">
                            <td>{{ $pat->fldencounterval }}</td>
                            <td>{{ ($pat->encounter && $pat->encounter->patientInfo) ? $pat->encounter->patientInfo->fldfullname : '' }}</td>
                            <td>{{ ($pat->encounter && $pat->encounter->consultant) ? $pat->encounter->consultant->fldconsultname : '' }}</td>
                            <td>{{ $pat->flduserid_report }}</td>
                            <td>{{ $pat->fldtime_report }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @if(FALSE)
            <div class="table-responsive table-scroll-sampling table-sticky-th mt-2">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                        </tr>
                    </thead>
                </table>
                <select multiple style="width: 100%;height: 255px;" class="form-control" id="js-sampling-test-tbody"></select>
            </div>
            <div class="mt-2">
                <div class="form-group form-row align-items-center">
                    <label for="" class="col-sm-3">Payable:</label>
                    <div class="col-sm-7">
                        <select class="form-control" id="js-sampling-userid-select">
                            <option value="">-- Select user --</option>
                            @foreach($all_users as $user)
                            <option value="{{ $user->flduserid }}">{{ $user->fldusername }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group form-row align-items-center">
                    <button class="btn btn-primary btn-sm-in" id="js-sampling-add-btn"><i class="ri-add-fill"></i>&nbsp;Add</button>&nbsp;
                    <button class="btn btn-danger btn-sm-in" id="js-sampling-delete-btn"><i class="ri-delete-bin-5-fill"></i>&nbsp;Delete</button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="col-sm-12 col-lg-8 rightdiv">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="res-table table-sticky-th">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="tittle-th">&nbsp;</th>
                            <th class="tittle-th"><input type="checkbox" name=""></th>
                            <th class="tittle-th">Examination</th>
                            <th class="tittle-th">Observation</th>
                            <th class="tittle-th">Visibility</th>
                            <th class="tittle-th">Method</th>
                            <th class="tittle-th">Evaluation</th>
                            <th class="tittle-th">ReportDate</th>
                            <th class="tittle-th">Comment</th>
                            <th class="tittle-th">Conditions</th>
                        </tr>
                    </thead>
                    <tbody id="js-sampling-labtest-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<div class="modal fade show" id="js-sampling-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align: center;">Change Data</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <input type="hidden" id="js-sampling-modal-column-input">
                        <select class="form-control toggleHide flvisible">
                            @foreach($visibilities as $visibility)
                            <option value="{{ $visibility }}">{{ $visibility }}</option>
                            @endforeach
                        </select>
                        <select class="form-control toggleHide fldmethod">
                            @foreach($methods as $method)
                            <option value="{{ $method }}">{{ $method }}</option>
                            @endforeach
                        </select>
                        <input type="text" class="form-control toggleHide fldsampletype">
                        <textarea class="form-control toggleHide fldcomment fldcondition"></textarea>
                    </div>
                    <div class="col-md-4">
                        <button style="width: 100%;margin-bottom: 5px;" class="btn" onclick="changeRadioData.saveData()">Ok</button>
                        <button style="width: 100%;" type="button" class="btn onclose" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="radiology-observation-modal"></div>
@include('laboratory::modal.patient-image')
@include('radiology::layouts.modal.test-group-modal')
@endsection

@push('after-script')
<script src="{{asset('js/radiology_form.js')}}"></script>
<script type="text/javascript">
    var moduleName = '{{ $moduleName }}';
</script>
@endpush
