<style>
    .multiselect {
  width: 200px;
}

.selectBox {
  position: relative;
}

.selectBox select {
  width: 100%;
}

.overSelect {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}

#checkboxes {
  display: none;
  border: 1px #000 solid;
      overflow: scroll;
    max-height: 135px;
    position: absolute;
    width: 91%;
    background: #f1f1f1;
    z-index: 1000;
}

#checkboxes label {
  display: block;
  padding: 4px;
    margin-bottom: 0px
}

#checkboxes label:hover {
  background-color: #1e90ff;
}
#select-multiple-diagno {
    height: 150px;
}
</style>
<div class="tab-pane fade" id="intraoperative" role="tabpanel" aria-labelledby="intraoperative-tab">
     <form id="intraoperative-form">
        <div class="form-group-second form-row">
           
            <div class="col-sm-12">
                <div class="form-group er-input">
                    <label for="" class="col-sm-3 col-lg-3">Revised PAC:</label>
                    <div class="col-sm-6 col-lg-5">
                        <!-- <input type="text" class="form-control" value="" /> -->
                        <div class="checkbox d-inline-block mr-2">
                           <input type="checkbox" name="revised_pac[]" class="custom-checkbox" value="I" {{(isset($intra_physicalstatus) and in_array('I',$intra_physicalstatus)) ? 'checked':''}}/> I   
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="revised_pac[]" class="custom-checkbox"  value="IE" {{(isset($intra_physicalstatus) and in_array('IE',$intra_physicalstatus)) ? 'checked':''}}/>IE 
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="revised_pac[]" class="custom-checkbox"  value="II" {{(isset($intra_physicalstatus) and in_array('II',$intra_physicalstatus)) ? 'checked':''}}/> II
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="revised_pac[]" class="custom-checkbox"  value="IIE" {{(isset($intra_physicalstatus) and in_array('IIE',$intra_physicalstatus)) ? 'checked':''}}/> IIE 
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="revised_pac[]" class="custom-checkbox"  value="III" {{(isset($intra_physicalstatus) and in_array('III',$intra_physicalstatus)) ? 'checked':''}}/> III 
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="revised_pac[]" class="custom-checkbox"  value="IIIE" {{(isset($intra_physicalstatus) and in_array('IIIE',$intra_physicalstatus)) ? 'checked':''}}/> IIIE
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="revised_pac[]" class="custom-checkbox"  value="IV" {{(isset($intra_physicalstatus) and in_array('IV',$intra_physicalstatus)) ? 'checked':''}}/> IV    
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                          <input type="checkbox" name="revised_pac[]" class="custom-checkbox"  value="IVE" {{(isset($intra_physicalstatus) and in_array('IVE',$intra_physicalstatus)) ? 'checked':''}}/> IVE    
                        </div>
                    </div>
                </div>
                <div class="form-group er-input">
                    <label for="exampleFormControlSelect2" class="col-sm-3 col-lg-3">Consultant Name :</label>
                    <div class="col-sm-6 col-lg-5">
                        <select multiple="" name="consultant_name[]" class="form-control" id="exampleFormControlSelect2">
                             <option value="">---select---</option>
                            @php
                                $consultantList = Helpers::getConsultantList();
                            @endphp
                            @if(count($consultantList))
                                @foreach($consultantList as $con)
                               
                                <option value="{{ $con->username }}" {{(isset($intra_consultants) and in_array($con->username,$intra_consultants)) ? 'selected':''}}>{{ $con->username }}</option>

                                @endforeach
                            @endif
                            
                        </select>
                    </div>
                </div>
                <div class="form-group er-input">
                    <label for="" class="col-sm-3 col-lg-3">Patients shifted :</label>
                    <div class="col-sm-6 col-lg-5">
                       
                        <select name="shifted_to" class="form-control">
                        <option value="">---select---</option>
                            @if(isset($shiftdepartments) and count($shiftdepartments) > 0)
                                @foreach ($shiftdepartments as $department)
                                    <option value="{{ $department->flddept }}" {{ (isset($intraoperative['otherData']['shifted_to']) and ($intraoperative['otherData']['shifted_to'] == $department->flddept)) ? 'selected' : ''}}>{{ $department->flddept }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group er-input">
                    <label for="" class="col-sm-3 col-lg-3">Post operative analgesia:</label>
                    <div class="col-sm-6 col-lg-5">
                        <textarea class="form-control" name="post_operative_analgesia" id="js-post-operative-analgesia">{{ isset($intraoperative['otherData']['post_operative_analgesia']) ? $intraoperative['otherData']['post_operative_analgesia'] : ''}}</textarea>
                    </div>
                </div>
                <div class="form-group er-input">
                    <label for="" class="col-sm-3 col-lg-3">Intraoperative Monitoring:</label>
                    <div class="col-sm-6 col-lg-7 ">
                        <div class="checkbox d-inline-block mr-2">

                           <input type="checkbox" name="intraoperative_monitoring[]" class="custom-checkbox intraoperative_monitoring"  value="SPO2" {{(isset($intra_monitoring) and in_array('SPO2',$intra_monitoring)) ? 'checked':''}}/> SPO2     
                        </div>
                         <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="intraoperative_monitoring[]" class="custom-checkbox intraoperative_monitoring"  value="ECG" {{(isset($intra_monitoring) and in_array('ECG',$intra_monitoring)) ? 'checked':''}}/>ECG    
                        </div>
                         <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="intraoperative_monitoring[]" class="custom-checkbox intraoperative_monitoring"  value="NIBP" {{(isset($intra_monitoring) and in_array('NIBP',$intra_monitoring)) ? 'checked':''}}/> NIBP     
                        </div>
                        
                       <div class="checkbox d-inline-block mr-2">
                               <a href="javascript:;" class="btn btn-primary" id="addmore_intraoperative_monitoring">
                                <i class="fa fa-plus"></i>
                                Others
                            </a>    
                        </div>
                       
                     
                      
                         
                        <!-- <input type="checkbox" class="custom-checkbox intraoperative_monitoring" id="" value="Others" /> Others -->
                    </div>
                </div>
                @if(isset($intraoperative['otherData']['other_intraoperative_monitoring']) and $intraoperative['otherData']['other_intraoperative_monitoring'] !='')
                    @php
                        $style = 'display:block';
                    @endphp
                @else
                     @php
                        $style = 'display:none';
                    @endphp
                @endif
                <div class="form-group er-input" id="other_intraoperative_monitoring" style="{{$style}}">
                    <label for="" class="col-sm-3 col-lg-3 pr-0">Other Intraoperative Monitoring: </label>
                    <div class="col-sm-8 col-lg-8 ">
                        <textarea class="form-control" name="other_intraoperative_monitoring" id="js-other-intraoperative">
                            {{ isset($intraoperative['otherData']['other_intraoperative_monitoring']) ? $intraoperative['otherData']['other_intraoperative_monitoring'] : ''}}
                        </textarea>
                    </div>
                </div>
                <div class="form-group er-input">
                    <label for="" class="col-sm-3 col-lg-3">Recovery:</label>
                    <div class="col-sm-6 col-lg-5">
                       <select name="recovery"  class="form-control" id="recovery">
                            <option value="">---select---</option>
                            <option value="Complete" {{ (isset($intraoperative['otherData']['recovery']) and ($intraoperative['otherData']['recovery'] == 'Complete')) ? 'selected' : ''}}>Complete</option>
                            <option value="Incomplete" {{ (isset($intraoperative['otherData']['recovery']) and ($intraoperative['otherData']['recovery'] == 'Incomplete')) ? 'selected' : ''}}>Incomplete</option>
                        </select>
                    </div>
                </div>
                @if(isset($intraoperative['otherData']['recovery']) and $intraoperative['otherData']['recovery'] =='Incomplete')
                    @php
                        $style = 'display:block';
                    @endphp
                @else
                     @php
                        $style = 'display:none';
                    @endphp
                @endif
                <div class="form-group er-input" id="incomplete_recovery" style="{{$style}}">
                    <label for="" class="col-sm-3 col-lg-3">Incomplete Recovery Detail:</label>
                    <div class="col-sm-8 col-lg-8">
                        <textarea class="form-control" name="incomplete_recovery_detail" id="js-incomplete-recovery-detail">{{ isset($intraoperative['otherData']['incomplete_recovery_detail']) ? $intraoperative['otherData']['incomplete_recovery_detail'] : ''}}</textarea>
                    </div>
                </div>
                <div class="form-group er-input">
                    <label for="" class="col-sm-3 col-lg-3">Type of Anaesthesia:</label>
                    <div class="col-sm-9 col-lg-9 ">
                        <div class="checkbox d-inline-block mr-2">
                           <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type" value="GA" {{(isset($intra_anaesthesia) and in_array('GA',$intra_anaesthesia)) ? 'checked':''}}/> GA
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="SAB" {{(isset($intra_anaesthesia) and in_array('SAB',$intra_anaesthesia)) ? 'checked':''}}/> SAB
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="Epidural" {{(isset($intra_anaesthesia) and in_array('Epidural',$intra_anaesthesia)) ? 'checked':''}}/> Epidural
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="CSEA" {{(isset($intra_anaesthesia) and in_array('CSEA',$intra_anaesthesia)) ? 'checked':''}}/> CSEA
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="Local" {{(isset($intra_anaesthesia) and in_array('Local',$intra_anaesthesia)) ? 'checked':''}}/> Local 
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                             <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type" value="Peripheral nerve block" {{(isset($intra_anaesthesia) and in_array('Peripheral nerve block',$intra_anaesthesia)) ? 'checked':''}}/> Peripheral nerve block 
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="Anatomical Landmark" {{(isset($intra_anaesthesia) and in_array('Anatomical Landmark',$intra_anaesthesia)) ? 'checked':''}}/> Anatomical Landmark<br/>
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="PNS guided" {{(isset($intra_anaesthesia) and in_array('PNS guided',$intra_anaesthesia)) ? 'checked':''}}/> PNS guided
                        </div>

                        <div class="checkbox d-inline-block mr-2">
                          <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="USG guided" {{(isset($intra_anaesthesia) and in_array('USG guided',$intra_anaesthesia)) ? 'checked':''}}/> USG guided  
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                             
                          <a href="javascript:;" id="addmore_anaesthesia" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            </a>  
                        </div>  
                    </div>
                </div>
                @if(isset($intraoperative['otherData']['extra_anaesthesia']) and $intraoperative['otherData']['extra_anaesthesia'] !='')
                    @php
                        $style = 'display:block';
                    @endphp
                @else
                     @php
                        $style = 'display:none';
                    @endphp
                @endif
                <div class="form-group er-input" id="more_anaesthesia" style="{{$style}}">
                    <label for="" class="col-sm-3 col-lg-3">Add Anaesthesia:</label>
                    <div class="col-sm-8 col-lg-8">
                         <textarea class="form-control" name="extra_anaesthesia" id="js-more-anaesthesia">{{ isset($intraoperative['otherData']['extra_anaesthesia']) ? $intraoperative['otherData']['extra_anaesthesia'] : ''}}</textarea>
                    </div>
                </div>
                @if(isset($intra_anaesthesia) and !empty($intra_anaesthesia) and in_array('Peripheral nerve block',$intra_anaesthesia))
                    @php
                        $style = 'display:block';
                    @endphp
                @else
                     @php
                        $style = 'display:none';
                    @endphp
                @endif
                <div class="form-group er-input" id="peripheral_nerver_detail" style="{{$style}}">
                    <label for="" class="col-sm-3 col-lg-3">Peripheral nerve block Detail:</label>
                    <div class="col-sm-8 col-lg-8">
                        <textarea class="form-control" name="peripheral_nerve_block_detail" id="js-peripheral-nerve-block-detail">{{ isset($intraoperative['otherData']['peripheral_nerve_block_detail']) ? $intraoperative['otherData']['peripheral_nerve_block_detail'] : ''}}</textarea>
                    </div>
                </div>
                <div class="form-group col-sm-5">
                    <button type="button" class="btn btn-primary" id="vital">Add Vitals</button>
                </div>
                <div class="form-group er-input" id="vital_details" style="display: none;">
                    <div class="col-sm-12" >
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Vital Exam</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="">Pulse Rate</label>
                                            <input type="text" class="form-control @if(isset($pulse->fldrepquanti) &&  $pulse->fldrepquanti >=  $pulse->fldhigh) highline @endif  @if(isset($pulse->fldrepquanti) &&  $pulse->fldrepquanti <=  $pulse->fldlow) lowline @endif remove_zero_to_empty" id="pulse_rate" placeholder="" pulse_rate="Pulse Rate"
                                                   value="{{ isset($pulse->fldrepquanti) ?  $pulse->fldrepquanti : 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="">Syst BP</label>
                                            <input type="text" class="form-control @if(isset($systolic_bp->fldrepquanti) &&  $systolic_bp->fldrepquanti >=  $systolic_bp->fldhigh) highline @endif  @if(isset($systolic_bp->fldrepquanti) &&  $systolic_bp->fldrepquanti <=  $systolic_bp->fldlow) lowline @endif remove_zero_to_empty" id="sys_bp" placeholder="" sys_bp="Systolic BP"
                                                   value="{{ isset($systolic_bp->fldrepquanti) ?  $systolic_bp->fldrepquanti : 0  }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="">Diast BP</label>
                                            <input type="text" class="form-control @if(isset($diasioli_bp->fldrepquanti) &&  $diasioli_bp->fldrepquanti >=  $diasioli_bp->fldhigh) highline @endif  @if(isset($diasioli_bp->fldrepquanti) &&  $diasioli_bp->fldrepquanti <=  $diasioli_bp->fldlow) lowline @endif remove_zero_to_empty " id="dia_bp" placeholder="" dia_bp="Diastolic BP"
                                                   value="{{ isset($diasioli_bp->fldrepquanti) ? $diasioli_bp->fldrepquanti : 0  }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="">Resp Rate</label>
                                            <input type="text" class="form-control @if(isset($respiratory_rate->fldrepquanti) &&  $respiratory_rate->fldrepquanti >=  $respiratory_rate->fldhigh) highline @endif  @if(isset($respiratory_rate->fldrepquanti) &&  $respiratory_rate->fldrepquanti <=  $respiratory_rate->fldlow) lowline @endif remove_zero_to_empty" id="respi" placeholder="" respi="Respiratory Rate"
                                                   value="{{ isset($respiratory_rate->fldrepquanti) ? $respiratory_rate->fldrepquanti : 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="">S P O2</label>
                                            <input type="text" class="form-control @if(isset($o2_saturation->fldrepquanti) &&  $o2_saturation->fldrepquanti >=  $o2_saturation->fldhigh) highline @endif  @if(isset($o2_saturation->fldrepquanti) &&  $o2_saturation->fldrepquanti <=  $o2_saturation->fldlow) lowline @endif remove_zero_to_empty" id="saturation" placeholder="" saturation="O2 Saturation"
                                                   value="{{ isset($o2_saturation->fldrepquanti) ? $o2_saturation->fldrepquanti : 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="">Temp</label>
                                            <input type="text" class="form-control @if(isset($temperature->fldrepquanti) &&  $temperature->fldrepquanti >=  $temperature->fldhigh) highline @endif  @if(isset($temperature->fldrepquanti) &&  $temperature->fldrepquanti <=  $temperature->fldlow) lowline @endif remove_zero_to_empty" id="pulse_rate_rate" placeholder="" pulse_rate_rate="Temperature (F)"
                                                   value="{{ isset($temperature->fldrepquanti) ?  $temperature->fldrepquanti : 0 }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <input type="hidden" id="check_vital" url="{{route('check_vital')}}">
                                    <a href="javascript:;" class="btn btn-primary rounded-pill {{$disableClass}}" type="button" url="{{ route('insert_essential_exam') }}" id="save_essential">

                                        Vital Save
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  <div class="form-group er-input">
                    <div class="col-sm-7 col-lg-6 pr-0">
                        <div class="custom-control custom-radio custom-control-inline align-items-right">
                            <input type="radio" name="customRadio-1" value="food" class="custom-control-input" checked="" />
                            <label class="custom-control-label" for="food-radio"> GA </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline align-items-right">
                            <input type="radio" name="customRadio-1" value="food" class="custom-control-input" checked="" />
                            <label class="custom-control-label" for="food-radio"> SAB </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline align-items-right">
                            <input type="radio" name="customRadio-1" value="food" class="custom-control-input" checked="" />
                            <label class="custom-control-label" for="food-radio"> Epidural </label>
                        </div>
                    </div>
                   <div class="col-sm-5 col-lg-6 er-input">
                        <label for="" class="col-sm-6 col-lg-4 pr-0">Other:</label>
                        <div class="col-sm-6 col-lg-8 p-0">
                            <input type="text" class="form-control" value="" />
                        </div>
                    </div>
                </div> -->
           
             
            
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <!-- <label for="" class="col-sm-6 col-lg-5">Intraoperative diagnosis::</label>
                    <div class="col-sm-6 col-lg-7">
                        <input type="text" class="form-control" value="" />
                    </div> -->
                    <div class="iq-card-header d-flex pl-2">
                            <div class="iq-header-title">
                                <h4 class="card-title">Diagnosis</h4>
                            </div>
                            <div class="allergy-add allergy-add ml-4">

                                @if(isset($enable_freetext) and $enable_freetext == 'Yes')
                                    <a href="javascript:void(0);" class="iq-bg-primary" data-toggle="modal" data-target="#diagnosisfreetext" onclick="diagnosisfreetext.displayModal()"><i class="ri-add-fill"></i></a>
                                @else
                                    <a href="javascript:void(0);" class="iq-bg-secondary"><i class="ri-add-fill"></i></a>
                                @endif

                                @if(isset($patient) and $patient->fldptsex == 'Female')
                                    <!-- <a href="javascript:void(0);" class="iq-bg-primary" id="pro_obstetric" data-toggle="modal" data-target="#obstetricdiagnosis" onclick="obstetric.displayModal()"><i class="ri-add-fill"></i></a> -->
                                @endif

                                <a href="#" class="iq-bg-primary" data-toggle="modal" data-target="#diagnosis"><i class="ri-add-fill"></i></a>


                                <a href="javascript:void(0);" class="iq-bg-danger" id="deletealdiagno"><i class="ri-delete-bin-5-fill"></i></a>
                            </div>
                        </div>
                        <div class="iq-card-body pt-0">
                            
                                <div class="form-group mb-0">
                                    <select name="" id="select-multiple-diagnos" class="form-control" multiple>
                                        @if(isset($patdiago) and count($patdiago) > 0)
                                            @foreach($patdiago as $patdiag)
                                                <option value="{{$patdiag->fldid}}">{{$patdiag->fldcode}}</option>
                                            @endforeach
                                        @else
                                            <option value="">No Diagnosis Found</option>
                                        @endif
                                    </select>
                                </div>
                           

                        </div>
                        
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="" class="col-12">Condition Before Shifting:</label>
                    <div class="col-12">
                         <textarea class="form-control" name="condition_before_shifting" id="js-condition-before-shifting">{{ isset($intraoperative['otherData']['condition_before_shifting']) ? $intraoperative['otherData']['condition_before_shifting'] : ''}}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="" class="col-12">Change in patient condition (if any):</label>
                    <div class="col-12">
                        <textarea class="form-control" name="patient_change_condition" id="js-patient-change-condition">{{ isset($intraoperative['otherData']['patient_change_condition']) ? $intraoperative['otherData']['patient_change_condition'] : ''}}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="textarea-intra">
                    <label for="" class="col-12">Any eventful changes in monitoring:</label>
                    <div class="col-12">
                        <textarea class="form-control" name="eventful_changes_in_monitoring" id="js-eventful-changes">{{ isset($intraoperative['otherData']['eventful_changes_in_monitoring']) ? $intraoperative['otherData']['eventful_changes_in_monitoring'] : ''}}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="" class="col-12">Surgery Performed :</label>
                    <div class="col-12">
                       <textarea class="form-control" name="surgery_performed" id="js-surgery-performed">{{ isset($intraoperative['otherData']['surgery_performed']) ? $intraoperative['otherData']['surgery_performed'] : ''}}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="" class="col-12">Resident name:</label>
                    <div class="col-12">
                         <textarea class="form-control" name="resident_name" id="js-resident-name">{{ isset($intraoperative['otherData']['resident_name']) ? $intraoperative['otherData']['resident_name'] : ''}}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="textarea-intra">
                    <label for="" class="col-12">Intraoperative events: </label>
                    <div class="col-12">
                        <textarea class="form-control" name="intraoperative_events" id="js-intraoperative-events">{{ isset($intraoperative['otherData']['intraoperative_events']) ? $intraoperative['otherData']['intraoperative_events'] : ''}}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="textarea-intra">
                    <label for="" class="col-12">Post operative events: </label>
                    <div class="col-12">
                        <textarea class="form-control" name="post_operative_events" id="js-post-operative-events">{{ isset($intraoperative['otherData']['post_operative_events']) ? $intraoperative['otherData']['post_operative_events'] : ''}}</textarea>
                    </div>
                </div>
            </div>
            <input type="hidden" name="fldencounterval" id="fldencountervals" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif "/>
            
            
        </div>
       </form> 
       <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body text-right">
                <div class="justify-content-around">
                    <a href="javascript:void(0);"  class="btn btn-action btn-primary" onclick="saveIntraOperativeDetail()">
                            Save
                        </a>
                    
                </div>
            </div>
        </div>
</div>
<script type="text/javascript">
     var diagnosisfreetext = {
            displayModal: function () {
                // alert('obstetric');
                // if($('encounter_id').val() == 0)
                // alert($('#encounter_id').val());
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route('patient.diagnosis.freetext') }}',
                    type: "POST",
                    data: {
                        encounterId: $('#encounter_id').val()
                    },
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-diagnosis-freetext').html(response);
                        $('#diagnosis-freetext-modal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            },
        }
$('#recovery').on('change', function(){
    var value = $(this).val();
    if(value === 'Incomplete'){
        $('#incomplete_recovery').show();
    }else{
        $('#incomplete_recovery').hide();
    }
})
$('.anaesthesia_type').on("click", function(e) {
    var checkvalue = $(this).val();
    if(checkvalue === 'Peripheral nerve block'){
        
        if($("#peripheral_nerver_detail").is(":hidden")){
            $('#peripheral_nerver_detail').show();
        } else{
            $('#peripheral_nerver_detail').hide();
        }
    }
});


$('#addmore_anaesthesia').on('click', function(){
    if($("#more_anaesthesia").is(":hidden")){
        $('#more_anaesthesia').show();
    } else{
        $('#more_anaesthesia').hide();
    }
});

$('#vital').on('click', function(){
    if($("#vital_details").is(":hidden")){
        $('#vital_details').show();
    } else{
        $('#vital_details').hide();
    }
});

$('#addmore_intraoperative_monitoring').on('click', function(){
    if($("#other_intraoperative_monitoring").is(":hidden")){
        $('#other_intraoperative_monitoring').show();
    } else{
        $('#other_intraoperative_monitoring').hide();
    }
});
 $( document ).ready(function() {
CKEDITOR.replace('js-post-operative-events',
{
height: '200px',
} );

CKEDITOR.replace('js-post-operative-analgesia',
{
height: '200px',
} );
CKEDITOR.replace('js-intraoperative-events',
{
height: '200px',
} );
CKEDITOR.replace('js-eventful-changes',
{
height: '200px',
} );


CKEDITOR.replace('js-patient-change-condition',
{
height: '200px',
} );

CKEDITOR.replace('js-surgery-performed',
{
height: '200px',
} );

CKEDITOR.replace('js-resident-name',
{
height: '200px',
} );

CKEDITOR.replace('js-incomplete-recovery-detail',
{
height: '200px',
} );

CKEDITOR.replace('js-peripheral-nerve-block-detail',
{
height: '200px',
} );

CKEDITOR.replace('js-more-anaesthesia',
{
height: '200px',
} );

CKEDITOR.replace('js-other-intraoperative',
{
height: '200px',
} );

CKEDITOR.replace('js-condition-before-shifting',
{
height: '200px',
} );

})

function saveIntraOperativeDetail(){
    // alert('saveanaesthesia');
    var url = "{{route('saveIntraOperativeDetail')}}";
    var alldata = $("#intraoperative-form").serialize();
    // alert(alldata);
    for (var i in CKEDITOR.instances) {
        CKEDITOR.instances[i].updateElement();
    };
    $.ajax({
        url: url,
        type: "POST",
        data:  $("#intraoperative-form").serialize(),"_token": "{{ csrf_token() }}",
        success: function(response) {
            // response.log()
            // console.log(response);
            // $('#select-multiple-diagno').html(response);
            // $('#diagnosis').modal('hide');
            showAlert('Information Saved !!');
            // if ($.isEmptyObject(data.error)) {
            //     showAlert('Data Added !!');
            //     $('#allergy-freetext-modal').modal('hide');
            // } else
            //     showAlert('Something went wrong!!');
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

</script>
