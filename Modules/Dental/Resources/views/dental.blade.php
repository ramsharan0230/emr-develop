@extends('frontend.layouts.master')

@section('content')
 @include('menu::common.dental-nav-bar')
@if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
@php
$disableClass = 'disableInsertUpdate';
@endphp
@else
@php
$disableClass = '';
@endphp
@endif
<link rel="stylesheet" href="{{ asset('assets/css/dental_style.css')}}">
<div class="container-fluid">
    <div class="row">
        @include('frontend.common.patientProfile')
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div id="accordion">
                        <div class="accordion-nav">
                            <ul>
                                <li><a href="#" data-toggle="collapse" data-target="#chief-complaint" aria-expanded="true"
                                           aria-controls="collapseOne">Chief Complaints</a></li>
                                <li><a href="#" id="dentalmedicalhistory" data-toggle="collapse" data-target="#medical-history" aria-expanded="false"
                                    aria-controls="collapseOne">Medical History</a></li>
                                <li><a href="#" id="dental_history" data-toggle="collapse" data-target="#dental-history" aria-expanded="false"
                                    aria-controls="collapseOne">Dental History</a></li>
                                <li><a href="#" id="dentalallergy" data-toggle="collapse" data-target="#allergy-dental" aria-expanded="false"
                                    aria-controls="collapseOne">Allergy</a></li>
                                <li><a href="#" id="dentaldigonosis" data-toggle="collapse" data-target="#diagnosis" aria-expanded="false"
                                    aria-controls="collapseOne">Diagnosis</a></li>
                                <li><a href="#" id="dentalextralaboratory" data-toggle="collapse" data-target="#laboratory" aria-expanded="false" aria-controls="collapseOne">Laboratory</a></li>
                                <li><a href="#" id="procedures" data-toggle="collapse" data-target="#Procedures" aria-expanded="false"
                                    aria-controls="collapseOne">Procedures</a></li>
                                </ul>
                        </div>
                            @include('eye::modal.chiefComplaints')

                            <div id="medical-history" class="collapse " aria-labelledby="headingOne" data-parent="#accordion"  >
                                <form method="post" action="{{ route('dental.examgeneral') }}">
                                    @csrf

                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">Medical History</h4>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <textarea name="Medical_History" id="js-medical-history-ck-textarea" class="form-control">{{ isset($dental_exam['otherData']['medical_history']) ? $dental_exam['otherData']['medical_history'] : ''}}</textarea>

                                    </div>
                                    <div class="text-right">
                                        <!-- <a href="javascript:void();" class="btn btn-primary" id="savemedical">Save</a>  -->
                                        <button class="btn btn-primary" id="savemedical">Save</button>
                                    </div>
                                </form>
                        </div>
                        <div id="dental-history" class="collapse" aria-labelledby="headingOne" data-parent="#accordion" >
                                <form method="post" action="{{ route('dental.examgeneral') }}">
                                    @csrf
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">Dental History</h4>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <textarea name="Dental_History" id="js-dental-history-ck-textarea" class="form-control">{{ isset($dental_exam['otherData']['dental_history']) ? $dental_exam['otherData']['dental_history'] : ''}}</textarea>
                                        <input type="hidden" name="fldtab" value="dentalhistory">
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                        </div>
                            @include('dental::modal.allergy')
                            @include('dental::modal.diagnosis')
                        <div id="laboratory" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"  >
                                <form method="post" action="{{ route('dental.examgeneral') }}">
                                    @csrf
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">Extra Laboratory</h4>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <textarea name="Dental_Extra_Laboratory" id="js-extralaboratory-ck-textarea" class="form-control">{{ isset($dental_exam['otherData']['dental_extra_laboratory']) ? $dental_exam['otherData']['dental_extra_laboratory'] : ''}}</textarea>
                                        <input type="hidden" name="fldtab" value="extralaboratory">
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                        </div>
                        <div id="Procedures" class="collapse" aria-labelledby="headingOne" data-parent="#accordion" >
                                <form method="post" action="{{ route('dental.examgeneral') }}">
                                    @csrf
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">Procedures</h4>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <textarea name="Dental_Procedures" id="js-procedures-ck-textarea" class="form-control">{{ isset($dental_exam['otherData']['dental_procedures']) ? $dental_exam['otherData']['dental_procedures'] : ''}}</textarea>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{route('dental.teethData')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="form-row">
                                <div class="col-sm-4">
                                    <div class="nav flex-column nav-pills text-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Basic Information</a>
                                        <a class="nav-link" id="v-pills-dentalrestore-tab" data-toggle="pill" href="#v-pills-dentalrestore" role="tab" aria-controls="v-pills-dentalrestore" aria-selected="false">Dental Restoration</a>
                                        <a class="nav-link" id="v-pills-ortho-tab" data-toggle="pill" href="#v-pills-ortho" role="tab" aria-controls="v-pills-ortho" aria-selected="false">Orthodontic</a>
                                        <a class="nav-link" id="v-pills-dentalana-tab" data-toggle="pill" href="#v-pills-dentalana" role="tab" aria-controls="v-pills-dentalana" aria-selected="false">Dental Anamolies</a>
                                        <a class="nav-link pl-0 pr-0" id="v-pills-cephal-tab" data-toggle="pill" href="#v-pills-cephal" role="tab" aria-controls="v-pills-cephal" aria-selected="false">Cephalometric Finding</a>
                                    </div>
                                </div>
                                <div class="col-sm-8 border-left">
                                    <div class="tab-content mt-0" id=" v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                            <div class="form-group-dental p-2">
                                                <input type="checkbox" class="trigger">
                                                <label class="">IMD/Clicks/Muscle pain</label>
                                                <div  class="hidden hidden_fields_one">
                                                    <input type="hidden" class="form-control" name="basic_info['imd_teeth']" id="imd_teeth" readonly="" value="@if(isset($basic_info) and count($basic_info) > 0){{ trim($basic_info[0]['fldteeth']) }}@endif">
                                                    <textarea id="hidden_one" name="basic_info['Imd_Click_Muscle_Pain']" class="top-req form-control">
                                                        @if(isset($basic_info) and count($basic_info) > 0){{trim($basic_info[0]['fldvalue'])}}@endif
                                                    </textarea>
                                                    <!-- <input type="text" class="form-control" id="hidden_one" name="Imd_Click_Muscle_Pain"> -->
                                                </div>
                                            </div>
                                            <div class="form-group-dental p-2">
                                                <input type="checkbox" class="trigger">
                                                <label class="">Soft Tissue Lesion</label>
                                                <div  class="hidden hidden_fields_one">
                                                    <input type="hidden" class="form-control top-req" name="basic_info['soft_tissue_lesson_teeth']" id="soft_tissue_lesson_teeth" readonly="" value="@if(isset($basic_info) and count($basic_info) > 0){{trim($basic_info[1]['fldteeth'])}}@endif">
                                                    <input type="text" class="form-control" id="hidden_two" name="basic_info['Soft_Tissue_Lesion']" class="top-req form-control" value="@if(isset($basic_info) and count($basic_info) > 0){{trim($basic_info[1]['fldvalue'])}}@endif">
                                                </div>
                                            </div>
                                            <div class="form-group-dental p-2">
                                                <input type="checkbox" class="trigger">
                                                <label class="">Smoker</label>
                                                <div  class="hidden hidden_fields_one">
                                                    <input type="hidden" class="form-control top-req" name="basic_info['smoker_teeth']" id="smoker" readonly="" value="@if(isset($basic_info) and count($basic_info) > 0){{trim($basic_info[2]['fldteeth'])}}@endif">
                                                    <input type="text" class="form-control" id="hidden_three" name="basic_info['Smoker']" class="top-req form-control" value="@if(isset($basic_info) and count($basic_info) > 0){{trim($basic_info[2]['fldvalue'])}}@endif">
                                                </div>
                                            </div>
                                            <div class="form-group-dental p-2">
                                                <input type="checkbox" class="trigger">
                                                <label class="">Periodental Diseases</label>
                                                <div class="hidden hidden_fields_one">
                                                    <input type="hidden" class="form-control" name="basic_info['periodental_teeth']" id="periodental_teeth" readonly="" value="@if(isset($basic_info) and count($basic_info) > 0){{trim($basic_info[3]['fldteeth'])}}@endif">
                                                    <select class="top-req form-control form-control-sm" name="basic_info['Periodental_Diseases']" id="hidden_four" class="top-req form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="Needs Scaling" {{ (isset($basic_info[3]) and ($basic_info[3]['fldvalue'] == 'Needs Scaling')) ? 'selected' : ''}}>Needs Scaling</option>
                                                        <option value="Needs Oral Hygiene" {{ (isset($basic_info[3]) and ($basic_info[3]['fldvalue'] == 'Needs Oral Hygiene')) ? 'selected' : ''}}>Needs Oral Hygiene</option>
                                                        <option value="Instruction" {{ (isset($basic_info[3]) and ($basic_info[3]['fldvalue'] == 'Instruction')) ? 'selected' : ''}}>Instruction</option>
                                                    </select>
                                                    <!-- <input type="text" class="form-control" id="hidden_four" name="Periodental_Diseases"> -->
                                                </div>
                                            </div>
                                            <div class="form-group-dental p-2">
                                                <input type="checkbox" class="trigger">
                                                <label class="">Gingival Recession</label>
                                                <div class="hidden hidden_fields_one">
                                                    <input type="hidden" class="full-width" name="basic_info['gingival_teeth']" id="gingival_teeth" readonly="" value=" @if(isset($basic_info) and count($basic_info) > 0){{trim($basic_info[4]['fldteeth'])}}@endif">
                                                    <textarea id="hidden_five" name="basic_info['Gingival_Recession']" class="ck-eye top-req form-control" > @if(isset($basic_info) and count($basic_info) > 0){{trim($basic_info[4]['fldvalue'])}}@endif</textarea>
                                                    <!-- <input type="text" class="full-width" id="hidden_five" name="Gingival_Recession"> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-dentalrestore" role="tabpanel" aria-labelledby="v-pills-dentalrestore-tab">
                                            <div class="form-group">
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="crown_teeth">
                                                    <label class="">Crown</label>
                                                    <div  class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_restoration['crown_teeth']" id="crown_teeth" readonly="" value="@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[0]['fldteeth'])}}@endif">
                                                        <textarea id="hidden2retro_one" name="dental_restoration['Crown']" class="ck-eye top-req form-control mt-2" >@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[0]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hidden2retro_one" name="Crown"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="rcts_teeth">
                                                    <label class="">RCTS</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_restoration['rcts_teeth']" id="rcts_teeth" readonly="" value="@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[1]['fldteeth'])}}@endif">
                                                        <textarea id="hidden2retro_two" name="dental_restoration['Rcts']" class="ck-eye top-req form-control mt-2" >@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[1]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hidden2retro_two" name="Rcts"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="fillings_teeth">
                                                    <label class="">Fillings</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_restoration['fillings_teeth']" id="fillings_teeth" readonly="" value="@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[2]['fldteeth'])}}@endif">
                                                        <textarea id="hiddenretro_three" name="dental_restoration['Fillings']" class="ck-eye top-req form-control mt-2" >@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[2]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hiddenretro_three" name="Fillings"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="tooth_wears_teeth">
                                                    <label class="">Tooth Wears</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_restoration['tooth_wears_teeth']" id="tooth_wears_teeth" readonly="" value="@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[3]['fldteeth'])}}@endif">
                                                        <textarea id="hiddenretro_four" name="dental_restoration['Tooth_Wears']" class="ck-eye top-req form-control mt-2" >@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[3]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hiddenretro_four" name="Tooth_Wears"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="extraction_teeth">
                                                    <label class="">Extraction</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_restoration['extraction_teeth']" id="extraction_teeth" readonly="" value="@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[4]['fldteeth'])}}@endif">
                                                        <textarea id="hiddenretro_five" name="dental_restoration['Extraction']" class="ck-eye top-req form-control mt-2" >@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[4]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hiddenretro_five" name="Extraction"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="impacted_teeth">
                                                    <label class="">Impacted Teeth</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_restoration['impacted_teeth']" id="impacted_teeth" readonly="" value="@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[5]['fldteeth'])}}@endif">
                                                        <textarea id="hiddenretro_six" name="dental_restoration['Impacted_Teeth']" class="ck-eye top-req form-control mt-2" >@if(isset($dentalrest) and count($dentalrest) > 0){{trim($dentalrest[5]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hiddenretro_six" name="Impacted_Teeth"> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-ortho" role="tabpanel" aria-labelledby="v-pills-ortho-tab">
                                            <div class="form-group">
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="malocclusion_teeth">
                                                    <label class="">Malocclusion</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['malocclusion_teeth']" id="malocclusion_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[0]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Malocclusion']" id="hidden2ortho_one">
                                                            <option value="">--Select--</option>
                                                            <option value="CI I   CI IIdiv 1" {{ (isset($ortho[0]) and ($ortho[0]['fldvalue'] == 'CI I   CI IIdiv 1')) ? 'selected' : ''}}>CI I   CI IIdiv 1</option>
                                                            <option value="CI II div2" {{ (isset($ortho[0]) and ($ortho[0]['fldvalue'] == 'CI II div2')) ? 'selected' : ''}}>CI II div2</option>
                                                            <option value="CI III" {{ (isset($ortho[0]) and ($ortho[0]['fldvalue'] == 'CI III')) ? 'selected' : ''}}>CI III</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="sagittal_sekeleton_pattern_teeth">
                                                    <label class="">Sagittal Sekeleton PatternS</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['sagittal_sekeleton_pattern_teeth']" id="sagittal_sekeleton_pattern_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[1]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Sagittal_Sekeleton_Pattern']" id="hidden2ortho_two">
                                                            <option value="">--Select--</option>
                                                            <option value="I" {{ (isset($ortho[1]) and ($ortho[1]['fldvalue'] == 'I')) ? 'selected' : ''}}>I</option>
                                                            <option value="II" {{ (isset($ortho[1]) and ($ortho[1]['fldvalue'] == 'II')) ? 'selected' : ''}}>II</option>
                                                            <option value="III" {{ (isset($ortho[1]) and ($ortho[1]['fldvalue'] == 'III')) ? 'selected' : ''}}>III</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="vertical_skeleton_pattern_teeth">
                                                    <label class="">Vertical Skeleton Pattern</label>
                                                    <div  class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['vertical_skeleton_pattern_teeth']" id="vertical_skeleton_pattern_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[2]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Vertical_Skeleton_Pattern']" id="hidden2ortho_three">
                                                            <option value="">--Select--</option>
                                                            <option value="Increased" {{ (isset($ortho[2]) and ($ortho[2]['fldvalue'] == 'Increased')) ? 'selected' : ''}}>Increased</option>
                                                            <option value="Average" {{ (isset($ortho[2]) and ($ortho[2]['fldvalue'] == 'Average')) ? 'selected' : ''}}>Average</option>
                                                            <option value="Decreased" {{ (isset($ortho[2]) and ($ortho[2]['fldvalue'] == 'Decreased')) ? 'selected' : ''}}>Decreased</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="position_of_upper_jaw_teeth">
                                                    <label class="">Position Of Upper Jaw</label>
                                                    <div  class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['position_of_upper_jaw_teeth']" id="position_of_upper_jaw_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[3]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Position_Of_Upper_Jaw']" id="hidden2ortho_four">
                                                            <option value="">--Select--</option>
                                                            <option value="Normal" {{ (isset($ortho[3]) and ($ortho[3]['fldvalue'] == 'Normal')) ? 'selected' : ''}}>Normal</option>
                                                            <option value="Retruded" {{ (isset($ortho[3]) and ($ortho[3]['fldvalue'] == 'Retruded')) ? 'selected' : ''}}>Retruded</option>
                                                            <option value="Protroded" {{ (isset($ortho[3]) and ($ortho[3]['fldvalue'] == 'Protroded')) ? 'selected' : ''}}>Protroded</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="position_of_lower_jaw_teeth">
                                                    <label class="">Position Of Lower Jaw</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['position_of_lower_jaw_teeth']" id="position_of_lower_jaw_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[4]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Position_Of_Lower_Jaw']" id="hidden2ortho_five">
                                                            <option value="">--Select--</option>
                                                            <option value="Normal" {{ (isset($ortho[4]) and ($ortho[4]['fldvalue'] == 'Normal')) ? 'selected' : ''}}>Normal</option>
                                                            <option value="Retruded" {{ (isset($ortho[4]) and ($ortho[4]['fldvalue'] == 'Retruded')) ? 'selected' : ''}}>Retruded</option>
                                                            <option value="Protroded" {{ (isset($ortho[4]) and ($ortho[4]['fldvalue'] == 'Protroded')) ? 'selected' : ''}}>Protroded</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="lip_position_teeth">
                                                    <label class="">Lip Position</label>
                                                    <div  class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['lip_position_teeth']" id="lip_position_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[5]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Lip_Position']" id="hidden2ortho_six">
                                                            <option value="">--Select--</option>
                                                            <option value="Normal" {{ (isset($ortho[5]) and ($ortho[5]['fldvalue'] == 'Normal')) ? 'selected' : ''}}>Normal</option>
                                                            <option value="Incompetent" {{ (isset($ortho[5]) and ($ortho[5]['fldvalue'] == 'Incompetent')) ? 'selected' : ''}}>Incompetent</option>
                                                            <option value="Protruded Lips" {{ (isset($ortho[5]) and ($ortho[5]['fldvalue'] == 'Protruded Lips')) ? 'selected' : ''}}>Protruded Lips</option>
                                                            <option value="Retruded Lips" {{ (isset($ortho[5]) and ($ortho[5]['fldvalue'] == 'Retruded Lips')) ? 'selected' : ''}}>Retruded Lips</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="gum_exposure_teeth">
                                                    <label class="">Gum Exposure</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['gum_exposure_teeth']" id="gum_exposure_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[6]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Gum_Exposure']" id="hidden2ortho_seven">
                                                            <option value="">--Select--</option>
                                                            <option value="Average" {{ (isset($ortho[6]) and ($ortho[6]['fldvalue'] == 'Average')) ? 'selected' : ''}}>Average</option>
                                                            <option value="Too Much" {{ (isset($ortho[6]) and ($ortho[6]['fldvalue'] == 'Too Much')) ? 'selected' : ''}}>Too Much</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="overbite_teeth">
                                                    <label class="">Overbite</label>
                                                    <div  class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['overbite_teeth']" id="overbite_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[7]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Overbite']" id="hidden2ortho_eight">
                                                            <option value="">--Select--</option>
                                                            <option value="Increased" {{ (isset($ortho[7]) and ($ortho[7]['fldvalue'] == 'Increased')) ? 'selected' : ''}}>Increased</option>
                                                            <option value="Decreased" {{ (isset($ortho[7]) and ($ortho[7]['fldvalue'] == 'Decreased')) ? 'selected' : ''}}>Decreased</option>
                                                            <option value="Average" {{ (isset($ortho[7]) and ($ortho[7]['fldvalue'] == 'Average')) ? 'selected' : ''}}>Average</option>
                                                            <option value="Openbit" {{ (isset($ortho[7]) and ($ortho[7]['fldvalue'] == 'Openbit')) ? 'selected' : ''}}>Openbit</option>
                                                            <option value="Depbite" {{ (isset($ortho[7]) and ($ortho[7]['fldvalue'] == 'Depbite')) ? 'selected' : ''}}>Depbite</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="crowding_teeth">
                                                    <label class="">Crowding</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['crowding_teeth']" id="crowding_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[8]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Crowding']" id="hidden2ortho_nine">
                                                            <option value="">--Select--</option>
                                                            <option value="Upper arch" {{ (isset($ortho[8]) and ($ortho[8]['fldvalue'] == 'Upper arch')) ? 'selected' : ''}}>Upper arch</option>
                                                            <option value="Lower arch" {{ (isset($ortho[8]) and ($ortho[8]['fldvalue'] == 'Lower arch')) ? 'selected' : ''}}>Lower arch</option>
                                                            <option value="Both arches" {{ (isset($ortho[8]) and ($ortho[8]['fldvalue'] == 'Both arches')) ? 'selected' : ''}}>Both arches</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="crossbite_teeth">
                                                    <label class="">Crossbite</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['crossbite_teeth']" id="crossbite_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[9]['fldteeth'])}}@endif">
                                                        <select class="top-req full-width form-control mt-2" name="orthodoxtic_finding['Crossbite']" id="hidden2ortho_ten">
                                                            <option value="">--Select--</option>
                                                            <option value="Anterior" {{ (isset($ortho[9]) and ($ortho[9]['fldvalue'] == 'Anterior')) ? 'selected' : ''}}>Anterior</option>
                                                            <option value="Posterior" {{ (isset($ortho[9]) and ($ortho[9]['fldvalue'] == 'Posterior')) ? 'selected' : ''}}>Posterior</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="overjet_teeth">
                                                    <label class="">Overjet</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['overjet_teeth']" id="overjet_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[10]['fldteeth'])}}@endif">
                                                        <textarea id="hidden2ortho_eleven" name="orthodoxtic_finding['Overjet']" class="ck-eye top-req form-control mt-2" >@if(isset($ortho) and count($ortho) > 0){{trim($ortho[10]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hidden2ortho_eleven" name="Overjet"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="scissors_bite_teeth">
                                                    <label class="">Scissors Bite</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="orthodoxtic_finding['scissors_bite_teeth']" id="scissors_bite_teeth" readonly="" value="@if(isset($ortho) and count($ortho) > 0){{trim($ortho[11]['fldteeth'])}}@endif">
                                                        <textarea id="hiddenortho_twelve" name="orthodoxtic_finding['Scissors_Bite']" class="ck-eye top-req form-control mt-2" >@if(isset($ortho) and count($ortho) > 0){{trim($ortho[11]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hiddenortho_twelve" name="Scissors_Bite"> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-dentalana" role="tabpanel" aria-labelledby="v-pills-dentalana-tab">
                                            <div class="form-group">
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="hypodontia_teeth">
                                                    <label class="">Hypodontia</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_anamolies['hypodontia_teeth']" id="hypodontia_teeth" readonly="" value="@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[0]['fldteeth'])}}@endif">
                                                        <textarea id="hidden2dental_one" name="dental_anamolies['Hypodontia']" class="ck-eye top-req form-control mt-2" >@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[0]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hidden2dental_one" name="hidden"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="super_numerary_teeth">
                                                    <label class="">Super Numerary Teeth</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_anamolies['super_numerary_teeth']" id="super_numerary_teeth" readonly="" value="@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[1]['fldteeth'])}}@endif">
                                                        <textarea id="hidden2dental_two" class="top-req form-control mt-2" name="dental_anamolies['Super_Numerary_Teeth']">@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[1]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hidden2dental_two" name="hidden"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="small_teeth">
                                                    <label class="">Small Teeth</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_anamolies['small_teeth']" id="small_teeth" readonly="" value="@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[2]['fldteeth'])}}@endif">
                                                        <textarea id="hiddendental_three" name="dental_anamolies['Small_Teeth']" class="ck-eye top-req form-control mt-2" >@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[2]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hiddendental_three" name="hidden"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="malformed_teeth">
                                                    <label class="">Malformed Teeth</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_anamolies['malformed_teeth']" id="malformed_teeth" readonly="" value="@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[3]['fldteeth'])}}@endif">
                                                        <textarea id="hiddendental_four" name="dental_anamolies['Malformed_Teeth']" class="ck-eye top-req form-control mt-2" >@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[3]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hiddendental_four" name="hidden"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="short_abnormal_roots_teeth">
                                                    <label class="">Short Abnormal Roots</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="dental_anamolies['short_abnormal_roots_teeth']" id="short_abnormal_roots_teeth" readonly="" value="@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[4]['fldteeth'])}}@endif">
                                                        <textarea id="hiddendental_five" name="dental_anamolies['Short_Abnormal_Roots']" class="ck-eye top-req form-control mt-2" >@if(isset($dental_anamolies) and count($dental_anamolies) > 0){{trim($dental_anamolies[4]['fldvalue'])}}@endif</textarea>
                                                        <!-- <input type="text" class="full-width form-control" id="hiddendental_five" name="hidden"> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-cephal" role="tabpanel" aria-labelledby="v-pills-cephal-tab">
                                            <div class="form-group">
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="sna_teeth">
                                                    <label class="">SNA</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="cephalometric_finding['sna_teeth']" id="sna_teeth" readonly="" value="@if(isset($cephalometric) and count($cephalometric) > 0){{trim($cephalometric[0]['fldteeth'])}}@endif">
                                                        <input type="number" class="top-req full-width form-control mt-2" id="hidden2cepha" name="cephalometric_finding['Sna']" class="number" onkeypress="return isNumberKey(event)" value="@if(isset($cephalometric) and count($cephalometric) > 0){{trim($cephalometric[0]['fldvalue'])}}@endif">
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="snb_teeth">
                                                    <label class="">SNB</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="cephalometric_finding['snb_teeth']" id="snb_teeth" readonly="" value="@if(isset($cephalometric[1])){{trim($cephalometric[1]['fldteeth'])}}@endif">
                                                        <input type="number" class="top-req full-width form-control mt-2" id="hidden2cepha_one" name="cephalometric_finding['Snb']" class="number" onkeypress="return isNumberKey(event)" value="@if(isset($cephalometric[1])){{trim($cephalometric[1]['fldvalue'])}}@endif">
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="anb_teeth">
                                                    <label class="">ANB</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="cephalometric_finding['anb_teeth']" id="anb_teeth" readonly="" value="@if(isset($cephalometric[2])){{trim($cephalometric[2]['fldteeth'])}}@endif">
                                                        <input type="number" class="top-req full-width form-control mt-2" id="hidden2cepha_two" name="cephalometric_finding['Anb']" class="number" onkeypress="return isNumberKey(event)" value="@if(isset($cephalometric[2])){{trim($cephalometric[2]['fldvalue'])}}@endif">
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="upper_incisor_to_mandibular_plane_angle_teeth">
                                                    <label class="">Upper Incisor to Mandibular Plane Angle</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="cephalometric_finding['upper_incisor_to_mandibular_plane_angle_teeth']" id="upper_incisor_to_mandibular_plane_angle_teeth" readonly="" value="@if(isset($cephalometric[3])){{trim($cephalometric[3]['fldteeth'])}}@endif">
                                                        <input type="number" class="top-req full-width form-control mt-2" id="hiddencepha_three" name="cephalometric_finding['Upper_Incisor_To_Mandibular_Plane_Angle']" class="number" onkeypress="return isNumberKey(event)" value="@if(isset($cephalometric[3])){{trim($cephalometric[3]['fldvalue'])}}@endif">
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="lower_incisor_to_mandibular_plane_angle_teeth">
                                                    <label class="">Lower Incisor to Mandibular Plane Angle</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="cephalometric_finding['lower_incisor_to_mandibular_plane_angle_teeth']" id="lower_incisor_to_mandibular_plane_angle_teeth" readonly="" value="@if(isset($cephalometric[4])){{trim($cephalometric[4]['fldteeth'])}}@endif">
                                                        <input type="number" class="top-req full-width form-control mt-2" id="hiddencepha_four" name="cephalometric_finding['Lower_Incisor_To_Mandibular_Plane_Angle']" class="number" onkeypress="return isNumberKey(event)" value="@if(isset($cephalometric[4])){{trim($cephalometric[4]['fldvalue'])}}@endif">
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="maxillary_mandibular_plane_angle_teeth">
                                                    <label class="">Maxillary Mandibular Plane Angle</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="cephalometric_finding['maxillary_mandibular_plane_angle_teeth']" id="maxillary_mandibular_plane_angle_teeth" readonly="" value="@if(isset($cephalometric[5])){{trim($cephalometric[5]['fldteeth'])}}@endif">
                                                        <input type="number" class="top-req full-width form-control mt-2" id="hiddencepha_five" name="cephalometric_finding['Maxillary_Mandibular_Plane_Angle']" class="number" onkeypress="return isNumberKey(event)" value="@if(isset($cephalometric[5])){{trim($cephalometric[5]['fldvalue'])}}@endif">
                                                    </div>
                                                </div>
                                                <div class="form-group-dental p-2 custom-control custom-checkbox">
                                                    <input type="checkbox" class="trigger" value="lower_face_height_teeth">
                                                    <label class="">Lower Face Height (%)</label>
                                                    <div class="hidden hidden_fields_one">
                                                        <input type="text" class="full-width form-control" name="cephalometric_finding['lower_face_height_teeth']" id="lower_face_height_teeth" readonly="" value="@if(isset($cephalometric[6])){{trim($cephalometric[6]['fldteeth'])}}@endif">
                                                        <input type="text" class="full-width form-control mt-2 top-req" id="hiddencepha_six" name="cephalometric_finding['Lower_Face_Height']" class="number" onkeypress="return isNumberKey(event)" value="@if(isset($cephalometric[6])){{trim($cephalometric[6]['fldvalue'])}}@endif">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="dental-body">
                                <div class="dental-img">
                                    <img src="{{asset('assets/images/teeth.jpg')}}" alt="">
                                </div>
                                <div class="dental-click">
                                    <span data-teeth="18"></span>
                                    <span data-teeth="17"></span>
                                    <span data-teeth="16"></span>
                                    <span data-teeth="15"></span>
                                    <span data-teeth="14"></span>
                                    <span data-teeth="13"></span>
                                    <span data-teeth="12"></span>
                                    <span data-teeth="11"></span>
                                    <span data-teeth="21"></span>
                                    <span data-teeth="22"></span>
                                    <span data-teeth="23"></span>
                                    <span data-teeth="24"></span>
                                    <span data-teeth="25"></span>
                                    <span data-teeth="26"></span>
                                    <span data-teeth="27"></span>
                                    <span data-teeth="28"></span>
                                    <span data-teeth="48"></span>
                                    <span data-teeth="47"></span>
                                    <span data-teeth="46"></span>
                                    <span data-teeth="45"></span>
                                    <span data-teeth="44"></span>
                                    <span data-teeth="43"></span>
                                    <span data-teeth="42"></span>
                                    <span data-teeth="41"></span>
                                    <span data-teeth="31"></span>
                                    <span data-teeth="32"></span>
                                    <span data-teeth="33"></span>
                                    <span data-teeth="34"></span>
                                    <span data-teeth="35"></span>
                                    <span data-teeth="36"></span>
                                    <span data-teeth="37"></span>
                                    <span data-teeth="38"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Advice</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="form-group">
                                <textarea name="Dental_Advice" id="js-advice-ck-textarea" class="form-control">{{ isset($dental_exam['otherData']['dental_advice']) ? $dental_exam['otherData']['dental_advice'] : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Notes</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="form-group">
                                <textarea name="Dental_Notes" id="js-notes-ck-textarea" class="form-control">{{ isset($dental_exam['otherData']['dental_notes']) ? $dental_exam['otherData']['dental_notes'] : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="justify-content-around">
                                <a href="javascript:void(0);" class="btn btn-primary mr-4" onclick="laboratory.displayModal()">Laboratory</a>
                                <a href="javascript:void(0);" class="btn btn-primary mr-4" onclick="radiology.displayModal()">Radiology</a>
                                <a href="javascript:void(0);" class="btn btn-primary mr-4" onclick="pharmacy.displayModal()">Pharmacy</a>
                                <!-- <button class="btn btn-primary mr-4" onclick="radiology.displayModal()">Radiology</button>
                                <button class="btn btn-primary mr-4" onclick="pharmacy.displayModal()">Pharmacy</button> -->
                                <a href="{{ route('dental.history.generate', $patient->fldpatientval??0) }}" target="_blank" class="mr-4 btn btn-primary">
                                    History
                                </a>
                                <a href="{{route('dental.opdsheet.generate', $enpatient->fldencounterval ?? 0)}}" id="finish" target="_blank" class="mr-4 btn btn-primary">
                                    OPD Sheet
                                </a>
                                <button class="btn btn-primary mr-4">Save</button>
                                <a href="javascript:;" data-toggle="modal" data-target="#finish_box" id="finish" class="mr-4 btn btn-primary">
                                    Finish
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                            <div class="modal-body">

                                <input type="hidden" id="patientID" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">

                                    <div class="form-group mb-0">
                                        <input type="text" name="searchdrugs" class="form-control" id="searchdrugs">
                                    </div>
                                    <div id="allergicdrugss" class="res-table-drugs">
                                        <ul class="list-group">
                                            <!-- <div id="searchresult"></div> -->
                                            @if(isset($allergicdrugs) and count($allergicdrugs) > 0)
                                            @foreach($allergicdrugs as $ad)
                                            <li class="list-group-item"><input type="checkbox" value="{{$ad->fldcodename}}" class="fldcodename" name="allergydrugs[]"/>&nbsp; {{$ad->fldcodename}}</li>
                                            @endforeach
                                            @else
                                            <li class="list-group-item">No Drugs Available</li>
                                            @endif
                                        </ul>
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="saveAllergyDrugs()">Save</button>
                            </div>
                        </form>
                    </div>
                 </div>
        </div>
    </div>
</div>

    @include('inpatient::layouts.modal.patient-image')
    @include('inpatient::layouts.modal.triage')
    @include('inpatient::layouts.modal.demographics')

    @stop
    @push('after-script')
    <script>

        $(document).ready(function() {
            $('.trigger').click(function() {
                if($(this).siblings('.hidden_fields_one').is(':hidden'))
                {
                 $(this).siblings('.hidden_fields_one').show();
             }else{
                 $(this).siblings('.hidden_fields_one').hide();
             }


         })

        // Hide the hidden sections.
        // Use JS to do this in case the user doesn't have JS
        // enabled.
        $('.hidden').hide();

        // Setup an event listener for each trigger checkbox that
        // fires when the state of the checkbox changes.
        $('.trigger').change(function() {
            // Get the ID of the hidden area from the data-trigger
            // attribute.
            var hiddenId = $(this).attr("data-trigger");

            // Check to see if the checkbox is checked.
            // If it is, show the fields and populate the input.
            // If not, hide the fields.
            if ($(this).is(':checked')) {
                // Show the hidden fields.
                $("#" + hiddenId).show();
            } else {
                // Make sure that the hidden fields are indeed
                // hidden.
                $("#" + hiddenId).hide();

                // You may also want to clear the value of the
                // hidden fields here. Just in case somebody
                // shows the fields, enters data to them and then
                // unticks the checkbox.
                //
                // This would do the job:
                //
                // $("#hidden_field").val("");
            }
        });

        $(".trigger").click(function() {
         $('.trigger').bind('click',function() {
          $('.trigger').not(this).prop("checked", false);
      });
     });

        $('.dental-click span').on('click', function(e){
            var boxes = $.map($('input[class="trigger"]:checked'), function(c){return c.value; })

            var teeth = $(this).attr("data-teeth");
            //alert(teeth);
            if($(this).hasClass('active')){
                // alert('has');
                $('.dental-click span').removeClass('active');
            }else{
                // alert('dont')
                $('.dental-click span').removeClass('active');
                $(this).addClass('active');

            }

            var vallen = $('#'+boxes).val();
            if(vallen !=''){

               if(vallen.includes(teeth)){
                var fields = vallen.split(/,/);
                fields = fields.filter(function(item) {
                    return item !== teeth
                })

                $('#'+boxes).val(fields.join());
            }else{

                var conval = vallen+','+teeth;
                $('#'+boxes).val(conval);
            }

        }else{
         $('#'+boxes).val(teeth);
     }
 });



    });
        function openForm(evt, cityName) {
          var i, tabcontent, tablinks;
          tabcontent = document.getElementsByClassName("tabcontent");
          for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" activetab", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " activetab";
    }

    // Get the element with id="defaultOpen" and click on it
    // document.getElementById("defaultOpen").click();

    function switchVisible() {
        if (document.getElementById('Div1')) {

            if (document.getElementById('Div1').style.display == 'none') {
                document.getElementById('Div1').style.display = 'block';
                document.getElementById('Div2').style.display = 'none';
            }
            else {
                document.getElementById('Div1').style.display = 'none';
                document.getElementById('Div2').style.display = 'block';
            }
        }
    }
    // $('#savemedical').on('click', function(){
    //     // e.preventDefault();
    //     var url = "{{ route('dental.examgeneral') }}";
    //     // alert(url);
    //     $.ajax({
    //         url: url,
    //         type: "POST",
    //         dataType: "json",
    //         data:  $("#medical").serialize(),"_token": "{{ csrf_token() }}",
    //         success: function(response) {

    //             showAlert('Data Added !!');

    //         },
    //         error: function (xhr, status, error) {
    //             var errorMessage = xhr.status + ': ' + xhr.statusText;
    //             console.log(xhr);
    //         }
    //     });
    // })
    $(document).ready(function () {

            setTimeout(function () {
                $(".flditem").select2();
                $(".find_fldhead").select2();
            }, 1500);



            $(document).on("keydown", ".select2-search__field", function (e) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    //alert('You pressed a "enter" key in textbox');
                    $('.flditem').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
                }
            });

        });
</script>
<script src="{{ asset('js/dental_form.js')}}"></script>
@endpush
