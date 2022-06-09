@extends('frontend.layouts.master') @section('content')
    <!-- <div class="iq-top-navbar second-nav"> -->
    @if(isset($patient_status_disabled) && $patient_status_disabled == 1 ) @php $disableClass = 'disableInsertUpdate'; @endphp
    @else
        @php $disableClass = ''; @endphp
    @endif
    @include('menu::common.icu-general-nav-bar')


{{--    patient profile --}}
    @include('frontend.common.patientProfile')
{{--    end patient profile--}}

{{--    bollus template--}}

    <template id="js-multi-bollus-tr-template">
        <tr class="bollus-tr">
                    <td width="35%">
                        <div class="">
                            <select id="bollus_medicine" class="form-control medicine" name="bollus_medicine[]">
                                <option value="">--select--</option>
                                @if(isset($medicines))
                                    @forelse($medicines as $medicine)
                                        <option value="{{ $medicine->flditem ?? null }}"> {{ $medicine->flditem ?? null }}</option>
                                    @empty
                                        <option value="">--Not availlable--</option>
                                    @endforelse
                                 @endif
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="">
                            <input type="text" name="bollus_val[]" id="bollus_val" class="form-control answer" value=""/>
                        </div>
                    </td>
            <td><button type="button" class="btn btn-danger btn-sm-in mt-4 js-multi-bollus-remove-btn"><i class="fa fa-times"></i></button></td>
        </tr>
    </template>
    <template id="js-multi-intravenous-tr-template">

        <tr>
            <td width="35%">
                <div class="">
                    <input type="text" name="intravenous[]" id="intravenous" class="form-control intravenous" value=""/>
                </div>
            </td>
            <td>
                <div class="">
                    <input type="text" name="intravenous_val[]" id="intravenous_value " class="form-control  intravenous_val" value=""/>
                </div>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm-in mt-4 js-multi-intravenous-remove-btn"><i class="fa fa-times"></i></button></td>
        </tr>
    </template>






    <div class="container-fluid">
        <input type="hidden" name="encounter_id" value="{{ isset($encounter_no) ? $encounter_no : ''  }}" id="encounter_no">
        <input type="hidden" name="fldpatientval" value="{{ isset($enpatient->fldpatientval) ? $enpatient->fldpatientval :'' }}" id="fldpatientval">
        <div class="row">
            <div class="col-sm-4">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title tittle-resp">Provisional Diagnosis</h4>
                        </div>
                        <div class="allergy-add">
                            @if(isset($enable_freetext) and $enable_freetext == 'Yes')
                                <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-primary"
                                   data-toggle="modal" data-target="#" onclick="diagnosisfreetext.displayModal()"><i
                                        class="ri-add-fill"></i></a>
                            @else
                                <a href="#javascript:void(0);" class="{{ $disableClass }} iq-bg-primary"><i
                                        class="ri-add-fill"></i></a>
                            @endif @if(isset($patient) and $patient->fldptsex == 'Female')
                                <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-primary"
                                   id="pro_obstetric" data-toggle="modal" data-target="#obstetricdiagnosis"
                                   onclick="proobstetric.displayModal()"><i class="ri-add-fill"></i></a>
                            @endif
                            <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-primary" id="pro_diagnosis"
                               data-toggle="modal" data-target="#dliago_group"><i class="ri-add-fill"></i></a>
                            <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-danger"
                               id="delete__provisional_item"><i class="ri-delete-bin-5-fill"></i></a>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="" class="form-horizontal">
                            <div class="form-group mb-0">
                                <select class="form-control" multiple id="provisional_delete">
                                    @if(isset($pat_findings)) @foreach($pat_findings as $provisional) @if($provisional->fldtype == 'Provisional Diagnosis')
                                        <option value="{{ $provisional->fldid }}">{{ $provisional->fldcode }}</option>
                                    @endif @endforeach @endif
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title tittle-resp">Final Diagnosis</h4>
                        </div>
                        <div class="allergy-add">
                            @if(isset($enable_freetext) and $enable_freetext == 'Yes')
                                <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-primary"
                                   data-toggle="modal" onclick="finaldiagnosisfreetext.displayModal()"><i
                                        class="ri-add-fill"></i></a>
                            @else
                                <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-primary"><i
                                        class="ri-add-fill"></i></a>
                            @endif @if(isset($patient) and $patient->fldptsex == 'Female')
                                <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-primary"
                                   id="final_obstetric" data-toggle="modal" data-target="#finalobstetricdiagnosis"
                                   onclick="finalobstetric.displayModal()">
                                    <i class="ri-add-fill"></i>
                                </a>
                            @endif
                            <a href="javascript:void(0);" class="iq-bg-primary" id="final_diagnosis" data-toggle="modal"
                               data-target="#final_dliago_group" class="iq-bg-primary"><i class="ri-add-fill"></i></a>
                            <a href="javascript:void(0);" class="{{ $disableClass }} iq-bg-danger"
                               id="delete__final_item"><i class="ri-delete-bin-5-fill"></i></a>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="" class="form-horizontal">
                            <div class="form-group mb-0">
                                <select name="" class="form-control" multiple id="final_delete">
                                    @if(isset($pat_findings)) @foreach($pat_findings as $findings) @if($findings->fldtype == 'Final Diagnosis')
                                        <option value="{{ $findings->fldid }}">{{ $findings->fldcode }}</option>
                                    @endif @endforeach @endif
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title tittle-resp">Allergy Drugs</h4>
                        </div>
                        <div class="allergy-add">
                            @if(isset($enable_freetext) and $enable_freetext == 'Yes')
                                <a href="#" class="iq-bg-primary" data-toggle="modal" data-target="#allergyfreetext"
                                   onclick="allergyfreetext.displayModal()"><i class="ri-add-fill"></i></a>
                            @else
                                <a href="#" class="iq-bg-secondary"><i class="ri-add-fill"></i></a>
                            @endif
                            <a href="#" class="{{ $disableClass }} iq-bg-primary" data-toggle="modal"
                               data-target="#allergic_modal"><i class="ri-add-fill"></i></a>
                            <a href="#" class="{{ $disableClass }} iq-bg-danger" id="delete__allergic_item"><i
                                    class="ri-delete-bin-5-fill"></i></a>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="" class="form-horizontal">
                            <div class="form-group mb-0">
                                <select name="" class="form-control" multiple id="select-multiple-aldrug">
                                    @if(isset($pat_findings)) @foreach($pat_findings as $findings) @if($findings->fldtype == 'Allergic Drugs')
                                        <option value="{{ $findings->fldid }}">{{ $findings->fldcode }}</option>
                                    @endif @endforeach @endif
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="lines-tab" data-toggle="tab" href="#lines" role="tab"
                                   aria-controls="lines" aria-selected="false">lines/Tubes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="products-tab" data-toggle="tab" href="#products" role="tab"
                                   aria-controls="products" aria-selected="false">Blood Products/Isolation</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vital-tab" data-toggle="tab" href="#vital" role="tab"
                                   aria-controls="vital" aria-selected="false">Vital</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                   aria-controls="home" aria-selected="true">24hr input/output</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="gcs-tab" data-toggle="tab" href="#gcs" role="tab"
                                   aria-controls="gcs" aria-selected="true">GCS/ Pupils</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bollus-tab" data-toggle="tab" href="#bollus" role="tab"
                                   aria-controls="bollus" aria-selected="false">Bollus/Intravenous Fluid Plan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="routines-tab" data-toggle="tab" href="#routines" role="tab"
                                   aria-controls="routines" aria-selected="false">Routines & Safety/ Fall Prevention</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="mechanical-tab" data-toggle="tab" href="#mechanical" role="tab"
                                   aria-controls="mechanical" aria-selected="false"> Mechanical Ventilation & Oxygen
                                    Therapy (MV & OTR)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="progress-tab" data-toggle="tab" href="#progress" role="tab"
                                   aria-controls="progress" aria-selected="false">Progress Notes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="head-tab" data-toggle="tab" href="#head" role="tab"
                                   aria-controls="head" aria-selected="false">Head to Toe Assessment</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-2">
                            <div class="tab-pane fade show active" id="lines" role="tabpanel"
                                     aria-labelledby="lines-tab">
                                <form id="lines_form">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th width="11%">Types</th>
                                                <th>Pheripheral Line</th>
                                                <th width: 14%;>Artherial Catheter</th>
                                                <th>Central Line</th>
                                                <th>ETTube</th>
                                                <th width: 16%;>Tracheostomy Tube</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Insertion Date</td>
                                                <td><input type="text" class="form-control" placeholder="" name="peripheral_insertion_date" id="peripheral_insertion_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="artherial_insertion_date" id="artherial_insertion_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="central_insertion_date" id="central_insertion_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="ettube_insertion_date" id="ettube_insertion_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="tracheostomy_insertion_date" id="tracheostomy_insertion_date"/></td>
                                                <td>
                                                    <div class="form-row">
                                                        <label class="col-sm-5">Foley</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" placeholder="" name="foley_insertion_date" id="foley_insertion_date"/>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Insertion Date</td>
                                                <td><input type="text" class="form-control" placeholder=" " name="peripheral_incertion_date" id="peripheral_incertion_date"/></td>
                                                <td><input type="text" class="form-control" placeholder=" " name="artherial_incertion_date" id="artherial_incertion_date"/></td>
                                                <td><input type="text" class="form-control" placeholder=" " name="central_incertion_date" id="central_incertion_date"/></td>
                                                <td><input type="text" class="form-control" placeholder=" " name="ettube_incertion_date" id="ettube_incertion_date" /></td>
                                                <td><input type="text" class="form-control" placeholder="" name="tracheostomy_incertion_date" id="tracheostomy_incertion_date"/></td>
                                                <td>
                                                    <div class="form-row">
                                                        <label class="col-sm-5">NG/OG</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" placeholder="" name="ng_og" id="ng_og"/>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Changed Date</td>
                                                <td><input type="text" class="form-control" placeholder="" name="peripheral_changed_date" id="peripheral_changed_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="artherial_changed_date" id="artherial_changed_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="central_changed_date" id="central_changed_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="ettube_changed_date" id="ettube_changed_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="tracheostomy_changed_date" id="tracheostomy_changed_date"/></td>
                                                <td>
                                                    <div class="form-row">
                                                        <label class="col-sm-5">Chest Tube</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" placeholder="" name="chest_tube" id="chest_tube"/>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Removed Date</td>
                                                <td><input type="text" class="form-control" placeholder="" name="peripheral_removed_date" id="peripheral_removed_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="artherial_removed_date" id="artherial_removed_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="central_removed_date" id="central_removed_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="ettube_removed_date" id="ettube_removed_date"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="tracheostomy_removed_date" id="tracheostomy_removed_date"/></td>
                                                <td>
                                                    <div class="form-row">
                                                        <label class="col-sm-5">Others</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" placeholder="" name="line_others" id="line_others"/>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Duration</td>
                                                <td><input type="text" class="form-control" placeholder="" name="peripheral_duration" id="peripheral_duration"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="artherial_duration" id="artherial_duration"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="central_duration" id="central_duration"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="ettube_duration" id="ettube_duration"/></td>
                                                <td><input type="text" class="form-control" placeholder="" name="tracheostomy_duration" id="tracheostomy_duration"/></td>
                                                <td>
                                                    <div class="form-row">
                                                        <label class="col-sm-5">Duration</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" placeholder="" name="lines_duration" id="lines_duration"/>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group text-right">
                                        <button type="button" class="btn btn-primary btn-action" id="lines_save"><i class="fa fa-check"></i>&nbsp;Save
                                        </button>
                                    </div>
                                
                                </form>
                            </div>

                            <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                            
                            <form id="blood_form">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Amount Status</th>
                                            <th>Whole Blood</th>
                                            <th>PRP</th>
                                            <th>FFP</th>
                                            <th>RBC</th>
                                            <th>Platelet</th>
                                            <th>Others</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Total Blood Transfusion</td>
                                            <td><input type="text" class="form-control" placeholder="" name="whole_blood_transfusion"  id="whole_blood_transfusion"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="prp_transfusion"  id="prp_transfusion"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="ffp_transfusion"  id="ffp_transfusion"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="rbc_transfusion"  id="rbc_transfusion"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="platelet_transfusion"  id="platelet_transfusion"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="others_transfusion"  id="others_transfusion"/></td>
                                        </tr>
                                        <tr>
                                            <td width="20%">Total In Collection</td>
                                            <td><input type="text" class="form-control" placeholder="" name="whole_blood_collection"  id="whole_blood_collection"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="prp_collection"  id="prp_collection"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="ffp_collection"  id="ffp_collection"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="rbc_collection"  id="rbc_collection"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="platelet_collection"  id="platelet_collection"/></td>
                                            <td><input type="text" class="form-control" placeholder="" name="others_collection"  id="others_collection"/></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td rowspan="2">Isolation</td>
                                            <td>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" class="custom-control-input" id="isolation" name="isolation" value="yes"/>
                                                    <label class="custom-control-label" for="">Yes</label>
                                                </div>
                                            </td>
                                            <td rowspan="2">
                                                <div class="d-flex justify-content-between">
                                                    <label for="">If Yes,Isolation Type: </label>
                                                    <input type="checkbox" class="mt-2" id="isolation_type" value="yes"/>
                                                    <input type="text" class="form-control col-7" name="isolation_type" id="isolation_val"  style="display: none">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input" id="creatimine_clearence" name="creatimine_clearence" value="yes"/>
                                                        <label class="custom-control-label" for="">Creatimine
                                                            Clearence</label>
                                                    </div>
                                                    <span class="btm-icu"></span>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" class="custom-control-input" id="isolation_no" name="isolation" value="no"/>
                                                    <label class="custom-control-label" for="">No</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input" id="predicted_body_weight" name="predicted_body_weight" value="yes"/>
                                                        <label class="custom-control-label" for="">Predicted Body
                                                            Weight</label>
                                                    </div>
                                                    <span class="btm-icu"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">Code Status</td>
                                            <td>
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input" id="full_code" name="code_status_full_code" value="yes"/>
                                                    <label class="custom-control-label" for="">Full Code</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input" id="dnr" name="code_status_full_dnr" value="yes"/>
                                                    <label class="custom-control-label" for="">DNR</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="custom-control custom-control-inline">

                                                        <label for="code_status_other">Others</label> &nbsp; &nbsp;&nbsp;<input type="text" class="form-control" id="code_status_other" name="code_status_other" value=""/>
                                                    </div>
                                                    <span class="btm-icu"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group text-right">
                                    <button class="btn btn-primary btn-action" type="button" id="blood_product_save"><i class="fa fa-check"></i>&nbsp;Save
                                    </button>
                                </div>
                                
                            </form>
                            </div>
                            <div class="tab-pane fade" id="vital" role="tabpanel" aria-labelledby="vital-tab">
                            
                            <form id="vital_form">
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="">Temprature:</label>
                                            <div class="d-flex">
                                                <select id="temp" class="form-control" name="temp">
                                                    <option value="">--select--</option>
                                                    <option value="oral">Oral</option>
                                                    <option value="skin_axillary">SKin/axillary</option>
                                                    <option value="rectal">R+Rectal</option>
                                                </select>
                                                &nbsp;
                                                <input type="text" id="" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="">Heart Rate /Pulse:</label>
                                            <div class="">
                                                <input type="text" id="pulse" class="form-control" name="pulse"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="">Respiration/Min:</label>
                                            <div class="mt-1">
                                                <input type="text" id="respiratory" class="form-control" name="respiratory"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="d-flex">
                                                <label class="">Rhthym:</label>&nbsp;
                                                <select id="rhythm" class="form-control" name="rhythm">
                                                    <option value="">--select--</option>
                                                    <option value="regular">Regular</option>
                                                    <option value="irregular">Irregular</option>
                                                </select>
                                            </div>
                                            <div class="mt-1">
                                                <input type="number" step="0.01" min="0.01" id="temp_val" name="temp_val" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-sm-12 mb-1">
                                        <label class=""><strong>Pressure:</strong></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="">SBP:</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="sbp"  name="sbp" class="form-control input-blood-pressure"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="">DBP:</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="dbp"  name="dbp" class="form-control input-blood-pressure"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="">MAP:</label>
                                            <div class="">
                                                <input type="text" id="map" name="map" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="">CVP:</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="cvp" name="cvp" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="">ICP:</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="icp" name="icp" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mt-2">
                                        <div class="form-group">
                                            <label class="">Pulse Origin Saturation(spo2):</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="spo" name="spo" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mt-2">
                                        <div class="form-group">
                                            <label class="">Air Entry=Assculation(R/l):</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="air_entry" name="air_entry" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="">Tracked Suctioning:</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="tracheal_suctioning" name="tracheal_suctioning" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="">IV site check:</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="iv_site_check" name="iv_site_check" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="">Position(R/L/SC):</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="position" name="position" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="">RASS:</label>
                                            <div class="">
                                                <input type="number" step="0.01" min="0.01" id="rass" name="rass" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-sm btn-primary btn-action" id=""><i
                                                    class="fa fa-eye"></i>&nbsp;View
                                            </button>&nbsp;
                                            <button type="button" class="btn btn-sm btn-primary btn-action" id="vital_save"><i
                                                    class="fa fa-check"></i>&nbsp;Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>

                            </div>
                            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Intake in 24hr</th>
                                                    <th>Output in 24hr</th>
                                                    <th>Balnc (-ve/+ve)</th>
                                                    <th>ml/hrs</th>
                                                    <th>Balnc (-ve/+ve)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" placeholder="" name="intake" id="intake" readonly/>
                                                    </td>
                                                    <td><input type="text" class="form-control" placeholder="" name="output" id="output" readonly/>
                                                    </td>
                                                    <td><input type="text" class="form-control" placeholder="" name="balance" id="balance" readonly/>
                                                    </td>
                                                    <td><input type="text" class="form-control" placeholder="" name="mhrs" id="mhrs" readonly/>
                                                    </td>
                                                    <td><input type="text" class="form-control" placeholder="" name="second_balance" id="second_balance" readonly/>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="border" style="height: 512px;">
                                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                                <div class="iq-card-header d-flex justify-content-between">
                                                    <div class="iq-header-title">
                                                        <h4 class="card-title pl-2">Input (ML)</h4>
                                                    </div>
                                                </div>
                                                <div class="iq-card-body">
                                                    <div class="form-row form-group pl-1">
                                                        <b class="">Intake</b>&nbsp;&nbsp;&nbsp;
                                                        <a href="#collapseExample" class="btn btn-sm btn-primary collapsed mt-"
                                                        data-toggle="collapse" role="button" aria-expanded="false"
                                                        aria-controls="collapseExample"><i class="fa fa-plus pr-0"></i></a>
                                                    </div>
                                                    <div class="collapse" id="collapseExample" style="width: 100%;">
                                                        <table class="table table-bordered" style="width: 100%;">
                                                            <thead>
                                                            <tr>
                                                                <th>Start Date</th>
                                                                <th>Medicine</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if(isset($fluid_list) && $fluid_list) @forelse($fluid_list as $fluid)
                                                                <tr>
                                                                    <td>{{ $fluid->fldstarttime ?? null }}</td>
                                                                    <td>{{ $fluid->flditem ?? null }}</td>
                                                                    <td class="text-center">
                                                                        <a
                                                                                type="button "
                                                                                title="Start"
                                                                                class="btn check_btn prevent fluid_button"
                                                                                data-toggle="modal"
                                                                                data-id="{{ $fluid->fldid  }}"
                                                                                data-target="#fluidModal"
                                                                                id="fluid_start_btn"
                                                                                data-medicine="{{ $fluid->flditem }}"
                                                                                data-dose="{{ $fluid->flddose  }}"
                                                                                data-frequency=" {{ $fluid->fldfreq }}"
                                                                                data-days=" {{ $fluid->flddays }} "
                                                                                data-status=" {{ $fluid->fldstatus }} "
                                                                                data-start_time=" {{ $fluid->fldstarttime }}"
                                                                        >
                                                                            <i class="fas fa-play"></i>
                                                                        </a>
                                                                        <a type="button " class="btn check_btn prevent"
                                                                        style="display: none;" id="fluid_pause_btn" title="Pause">
                                                                            <i class="fas fa-pause"></i>
                                                                        </a>
                                                                        <a type="button " class="btn check_btn prevent"
                                                                        style="display: none;" id="fluid_stop_btn" title="Stop">
                                                                            <i class="fas fa-stop"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3">There is no fluid dispensed!!</td>
                                                                </tr>
                                                            @endforelse @else
                                                                <tr>
                                                                    <td colspan="3">There is no fluid dispensed!!</td>
                                                                </tr>
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>Particulars</th>
                                                                <th>Rate</th>
                                                                <th>Unit</th>
                                                                <th>Start</th>
                                                                <th>End</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="fluid_particulars_body">
                                                            @if(isset($fluid_particulars)) @forelse( $fluid_particulars as $particulars)
                                                                <tr>
                                                                    <td>{{ $particulars->getName->flditem ?? null }}</td>
                                                                    <td>{{ $particulars->fldvalue ?? null }}</td>
                                                                    <td>{{ $particulars->fldunit ?? null }}</td>
                                                                    <td>{{ $particulars->fldfromtime ?? null }}</td>
                                                                    <td>{{ $particulars->fldtotime ?? null }}</td>
                                                                    <td>
                                                                        @if( $particulars->fldstatus =='ongoing')
                                                                            <button type="button" class="fluid_stop_btn"
                                                                                    data-stop_id="{{ $particulars->fldid ?? null }}"
                                                                                    data-dose_no="{{ $particulars->flddose ?? null }}"><i
                                                                                        class="fas fa-stop"></i></button>
                                                                        @elseif( $particulars->fldstatus =='stopped')
                                                                            <button type="button"><i class="fas fa-lock"></i></button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @empty @endforelse @endif
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <hr>

                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">IV:</label>
                                                                <div class="">
                                                                    <input type="text" name="iv" id="iv"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Tube Feeding:</label>
                                                                <div class="">
                                                                    <input type="text" name="tube_feeding" id="tube_feeding"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Oral:</label>
                                                                <div class="">
                                                                    <input type="text" name="oral" id="oral"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Type Of Diet:</label>
                                                                <div class="">
                                                                    <input type="text" name="type_of_diet" id="type_of_diet"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Whole Blood/Packed Cell:</label>
                                                                <div class="">
                                                                    <input type="text" name="whole_blood_cell" id="whole_blood_cell"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Fresh Frozen Plasma
                                                                    (FFP):</label>
                                                                <div class="">
                                                                    <input type="text" name="fresh_frozen_plasma" id="fresh_frozen_plasma"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Platelets Rich Plasma:</label>
                                                                <div class="">
                                                                    <input type="text" name="platelets_reach_plasma" id="platelets_reach_plasma"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Albumin/Colloid:</label>
                                                                <div class="">
                                                                    <input type="text" name="albumin" id="albumin"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">TPN:</label>
                                                                <div class="">
                                                                    <input type="text" name="tpn" id="tpn"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Total:</label>
                                                                <div class="">
                                                                    <input type="text" name="intake_total" id="intake_total"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group text-right">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-primary btn-action" id="save_intake">
                                                                    <i class="fa fa-check"></i>&nbsp;Save
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <form id="output_form">
                                        <div class="border">
                                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                                <div class="iq-card-header d-flex justify-content-between">
                                                    <div class="iq-header-title">
                                                        <h4 class="card-title pl-2">Output (ml)</h4>
                                                    </div>
                                                </div>
                                                <div class="iq-card-body">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Urine:</label>
                                                                <div class="">
                                                                    <input type="number" name="urine" id="urine"
                                                                           class="form-control output" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Total:</label>
                                                                <div class="">
                                                                    <input type="number" name="urine_total" id="urine_total"
                                                                           class="form-control output" value="" readonly/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Gastic Tube:</label>
                                                                <div class="">
                                                                    <input type="number" name="gastic_tube" id="gastic_tube"
                                                                           class="form-control output" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">chest Tube:</label>
                                                                <div class="">
                                                                    <input type="number" name="otput_chest_tube" id="otput_chest_tube"
                                                                           class="form-control output" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Rectal Tube Stool:</label>
                                                                <div class="">
                                                                    <input type="number" name="rectal_tube" id="rectal_tube"
                                                                           class="form-control output" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Dialysis(Total Fluid
                                                                    Removed):</label>
                                                                <div class="">
                                                                    <input type="number" name="dialysis" id="dialysis"
                                                                           class="form-control output" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Vomitus:</label>
                                                                <div class="">
                                                                    <input type="number" name="vomits" id="vomits"
                                                                           class="form-control output" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Naso Gatsric/Oro Gastric:</label>
                                                                <div class="">
                                                                    <input type="number" name="naso_gastric" id="naso_gastric"
                                                                           class="form-control output" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Maelena:</label>
                                                                <div class="">
                                                                    <input type="number" name="maelena" id="maelena"
                                                                           class="form-control output" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="" class="">Others:</label>
                                                                <div class="">
                                                                    <input type="number" name="output_others" id="output_others"
                                                                           class="form-control output" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="" class="">Drain Tube:</label>
                                                                <div class="d-flex">
                                                                    <select id="drain" class="form-control" name="drain">
                                                                        <option value="">--select--</option>
                                                                        <option value="a">A</option>
                                                                        <option value="b">B</option>
                                                                        <option value="c">C</option>
                                                                        <option value="d">D</option>
                                                                    </select>
                                                                    &nbsp;
                                                                    <input type="text" name="drain_value" id="drain_value"
                                                                           class="form-control" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group text-right">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-primary btn-action" id="output_save">
                                                                    <i class="fa fa-check"></i>&nbsp;Save
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div></div>
                                        </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                            <div class="tab-pane fade" id="gcs" role="tabpanel" aria-labelledby="gcs-tab">
                                <form id="gcs_form">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label><strong>GCS</strong></label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="e" class="">Eye Open </label>
                                            <div class="">
                                                <select class="form-control gcs_class" id="gcs_e" name="e">
                                                    <option value="4" selected>Spontaneous</option>
                                                    <option value="3">To speech</option>
                                                    <option value="2">To pain</option>
                                                    <option value="1">None</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="e" class="">Best Verbal Response</label>
                                            <div class="">
                                                <select class="form-control gcs_class" id="gcs_v" name="v">
                                                    <option value="5" selected>Oriented</option>
                                                    <option value="4">Confused</option>
                                                    <option value="3">Words</option>
                                                    <option value="2">Sounds</option>
                                                    <option value="T" id="verbal_t">T</option>
                                                    <option value="none" id="verbal_none">None</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="e" class="">Best Motor Response</label>
                                            <div class="">
                                                <select class="form-control gcs_class" id="gcs_m" name="m">
                                                    <option value="6" selected>Obeys Command</option>
                                                    <option value="5">Localizes Pain</option>
                                                    <option value="4">Normal Flexion</option>
                                                    <option value="3">Abnormal Flexion</option>
                                                    <option value="2">Extension</option>
                                                    <option value="1">None</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="e" class="">Total</label>
                                            <div class="">
                                                <input type="text" step="0.01" min="0.01"
                                                       class="form-control gcs_class full-width" id="total_gcs"
                                                       placeholder="" name="total_gcs" autocomplete="off"
                                                       style="width: 80px;" readonly/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label><strong>Pupils</strong></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="" class="">Size</label>
                                            <div class="">
                                                <select name="left_side_size" class="form-control" autocomplete="off" id="left_side_size">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="" class="">Reaction</label>
                                            <div class="">
                                                <select class="form-control" name="left_side_reaction" id="left_side_reaction">
                                                    <option value="Normal Reaction">Normal Reaction</option>
                                                    <option value="No Reaction">No Reaction</option>
                                                    <option value="Sluggish Reaction">Sluggish Reaction</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
{{--<<<<<<< HEAD--}}
                                    <button type="button" class="btn btn-primary btn-action" id="save_gcs"><i class="fa fa-check"></i>&nbsp;Save
{{--=======--}}
                                    <button type="button" class="btn btn-sm btn-primary btn-action" id=""><i
                                            class="fa fa-eye"></i>&nbsp;View
                                    </button>&nbsp;
{{--                                    <button class="btn btn-primary btn-action" id="save_gcs"><i class="fa fa-check"></i>&nbsp;Save--}}
{{-->>>>>>> 56a388c84f9bdfb6e95f06925ceac551c6ad1555--}}
{{--                                    </button>--}}
                                </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="bollus" role="tabpanel" aria-labelledby="bollus-tab">
                                <form id="bollus_form">

                                <div class="form-group">
                                    <label><strong>Bollus</strong></label>
                                </div>
                                <div class="form-group">
                                    <table class="table table-reponsive table-bordered">
                                        <tbody id="" class="js-multi-bollus-tbody">
                                        <tr class="bollus-tr">
                                            <td width="35%">
                                                <div class="">
                                                    <select id="bollus_medicine_main" class="form-control medicine" name="bollus_medicine[]">
                                                        <option value="">--select--</option>
                                                        @if(isset($medicines))
                                                            @forelse($medicines as $medicine)
                                                                <option value="{{ $medicine->flditem ?? null }}"> {{ $medicine->flditem ?? null }}</option>
                                                            @empty
                                                                <option value="">--Not availlable--</option>
                                                            @endforelse
                                                        @endif
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input type="text" name="bollus_val[]" id="bollus_values " class="form-control answer" value=""/>
                                                </div>
                                            </td>
                                            <td class="text-center" width="10%">
                                                <div class="">
                                                    <button type="button" class="btn btn-primary btn-action bollus-add-btn"><i
                                                                class="fa fa-plus"></i>&nbsp;Add
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label><strong>Intravenous Fluid Plan</strong></label>
                                </div>
                                <div class="form-group">
                                    <table class="table table-reponsive table-bordered">
                                        <tbody class="multi-intravenous">

                                        <tr>
                                            <td>Intravenous Fluid</td>
                                            <td>Drug Added</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td width="35%">
                                                <div class="">
                                                    <input type="text" name="intravenous[]" id="intravenous_mian" class="form-control intravenous" value=""/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input type="text" name="intravenous_val[]" id="intravenous_val_main" class="form-control intravenous_val" value=""/>
                                                </div>
                                            </td>
                                            <td class="text-center" width="10%">
                                                <div class="">
                                                    <button  type="button" class="btn btn-primary btn-action multi-intravenous-btn"><i
                                                                class="fa fa-plus"></i>&nbsp;Add
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group text-right">
                                <button type="button" class="btn btn-sm btn-primary btn-action" id=""><i
                                            class="fa fa-eye"></i>&nbsp;View
                                    </button>&nbsp;
                                    <button class="btn btn-primary btn-action" id="bollus_save"><i class="fa fa-check"></i>&nbsp;Save
                                    </button>
                                </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="routines" role="tabpanel" aria-labelledby="routines-tab">
                                <div class="form-group">
                                    <label><strong>Routines and Safety</strong></label>
                                </div>
                                <div class="form-row form-group">
                                    <div class="col-sm-3">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="trachestomy_care"
                                                   name="trachestomy_care" value="yes"/>
                                            <label class="custom-control-label" for="">Tracheostomy Care</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input"  id="back_care" name="back_care"
                                                   value="yes"/>
                                            <label class="custom-control-label" for="">Back Care</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="bath" name="bath" value="yes"/>
                                            <label class="custom-control-label" for="">Bed Bath/Sponge Bath</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="oral_hygine"
                                                   name="oral_hygine" value="yes"/>
                                            <label class="custom-control-label" for="">Oral Hygiene</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="perimeal" name="perimeal" value="yes"/>
                                            <label class="custom-control-label" for="">Perimeal/Catheter Care</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="skin_care" name="skin_care"
                                                   value="yes"/>
                                            <label class="custom-control-label" for="">Skin Care</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="hair_wash_care"
                                                   name="hair_wash_care" value="yes"/>
                                            <label class="custom-control-label" for="">Hair Wash Care</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="activity" name="activy"
                                                   value="yes"/>
                                            <label class="custom-control-label" for="">Activity (Chair
                                                ,Ambulation)</label>
                                        </div>
                                    </div>
{{--                                    <div class="col-sm-3">--}}
{{--                                        <div class="custom-control custom-checkbox custom-control-inline">--}}
{{--                                            <input type="checkbox" class="custom-control-input" id=""/>--}}
{{--                                            <label class="custom-control-label" for="">Tracheostomy Care</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="form-group">
                                    <label><strong>Fall Prevention</strong></label>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-sm-3">
                                        <label class="" for="">Fall Standard in Use(Morse Scale)</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-control" id="fall_standard_day_night" name="fall_standard_day_night">
                                            <option value="">--select--</option>
                                            <option value="day">Day</option>
                                            <option value="night">Night</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="fall_standard_radio_yes" name="fall_standard_radio" value="yes"/>
                                            <label class="custom-control-label" for="fall_standard_day_night">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="fall_standard_radio_no" name="fall_standard_radio" value="no"/>
                                            <label class="custom-control-label" for="fall_standard_day_night">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="fall_standard_radio_na" name="fall_standard_radio" value="na"/>
                                            <label class="custom-control-label" for="fall_standard_day_night">NA</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-sm-3">
                                        <label class="" for="">Yellow card on Door</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-control" id="yellow_card" name="yellow_card">
                                            <option value="">--select--</option>
                                            <option value="day">Day</option>
                                            <option value="night">Night</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="yellow_card_radio_yes" name="yellow_card_radio" value="yes"/>
                                            <label class="custom-control-label" for="">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="yellow_card_radio_no" name="yellow_card_radio" value="no"/>
                                            <label class="custom-control-label" for="">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="yellow_card_radio_na" name="yellow_card_radio" value="na"/>
                                            <label class="custom-control-label" for="">NA</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-sm-3">
                                        <label class="" for="">Call Light in Reach</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-control" id="call_light" name="call_light">
                                            <option value="">--select--</option>
                                            <option value="day">Day</option>
                                            <option value="night">Night</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="call_light_radio_yes" name="call_light_radio" value="yes"/>
                                            <label class="custom-control-label" for="">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="call_light_radio_n" name="call_light_radio" value="no"/>
                                            <label class="custom-control-label" for="">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="call_light_radio_na" name="call_light_radio" value="na"/>
                                            <label class="custom-control-label" for="">NA</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-sm-3">
                                        <label class="" for="">Bed Love and Locked</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-control" id="bed_love_and_locked" name="bed_love_and_locked">
                                            <option value="">--select--</option>
                                            <option value="day">Day</option>
                                            <option value="night">Night</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="bed_love_and_locked_radio_yes" name="bed_love_and_locked_radio" value="yes"/>
                                            <label class="custom-control-label" for="">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="bed_love_and_locked_radio_no" name="bed_love_and_locked_radio" value="no"/>
                                            <label class="custom-control-label" for="">No</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="bed_love_and_locked_radio_na" name="bed_love_and_locked_radio" value="na"/>
                                            <label class="custom-control-label" for="">NA</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-sm-3">
                                            <label class="" for="">Bed Alaram in Use</label>
                                        </div>
                                        <div class="col-sm-2">
                                            <select class="form-control" id="bed_alarm" name="bed_alarm">
                                                <option value="">--select--</option>
                                                <option value="day">Day</option>
                                                <option value="night">Night</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="bed_alarm_radio_yes" name="bed_alarm_radio" value="yes"/>
                                                <label class="custom-control-label" for="">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="bed_alarm_radio_no" name="bed_alarm_radio" value="no"/>
                                                <label class="custom-control-label" for="">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="bed_alarm_radio_na" name="bed_alarm_radio" value="na"/>
                                                <label class="custom-control-label" for="">NA</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-sm-3">
                                            <label class="" for="">Side Rails Up</label>
                                        </div>
                                        <div class="col-sm-2">
                                            <select class="form-control" id="side_rails_up" name="side_rails_up">
                                                <option value="">--select--</option>
                                                <option value="day">Day</option>
                                                <option value="night">Night</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="side_rails_up_yes" name="side_rails_up_radio" value="yes"/>
                                                <label class="custom-control-label" for="">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="side_rails_up_no" name="side_rails_up_radio" value="no"/>
                                                <label class="custom-control-label" for="">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="side_rails_up_na" name="side_rails_up_radio" value="na"/>
                                                <label class="custom-control-label" for="">NA</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="" for="">Nurse Initial</label>
                                    <div class="">
                                        <textarea rows="10" class="form-control" id="nurse_initial"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button class="btn btn-primary btn-action" id="save_routine_safety"><i class="fa fa-check"></i>&nbsp;Save
                                    </button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mechanical" role="tabpanel" aria-labelledby="mechanical-tab">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label><strong>Mechanical Ventilation and oxygen therapy Record</strong></label>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Ventilation Therapy</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="ventilation_therapy"
                                                       id="ventilation_therapy" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Ventilator Mode and Adjustment Setting</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="ventilator_mode_and_adjustment"
                                                       id="ventilator_mode_and_adjustment" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Volume Control (VT)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="volume_control"
                                                       id="volume_control" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Pressure Control or Pressure Support (IPS/PS)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="pressure_control_and_support"
                                                       id="pressure_control_and_support" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Positive End Expirtatory Pressure(PEEP)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="positive_end_expirtatory"
                                                       id="positive_end_expirtatory" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Peak Inspiratory Airway Pressure(PIP)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="peak_inspiratory_airway_pressure"
                                                       id="peak_inspiratory_airway_pressure" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Mean Airway Pressure(MaW)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="mean_airway_pressure"
                                                       id="mean_airway_pressure" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Tidal Volume(VT)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="tidal_volume"
                                                       id="tidal_volume" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class=""> Respiratory Rate(RR)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="respiratory_rate_ventilator"
                                                       id="respiratory_rate_ventilator" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class=""> Expired Minute Ventilation(1/min)(VT)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="expired_minute_ventilation"
                                                       id="expired_minute_ventilation" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Inspiratory Time(s) or Flowrate
                                                (L/min)(TI/VI)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="inspiratory_rate_or_flowrate"
                                                       id="inspiratory_rate_or_flowrate" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Sensitivity: Inspiratory Time(s) or Flowrate</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="sensitivity" id="sensitivity" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="" class=""> Inspiratory Rise Time(%)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="inspiratory_rise_time"
                                                       id="inspiratory_rise_time" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="" class="">Flow By(L/min) or Waveform(S=Square,R=Ramp)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="flow_and_waveform"
                                                       id="flow_and_waveform" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Fracional Inspiratory Oxygen (FIO2)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="fractional_inspiratory_oxygen"
                                                       id="fractional_inspiratory_oxygen" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="" class="">Dr's Name</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="dr_name" id="dr_name" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="" class="">Time of Setting Change</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="time_of_setting_changed"
                                                       id="time_of_setting_changed" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Humidifier Temp(c)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="humidifier_temp"
                                                       id="humidifier_temp" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class=""> Endotracheal Tube Cuff Pressure</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="endotracheal_tube_cuff"
                                                       id="endotracheal_tube_cuff" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Oxygen Therapy</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="" id="" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Administrated by (prongs ,mask,etc)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="administered_by"
                                                       id="administered_by"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class=""> Flow Rate (L/min)</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="flow_rate" id="flow_rate" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Fractional Oxygen (%) FL02</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="fractional_oxygen"
                                                       id="fractional_oxygen" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="" class="">Oxygen Equipment or circuit change</label>
                                            <div class="">
                                                <input type="text" class="form-control" name="oxygen_equipment_circuit_change" id="oxygen_equipment_circuit_change" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                <button type="button" class="btn btn-sm btn-primary btn-action" id=""><i
                                            class="fa fa-eye"></i>&nbsp;View
                                    </button>&nbsp;
                                    <button class="btn btn-primary btn-action" id="ventilator_save" type="button"><i class="fa fa-check"></i>&nbsp;Save
                                    </button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                                <form action="" class="form-horizontal">
                                    <div class="form-group form-row align-items-center">
                                        <label class="pl-2"> Category</label>
                                        <div class="col-sm-5">
                                            <select name="duration_type" id="note_list_select"
                                                    class="form-control presentType note__field_item">
                                                <option disabled="disabled">Select...</option>
                                                <option value="Progress Note">Progress Note</option>
                                                <option value="Clinicians Note">Clinicians Note</option>
                                                <option value="Nurses Note">Nurses Note</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-sm-in btn-warning" type="button" data-toggle="modal"
                                                    data-target="#refere__to" title="Refere Patient">
                                                <i class="fa fa-retweet"></i>
                                            </button>
                                            <button class="btn btn-sm-in btn-info" id="insert__notes"
                                                    url="{{ route('icu.insert.note') }}"
                                                    type="button"><i class="fa fa-plus"></i>&nbsp;Add
                                            </button>
                                            <button class="btn btn-sm-in btn-primary" id="update__notes"
                                                    url="{{ route('icu.update.note') }}"
                                                    type="button">
                                                <i class="ri-edit-fill pr-0"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="form-group">
                                    <textarea name="notes_field" id="notes_field" class="form-control textarea-notes"
                                              spellcheck="false"></textarea>
                                </div>
                                <div class="form-group form-row">
                                    <label class="pl-2">Impression</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control label-notes note__fldreportquali"
                                               value=""/>
                                        <input type="hidden" class="notes_fldtime" value=""/>
                                        <input type="hidden" class="note__field_id" value=""/>
                                    </div>
                                    <div class="col-sm-2">
                                    <button type="button" class="btn btn-sm btn-primary btn-action" id=""><i
                                            class="fa fa-eye"></i>&nbsp;View
                                    </button>&nbsp;
                                        <button class="btn btn-primary btn-action"><i class="fa fa-check"></i>&nbsp;Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="head" role="tabpanel" aria-labelledby="head-tab">
                                <form id="assessment_form">
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div id="accordion">
                                        <div class="card">
                                            <div class="card-header card-header-icu" id="headingOne">
                                                <h5 class="mb-0">
                                                    <button class="btn text-primary font-icu" data-toggle="collapse"
                                                            data-target="#collapseOne" aria-expanded="true"
                                                            aria-controls="collapseOne">
                                                        Neurological Safety &nbsp;<i class="fas fa-angle-down"></i>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="" class="">Gag</label>
                                                                <div class="">
                                                                    <select name="gag" class="form-control" id="gag">
                                                                        >
                                                                        <option value="present">Present</option>
                                                                        <option value="absent">Absent</option>
                                                                        <option value="weak">Weak</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="" class="">Cough</label>
                                                                <div class="">
                                                                    <select name="cough" class="form-control" id="cough">
                                                                        >
                                                                        <option value="present">Present</option>
                                                                        <option value="absent">Absent</option>
                                                                        <option value="weak">Weak</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="" class="">Pain</label>
                                                                <div class="">
                                                                    <select name="pain" class="form-control" id="pain">
                                                                        >
                                                                        <option value="yes">Yes</option>
                                                                        <option value="no">No</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="" class="">Intensity (0-10)</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" id="intensitity"
                                                                           name="intensitity" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="" class="">Location/Duration</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" id="location"
                                                                           name="location" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="" class="">Safety:Morse Score</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" id="morse_score"
                                                                           name="morse_score" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="" class=""><strong>Patient Receiving:See
                                                                        Nursing Flowsheet</strong></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   id="sedation" checked="" name="sedation" value="yes"/>
                                                                            <label class="custom-control-label" for="sedation">
                                                                                Sedation</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-5 pr-0">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="narcotic_analgesia" id="narcotic_analgesia" value="yes"/>
                                                                            <label class="custom-control-label" for="narcotic_analgesia">
                                                                                Narcotic Analgesia</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-control-inline">
                                                                            <label class="" for="rass_secure">
                                                                                RAAS Secure</label> &nbsp;<input type="text"
                                                                                   class="form-control"
                                                                                   name="rass_secure" id="rass_secure" value=""/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="po" id="po" value="yes"/>
                                                                            <label class="custom-control-label" for="po">
                                                                                PO</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="pca" id="pca" value="yes"/>
                                                                            <label class="custom-control-label" for="pca">
                                                                                PCA</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="pcea"
                                                                                   id="pcea"
                                                                                   value="yes"/>
                                                                            <label class="custom-control-label" for="pcea">
                                                                                PCEA</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="iv" id="iv"
                                                                                   value="yes"/>
                                                                            <label class="custom-control-label" for="iv">
                                                                                IV</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="dermal"
                                                                                   id="dermal"
                                                                                   value="yes"/>
                                                                            <label class="custom-control-label" for="dermal">
                                                                                Dermal</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="bed_rails" id="bed_rails" value="yes"/>
                                                                            <label class="custom-control-label" for="bed_rails">
                                                                                Bedrails*4</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 pr-0">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="hob"
                                                                                   id="hob"
                                                                                   value="yes"/>
                                                                            <label class="custom-control-label" for="hob">
                                                                                HOB &gt;30</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="cell_bell_in_reach" id="cell_bell_in_reach"
                                                                                   value="yes"/>
                                                                            <label class="custom-control-label" for="cell_bell_in_reach">
                                                                                Cell Bell In Reach</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="monitor_alarm_set"
                                                                                   id="monitor_alarm_set" value="yes"/>
                                                                            <label class="custom-control-label" for="">Monitor
                                                                                Alarm Set and Audible</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="glasses" id="glasses" value="yes"/>
                                                                            <label class="custom-control-label" for="glasses">
                                                                                Glasses</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="pt_wearinf_id" id="pt_wearinf_id" value="yes"/>
                                                                            <label class="custom-control-label" for="pt_wearinf_id">
                                                                                PT wearing ID Band</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="physical_restrain"
                                                                                   id="physical_restrain" value="yes"/>
                                                                            <label class="custom-control-label" for="physical_restrain">Physical
                                                                                Restrain Type</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-7">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="allergy_band"
                                                                                   id="allergy_band" value="yes"/>
                                                                            <label class="custom-control-label" for="allergy_band">Allergy
                                                                                Band(if Applicable)</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                            <input type="checkbox"
                                                                                   class="custom-control-input bg-primary"
                                                                                   name="physiian_order_for_restain"
                                                                                   id="physiian_order_for_restain" value="yes"/>
                                                                            <label class="custom-control-label" for="">Physician
                                                                                Order for Restrain</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header card-header-icu" id="headingTwo">
                                                <h5 class="mb-0">
                                                    <button class="btn text-primary font-icu collapsed"
                                                            data-toggle="collapse" data-target="#collapseTwo"
                                                            aria-expanded="false" aria-controls="collapseTwo">
                                                        Respiratory
                                                     &nbsp;<i class="fas fa-angle-down"></i></button>
                                                </h5>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Airways</label>
                                                                <div class="">
                                                                    <select name="airway" id="airway"
                                                                            class="form-control">
                                                                        <option value="patent">Patent</option>
                                                                        <option value="oral">Oral Airway</option>
                                                                        <option value="nasal">Nasal Airway</option>
                                                                        <option value="trachestomy">Trachestomy</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Breathing</label>
                                                                <div class="">
                                                                    <select name="breathing" id="breathing"
                                                                            class="form-control">
                                                                        <option value="sponetaneosly">Spontaneously
                                                                        </option>
                                                                        <option value="regular_rhythm">Regular Rhythm
                                                                        </option>
                                                                        <option value="irregular_rhythm">Irregular
                                                                            Rhythm
                                                                        </option>
                                                                        <option value="apnetic">Apnetic</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Quality/Depath</label>
                                                                <div class="">
                                                                    <select name="quality" id="quality"
                                                                            class="form-control">
                                                                        <option value="normal">Normal</option>
                                                                        <option value="shallow">Shallow</option>
                                                                        <option value="laboured">Laboured</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="" class=""><strong>Oxygenation and
                                                                        ventilation therapy</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Air Entry</label>
                                                                <div class="">
                                                                    <select name="oxygenation_air_entry" id="oxygenation_air_entry"
                                                                            class="form-control">
                                                                        <option value="equal_bilaterally">Equal
                                                                            Bilaterally
                                                                        </option>
                                                                        <option value="dimineshed">Diminished</option>
                                                                        <option value="laboured">Laboured</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Breath Sounds</label>
                                                                <div class="">
                                                                    <select name="quality" id="quality"
                                                                            class="form-control">
                                                                        <option value="normal">Normal</option>
                                                                        <option value="shallow">Shallow</option>
                                                                        <option value="laboured">Laboured</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Chest tube(s)</label>
                                                                <div class="">
                                                                    <select name="chest_tube" id="chest_tube" class="form-control">
                                                                        <option value="ltx">LTX</option>
                                                                        <option value="rtx">RTX</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Date Inserted</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" name="ltx_date_inserted"
                                                                           id="ltx_date_inserted" value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="suction" id="suction" value="yes"/>
                                                                    <label class="custom-control-label" for="">Suction-20
                                                                        cm H2O</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="drainage" id="drainage" value="yes"/>
                                                                    <label class="custom-control-label" for="">Drainage
                                                                        Describe</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="secretion" id="secretion" value="yes"/>
                                                                    <label class="custom-control-label"
                                                                           for="">Secretion</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="chest_tube_other"
                                                                           id="chest_tube_other" value="yes"/>
                                                                    <label class="custom-control-label"
                                                                           for="chest_tube_other">Others</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Chest Expansion</label>
                                                                <div class="">
                                                                    <select name="chest_expansion" id="chest_expansion"
                                                                            class="form-control">
                                                                        <option value="symmetrical">Symmetrical</option>
                                                                        <option value="assymmetrical">Asymmetrical
                                                                        </option>
                                                                        <option value="accessory_muscle">Accessory
                                                                            Muscle Use
                                                                        </option>
                                                                        <option value="subcateneous_emphysema">
                                                                            Subcateneous emphysema
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Location</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control"
                                                                           name="chest_expansion_location"
                                                                           id="chest_expansion_location"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header card-header-icu" id="headingThree">
                                                <h5 class="mb-0">
                                                    <button class="btn text-primary font-icu collapsed"
                                                            data-toggle="collapse" data-target="#collapseThree"
                                                            aria-expanded="false" aria-controls="collapseThree">
                                                        Cardioivascular
                                                     &nbsp;<i class="fas fa-angle-down"></i></button>
                                                </h5>
                                            </div>
                                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Monitor Lead</label>
                                                                <div class="">
                                                                    <select class="form-control" name="monitor_lead"
                                                                            id="monitor_lead">
                                                                        <option value="ectopic">Ectopic</option>
                                                                        <option value="pvc_rate">PVC Rate</option>
                                                                        <option value="monitor_alarm_checked">Monitor
                                                                            Alarms checked
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 pr-0">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="central_line_type" id="central_line_type" value="yes"/>
                                                                    <label class="custom-control-label" for="">Central
                                                                        Line Type S/D/T/O</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Location</label>
                                                                <div class="">
                                                                    <input type="text" name="cardiovascular_location"
                                                                           id="cardiovascular_location"
                                                                           class="form-control"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">DrSG Date</label>
                                                                <div class="">
                                                                    <input type="text" name="cardio_drsg_date"
                                                                           id="cardio_drsg_date"
                                                                           class="form-control nepaliDatePicker"
                                                                           value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Tubing Date</label>
                                                                <div class="">
                                                                    <input type="text" name="tubing_date"
                                                                           id="tubing_date"
                                                                           class="form-control nepaliDatePicker"
                                                                           value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Insertion Date</label>
                                                                <div class="">
                                                                    <input type="text" name="cardio_insertion_date"
                                                                           id="cardio_insertion_date"
                                                                           class="form-control nepaliDatePicker"
                                                                           value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Site</label>
                                                                <div class="">
                                                                    <select name="site" id="site" class="form-control">
                                                                        <option value="d_and_i">D & I</option>
                                                                        <option value="reddend">Reddend</option>
                                                                        <option value="inflamed_swallen">Inflammed
                                                                            Swallen
                                                                        </option>
                                                                        <option value="drainage">Drainage</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Capillary Refill</label>
                                                                <div class="">
                                                                    <select name="capillary_refill"
                                                                            id="capillary_refill" class="form-control">
                                                                        <option value="within_three_second">With in
                                                                            three second
                                                                        </option>
                                                                        <option value="sluggis">Sluggis</option>
                                                                        <option value="absent">Absent</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Muccus Membrance</label>
                                                                <div class="">
                                                                    <select name="muccus_membrance"
                                                                            id="muccus_membrance" class="form-control">
                                                                        <option value="moist">Moist</option>
                                                                        <option value="dry">Dry</option>
                                                                        <option value="cyanotic">Cyanotic</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="art_line_zerod"
                                                                           id="art_line_zerod" value="yes"/>
                                                                    <label class="custom-control-label" for="">Art Line
                                                                        Zerod</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="piv_location"
                                                                           id="piv_location" value="yes"/>
                                                                    <label class="custom-control-label" for="">PIV
                                                                        Location</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 pr-0">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="central_line_type" id="central_line_type" value="yes"/>
                                                                    <label class="custom-control-label" for="">Central
                                                                        Line Type S/D/T/O</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Date insertion</label>
                                                                <div class="">
                                                                    <input type="text"
                                                                           class="form-control nepaliDatePicker"
                                                                           name="piv_date_insertion"
                                                                           id="piv_date_insertion"
                                                                           value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Art Line site</label>
                                                                <div class="">
                                                                    <select name="art_line_site" id="art_line_site"
                                                                            class="form-control">
                                                                        <option value="d_and_i">D & I</option>
                                                                        <option value="reddend">Reddend</option>
                                                                        <option value="inflamed_swallen">Inflammed
                                                                            Swallen
                                                                        </option>
                                                                        <option value="drainage">Drainage</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Dressing Date</label>
                                                                <div class="">
                                                                    <input type="text"
                                                                           class="form-control nepaliDatePicker"
                                                                           name="dressing_date" id="dressing_date"
                                                                           value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Location</label>
                                                                <div class="">
                                                                    <select type="text" class="form-control"
                                                                            name="artline_location"
                                                                            id="artline_location">
                                                                        <option value="radial">Radial</option>
                                                                        <option value="bracheal">Bracheal</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Artline insertion date</label>
                                                                <div class="">
                                                                    <input type="text"
                                                                           class="form-control nepaliDatePicker"
                                                                           name="artline_insertion_date"
                                                                           id="artline_insertion_date"
                                                                           value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Others</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control"
                                                                           name="artline_other" id="artline_other"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Skin</label>
                                                                <div class="">
                                                                    <select name="skin" id="skin" class="form-control">
                                                                        <option value="warm">Warm</option>
                                                                        <option value="dry">Dry</option>
                                                                        <option value="good_turgor">Good Turgor</option>
                                                                        <option value="cool">Cool</option>
                                                                        <option value="cammy">Cammy</option>
                                                                        <option value="poor_turgor">Poor Turgor</option>
                                                                        <option value="cold">Cold</option>
                                                                        <option value="diaphoretic">Diaphoretic</option>
                                                                        <option value="color">Color</option>
                                                                        <option value="ns_flush">NS Flush</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header card-header-icu" id="headingGastro">
                                                <h5 class="mb-0">
                                                    <button class="btn text-primary font-icu collapsed"
                                                            data-toggle="collapse" data-target="#collapseGastro"
                                                            aria-expanded="false" aria-controls="collapseGastro">
                                                        Gastrointestinal Genitourinary
                                                     &nbsp;<i class="fas fa-angle-down"></i></button>
                                                </h5>
                                            </div>
                                            <div id="collapseGastro" class="collapse" aria-labelledby="headingGastro"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Abdomen</label>
                                                                <div class="">
                                                                    <select class="form-control" name="abdomen"
                                                                            id="abdomen">
                                                                        <option value="soft">Soft</option>
                                                                        <option value="flat">Flat</option>
                                                                        <option value="firm">Firm</option>
                                                                        <option value="obese">Obese</option>
                                                                        <option value="tender">Tender</option>
                                                                        <option value="distended">Distended</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Bowel Sounds</label>
                                                                <div class="">
                                                                    <select class="form-control" name="bowel_sound"
                                                                            id="bowel_sound">
                                                                        <option value="present">Present</option>
                                                                        <option value="absent">Absent</option>
                                                                        <option value="last_bm">Last BM</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Gastric Drainage</label>
                                                                <div class="">
                                                                    <select class="form-control" name="gastric_drainage"
                                                                            id="gastric_drainage">
                                                                        <option value="low_scution">Low Suction</option>
                                                                        <option value="straight_drain">Straight Drain
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Nutrition</label>
                                                                <div class="">
                                                                    <select class="form-control" name="nutrition"
                                                                            id="nutrition">
                                                                        <option value="nutrition_npo">NPO</option>
                                                                        <option value="nutrition_po">PO</option>
                                                                        <option value="nutrition_tpn">TPN</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Diet</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control"
                                                                           name="nutrition_diet" id="nutrition_diet"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Rate</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control"
                                                                           name="nutrition_rate" id="nutrition_rate"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">External Access</label>
                                                                <div class="">
                                                                    <select class="form-control" name="external_access"
                                                                            id="external_access">
                                                                        <option value="og">OG</option>
                                                                        <option value="ng">NG</option>
                                                                        <option value="rl">RL</option>
                                                                        <option value="g_gi">G/GI</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Date Inserted</label>
                                                                <div class="">
                                                                    <input type="text"
                                                                           class="form-control nepaliDatePicker"
                                                                           name="external_access_date_inserted"
                                                                           id="external_access_date_inserted"
                                                                           value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Status</label>
                                                                <div class="">
                                                                    <select class="form-control" name="status"
                                                                            id="status">
                                                                        <option value="nausa">Nausea</option>
                                                                        <option value="diarrhea">Diarrhea</option>
                                                                        <option value="posititon_verified">Position
                                                                            Verified
                                                                        </option>
                                                                        <option value="vomitting">Vomitting</option>
                                                                        <option value="guarding">Guarding</option>
                                                                        <option value="ostomy">ostomy</option>
                                                                        <option value="flatus">Flatus</option>
                                                                        <option value="rectal">Rectal/Tube</option>
                                                                        <option value="aspiration">Aspiration</option>
                                                                        <option value="ausculation">Auscultation
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Type</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control"
                                                                           name="geni_type" id="geni_type"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Voiding</label>
                                                                <div class="">
                                                                    <select class="form-control" name="voiding"
                                                                            id="voiding">
                                                                        <option value="incontinent">Incontinent</option>
                                                                        <option value="urinal">Urinal</option>
                                                                        <option value="other">Other</option>
                                                                        <option value="display_mode">Display Mode
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Foli catheter situ</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control"
                                                                           name="foli_cather_situ"
                                                                           id="foli_cather_situ"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Date Inserted</label>
                                                                <div class="">
                                                                    <input type="text"
                                                                           class="form-control nepaliDatePicker"
                                                                           name="voiding_date_inserted"
                                                                           id="voiding_date_inserted"
                                                                           value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Description of Urine</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control"
                                                                           name="description_of_urine"
                                                                           id="description_of_urine"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mt-4">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           id="incontinent" value="yes"/>
                                                                    <label class="custom-control-label" for="incontinent">Incontinent</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           id="urinal" value="yes"/>
                                                                    <label class="custom-control-label"
                                                                           for="urinal">Urinal</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           id="voiding_others" value="yes"/>
                                                                    <label class="custom-control-label"
                                                                           for="voiding_others">Others</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           id="voiding_dialysis" value="yes"/>
                                                                    <label class="custom-control-label" for="voiding_dialysis">Dialysis
                                                                        mode</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header card-header-icu" id="headingFive">
                                                <h5 class="mb-0">
                                                    <button class="btn text-primary font-icu collapsed"
                                                            data-toggle="collapse" data-target="#collapseFive"
                                                            aria-expanded="false" aria-controls="collapseFive">
                                                        Skin Care
                                                     &nbsp;<i class="fas fa-angle-down"></i></button>
                                                </h5>
                                            </div>
                                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="skin_intact" value="yes"
                                                                           id="skin_intact"/>
                                                                    <label class="custom-control-label" for="skin_intact">Skin
                                                                        Intact</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="" class="">Stage</label>
                                                                <div class="">
                                                                    <select name="stage" id="stage"
                                                                            class="form-control">
                                                                        <option value="1">I</option>
                                                                        <option value="2">II</option>
                                                                        <option value="3">III</option>
                                                                        <option value="4">IV</option>
                                                                        <option value="5">V</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="braden_score" class="">Braden Score</label>
                                                                <div class="">
                                                                    <input type="text" name="braden_score"
                                                                           id="braden_score" class="form-control"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="drsg_date" class="">DrSG Date</label>
                                                                <div class="">
                                                                    <input type="text" name="drsg_date" id="drsg_date"
                                                                           class="form-control nepaliDatePicker"
                                                                           value="{{ isset($date) ? $date :'' }}"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="therapiutic_surface" class="">Therapiutic Surface</label>
                                                                <div class="">
                                                                    <input type="text" name="therapiutic_surface"
                                                                           id="therapiutic_surface"
                                                                           class="form-control"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="skin_care_intensity" class="">Intensity</label>
                                                                <div class="">
                                                                    <input type="text" name="skin_care_intensity"
                                                                           id="skin_care_intensity"
                                                                           class="form-control"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="compromised_skin_integrity"
                                                                           id="compromised_skin_integrity" value="yes"/>
                                                                    <label class="custom-control-label" for="compromised_skin_integrity">Compromised
                                                                        Skin</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="coccyx"
                                                                           id="coccyx" value="yes"/>
                                                                    <label class="custom-control-label"
                                                                           for="coccyx">Coccyx</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="secrum"
                                                                           id="secrum" value="yes"/>
                                                                    <label class="custom-control-label"
                                                                           for="secrum">Secrum</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="heel"
                                                                           id="heel" value="yes"/>
                                                                    <label class="custom-control-label"
                                                                           for="heel">Heel</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="elbow"
                                                                           id="elbow" value="yes"/>
                                                                    <label class="custom-control-label"
                                                                           for="elbow">Elbow</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="occipital" id="occipital" value="yes"/>
                                                                    <label class="custom-control-label"
                                                                           for="occipital">Occipital</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input bg-primary"
                                                                           name="vac_pressure"
                                                                           id="vac_pressure" value="yes"/>
                                                                    <label class="custom-control-label" for="vac_pressure">Vac
                                                                        Presuure</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="modal fade" id="fluidModal">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="fluid_title"></h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Start Date</th>
                                                        <th>Medicine</th>
                                                        <th>Dose</th>
                                                        <th>Frequency</th>
                                                        <th>Days</th>
                                                        <th>Status</th>
                                                        {{--
                                                        <th>Action</th>
                                                        --}}
                                                    </tr>
                                                    </thead>
                                                    <tbody id="fluid_table_body"></tbody>
                                                    {{--
                                                    <tr>
                                                    --}} {{--
                                <td>--}} {{-- <input type="text" class="form-control" --}} {{-- placeholder="" />--}} {{--</td>
                                --}} {{--
                                <td>--}} {{-- <label for="">ml/Hr</label>--}} {{--</td>
                                --}} {{--
                                </tr>
                                --}}
                                                </table>
                                                <table>
                                                    <tr>
                                                        <td><label>Enter rate of Administration in ML/Hour: </label></td>
                                                        <td><input type="text" class="form-control" id="fluid_dose"/></td>
                                                        <td><label id="empty_dose_alert" style="color: red;"></label></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" id="fluid_modal_save_btn">Save</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-right mt-2">
                                    <button class="btn btn-primary btn-action" id="head_assestment_save"><i class="fa fa-check"></i>&nbsp;Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('icu::dynamic-views.allergic-drugs')
    @include('icu::dynamic-views.diagnosis')
    @include('icu::dynamic-views.final-diagnosis')

    <!-- Footer -->
    {{-- @include('frontend.layouts.footer')--}} @endsection

@push('after-script')
    <script src="{{ asset('js/icu_general.js')}}"></script>
    <script>
        $(document).on('click', '.dccat', function () {
            // alert('click +bhayo');
            $('input[name="dccat"]').bind('click', function () {
                $('input[name="dccat"]').not(this).prop("checked", false);
            });
            var diagnocode = $("input[name='dccat']");
            $('.code').val($(this).val());
            if (diagnocode.is(':checked')) {
                diagnocode = $(this).val() + ",";
                diagnocode = diagnocode.slice(0, -1);
                $("input[name='dccat']").attr('checked', false);
                if (diagnocode.length > 0) {
                    // alert(diagnocode);
                    $.get("{{ route('icu.general.getDiagnosisByCodes') }}", {term: diagnocode}).done(function (data) {
                        // Display the returned data in browser
                        $(".sublist").html(data);
                    });
                }
            } else {
                $(".sublist").html('');
            }
        });

        /**
         * This fucntion is used for adding drugs
         */
        var add_drug_route = "{{ route('icu.store.drug') }}";
        $("#save_drug").click(function (e) {
            var status = true;
            $(".drug_checkbox").each(function (index, obj) {
                if (this.checked === true) {
                    var sib = $(this).parent().prev().find(".quantity").val();
                    if (sib == undefined || sib == "") {
                        $(this).parent().prev().find(".quantity").focus();
                        $("#drug_status_message").empty().text("Please enter quantity to record").css("color", "red");
                        status = false;
                        return false;
                    }
                }
            });
            var drugs = $(":checkbox:checked")
                .map(function () {
                    var id = $(this).data("id");
                    var name = $(this).data("name");
                    var quantity = $(this).parent().prev().find(".quantity").val();
                    return {
                        id: id,
                        name: name,
                        quantity: quantity,
                    };
                })
                .get();

            if (drugs.length <= 0) {
                $("#drug_status_message").empty().text("Please select drug to record").css("color", "red");
                status = false;
                return false;
            }

            if (status) {
                $("#drug_status_message").empty();
                $.ajax({
                    url: add_drug_route,
                    method: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        drug: drugs,
                        encounter: $("#encounter_no").val(),
                    },
                    success: function (data) {
                        $("#drug_status_message").empty().text("Drug Recorded Successfully.").css("color", "green");
                        $("input:checkbox").attr("checked", false);
                        $(".quantity").val("");
                        // $('.quantity').each(function (index, obj){
                        //     $('.quantity').empty();
                        // });
                    },
                    error: function (data) {
                        $("#drug_status_message").empty().text("Cannot record now something went wrong.").css("color", "red");
                        $("input:checkbox").attr("checked", false);
                    },
                });
            } else {
                return false;
            }
        });


        /**
         * Actions on save button and plotting data to particulars table
         */
        $(document).on("click", "#fluid_modal_save_btn", function () {
            if ($("#fluid_dose").val() == "") {
                $("#empty_dose_alert").text("Please end dose");
                $("#fluid_dose").focus();
            } else {
                var add_fluid_route = "{{ route('icu.store.drug') }}";
                var id = $(".fluid_button").data("id");
                var value = $("#fluid_dose").val();
                var data_val = $("#fluid_table_body").find("input").attr("data-val");
                // return false;
                $.ajax({
                    url: add_fluid_route,
                    method: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        type: "fluid",
                        status: "ongoing",
                        value: value,
                        encounter: $("#encounter_no").val(),
                    },
                    success: function (data) {
                        var particular_html = "";

                        var endtime = data.data.fldtotime ? data.data.fldtotime : "&nbsp;";
                        var name = data.data.name ? data.data.name : "&nbsp;";
                        particular_html += '<tr class="to_remove">';
                        particular_html += "<td>" + name + "</td>";
                        particular_html += "<td>" + data.data.fldvalue + "</td>";
                        particular_html += "<td>" + data.data.fldunit + "</td>";
                        particular_html += "<td>" + data.data.fldfromtime + "</td>";
                        particular_html += '<td class="endtime_js">' + endtime + "</td>";
                        particular_html += '<td><button type="button" class="fluid_stop_btn" data-stop_id = " ' + data.data.fldid + '" data-dose_no = "' + data.data.flddoseno + '"> <i class="fas fa-stop"></i></button></td>';
                        particular_html += "</tr>";
                        $("#fluid_particulars_body").append(particular_html);
                        $("#fluid_dose").val("");
                        $("#fluidModal").modal("toggle");
                        $("[data-id=" + data_val + "]").hide();
                    },
                    error: function (data) {
                        $("#drug_status_message").empty().text("Cannot Record now something went wrong.").css("color", "red");
                    },
                });
            }
        });

        /**
         * Actions on stop button
         */
        $(document).on("click", ".fluid_stop_btn", function () {
            var tr_elem = $(this).closest("tr");
            var stop_fluid_route = "{{ route('icu.stop.fluid') }}";
            var id = $(this).data("stop_id");
            var dose_no = $(this).data("dose_no");
            $.ajax({
                url: stop_fluid_route,
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    dose_no: dose_no,
                    encounter: $("#encounter_no").val(),
                },
                success: function (data) {
                    $(tr_elem).find(".endtime_js").text(data.data.fldtotime);
                    var btn_elem = $(tr_elem).find("button.fluid_stop_btn");

                    $(btn_elem).attr("class", "");
                    $(btn_elem).find("i").attr("class", "fas fa-lock");

                    $(this).closest(".to_remove").remove();
                    return false;
                    $(elem).remove();
                    var particular_html = "";
                    var endtime = data.data.fldtotime ? data.data.fldtotime : "&nbsp;";
                    particular_html += "<td>" + endtime + "</td>";
                    particular_html += '<td><button type="button"><i class="fas fa-lock"></i></button></td>';
                    $("#fluid_particulars_body").append(particular_html);
                    $("#fluid_dose").val("");
                },
                error: function (data) {
                    $("#drug_status_message").empty().text("Cannot Record now something went wrong.").css("color", "red");
                },
            });
        });

        $('#closesearchgroups').on('click', function () {
            $('#diagnogroup').val('');
            $.get("{{ route('icu.general.getInitialDiagnosisCategoryAjaxs') }}", {term: ''}).done(function (data) {
                // Display the returned data in browser
                $("#diagnosiscat").html(data);
            });
        });

        $('#final_diagnosis').click(function () {
            $('#change_action_value').attr('action', '{{route("icu.general.finalDiagnosisStore")}}');
        });

        $('#pro_diagnosis').click(function () {
            $('#change_action_value').attr('action', '{{route("icu.general.diagnosisStore")}}');
        });

        var neuroForm = {
            getDiagnosisList: function () {
                var url_dignosis_list = "{{ route('icu.diagnosis-list') }}";
                $.ajax({
                    url: url_dignosis_list,
                    method: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    beforeSend: function () {
                        $("#diagnosis_table").html("");
                        var html = '<tr><td colspan="2" class="text-center">' + "Please Wait....." + "</td></tr>";
                        $("#diagnosis_table").append(html);
                    },
                    success: function (data) {
                        var html_sub_diagnosis_list = '<tr><td colspan="2" class="text-center">' + "Please Wait....." + "</td></tr>";
                        if (data.status == "success") {
                            html_sub_diagnosis_list = "";
                            $.each(data.data, function (index, value) {
                                html_sub_diagnosis_list += '<tr data-code="' + value.code + '" class="tr_diagnosis">' + '<td class="text-center">' + value.code + "</td>" + "<td>" + value.name + "</td>" + "</tr>";
                            });
                        }
                        $("#diagnosis_table").html("");
                        $("#diagnosis_table").append(html_sub_diagnosis_list);
                        $("#table-diagnosis").DataTable({
                            paging: false,
                            ordering: false,
                            info: false,
                        });
                    },
                });
            },
        };

        neuroForm.getDiagnosisList();

        var route = "{{ route('icu.autocomplete') }}";
        $("#drug").keyup(function (e) {
            $.ajax({
                url: route,
                method: "get",
                data: {
                    term: $(this).val(),
                },
                success: function (data) {
                    $("#drug").autocomplete({
                        source: data,
                    });
                },
            });
        });

        /**
         * Function for gettting the sub group list from csv
         */
        $(document).on("click", "#save_diagnosis", function () {
            var fldcode = $("#sub_diagnosis_table").find("tr.diagnosisSelected").data("fldcode");
            var fldcodeid = $("#sub_diagnosis_table").find("tr.diagnosisSelected").data("fldcodeid");

            if (typeof fldcode != "undefined" && typeof fldcodeid != "undefined" && fldcodeid != "" && fldcode != "") {
                var add_diagnosis = "{{ route('icu.store-diagnosis') }}";
                $.ajax({
                    url: add_diagnosis,
                    method: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        fldtype: "Provisional Diagnosis",
                        fldcode: fldcode,
                        fldcodeid: fldcodeid,
                        encounter: $("#encounter_no").val(),
                    },
                    success: function (data) {
                        var diagnosis_html = "";
                        $("#diagnosis_list_front").empty();
                        $.each(data, function (index, value) {
                            diagnosis_html +=
                                '<li class="list-group-item" style="border-bottom: 1px solid #eae5e5 !important;">\n' +
                                "                                         " +
                                value.fldcode +
                                ' <a class="delete-diagnosis" href="#" style="float: right;color: #dc3545;margin-right: -20px" data-id = " ' +
                                value.fldid +
                                '">  <i class="fa fa-trash"></i></a>\n' +
                                "                                        </li>";
                        });
                        $("#diagnosis_list_front").html("");
                        $("#diagnosis_list_front").append(diagnosis_html);
                        $("#addDiagnosisModal").modal("toggle");
                    },
                    error: function (data) {
                    },
                });
            }
        });

        $(document).on("click", ".tr_diagnosis", function () {
            var getdiagnosis = "{{ route('icu.diagnosis-by-code') }}";
            var code = $(this).data("code");
            $("#diagnosis_code").val(code);
            $.ajax({
                url: getdiagnosis,
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    code: code,
                },
                beforeSend: function () {
                    $("#sub_diagnosis_table_body").html("");
                    var html = '<tr ><td colspan="2" class="text-center">' + "Please Wait....." + "</td></tr>";
                    $("#sub_diagnosis_table_body").append(html);
                },
                success: function (data) {
                    var html_sub_diagnosis_list = "";
                    $("#sub_diagnosis_table_body").append("");
                    $.each(data, function (index, value) {
                        html_sub_diagnosis_list += '<tr class="tr_sub_diagnosis" data-fldcode="' + value + '" data-fldcodeid ="' + code + '"><td><a href="#" class="btnDiagnosisName">' + value + "<a></td></tr>";
                    });

                    $("#sub_diagnosis_table_body").html("");
                    $("#sub_diagnosis_table_body").append(html_sub_diagnosis_list);
                },
            });
        });

        /**
         * Function for removing  diagnosis
         */
        $(document).on("click", ".delete-diagnosis", function () {
            var id = $(this).data("id");
            var remove_diagnosis = "{{ route('icu.remove-diagnosis') }}";
            $.ajax({
                url: remove_diagnosis,
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    encounter: $("#encounter_no").val(),
                },
                success: function (data) {
                    var diagnosis_html = "";
                    $("#diagnosis_list_front").empty();
                    $.each(data, function (index, value) {
                        diagnosis_html +=
                            '<li class="list-group-item" style="border-bottom: 1px solid #eae5e5 !important;">\n' +
                            "                                         " +
                            value.fldcode +
                            ' <a class="delete-diagnosis" href="#" style="float: right;color: #dc3545;margin-right: -20px" data-id = " ' +
                            value.fldid +
                            '">  <i class="fa fa-trash"></i></a>\n' +
                            "                                        </li>";
                    });
                    $("#diagnosis_list_front").html("");
                    $("#diagnosis_list_front").append(diagnosis_html);
                },
                error: function (data) {
                    //console.log('error');
                },
            });
        });
        /**
         * Function for saving Notes and messages
         */
        $(document).on("click", "#save_note", function () {
            var store_notes = "{{ route('icu.store.notes') }}";
            var note_by = $("#exampleFormControlSelect1").val();
            var encounter = $("#encounter_no").val();
            var message = CKEDITOR.instances["notes_message"].getData();
            if (encounter === "" || encounter === null) {
                showAlert("Missing encounter no", "error");
                return false;
            }
            if (message === "" || message === null) {
                showAlert("Cannot save empty note", "error");
                return false;
            }
            $.ajax({
                url: store_notes,
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    note_by: note_by,
                    message: message,
                    encounter: encounter,
                },
                success: function (data) {
                    if (data.error) {
                        showAlert(data.error, "error");
                    }
                    var notes_html = "";
                    $("#notes_tbody").empty();
                    $.each(data, function (index, value) {
                        var detail = value.flddetail ? value.flddetail : "&nbsp;";
                        notes_html += "<tr>";
                        notes_html += "<td align='center'>" + value.flditem + "</td>";
                        notes_html += "<td align='center'>" + detail + "</td>";
                        notes_html += "<td align='center'>" + value.flduserid + "</td>";
                        if (value.flduserid === "{{ \App\Utils\Helpers::getCurrentUserName() }}") {
                            notes_html += "<td align='center'><a href='javascript:void(0);' class='iq-bg-danger deleteNote' data-noteid=" + value.fldid + "><i class='ri-delete-bin-5-fill'></i></a> </td>";
                        } else {
                            notes_html += "<td></td>";
                        }
                        notes_html += "</tr>";
                    });

                    $("#notes_tbody").html("");
                    $("#notes_tbody").append(notes_html);
                    $("#note_status_message").empty().text("Note Saved Successfully.");
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    CKEDITOR.instances[instance].setData("");
                },
                error: function (data) {
                    $("#note_status_message").empty().text("Something Went Wrong.").css("color", "red");
                },
            });
        });
        /**
         * Function for saving Notes and messages
         */
        $(document).on("click", ".deleteNote", function () {
            var encounter = $("#encounter_no").val();
            var noteId = $(this).data("noteid");
            if (encounter === "" || encounter === null) {
                showAlert("Missing encounter no", "error");
                return false;
            }
            if (noteId === "" || noteId === null) {
                showAlert("Something went Wrong", "error");
                return false;
            }
            var delete_notes = "{{ route('icu.delete.notes',':id') }}";
            delete_notes = delete_notes.replace(":id", noteId);
            $.ajax({
                url: delete_notes,
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    encounter: encounter,
                },
                success: function (data) {
                    if (data.error) {
                        showAlert(data.error, "error");
                    }
                    var notes_html = "";
                    $("#notes_tbody").empty();
                    $.each(data, function (index, value) {
                        var detail = value.flddetail ? value.flddetail : "&nbsp;";
                        notes_html += "<tr>";
                        notes_html += "<td align='center'>" + value.flditem + "</td>";
                        notes_html += "<td align='center'>" + detail + "</td>";
                        notes_html += "<td align='center'>" + value.flduserid + "</td>";
                        if (value.flduserid === "{{ \App\Utils\Helpers::getCurrentUserName() }}") {
                            notes_html += "<td align='center'><a href='javascript:void(0);' class='iq-bg-danger deleteNote' data-noteid=" + value.fldid + "><i class='ri-delete-bin-5-fill'></i></a> </td>";
                        } else {
                            notes_html += "<td></td>";
                        }
                        notes_html += "</tr>";
                    });

                    $("#notes_tbody").html("");
                    $("#notes_tbody").append(notes_html);
                    showAlert("Note Deleted Successfully");
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    CKEDITOR.instances[instance].setData("");
                },
                error: function (data) {
                    $("#note_status_message").empty().text("Something Went Wrong.").css("color", "red");
                },
            });
        });


    </script>
    <script>
        $(document).ready(function () {
            $('[data-tooltip="tooltip"]').tooltip();

            CKEDITOR.replace('notes_field',
                {
                    height: '400px',
                });

        });
    </script>
@endpush
