@extends('frontend.layouts.master')
@push('after-styles')
<style>
    .text-haemo {
        color: #279faf;
    }

    .Haemodialysis-texarea {
        display: none;
    }

    .dg-actions>div,.dg-actions>a{
        display: inline-block;
    }

#allergy-form{
    max-height:80%;
}

</style>
@endpush
@section('content')
@if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
@php
$disableClass = 'disableInsertUpdate';
@endphp
@else
@php
$disableClass = '';
@endphp
@endif

@php
    $segment = Request::segment(1);

@endphp
@if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
    @php
        $disableClass = 'disableInsertUpdate';
    @endphp
@else
    @php
        $disableClass = '';
    @endphp
@endif
@php
    $segment = Request::segment(1);
    if($segment == 'admin'){
    $segment2 = Request::segment(2);
    $segment3 = Request::segment(3);
    if(!empty($segment3))
    $route = 'admin/'.$segment2 . '/'.$segment3;
    else
    $route = 'admin/'.$segment2;

    }else{
    $route = $segment;
    }
@endphp
<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">

                    <input type="hidden" id="fldencounterval" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif"/>
                    <input type="hidden" id="encounter_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif"/>
                    <input type="hidden" id="flduserid" class="current_user" value="{{Helpers::getCurrentUserName()}}"/>
                    <input type="hidden" id="fldcomp" value="{{ Helpers::getCompName() }}">
                    <input type="hidden" name="req_segment" id="req_segment" value="{{$segment}}">
                    <div class="form-group row mb-0 align-items-center">
                        <label for="" class="control-label col-sm-3 mb-0">Encounter ID</label>
                        <div class="col-sm-6">
                            <input type="text" name="encounter_number" id="encounter_number" class="form-control" placeholder="Enter patient ID"/>
                        </div>
                        <a href="javascript:;" id="patient_req"  class="btn btn-primary btn-sm searchByEncounter" >
                            Submit <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div> 
