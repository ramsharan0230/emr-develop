 <div class="tab-pane fade" id="pre-anaesthesia" role="tabpanel" aria-labelledby="pre-anaesthesia-tab">
    <form id="preanesthesia-form">
        <div class="form-group-second form-row">  
            <div class="col-sm-6">
            <div class="form-group">
                    <label for="">Diagnosis:</label>
                    <div class="form-group mb-0">
                        <select name="" id="" class="form-control" multiple>

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
            <div class="col-sm-6">
                <div class="form-group">
                <label for="">Drug Allergy:</label>
                    <div class="form-group mb-0">
                        
                        <select name="" id="" class="form-control" multiple>
                            @if(isset($patdrug) && count($patdrug) >0)
                                @foreach($patdrug as $pd)
                                    <option value="{{$pd->fldid}}">{{$pd->fldcode}} </option>
                                @endforeach
                            @else
                                <option value="">No Allergic Drugs Found</option>
                            @endif
                        </select>
                    </div>  
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="" class="">Co-morbidities: </label>
                    <textarea cols="68" name="comorbidities" class="form-control" id="comorbidities">{{ isset($preanaesthesia['otherData']['comorbidities']) ? $preanaesthesia['otherData']['comorbidities'] : ''}}</textarea>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <label for="" class="col-sm-4 col-lg-4 pl-0">Clinical findings :</label>
                <div class="res-table" style="max-height: 371px;">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Examination</th>
                            <th>&nbsp;</th>
                            <th>Observation</th>
                            
                            <th>Report Time</th>
                        </tr>
                        </thead>
                        @if(isset($patientexam))
                            <tbody id="js-outpatient-findings-tbody">
                            @foreach($patientexam as $pexam)
                                <tr data-fldid="{{ $pexam->fldid }}">

                                    <td>{{ $pexam->fldhead}}</td>
                                    <td>
                                        <a href="javascript:;" class="clicked_flag @if($pexam->fldabnormal == 0 ) text-success @elseif($pexam->fldabnormal == 1) text-danger @endif ">
                                            <i class="fas fa-square"></i>
                                        </a>
                                    </td>
                                    <?php
                                    $result_clinical_finding = json_decode($pexam->fldrepquali);
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        $observationResult= "";
                                        if (is_array($result_clinical_finding) || is_object($result_clinical_finding)){
                                            foreach ($result_clinical_finding as $key => $val) {
                                                $observationResult .= $key . ': ' . $val . ', ';
                                            }
                                        }
                                    } else {
                                        $observationResult = $pexam->fldrepquali;
                                    }

                                    ?>
                                    <td>{!! $observationResult !!}</td>
                                    
                                    <td>{{ $pexam->fldtime}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                        <tr><td colspan="4">No Data Available</td></tr>
                        @endif
                    </table>
                </div> 
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group er-input">
                    <label for="" class="col-sm-3 col-lg-2 pl-0">Minor Procedure:</label>
                    <div class="col-sm-5 col-lg-4">
                    
                    <input name="anaesthesia_procedure" id="anaesthesia-procedure" type="text" list="procedures" class="form-control" />
                            <datalist id="procedures">
                            @if(isset($minor_procedure) && count($minor_procedure) > 0)
                            @foreach($minor_procedure as $mp)
                            <option value="{{$mp->fldbillitem}}" >{{$mp->fldbillitem}}</option>
                            @endforeach
                            @endif
                        </datalist>
                    </div>
                    <div class=" col-sm-5 col-lg-4 pl-0">
                        <label>{{ isset($preanaesthesia['otherData']['anaesthesia_procedure']) ? $preanaesthesia['otherData']['anaesthesia_procedure'] : ''}}</label>
                    </div>
                </div>
            </div> 
            <div class="col-sm-12 ">
                <div class="form-group er-input">
                    <label for="" class="col-sm-3 col-lg-2 pl-0"> Airway examination:</label>
                    <div class="col-sm-5 col-lg-4">
                        <select name="airway_examination" class="form-control" id="airway_examination">
                            <option value="">---select---</option>
                            <option value="No anticipated difficult airway" {{ (isset($preanaesthesia['otherData']['airway_examination']) and ($preanaesthesia['otherData']['airway_examination'] == 'No anticipated difficult airway')) ? 'selected' : ''}}>No anticipated difficult airway</option>
                            <option value="Anticipated difficult airway" {{ (isset($preanaesthesia['otherData']['airway_examination']) and ($preanaesthesia['otherData']['airway_examination'] == 'Anticipated difficult airway')) ? 'selected' : ''}}>Anticipated difficult airway </option>
                        </select>
                    </div>
                </div>
            </div>
            @if(isset($preanaesthesia['otherData']['airway_examination']) and $preanaesthesia['otherData']['airway_examination'] == 'Anticipated difficult airway')
                @php
                    $style = 'display:block';
                @endphp
            @else
                @php
                    $style = 'display:none';
                @endphp
            @endif
                <div class="col-sm-12 " id="anticipated_issues" style="{{$style}}">
                    <div class="form-group er-input">
                        <label for="" class="col-sm-5 col-lg-4 pl-0">Issues of Anticipated difficult airway:</label>
                        <div class="col-sm-7 col-lg-8">
                            <textarea cols="68" name="airway_issue" class="form-control" id="js-anticipated-issues">{{ isset($preanaesthesia['otherData']['airway_issue']) ? $preanaesthesia['otherData']['airway_issue'] : ''}}</textarea>
                        </div> 
                    </div>
                </div>
            
            
            <div class="col-sm-12">
                <div class="form-group er-input">
                <label for="" class="col-sm-3 col-lg-2 pl-0">Acceptance:</label>
                    <div class="col-sm-4 col-lg-4">
                        <select name="acceptance" class="form-control" id="acceptance">
                            <option value="">---select---</option>
                            <option value="Accepted" {{ (isset($preanaesthesia['otherData']['acceptance']) and ($preanaesthesia['otherData']['acceptance'] == 'Accepted')) ? 'selected' : ''}}>Accepted</option>
                            <option value="Needs optimization" {{ (isset($preanaesthesia['otherData']['acceptance']) and ($preanaesthesia['otherData']['acceptance'] == 'Needs optimization')) ? 'selected' : ''}}>Needs optimization</option>
                        </select>
                    </div>   
                </div>
            </div>
            
                @if(isset($preanaesthesia['otherData']['acceptance']) and $preanaesthesia['otherData']['acceptance'] == 'Accepted')
                    @php
                        $style = 'display:block';
                    @endphp
                @else
                    @php
                        $style = 'display:none';
                    @endphp
                @endif
                <div class="col-sm-12 " id="accepted_option" style="{{$style}}" >
                    <div class="form-group er-input">
                        <label for="" class="col-sm-3 col-lg-2 pl-0">ASA Physical Status:</label>
                    <div class="col-sm-9 col-lg-10 ">
                            <div class="checkbox d-inline-block mr-2">
                                <input type="checkbox" name="physical_status[]" class="custom-checkbox"  value="I" {{(isset($pre_anaes_physicalstatus) and in_array('I',$pre_anaes_physicalstatus)) ? 'checked':''}}/> I<br/>
                            </div>
                            <div class="checkbox d-inline-block mr-2">
                                <input type="checkbox" name="physical_status[]" class="custom-checkbox"  value="IE" {{(isset($pre_anaes_physicalstatus) and in_array('IE',$pre_anaes_physicalstatus)) ? 'checked':''}}/>IE<br/>
                            </div>
                            <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="physical_status[]" class="custom-checkbox"  value="II" {{(isset($pre_anaes_physicalstatus) and in_array('II',$pre_anaes_physicalstatus)) ? 'checked':''}}/> II<br/>
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                                <input type="checkbox" name="physical_status[]" class="custom-checkbox" value="IIE" {{(isset($pre_anaes_physicalstatus) and in_array('IIE',$pre_anaes_physicalstatus)) ? 'checked':''}}/> IIE<br/>
                            </div>
                            <div class="checkbox d-inline-block mr-2">
                                <input type="checkbox" name="physical_status[]" class="custom-checkbox"  value="III" {{(isset($pre_anaes_physicalstatus) and in_array('III',$pre_anaes_physicalstatus)) ? 'checked':''}}/> III<br/>
                            </div>
                            <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="physical_status[]" class="custom-checkbox"  value="IIIE" {{(isset($pre_anaes_physicalstatus) and in_array('IIIE',$pre_anaes_physicalstatus)) ? 'checked':''}}/> IIIE<br/>
                        </div>
                        <div class="checkbox d-inline-block mr-2">
                                <input type="checkbox" name="physical_status[]" class="custom-checkbox"  value="IV" {{(isset($pre_anaes_physicalstatus) and in_array('IV',$pre_anaes_physicalstatus)) ? 'checked':''}}/> IV <br/>
                            </div>
                            <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="physical_status[]" class="custom-checkbox"  value="IVE" {{(isset($pre_anaes_physicalstatus) and in_array('IVE',$pre_anaes_physicalstatus)) ? 'checked':''}}/> IVE<br/>
                        </div>
                                
                        </div>
                    </div>
                </div>
            
            
            <div class="col-sm-12 ">
                <div class="form-group er-input">
                    <label for="" class="col-sm-3 col-lg-2 pl-0">Plan of Anaesthesia:</label>
                    <div class="col-sm-9 col-lg-10 ">

                        <div class="checkbox d-inline-block mr-2">
                        <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="GA" {{(isset($pre_anaes_anaesthesia) and in_array('GA',$pre_anaes_anaesthesia)) ? 'checked':''}}/> GA
                    </div>
                    <div class="checkbox d-inline-block mr-2">
                        <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="SAB" {{(isset($pre_anaes_anaesthesia) and in_array('SAB',$pre_anaes_anaesthesia)) ? 'checked':''}}/>SAB
                    </div>
                        <div class="checkbox d-inline-block mr-2">
                        <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="Epidural" {{(isset($pre_anaes_anaesthesia) and in_array('Epidural',$pre_anaes_anaesthesia)) ? 'checked':''}}/> Epidural
                    </div>
                        <div class="checkbox d-inline-block mr-2">
                        <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="CSEA" {{(isset($pre_anaes_anaesthesia) and in_array('CSEA',$pre_anaes_anaesthesia)) ? 'checked':''}}/>CSEA
                    </div>
                        <div class="checkbox d-inline-block mr-2">
                        <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type" value="Local"{{(isset($pre_anaes_anaesthesia) and in_array('Local',$pre_anaes_anaesthesia)) ? 'checked':''}} /> Local
                    </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="PNS guided" {{(isset($pre_anaes_anaesthesia) and in_array('PNS guided',$pre_anaes_anaesthesia)) ? 'checked':''}}/>PNS guided
                    </div>
                        <div class="checkbox d-inline-block mr-2">
                            
                        <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="USG guided" {{(isset($pre_anaes_anaesthesia) and in_array('USG guided',$pre_anaes_anaesthesia)) ? 'checked':''}}/> USG guided
                    </div>
                        <div class="checkbox d-inline-block mr-2">
                        <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="Peripheral nerve block" {{(isset($pre_anaes_anaesthesia) and in_array('Peripheral nerve block',$pre_anaes_anaesthesia)) ? 'checked':''}}/> Peripheral nerve block
                    </div>
                        <div class="checkbox d-inline-block mr-2">
                            <input type="checkbox" name="anaesthesia_type[]" class="custom-checkbox anaesthesia_type"  value="Anatomical Landmark" {{(isset($pre_anaes_anaesthesia) and in_array('Anatomical Landmark',$pre_anaes_anaesthesia))? 'checked':''}}/>Anatomical Landmark
                    </div>
                        <div class="checkbox d-inline-block mr-2">
                        <a href="javascript:;" class="btn btn-primary" id="addmore_plan">
                            <i class="fa fa-plus"></i>
                            </a> 
                    </div>        
                    </div>
                </div>
            </div>
            @if(isset($preanaesthesia['otherData']['extra_anaesthesia']) and $preanaesthesia['otherData']['extra_anaesthesia'] !='')
                @php
                    $style = 'display:block';
                @endphp
            @else
                @php
                    $style = 'display:none';
                @endphp
            @endif
            <div class="col-sm-12" id="more_anaesthesia_plan" style="{{$style}}">
                <div class="form-group">
                    <label for="" class="">Extra plan of anaesthesia: </label>
                    <textarea cols="68" name="extra_anaesthesia" class="form-control" id="js-plan-anaesthesia">{{ isset($preanaesthesia['otherData']['extra_anaesthesia']) ? $preanaesthesia['otherData']['extra_anaesthesia'] : ''}}</textarea>
                </div>
            </div>
            @if(isset($pre_anaes_anaesthesia) and !empty($pre_anaes_anaesthesia) and in_array('Peripheral nerve block',$pre_anaes_anaesthesia))
                @php
                    $style = 'display:block';
                @endphp
            @else
                @php
                    $style = 'display:none';
                @endphp
            @endif
            <div class="col-sm-12" id="pre_peripheral_nerver_detail" style="{{$style}}">
                <div class="form-group">
                    <label for="" class="">Peripheral nerve block Detail: </label>
                    <textarea class="form-control" name="peripheral_nerve_block_detail" id="js-pre-peripheral-nerve-block-detail">{{ isset($preanaesthesia['otherData']['peripheral_nerve_block_detail']) ? $preanaesthesia['otherData']['peripheral_nerve_block_detail'] : ''}}</textarea>
                </div>
                <!-- <label for="" class="col-sm-3 col-lg-3">Peripheral nerve block Detail:</label>
                <div class="col-sm-8 col-lg-8">
                    <textarea class="form-control" name="peripheral_nerve_block_detail" id="js-peripheral-nerve-block-detail">{{ isset($preanaesthesia['otherData']['peripheral_nerve_block_detail']) ? $preanaesthesia['otherData']['peripheral_nerve_block_detail'] : ''}}</textarea>
                </div> -->
            </div>        
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="" class="">Risk Stratification: </label>
                    <textarea cols="68" name="stratification_risk" class="form-control" id="js-riskstratification">{{ isset($preanaesthesia['otherData']['stratification_risk']) ? $preanaesthesia['otherData']['stratification_risk'] : ''}}</textarea>
                </div>
            </div>
            <div class="col-sm-6 ">
                <div class="form-group">
                    <label for="" class="">Advice:</label>
                    <textarea cols="68" name="advice" class="form-control" id="js-advice">{{ isset($preanaesthesia['otherData']['advice']) ? $preanaesthesia['otherData']['advice'] : ''}}</textarea>
                </div>
            </div>
            <input type="hidden" name="fldencounterval" id="fldencounterval" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif "/>
        
        </div>
    </form>

        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="justify-content-around">
                        <a href="javascript:void(0);"  class="mr-4 btn btn-primary" onclick="savePreAnaesthesiaDetail()">
                                Save
                            </a>
                         <a href="#" target="_blank" class="mr-4 btn btn-primary">
                                History
                            </a>
                    </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        CKEDITOR.replace('js-riskstratification',
        {
        height: '200px',
        } );
        CKEDITOR.replace('js-advice',
        {
        height: '200px',
        } );
        CKEDITOR.replace('js-anticipated-issues',
        {
        height: '200px',
        } );
        
        CKEDITOR.replace('comorbidities',
        {
        height: '200px',
        } );
        
        CKEDITOR.replace('js-plan-anaesthesia',
        {
        height: '200px',
        } );
        
        CKEDITOR.replace('js-pre-peripheral-nerve-block-detail',
        {
        height: '200px',
        } );
    });
    
    $('#airway_examination').on('change', function(){
        var value = $(this).val();
        if(value === 'Anticipated difficult airway'){
           $('#anticipated_issues').show();
        }else{
            $('#anticipated_issues').hide();
        }
    });
   $("#addmore_plan").click(function(){
        // show hide paragraph on button click
        // $("#more_anaesthesia_plan").toggle("slow", function(){
            // check paragraph once toggle effect is completed
            if($("#more_anaesthesia_plan").is(":hidden")){
                $('#more_anaesthesia_plan').show();
            } else{
                $('#more_anaesthesia_plan').hide();
            }
        // });
    });
   $('.anaesthesia_type').on("click", function(e) {
        var checkvalue = $(this).val();
        alert(checkvalue);
        if(checkvalue === 'Peripheral nerve block'){
            
            if($("#pre_peripheral_nerver_detail").is(":hidden")){
                $('#pre_peripheral_nerver_detail').show();
            } else{
                $('#pre_peripheral_nerver_detail').hide();
            }
        }
    });
    $('#acceptance').on('change', function(){
        var value = $(this).val();
        if(value === 'Accepted'){
           $('#accepted_option').show();
        }else{
            $('#accepted_option').hide();
        }
    });

    function savePreAnaesthesiaDetail(){
        // alert('saveanaesthesia');
        var url = "{{route('savePreAnaesthesia')}}";
        var alldata = $("#preanesthesia-form").serialize();
        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].updateElement();
        };
        $.ajax({
            url: url,
            type: "POST",
            data:  $("#preanesthesia-form").serialize(),"_token": "{{ csrf_token() }}",
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