<div class="row">
    <div class="col-sm-12">
        <div class="iq-card iq-card-block">
            <div class="iq-card-body">
                <div class="form-row">
                    <div class="col-sm-3">
                        <select class="form-control col-sm-3 mt-1" name="department" id="department">
                            <option value="">--Select Department--</option>
                            @if(isset($dischargeDepartment))
                            @forelse($dischargeDepartment as $department)
                            <option value="{{ $department->flddept ?? null }}">{{ $department->flddept ?? null }}</option>
                            @empty
                            <option>No deparments availlable</option>
                            @endforelse
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="search" class="form-control" placeholder="Search Admitted Patient">
                        </div>
                    </div>
                    {{-- <div class="form-group">--}}
                    {{-- <a href="{{ route('discharge.reset-encounter') }}" class="btn btn-primary mt-2">Clear Form</a>--}}
                    {{-- </div>--}}
                    <div class="col-sm-2">
                        <button class="btn btn-primary" id="filter"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-5" id="patient_list">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">
                        Admitted Patient
                    </h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="res-table-discharge">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Encounter</th>
                                <th>Name</th>
                                <th>Bed N0.</th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="patient_tbody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-7" id="patient_profile">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">
                        Patient Profile
                    </h4>
                </div>
            </div>
            <div class="iq-card-body">
                @include('frontend.common.dischargePatientProfile')
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="iq-card iq-card-block">
            <div class="iq-card-body">
                <form id="discharge_details">
                    <div class="form-row">
                        <input type="hidden" name="encounter_id" id="encounterid">
                        <div class="col-sm-4 mt-3">
                            <div class="form-group" style="height:calc(100% - 37px);">
                                <div class="d-flex justify-content-between mb-1">
                                <label for="" class="label-bold">Diagnosis</label>
                                <div class="dg-actions">
                                         <div data-toggle="tooltip" data-placement="top" style="display:none;" title="Free Writing" id="freewritingyes">
                                            <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Final Dignosis" onclick="finaldiagnosisfreetext.displayModal()"><i class="ri-add-fill"></i></a>

                                        </div>
                                        <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="Free Writing" id="freewritingno">
                                            <a href="javascript:void(0);" class="btn btn-primary">Free</a>
                                        </div>


                                        <div data-toggle="tooltip" data-placement="top" style="display:inline-block;" title="ICD">
                                            <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#diagnosis" title="ICD">ICD</a>
                                        </div>

                                        <div data-toggle="tooltip" data-placement="top" title="OBS" id="obs_div" style="display: none;">
                                            <a href="#" class="btn btn-warning" data-toggle="modal" onclick="finalobstetric.displayModal()" title="OBS">OBS</a>
                                        </div>

                                        <div data-toggle="tooltip" data-placement="top" style="display:inline-block; " title="Delete">

                                            <a href="javascript:void(0);" class="btn btn-danger" id="deletealdiagno"><i class="ri-delete-bin-6-line"></i></a>
                                        </div>
                                </div>
                                </div>
                                <select name="" id="diagnosistext" class="form-control discharge-diagno" multiple="">
                                    <option value="">No Diagnosis Found</option>
                                </select>
                                <!-- <div class="col-sm-2">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm" id="diagnosisBtn">
                                            <i class="fas fa-arrow-left"></i>
                                        </button>
                                    </div> -->
                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Presenting Complaints:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="complaints" name="complaints"></textarea>

                            </div>
                        </div>

                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Case Summary:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="pastHistory" name="past_history"></textarea>
                                <!-- <div class="col-sm-2">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm" id="pastHistoryBtn">
                                            <i class="fas fa-arrow-left"></i>
                                        </button>
                                    </div> -->
                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">
                        <label for="" class="label-bold">Operation:</label>
                            <div id="append-result"></div>
                            <div class="add-more">
                                <a href="javascript:void(0);" class="add_button btn btn-primary" title="Add field">Add More</a>

                                <div>
                                    <div class="form-group">
                                        <label for="" class="label-bold">Date Of Operation:</label>
                                        <input type="text" name="operation_date[]" class="form-control proc_date"  autocomplete="off">
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="label-bold">Operative Procedure:</label>
                                        <input type="text" name="operative_procedures[]" id="operative_procedures" class="form-control">
                                    </div>
                                     
                                 </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="label-bold">Operative Findings:</label>
                                <input type="text" name="operative_findings" id="operative_findings" class="form-control">
                            </div>

                        </div>
                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">On examination:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="onExamination" name="on_examination"></textarea>

                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Physical and Systemic Examination:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="physicalExamination" name="physical_examination"></textarea>

                            </div>
                        </div>

                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Surgerical Note:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="surgericalNote" name="surgerical_note"></textarea>

                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">

                            <div class="form-group">
                                <label for="" class="label-bold">Treatment At Hospital:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="course_in_hospital" name="course_in_hospital"></textarea>
                                <!--  <div class="col-sm-2">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm" id="opertionBtn">
                                            <i class="fas fa-arrow-left"></i>
                                        </button>
                                    </div> -->
                            </div>
                        </div>

                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Special Instruction:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="special_instruction" name="special_instruction"></textarea>
                                <!--  <div class="col-sm-2">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm" id="opertionBtn">
                                            <i class="fas fa-arrow-left"></i>
                                        </button>
                                    </div> -->
                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Medication:</label>
                                <button class="btn iq-bg-primary float-right" id="medicineBtn" onclick="dischargepharmacy.displayModal()">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="medicine" name="medication"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Laboratory:</label>
                                <button class="btn iq-bg-primary float-right" id="laboratoryBtn" onclick="dischargelaboratory.displayModal()">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="laboratory-test" name="laboratory"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">

                            <div class="form-group">
                                <label for="" class="label-bold">Radiology:</label>
                                <button class="btn iq-bg-primary float-right" id="radiologyBtn" onclick="dischargeradiology.displayModal()">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="radiology-test" name="radiology"></textarea>
                            </div>
                        </div>


                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Diet:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="diet" name="diet"></textarea>

                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">
                            <div class="form-group" style="height : calc(100% - 37px);">
                                <div class="d-flex justify-content-between">
                                <label for="" class="label-bold">Allergies</label>
                                <div class="dg-actions">

                                <a href="#" class=" btn iq-bg-primary" data-toggle="modal" id="freeallergyyes" data-target="#allergyfreetext" onclick="allergyfreetext.displayModal()" style="display: none;"><i class="ri-add-fill" ></i></a>

                                <a href="#" class=" btn iq-bg-secondary" id="freeallergyno"><i class="ri-add-fill" style="display: block;"></i></a>

                                <a href="#" class=" btn iq-bg-primary" data-toggle="modal" data-target="#allergicdrugs" ><i class="ri-add-fill"></i></a>
                                <!-- <a href="#" class="iq-bg-secondary"><i class="ri-add-fill"></i></a> -->
                                <a href="javascript:void(0)" class=" btn iq-bg-danger" id="deletealdrug"><i class="ri-delete-bin-5-fill"></i></a>
                                </div>
                                </div>
                                <select name="" id="select-multiple-aldrug" class="form-control select-discharge" multiple="">
                                    <option value="">No Alergic Drugs Found</option>
                                </select>
                                <!-- <div class="col-sm-2">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm" id="diagnosisBtn">
                                            <i class="fas fa-arrow-left"></i>
                                        </button>
                                    </div> -->

                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">

                            <div class="form-group">
                                <label for="" class="label-bold">Histology/Special Notes :</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" name="consult_note" id="consult_note"></textarea>

                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">

                            <div class="form-group">
                                <label for="" class="label-bold">Advice on discharge:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" name="advice" id="advice"></textarea>
                                <!--  <div class="col-sm-2">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm" id="opertionBtn">
                                            <i class="fas fa-arrow-left"></i>
                                        </button>
                                    </div> -->
                            </div>
                        </div>

                        <div class="col-sm-4 mt-3">
                        <div class="form-group">
                                <label for="" class="label-bold">Consultant:</label>
                                <select class="form-control" name="consultant" id="consultant">
                                    <option value="">--Select Consultant--</option>
                                    @if(isset($consultants) and count($consultants) > 0)
                                        @foreach($consultants as $c)
                                            <option value="{{$c->username}}">{{$c->firstname}} {{$c->middlename}} {{$c->lastname}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="label-bold">Medical Officer:</label>
                                <select class="form-control" name="medical_officer" id="medical_officer">
                                    <option value="">--Select Doctor--</option>
                                    @if(isset($consultants) and count($consultants) > 0)
                                        @foreach($consultants as $c)
                                            <option value="{{$c->username}}">{{$c->firstname}} {{$c->middlename}} {{$c->lastname}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="label-bold">Anaesthetists :</label>
                                <select class="form-control" name="anaesthetists" id="anaesthetists">
                                    <option value="">--Select Anaesthetists--</option>
                                    @if(isset($anaesthetists) and count($anaesthetists) > 0)
                                        @foreach($anaesthetists as $a)
                                            <option value="{{$a->username}}">{{$a->firstname}} {{$a->middlename}} {{$a->lastname}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="" class="label-bold">Condition At Discharge:</label>
                                
                                        <select class="form-control" name="patient_condition" id="patient_condition">
                                            <option value="">--- Patient Condition ---</option>
                                            <option value="Recovered">Recovered</option>
                                            <option value="Improved">Improved</option>
                                            <option value="Unchanged">Unchanged</option>
                                            <option value="Worse">Worse</option>
                                        </select>
                               
                            </div>
                            <div class="form-group">
                                <label for="" class="label-bold">Patient Status:</label>
                                
                                        <select class="form-control" name="patient_status" id="patient_status">
                                            <option value="">--- Patient Status ---</option>
                                            <option value="DOPR">DOPR</option>
                                            <option value="DOR">DOR</option>
                                            <option value="LAMA">LAMA</option>
                                            <option value="Normal Discharge">Normal Discharge</option>
                                        </select>
                                
                            </div>
                            <div class="form-group">
                                <label for="" class="label-bold">Discharge Date:</label>
                                
                                        <input type="text" class="form-control" name="discharge_nepali_date" id="discharge_nepali_date" value="{{isset($date) ? $date : ''}}" autocomplete="off" />
                                            <input type="hidden" name="discharge_english_date" id="discharge_english_date" value="{{date('Y-m-d')}}">
                                
                            </div>
                        </div>

                        <input type="hidden" name="bed_number" value="" id="bed_number">

                        <input type="hidden" name="department" value="" id="pat_department">
                        <!-- <div class="col-sm-4">
                            <div class="form-group">
                                <label for="" class="label-bold">Persisting Complaints:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="complaints" name="complaints"></textarea>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="" class="label-bold">On examination:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="onExamination" name="on_examination"></textarea>

                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Surgerical Note:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="surgericalNote" name="surgerical_note"></textarea>

                            </div>
                        </div>



                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Physical and Systemic Examination:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="physicalExamination" name="physical_examination"></textarea>

                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Operation performed:</label>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="operation" name="operation_performed"></textarea>

                            </div>
                        </div> -->



                        <!-- <div class="col-sm-4 mt-3">
                            <div class="form-group">
                                <label for="" class="label-bold">Doctors List:</label>
                                <button class="btn iq-bg-primary float-right" id="doctorsBtn" onclick="dischargedoctors.displayModal()">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <textarea class="form-control textarea-scroll mt-1" rows="5" id="doctors" name="doctors"></textarea>
                            </div>
                        </div> -->


                </form>

                    <div class="col-sm-12 text-right">
                        <button  href="javascript:void(0);" class="btn btn-primary btn-action mr-2" onclick="nextVisit()">Next Visit</button>
                        <button  href="javascript:void(0);" class="btn btn-primary btn-action mr-2" onclick="saveDischarge()">Save</button>
                        <button  href="javascript:void(0);" class="btn btn-primary btn-action mr-2" onclick="exportDischargeCertificate()" id="preview" style="display: none;">Preview</button>
                        <button class="btn btn-primary btn-action mr-2" type="button" onclick="exportDischargeCertificate()" id="discharge_certificate" style="display: none;">
                            Discharge Certificate
                        </button>
                        <!-- <button class="btn btn-primary btn-action float-right" type="button" onclick="saveDischargeDetail()">
                            Discharge
                        </button> -->
                    </div>

            </div>
        </div>
    </div>
</div>
        <!--MODALS-->
        <div class="modal fade" id="diagnosis" tabindex="-1" role="dialog" aria-labelledby="allergicdrugsLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="allergicdrugsLabel">ICD10 Database</h5>
                        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="opd-diagnosis">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="patient_id" id="patient_id">
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Group</label>
                                        <div class="col-sm-8">
                                            <select name="" id="diagnogroup" class="form-control">
                                                <option value="">--Select Group--</option>
                                                @if(isset($diagnosisgroup) and count($diagnosisgroup) > 0)
                                                    @foreach($diagnosisgroup as $dg)
                                                        <option value="{{$dg->fldgroupname}}">{{$dg->fldgroupname}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Groups Not Available</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="javascript:void(0);" class=" button btn btn-primary" id="searchbygroup"><i class="ri-refresh-line"></i></a>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#" class="button btn btn-danger" id="closesearchgroup"><i class="ri-close-fill"></i></a>
                                        </div>
                                    </div>
                                    <div id="diagnosiss">
                                        <div class="form-group form-row align-items-center">
                                            <!-- <label for="" class="col-sm-2">Search</label> -->
                                            <!-- <div class="col-sm-10">
                                                <input type="text" name="" palceholder="Search" class="form-control">
                                            </div> -->
                                        </div>
                                        <div class="icd-datatable">
                                            <table class="datatable table table-bordered table-striped table-hover" id="top-req datatable ">
                                                <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                </tr>
                                                </thead>
                                                <tbody id="diagnosiscat">
                                                @forelse($diagnosiscategory as $dc)
                                                    <tr>
                                                        <td><input type="checkbox" class="dccat" name="dccat" value="{{$dc['code']}}"></td>
                                                        <td>{{$dc['code']}}</td>
                                                        <td>{{$dc['name']}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            <em>No data available in table ...</em>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Search</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="search_diagnosis_sublist" id="search_diagnosis_sublist" placeholder="Search" class="form-control">
                                        </div>
                                    </div>
                                    <div class="table-responsive table-scroll-icd">
                                        <table class=" table table-bordered table-striped table-hover" id=" top-req">
                                            <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                            </tr>
                                            </thead>
                                            <tbody id="sublist">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group form-row align-items-center mt-2">
                                        <label for="" class="col-sm-2">Code</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="code" id="code" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Text</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="diagnosissubname" id="diagnosissubname" class="form-control">
                                            <input type="hidden" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitallergydrugs" onclick="updateDiagnosis()">Submit</button>
                            <!-- <input type="submit" name="submit" id="submitallergydrugs" class="btn btn-primary" value="Submit"> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--END MODALS-->
        <!--Allergic MODALS-->
        <div class="modal fade" id="allergicdrugs" tabindex="-1" role="dialog" aria-labelledby="llergicdrugsLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="allergicdrugsLabel" style="text-align: center;">Select Drugs</h5>
                        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="allergyform">
                        <div class="modal-body" style="height: calc(100vh - 200px);overflow-y:auto;overflow-x:hidden;">

                            <input type="hidden" id="patientID" name="patient_id" value="">

                                <div class="form-group mb-2">
                                    <input type="text" name="searchdrugs" class="form-control" id="searchdrugs" placeholder="Search Drugs">
                                </div>
                                <div id="allergicdrugss">
                                    <ul class="list-group">

                                    </ul>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="saveDischargeAllergyDrugs()">Save</button>
                        </div>
                    </form>
                </div>
             </div>
        </div>
        <!--END Allergic MODALS-->
        <!--Free text Modal-->
        <div class="modal fade" id="diagnosis-freetext-modal-final">
            <div class="modal-dialog ">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="inpatient__modal_title">Final diagnosis</h4>
                        <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="form-data-diagnosis-freetext-final"></div>
                </div>
            </div>
        </div>

        <!-- End free Text Modal-->
        <!-- Obstretic-->
        <div class="modal fade" id="diagnosis-obstetric-modal">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Obstetric Diagnosis</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="form-data-obstetric"></div>
                </div>
            </div>
        </div>
        <!--End obstetric-->
        <input type="hidden" name="delete_pat_findings" class="delete_pat_findings" value="{{ route('deletepatfinding') }}"/>
</div>


@include('discharge::modal.laboratory-list')
@include('discharge::modal.radiology-list')
@include('discharge::modal.doctors-list')
@include('outpatient::modal.allergy-freetext-modal')
@endsection
@push('after-script')
<script type="text/javascript">
    
    $('#js-dispensing-medicine-input').select2("destroy").select2();
    $(document).ready(function(){
        $( ".proc_date" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        });
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.add-more');
        var x = 1; //Initial field counter is 1
        
        //Once add button is clicked
        $(addButton).click(function(){
             //Input field wrapper
            var fieldHTML = '<div><div class="form-group"><label for="" class="label-bold">Date Of Operation:</label><input type="text" name="operation_date[]" class="form-control proc_date"  autocomplete="off"></div><div class="form-group"><label for="" class="label-bold">Operative Procedure:</label><input type="text" name="operative_procedures[]" id="operative_procedures" class="form-control"></div><a href="javascript:void(0);" class="remove_button btn btn-danger">Remove</a></div>'; //New input field html 
            //Check maximum number of input fields
            
            if(x < maxField){ 
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            }
            $( ".proc_date" ).datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
            });
        });
        
        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
    
    $('#discharge_nepali_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#discharge_english_date').val(BS2AD($('#discharge_nepali_date').val()));
            }
        });
    $('#searchdrugs').bind('keyup', function() {

        var searchString = $(this).val();

        $("ul li").each(function(index, value) {

            currentName = $(value).text()
            if( currentName.toUpperCase().indexOf(searchString.toUpperCase()) > -1) {
               $(value).show();
            } else {
                $(value).hide();
            }

        });

    });
    function saveDischargeAllergyDrugs() {
        // alert('add allergy drugs');
        $('form').submit(false);
        var url = "{{route('allergydrugstore')}}";
        $.ajax({
            url: url,
            type: "POST",
            data: $("#allergyform").serialize(), "_token": "{{ csrf_token() }}",
            success: function (response) {
                // response.log()
                // console.log(response);
                $('#select-multiple-aldrug').empty().append(response);
                $('#allergicdrugs').modal('hide');
                showAlert('Data Added !!');
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!', 'error);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
    function nextVisit(){
        var encounter = $('#encounterid').val();
        if(!encounter || encounter ==''){
            alert('Please select encounter id.');
            return false;
        }

        $.ajax({
            url: "{{ route('discharge.display.followup')}}",
            type: "GET",
            data: {
                encounterId: $('#encounterid').val()
            },
            success: function(response) {
                // console.log(response);

                $('.pharmacy-form-data').html(response);
                $('#pharmacy-modal').find('.modal-title').text('Next Visit');
                $('#pharmacy-modal') .modal('show');
                // var modal = $(popupTemplate);
                // modal.find('.modal-title').text('HELLO');
                // modal.modal();
                // $('.detailBtn').trigger('click');
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });


    }
    $('#deletealdrug').on('click', function () {
        if (confirm('Are You Sure ?')) {
            $('#select-multiple-aldrug').each(function () {

                var finalval = $(this).val().toString();
                // alert(finalval);
                var url = $('.delete_pat_findings').val();
                $(this).find('option:selected').remove();
                // alert(url);
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {ids: finalval},
                    success: function (data) {

                        console.log(data);

                        // if ($.isEmptyObject(data.error)) {

                        // } else {
                        //     showAlert('Something went wrong!!');
                        // }
                    }
                });
            });
        }
        


    });
    $('#filter').on('click', function(){
        var department = $('#department').val();

        var url = "{{route('patient-department-wise')}}";

        if (department == '' || typeof department == 'undefined' || typeof department == null) {
            return false;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                department: department,
            },
            success: function(data) {
                var html = '';
                if (data.patients.length === 0) {
                    $('#patient_list').show();
                    $('#patient_profile').removeClass('col-sm-12');
                    $('#patient_profile').addClass('col-sm-7');
                    var html = '';
                    html += '<td align="center" colspan="4">No data availlable!</td>';
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                } else {
                    $('#patient_list').show();
                    $('#patient_profile').removeClass('col-sm-12');
                    $('#patient_profile').addClass('col-sm-7');
                    $.each(data.patients, function(index, value) {
                        var name = (value.fldptnamefir) + ' ' + (value.fldmidname != null ? value.fldmidname : '') + ' ' + (value.fldptnamelast);
                        var gender = (value.fldptsex === 'Male' ? 'M' : 'F');

                        html += '<tr><td>' + value.fldencounterval + '</td ><td>' + name + '/' + gender + '/' + value.age + ' </td> <td>' + value.fldbed + '</td> <td><button type="button"  class="btn btn-primary btn-sm detailBtn" data-encounter="' + value.fldencounterval + '" > <i class="fas fa-arrow-right"></i> </button></td</tr>';
                    });
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                }
                $('#preview').hide();
                $('#discharge_certificate').hide();

            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });
    $('#department').change(function() {
        var department = $(this).val();
        var url = '{{ route("patient-department-wise") }}';

        if (department == '' || typeof department == 'undefined' || typeof department == null) {
            return false;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                department: department,
            },
            success: function(data) {
                var html = '';
                if (data.patients.length === 0) {
                    $('#patient_list').show();
                    $('#patient_profile').removeClass('col-sm-12');
                    $('#patient_profile').addClass('col-sm-7');
                    var html = '';
                    html += '<td align="center" colspan="4">No data availlable!</td>';
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                } else {
                    $('#patient_list').show();
                    $('#patient_profile').removeClass('col-sm-12');
                    $('#patient_profile').addClass('col-sm-7');
                    $.each(data.patients, function(index, value) {
                        var name = (value.fldptnamefir) + ' ' + (value.fldmidname != null ? value.fldmidname : '') + ' ' + (value.fldptnamelast);
                        var gender = (value.fldptsex === 'Male' ? 'M' : 'F');

                        html += '<tr><td>' + value.fldencounterval + '</td ><td>' + name + '/' + gender + '/' + value.age + ' </td> <td>' + value.fldbed + '</td> <td><button type="button"  class="btn btn-primary btn-sm detailBtn" data-encounter="' + value.fldencounterval + '" > <i class="fas fa-arrow-right"></i> </button></td</tr>';
                    });
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                }
                $('#preview').hide();
                $('#discharge_certificate').hide();

            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });


    });
    CKEDITOR.replace('pastHistory',
    {
    height: '100px',
    } );
    CKEDITOR.replace('course_in_hospital',
    {
    height: '100px',
    } );
    CKEDITOR.replace('special_instruction',
    {
    height: '100px',
    } );
    CKEDITOR.replace('diet',
    {
    height: '100px',
    } );
    CKEDITOR.replace('consult_note',
    {
    height: '100px',
    } );
    CKEDITOR.replace('complaints',
    {
    height: '100px',
    } );
    CKEDITOR.replace('onExamination',
    {
    height: '100px',
    } );
    CKEDITOR.replace('physicalExamination',
    {
    height: '100px',
    } );
    CKEDITOR.replace('surgericalNote',
    {
    height: '100px',
    } );
    CKEDITOR.replace('advice',
    {
    height: '100px',
    } );

    //patientProfile data
    $(document).on('click', '.detailBtn', function() {
        // alert('click bahyo');
        $('form').submit(false);
        var encounter = $(this).data('encounter');
        var url = "{{ route('populate-patient-profile') }}";
        if (encounter == '' || typeof encounter == 'undefined' || typeof encounter == null) {
            return false;
        } 

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                encounter_id: encounter,
            },
            success: function(data) {
                console.log(data);
                if (data.length != 0) {
                    // alert(data.dischargedata.othergeneralData.complaints);
                    dob = new Date(data.patient.fldptbirday);
                    var today = new Date();
                    var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));


                    var name = (data.patient.fldfullname)
                    var patID = (data.enpatient.fldpatientval)
                    var encID = (data.enpatient.fldencounterval)
                    var height = (data.height)
                    var heigt_rate = (data.heightrate)
                    var bmi = (data.bmi)
                    var weight = (data.body_weight != null && data.body_weight.fldrepquali) ? data.body_weight.fldrepquali : '';
                    var doReg = (data.enpatient.fldregdate)
                    var location = (data.enpatient.fldcurrlocat === 'Discharged') ? 'Discharged' : (data.enpatient.fldcurrlocat)
                    var status = (data.enpatient.fldadmission)
                    var gender = (data.patient.fldptsex)
                    if(data.patient.fldptaddvill != null){
                        var vill = data.patient.fldptaddvill;
                    }else{
                        var vill = '';
                    }
                    var address = (vill) + '/' + (data.patient.fldptadddist)
                    // alert(encID);
                    $('#selectedEncounter').val(encID);
                    $('#fldencounterval').val(encID);
                    $('#patient_id').val(encID);

                    $('#patientID').val(encID);
                    $('#encounter_id').val(encID);
                    $('#encounterid').val(encID);
                    $('#patientName').html('');
                    $('#patID').html('');
                    $('#EncID').html('');
                    $('#gender').html('');
                    $('#heightvalue').val('');
                    $('#weight').val('');
                    $('#bmi').html('');
                    $('#age').html('');
                    $('#DOReg').html('');
                    $('#location').html('');
                    $('#admitedstatus').html('');
                    $('#address').html('');
                    $('#diagnosistext').html('');
                    $('#complaints').html('');
                    $('#onExamination').val('');
                    $('#surgericalNote').val('');
                    $('#medicine').val('');
                    $('#pastHistory').val('');
                    $('#physicalExamination').val('');
                    $('#operation').html('');
                    $('#diet').html('');
                    $('#special_instruction').html('');
                    $('#course_in_hospital').html('');
                    $('#consult_note').html('');
                    $('#patient_condition').val('');
                    $('.list-group').html('');
                    $('#select-multiple-aldrug').html('');
                    $('#bed_number').val('');
                    $('#pat_department').val('');
                    $('#complaints').val('');
                    $('#pastHistory').val('');
                    $('#onExamination').val('');
                    $('#physicalExamination').val('');
                    $('#surgericalNote').val('');
                    $('#course_in_hospital').val('');
                    $('#special_instruction').val('');
                    
                    $('#laboratory-test').val('');
                    $('#radiology-test').val('');
                    $('#diet').val('');
                    $('#consult_note').val('');
                    $('#advice').val('');
                    $('#discharge_english_date').val('');
                    $('#discharge_nepali_date').val('');
                    $('#patient_status').val('');
                    // $('#complaints').val(data.dischargedata.othergeneralData.complaints);
                    CKEDITOR.instances['complaints'].setData(data.dischargedata.othergeneralData.complaints);
                    if(data.dischargedata.othergeneralData.past_history !=''){
                        CKEDITOR.instances['pastHistory'].setData(data.dischargedata.othergeneralData.past_history);
                    }else{
                        CKEDITOR.instances['pastHistory'].setData(data.pasthistoryhtml);
                    }
                    CKEDITOR.instances['onExamination'].setData(data.dischargedata.othergeneralData.on_examination);
                    CKEDITOR.instances['physicalExamination'].setData(data.dischargedata.othergeneralData.physical_examination);
                    CKEDITOR.instances['surgericalNote'].setData(data.dischargedata.othergeneralData.surgerical_note);
                    CKEDITOR.instances['course_in_hospital'].setData(data.dischargedata.othergeneralData.course_in_hospital);
                    CKEDITOR.instances['special_instruction'].setData(data.dischargedata.othergeneralData.special_instruction);
                    CKEDITOR.instances['special_instruction'].setData(data.dischargedata.othergeneralData.special_instruction);
                    if(data.dischargedata.othergeneralData.medication !=''){
                        $('#medicine').val(data.dischargedata.othergeneralData.medication);
                    }else{
                        $('#medicine').val(data.medicationhtml);
                    }

                    $('#laboratory-test').val(data.dischargedata.othergeneralData.laboratory);
                    $('#radiology-test').val(data.dischargedata.othergeneralData.radiology);
                    CKEDITOR.instances['diet'].setData(data.dischargedata.othergeneralData.diet);
                    CKEDITOR.instances['consult_note'].setData(data.dischargedata.othergeneralData.consult_note);
                    CKEDITOR.instances['advice'].setData(data.dischargedata.othergeneralData.advice);
                    // $('#patient_condition option[value="data.dischargedata.othergeneralData.patient_condition"]').prop('selected', true);
                    // $('#consultant option[value="data.dischargedata.othergeneralData.consultant"]').prop('selected', true);
                    // $('#medical_officer option[value="data.dischargedata.othergeneralData.medical_officer"]').prop('selected', true);
                    // $('#anaesthetists option[value="data.dischargedata.othergeneralData.anaesthetists"]').prop('selected', true);
                    $('#patient_condition').val(data.dischargedata.othergeneralData.patient_condition);
                    $('#patient_status').val(data.dischargedata.othergeneralData.patient_status);
                    $('#consultant').val(data.dischargedata.othergeneralData.consultant);
                    $('#medical_officer').val(data.dischargedata.othergeneralData.medical_officer);
                    $('#anaesthetists').val(data.dischargedata.othergeneralData.anaesthetists);
                    // $('#proc_date').val(data.dischargedata.othergeneralData.operation_date);
                    // $('#englis_proc_date').val(data.dischargedata.othergeneralData.eng_operation_date);
                    // $('#operative_procedures').val(data.dischargedata.othergeneralData.operative_procedures);
                    $('#operative_findings').val(data.dischargedata.othergeneralData.operative_findings);
                    $('#discharge_nepali_date').val(data.dischargedata.othergeneralData.discharge_nepali_date);
                    $('#discharge_english_date').val(data.dischargedata.othergeneralData.discharge_english_date);
                    
                    $('#patientName').html(name);
                    $('#patID').html(patID);
                    $('#EncID').html(encID);
                    $('#gender').html(gender);
                    $('#heightvalue').val(height);
                    $('#weight').val(weight);
                    $('#bmi').html(bmi);
                    $('#address').html(address);
                    $('#age').html(age + ' Years/' + gender);
                    $('#DOReg').html(doReg);
                    $('#location').html(location);
                    $('#admitedstatus').html(status);
                    $('#append-result').html(data.procedurehtml);
                    $('#billingmode').html(data.billing);
                    $('#diagnosistext').html(data.diagnosishtml);
                    $('#complaints').html(data.complaintshtml);
                    $('#onExamination').val(data.onexaminationhtml);
                    $('#surgericalNote').val(data.surgicalnotehtml);

                    $('#pastHistory').val(data.pasthistoryhtml);
                    $('#physicalExamination').val(data.onexaminationhtml);
                    $('#operation').html(data.operationperformedhtml);
                    $('#select-multiple-aldrug').html(data.drughtml);
                    $('.list-group').html(data.allergicdrugshtml);
                    $('#bed_number').val(data.bed_number);
                    $('#pat_department').val(data.patientdepartment);
                    $("#billingmode").attr('disabled', 'disabled');
                    if(data.enable_freetext == 1){
                            $('#freewritingyes').show();
                            $('#freewritingno').hide();
                            $('#freeallergyyes').show();
                            $('#freeallergyno').hide();
                    }else{
                        $('#freewritingyes').hide();
                        $('#freewritingno').show();
                        $('#freeallergyyes').hide();
                        $('#freeallergyno').show();
                    }
                    if(gender == 'Female'){
                            $('#obs_div').show();
                        }else{
                            $('#obs_div').hide();
                        }
                    if (data.heightrate === 'cm') {
                        $html = '<option selected>cm </option>'
                        $('#heightrate').empty().append($html)
                    }
                    if (data.heightrate === 'm') {
                        $html = '<option selected>m </option>'
                        $('#heightrate').empty().append($html)

                    }

                    setTimeout(function () {
                        $("#consultant").select2();
                        $('#medical_officer').select2();
                        $('#anaesthetists').select2();
                        $('#patient_condition').select2();
                        $('#patient_status').select2();
                    }, 1500);
                    $('#patient_list').hide();
                    $('#patient_profile').removeClass('col-sm-7');
                    $('#patient_profile').addClass('col-sm-12');

                }

            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });

    });
    $(document).on('click', '.searchByEncounter', function() {
        $('form').submit(false);
        var encounter = $('#encounter_number').val();
        var url = "{{ route('populate-patient-profile') }}";
        if (encounter == '' || typeof encounter == 'undefined' || typeof encounter == null) {
            alert('Please enter encounter number')
            return false;
        } 

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                encounter_id: encounter,
            },
            success: function(data) {
                if (data.length != 0) {
                    // alert(data.patient.fldptbirday);
                    dob = new Date(data.patient.fldptbirday);
                    var today = new Date();
                    var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));


                    var name = (data.patient.fldfullname)
                    var patID = (data.enpatient.fldpatientval)
                    var encID = (data.enpatient.fldencounterval)
                    var height = (data.height)
                    var heigt_rate = (data.heightrate)
                    var bmi = (data.bmi)
                    var weight = (data.body_weight != null && data.body_weight.fldrepquali) ? data.body_weight.fldrepquali : '';
                    var doReg = (data.enpatient.fldregdate)
                    var location = (data.enpatient.fldcurrlocat === 'Discharged') ? 'Discharged' : (data.enpatient.fldcurrlocat)
                    var status = (data.enpatient.fldadmission)
                    var gender = (data.patient.fldptsex)
                    if(data.patient.fldptaddvill != null){
                        var vill = data.patient.fldptaddvill;
                    }else{
                        var vill = '';
                    }
                    var address = (vill) + '/' + (data.patient.fldptadddist)
                    // alert(encID);
                    $('#selectedEncounter').val(encID);
                    $('#fldencounterval').val(encID);
                    $('#patient_id').val(encID);

                    $('#patientID').val(encID);
                    $('#encounter_id').val(encID);
                    $('#encounterid').val(encID);
                    $('#patientName').html('');
                    $('#patID').html('');
                    $('#EncID').html('');
                    $('#gender').html('');
                    $('#heightvalue').val('');
                    $('#weight').val('');
                    $('#bmi').html('');
                    $('#age').html('');
                    $('#DOReg').html('');
                    $('#location').html('');
                    $('#admitedstatus').html('');
                    $('#address').html('');
                    $('#diagnosistext').html('');
                    $('#complaints').html('');
                    $('#onExamination').val('');
                    $('#surgericalNote').val('');
                    $('#medicine').val('');
                    $('#pastHistory').val('');
                    $('#physicalExamination').val('');
                    $('#operation').html('');
                    $('#diet').html('');
                    $('#special_instruction').html('');
                    $('#course_in_hospital').html('');
                    $('#consult_note').html('');
                    $('#patient_condition').val('');
                    $('.list-group').html('');
                    $('#select-multiple-aldrug').html('');
                    $('#bed_number').val('');
                    $('#pat_department').val('');
                    $('#complaints').val('');
                    $('#pastHistory').val('');
                    $('#onExamination').val('');
                    $('#physicalExamination').val('');
                    $('#surgericalNote').val('');
                    $('#course_in_hospital').val('');
                    $('#special_instruction').val('');
                    $('#discharge_nepali_date').val('');
                    $('#discharge_english_date').val('');
                    $('#laboratory-test').val('');
                    $('#radiology-test').val('');
                    $('#diet').val('');
                    $('#consult_note').val('');
                    $('#advice').val('');
                    $('#discharge_english_date').val('');
                    $('#discharge_nepali_date').val('');
                    $('#patient_status').val('');
                    // $('#complaints').val(data.dischargedata.othergeneralData.complaints);
                    CKEDITOR.instances['complaints'].setData(data.dischargedata.othergeneralData.complaints);
                    if(data.dischargedata.othergeneralData.past_history !=''){
                        CKEDITOR.instances['pastHistory'].setData(data.dischargedata.othergeneralData.past_history);
                    }else{
                        CKEDITOR.instances['pastHistory'].setData(data.pasthistoryhtml);
                    }
                    CKEDITOR.instances['onExamination'].setData(data.dischargedata.othergeneralData.on_examination);
                    CKEDITOR.instances['physicalExamination'].setData(data.dischargedata.othergeneralData.physical_examination);
                    CKEDITOR.instances['surgericalNote'].setData(data.dischargedata.othergeneralData.surgerical_note);
                    CKEDITOR.instances['course_in_hospital'].setData(data.dischargedata.othergeneralData.course_in_hospital);
                    CKEDITOR.instances['special_instruction'].setData(data.dischargedata.othergeneralData.special_instruction);
                    CKEDITOR.instances['special_instruction'].setData(data.dischargedata.othergeneralData.special_instruction);
                    if(data.dischargedata.othergeneralData.medication !=''){
                        $('#medicine').val(data.dischargedata.othergeneralData.medication);
                    }else{
                        $('#medicine').val(data.medicationhtml);
                    }

                    $('#laboratory-test').val(data.dischargedata.othergeneralData.laboratory);
                    $('#radiology-test').val(data.dischargedata.othergeneralData.radiology);
                    CKEDITOR.instances['diet'].setData(data.dischargedata.othergeneralData.diet);
                    CKEDITOR.instances['consult_note'].setData(data.dischargedata.othergeneralData.consult_note);
                    CKEDITOR.instances['advice'].setData(data.dischargedata.othergeneralData.advice);
                    // $('#patient_condition option[value="data.dischargedata.othergeneralData.patient_condition"]').prop('selected', true);
                    // $('#consultant option[value="data.dischargedata.othergeneralData.consultant"]').prop('selected', true);
                    // $('#medical_officer option[value="data.dischargedata.othergeneralData.medical_officer"]').prop('selected', true);
                    // $('#anaesthetists option[value="data.dischargedata.othergeneralData.anaesthetists"]').prop('selected', true);
                    $('#patient_condition').val(data.dischargedata.othergeneralData.patient_condition);
                    $('#patient_status').val(data.dischargedata.othergeneralData.patient_status);
                    $('#consultant').val(data.dischargedata.othergeneralData.consultant);
                    $('#medical_officer').val(data.dischargedata.othergeneralData.medical_officer);
                    $('#anaesthetists').val(data.dischargedata.othergeneralData.anaesthetists);
                    $('#proc_date').val(data.dischargedata.othergeneralData.operation_date);
                    setTimeout(function () {
                        $("#consultant").select2();
                        $('#medical_officer').select2();
                        $('#anaesthetists').select2();
                        $('#patient_condition').select2();
                        $('#patient_status').select2();
                    }, 1500);
                    // $('#englis_proc_date').val(data.dischargedata.othergeneralData.eng_operation_date);
                    // $('#operative_procedures').val(data.dischargedata.othergeneralData.operative_procedures);
                    $('#operative_findings').val(data.dischargedata.othergeneralData.operative_findings);
                    $('#discharge_nepali_date').val(data.dischargedata.othergeneralData.discharge_nepali_date);
                    $('#discharge_english_date').val(data.dischargedata.othergeneralData.discharge_english_date);
                    
                    $('#patientName').html(name);
                    $('#patID').html(patID);
                    $('#EncID').html(encID);
                    $('#gender').html(gender);
                    $('#heightvalue').val(height);
                    $('#weight').val(weight);
                    $('#bmi').html(bmi);
                    $('#address').html(address);
                    $('#age').html(age + ' Years/' + gender);
                    $('#DOReg').html(doReg);
                    $('#location').html(location);
                    $('#admitedstatus').html(status);
                    $('#append-result').html(data.procedurehtml);
                    $('#billingmode').html(data.billing);
                    $('#diagnosistext').html(data.diagnosishtml);
                    $('#complaints').html(data.complaintshtml);
                    $('#onExamination').val(data.onexaminationhtml);
                    $('#surgericalNote').val(data.surgicalnotehtml);

                    $('#pastHistory').val(data.pasthistoryhtml);
                    $('#physicalExamination').val(data.onexaminationhtml);
                    $('#operation').html(data.operationperformedhtml);
                    $('#select-multiple-aldrug').html(data.drughtml);
                    $('.list-group').html(data.allergicdrugshtml);
                    $('#bed_number').val(data.bed_number);
                    $('#pat_department').val(data.patientdepartment);
                    $("#billingmode").attr('disabled', 'disabled');
                    if(data.enable_freetext == 1){
                            $('#freewritingyes').show();
                            $('#freewritingno').hide();
                            $('#freeallergyyes').show();
                            $('#freeallergyno').hide();
                    }else{
                        $('#freewritingyes').hide();
                        $('#freewritingno').show();
                        $('#freeallergyyes').hide();
                        $('#freeallergyno').show();
                    }
                    if(gender == 'Female'){
                            $('#obs_div').show();
                        }else{
                            $('#obs_div').hide();
                        }
                    if (data.heightrate === 'cm') {
                        $html = '<option selected>cm </option>'
                        $('#heightrate').empty().append($html)
                    }
                    if (data.heightrate === 'm') {
                        $html = '<option selected>m </option>'
                        $('#heightrate').empty().append($html)

                    }
                    $('#patient_list').hide();
                    $('#patient_profile').removeClass('col-sm-7');
                    $('#patient_profile').addClass('col-sm-12');
                }

            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });

    });



    var dischargelaboratory = {
        displayModal: function() {
            // alert('laboratory');
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            $('form').submit(false);
            if ($('#encounterid').val() == "") {
                alert('Please choose patient encounter.');
                return false;
            }
            $.ajax({
                url: "{{ route('discharge.lab.list')}}",
                type: "POST",
                data: {
                    encounter_id: $('#encounterid').val()
                },
                success: function(response) {
                    // console.log(response);
                    $('#laboratory-list-modal').modal('show');
                    $('#form-data-laboratory-table-list').html(response);
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
    }

    var dischargeradiology = {
        displayModal: function() {
            // alert('Radiology');
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            $('form').submit(false);
            if ($('#encounterid').val() == "") {
                alert('Please choose patient encounter.');
                return false;
            }
            $.ajax({
                url: "{{ route('discharge.radio.list') }}",
                type: "POST",
                data: {
                    encounter_id: $('#encounterid').val()
                },
                success: function(response) {
                    // console.log(response);
                    $('#radiology-list-modal').modal('show');
                    $('#form-data-radiology-table-list').html(response);


                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }

    var dischargedoctors = {
        displayModal: function() {
            // alert('Doctors');
            // if($('encounter_id').val() == 0)
            // alert($('#encounter_id').val());
            $('form').submit(false);
            if ($('#encounterid').val() == "") {
                alert('Please choose patient encounter.');
                return false;
            }
            $.ajax({
                url: "{{ route('discharge.doctors.list')}}",
                type: "POST",
                data: {
                    department: $('#department').val()
                },
                success: function(response) {
                    // console.log(response);
                    $('#doctors-list-modal').modal('show');
                    $('.form-data-doctors-list').html(response);


                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


        },
    }
    $(document).ready(function() {
        setTimeout(function() {
            $("#department").select2({

            });

        }, 1500);
    });

    function saveDischargeDetail() {
        $('form').submit(false);
        if ($('#encounterid').val() == "") {
            alert('Please choose patient encounter.');
            return false;
        }
        var cond = $('#patient_condition').val();

        if (cond == '') {
            alert('Please select patient condition');
            return false;
        }
        // alert('save discharge');
        var url = "{{route('saveDischarge')}}";
        var alldata = $("#discharge_details").serialize();
        // alert(alldata);
        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].updateElement();
        };
        $.ajax({
            url: url,
            type: "POST",
            data: $("#discharge_details").serialize(),
            "_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                // $('#select-multiple-diagno').html(response);
                // $('#diagnosis').modal('hide');
                showAlert('Information Saved !!');
                $('#discharge_certificate').show();
                $('#admitedstatus').text('Discharged');
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!');
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

     function saveDischarge() {
        $('form').submit(false);
        if ($('#encounterid').val() == "") {
            alert('Please choose patient encounter.');
            return false;
        }
        var cond = $('#patient_condition').val();

        if (cond == '') {
            alert('Please select patient condition');
            return false;
        }
        // alert('save discharge');
        var url = "{{route('save')}}";
        var alldata = $("#discharge_details").serialize();
        // alert(alldata);
        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].updateElement();
        };
        $.ajax({
            url: url,
            type: "POST",
            data: $("#discharge_details").serialize(),
            "_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                // $('#select-multiple-diagno').html(response);
                // $('#diagnosis').modal('hide');
                showAlert('Information Saved !!');
                $('#preview').show();
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!');
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function exportDischargeCertificate() {
        // alert('discharge certificate');
        $('form').submit(false);
        if ($('#encounterid').val() == "") {
            alert('Please choose patient encounter.');
            return false;
        }
        var encounter_id = $('#encounterid').val();
        var urlReport = baseUrl + "/discharge/dischargeCertificate?encounter_id=" + encounter_id + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


        window.open(urlReport, '_blank');
    }


    var dischargepharmacy = {
            displayModal: function() {

                $('form').submit(false);
                $('.pharmacy-form-data').empty();
                if ($('#encounterid').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }

                if($('#department').val() ==''){
                    alert('Please select department')
                    return false;
                }

                $.ajax({
                    url: "{{ route('discharge.medicineRequest')}}",
                    type: "POST",
                    data: {
                        encounterId: $('#encounterid').val(),
                        department: $('#department').val(),
                    },
                    success: function(response) {
                        // console.log(response);

                        $('.pharmacy-form-data').html(response.html);
                        $('#pharmacy-modal').find('#request_department_pharmacy').val(response.department);
                        $('#pharmacy-modal').modal('show');
                        // $('.detailBtn').trigger('click');
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            },
        }

        var dischargeallergyfreetext = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.', 'error');
                    return false;
                }
                $('.form-data-allergy-freetext').empty();
                $.ajax({
                    url: "{{ route('patient.allergy.freetext') }}",
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        $('.form-data-allergy-freetext').html(response);
                        $('#allergy-freetext-modal').modal('show');
                        // $('#allergy-freetext-modal').on('show.bs.modal', function (event) {
                        //     $('#custom_allergy').focus();
                        // });
                        setTimeout(function () {
                            $('#custom_allergy').focus();
                        }, 1500);

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
        }

    $(document).on('click','.diagnosissub', function(){
            // alert('click sub bhayo');

            $('input[name="diagnosissub"]').bind('click',function() {
                $('input[name="diagnosissub"]').not(this).prop("checked", false);
            });
            var diagnosub = $("input[name='diagnosissub']");

            if (diagnosub.is(':checked')) {
                var value = $(this).val();

                $('#diagnosissubname').val(value);
            }else{
                $("#diagnosissubname").val('');
            }
        });


        $(document).on('click','.dccat', function(){
            // alert('click bhayo');

            $('input[name="dccat"]').bind('click',function() {
                $('input[name="dccat"]').not(this).prop("checked", false);
            });
            var diagnocode = $("input[name='dccat']");
            $('#code').val($(this).val());
            if (diagnocode.is(':checked')) {

                diagnocode = $(this).val() + ",";
                diagnocode = diagnocode.slice(0, -1);

                $("input[name='dccat']").attr('checked', false);

                if(diagnocode.length > 0){
                    // alert(diagnocode);
                    $.get("getDiagnosisByCode", {term: diagnocode}).done(function(data){
                        // Display the returned data in browser
                        $("#sublist").html(data);
                    });
                }
            }else{
                $("#sublist").html('');
            }
        });

        $('.onclose').on('click', function(){

            $('input[name="dccat"]').prop("checked", false);
            $('#code').val('');
            $("#diagnosissubname").val('');
            $("#sublist").val('');
        });


        $('#searchbygroup').on('click', function(){
            // alert('searchbygroup');
            var groupname = $('#diagnogroup').val();
            // alert(groupname);
            if(groupname.length > 0){
                $.get("getDiagnosisByGroup", {term: groupname}).done(function(data){
                    // Display the returned data in browser
                    $("#diagnosiscat").html(data);
                });
            }
        });
        $('#closesearchgroup').on('click', function(){
            $('#diagnogroup').val('');
            $.get("getInitialDiagnosisCategoryAjax", {term:'' }).done(function(data){
                // Display the returned data in browser
                $("#diagnosiscat").html(data);
            });

        });

        $('#deletealdiagno').on('click', function(){
            var id = $('#diagnosistext').val();
            var encounter = $('#encounterid').val();
            if(!encounter || encounter ==''){
                alert('Please select encounter id.');
                return false;
            }
            if(id !=''){
                var url = "{{ route('discharge.deleteDiagnosis') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {ids:id,encounter:encounter},
                    success: function(data) {
                        // console.log(data);
                        if(data.message){
                            showAlert(data.message);
                        }
                        if(data.html){
                            $('#diagnosistext').empty().html(data.html);
                        }
                        if(data.error){
                            showAlert(data.error,'error');
                        }
                    }
                });

            }
        });

        function updateDiagnosis() {
            // alert('diagn');
            var url = "{{route('discharge.diagnosisStore')}}";
            if ($('#encounterid').val() == "") {
                alert('Please choose patient encounter.');
                return false;
            }
            $("#opd-diagnosis").append($("#patient_id"));

            $.ajax({
                url: url,
                type: "POST",
                data: $("#opd-diagnosis").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    // response.log()
                    console.log(response);
                    $('#diagnosistext').html(response);
                    $('#diagnosis').modal('hide');
                    showAlert('Data Added !!');
                    // if ($.isEmptyObject(data.error)) {
                    //     showAlert('Data Added !!');
                    //     $('#allergy-freetext-modal').modal('hide');
                    // } else
                    //     showAlert('Something went wrong!!', 'error);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        var finaldiagnosisfreetext = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    // url: "{{ route('discharge.diagnosis.freetext.final') }}",
                    url: '{{route("discharge.diagnosis.freetext.final")}}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-diagnosis-freetext-final').html(response);
                        setTimeout(function () {
                            $('#custom_diagnosis').focus();
                        }, 1500);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#diagnosis-freetext-modal-final').modal('show');
            },
        }

        var finalobstetric = {
            displayModal: function () {
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route("discharge.diagnosis.final.obstetric") }}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-obstetric').html(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#diagnosis-obstetric-modal').modal('show');
            },
        }

</script>

@endpush
