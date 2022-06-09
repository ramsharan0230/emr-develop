<!-- <style type="text/css">
    .hidden{
        display: none;
    }
</style> -->
<div class="row mt-3">
<div class="col-md-12" style="padding-left: 0; padding-right: 0;">
    <div class="cogent-nav" style="padding-left: 0;">
        <div class="tab-1" >
            <ul class="nav nav-tabs" id="yourTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#basicinformation" role="tab" aria-controls="home" aria-selected="true">Basic Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#basicretoration" role="tab" aria-controls="basic" aria-selected="false">Dental Restoration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#orthodoxtic" role="tab" aria-controls="ortho" aria-selected="false">Orthodoxtic Finding</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#dentalanomolies" role="tab" aria-controls="dentl" aria-selected="false">Dental Anomolies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#cephalometric" role="tab" aria-controls="cephal" aria-selected="false">Cephalometric Finding</a>
                </li>
            </ul>
            <div class="tab-content" id="nav-tabContent" style="height: 408px;">
                <div class="tab-pane fade show active" id="basicinformation" role="tabpanel" aria-labelledby="home-tab">
                    <form action="{{route('dental.teethData')}}" method="POST" style=" margin-left: 16px">
                        @csrf
                        <div class="mt-3">
                            <input type="checkbox" id="checkbox_one" name="" data-trigger="hidden_fields_one" class="trigger">
                            <label class="label-dental">IMD/Clicks/Muscle pain</label>
                        </div>
                        <div id="hidden_fields_one" class="hidden">
                            <textarea id="hidden_one" name="Imd_Click_Muscle_Pain" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hidden_one" name="Imd_Click_Muscle_Pain"> -->
                        </div>

                        <div class="mt-2">
                            <input type="checkbox" id="checkbox_two" data-trigger="hidden_fields_two" class="trigger">
                            <label class="label-dental">Soft Tissue Lesion</label>
                        </div>
                        <div id="hidden_fields_two" class="hidden">
                            <input type="text" id="hidden_two" name="Soft_Tissue_Lesion">
                        </div>

                        <div class="mt-2">
                            <input type="checkbox" id="checkbox_three" data-trigger="hidden_fields_three" class="trigger">
                            <label class="label-dental"> Smoker</label>
                        </div>
                        <div id="hidden_fields_three" class="hidden">
                            <input type="text" id="hidden_three" name="Smoker">
                        </div>

                        <div class="mt-2">
                            <input type="checkbox" id="checkbox_four" name="" data-trigger="hidden_fields_four" class="trigger">
                            <label class="label-dental">Periodental Diseases</label>
                        </div>
                        <div id="hidden_fields_four" class="hidden">
                            <select name="Periodental_Diseases" id="hidden_four">
                                <option value="">--Select--</option>
                                <option value="Needs Scaling">Needs Scaling</option>
                                <option value="Needs Oral Hygiene">Needs Oral Hygiene</option>
                                <option value="Instruction">Instruction</option>
                            </select>
                            <!-- <input type="text" id="hidden_four" name="Periodental_Diseases"> -->
                        </div>
                        <div class="mt-2">
                            <input type="checkbox" id="checkbox_five" name="" data-trigger="hidden_fields_five" class="trigger">
                            <label class="label-dental">Gingival Recession</label>
                        </div>
                        <div id="hidden_fields_five" class="hidden">
                            <textarea id="hidden_five" name="Gingival_Recession" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hidden_five" name="Gingival_Recession"> -->
                        </div>
                        <input type="hidden"  name="dental_fldhead" value="Basic Information">
                        <input type="hidden"  name="dental_teeth" value="{{$teeth}}">
                        <div><button class="btn btn-success">Save</button></div>
                    </form>
                </div>
                <div class="tab-pane fade" id="basicretoration" role="tabpanel">
                    <form action="{{route('dental.teethData')}}" method="POST" style=" margin-left: 16px">
                        @csrf
                        <div class="mt-2">
                            <input type="checkbox" id="checkboxretro_one" name="" data-trigger="hiddenretro_fields2_one" class="trigger">
                            <label class="label-dental">Crown</label>
                        </div>
                        <div id="hiddenretro_fields2_one" class="hidden">
                            <textarea id="hidden2retro_one" name="Crown" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hidden2retro_one" name="Crown"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxretro_two" name="" data-trigger="hiddenretro_fields2_two" class="trigger">
                            <label class="label-dental">RCTS</label>
                        </div>
                        <div id="hiddenretro_fields2_two" class="hidden">
                            <textarea id="hidden2retro_two" name="Rcts" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hidden2retro_two" name="Rcts"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxretro_three" data-trigger="hiddenretro_fields2_three" class="trigger">
                            <label class="label-dental">Fillings</label>
                        </div>
                        <div id="hiddenretro_fields2_three" class="hidden">
                            <textarea id="hiddenretro_three" name="Fillings" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hiddenretro_three" name="Fillings"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxretro_four" name="" data-trigger="hiddenretro_fields_four" class="trigger">
                            <label class="label-dental">Tooth Wears</label>
                        </div>
                        <div id="hiddenretro_fields_four" class="hidden">
                            <textarea id="hiddenretro_four" name="Tooth_Wears" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hiddenretro_four" name="Tooth_Wears"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxretro_five" name="" data-trigger="hiddenretro_fields_five" class="trigger">
                            <label class="label-dental">Extraction</label>
                        </div>
                        <div id="hiddenretro_fields_five" class="hidden">
                            <textarea id="hiddenretro_five" name="Extraction" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hiddenretro_five" name="Extraction"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxretro_six" name="" data-trigger="hiddenretro_fields_six" class="trigger">
                            <label class="label-dental">Impacted Teeth</label>
                        </div>
                        <div id="hiddenretro_fields_six" class="hidden">
                            <textarea id="hiddenretro_six" name="Impacted_Teeth" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hiddenretro_six" name="Impacted_Teeth"> -->
                        </div>
                        <input type="hidden"  name="dental_fldhead" value="Dental Restoration">
                        <input type="hidden"  name="dental_teeth" value="{{$teeth}}">
                        <div><button class="btn btn-success">Save</button></div>
                    </form>
                </div>
                <div class="tab-pane fade" id="orthodoxtic" role="tabpanel">
                    <form action="{{route('dental.teethData')}}" method="POST" style=" margin-left: 16px">
                        @csrf
                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_one" name="" data-trigger="hiddenortho_fields2_one" class="trigger">
                            <label class="label-dental">Malocclusion</label>
                        </div>
                        <div id="hiddenortho_fields2_one" class="hidden">
                            <select name="Malocclusion" id="hidden2ortho_one">
                                <option value="">--Select--</option>
                                <option value="CI I   CI IIdiv 1">CI I   CI IIdiv 1</option>
                                <option value="CI II div2">CI II div2</option>
                                <option value="CI III">CI III</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_two" name="" data-trigger="hiddenortho_fields2_two" class="trigger">
                            <label class="label-dental">Sagittal Sekeleton Pattern</label>
                        </div>
                        <div id="hiddenortho_fields2_two" class="hidden">
                            <select name="Sagittal_Sekeleton_Pattern" id="hidden2ortho_two">
                                <option value="">--Select--</option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_three" data-trigger="hiddenortho_fields2_three" class="trigger">
                            <label class="label-dental">Vertical Sekeleton Pattern</label>
                        </div>
                        <div id="hiddenortho_fields2_three" class="hidden">
                            <select name="Vertical_Sekeleton_Pattern" id="hidden2ortho_three">
                                <option value="">--Select--</option>
                                <option value="Increased">Increased</option>
                                <option value="Average">Average</option>
                                <option value="Decreased">Decreased</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_four" name="" data-trigger="hiddenortho_fields_four" class="trigger">
                            <label class="label-dental">Position Of Upper jaw</label>
                        </div>
                        <div id="hiddenortho_fields_four" class="hidden">
                            <select name="Position_Of_Upper_jaw" id="hidden2ortho_four">
                                <option value="">--Select--</option>
                                <option value="Normal">Normal</option>
                                <option value="Retruded">Retruded</option>
                                <option value="Protroded">Protroded</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_five" name="" data-trigger="hiddenortho_fields_five" class="trigger">
                            <label class="label-dental">Position Of Lower jaw</label>
                        </div>
                        <div id="hiddenortho_fields_five" class="hidden">
                            <select name="Position_Of_Lower_jaw" id="hidden2ortho_five">
                                <option value="">--Select--</option>
                                <option value="Normal">Normal</option>
                                <option value="Retruded">Retruded</option>
                                <option value="Protroded">Protroded</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_six" name="" data-trigger="hiddenortho_fields_six" class="trigger">
                            <label class="label-dental">Lip Position</label>
                        </div>
                        <div id="hiddenortho_fields_six" class="hidden">
                            <select name="Lip_Position" id="hidden2ortho_six">
                                <option value="">--Select--</option>
                                <option value="Normal">Normal</option>
                                <option value="Incompetent">Incompetent</option>
                                <option value="Protruded Lips">Protruded Lips</option>
                                <option value="Retruded Lips">Retruded Lips</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_seven" name="" data-trigger="hiddenortho_fields_seven" class="trigger">
                            <label class="label-dental">Gum Exposure</label>
                        </div>
                        <div id="hiddenortho_fields_seven" class="hidden">
                            <select name="Gum_Exposure" id="hidden2ortho_seven">
                                <option value="">--Select--</option>
                                <option value="Average">Average</option>
                                <option value="Too Much">Too Much</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_eight" name="" data-trigger="hiddenortho_fields_eight" class="trigger">
                            <label class="label-dental">Overbite</label>
                        </div>
                        <div id="hiddenortho_fields_eight" class="hidden">
                            <select name="Overbite" id="hidden2ortho_eight">
                                <option value="">--Select--</option>
                                <option value="Increased">Increased</option>
                                <option value="Decreased">Decreased</option>
                                <option value="Average">Average</option>
                                <option value="Openbit">Openbit</option>
                                <option value="Depbite">Depbite</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_nine" name="" data-trigger="hiddenortho_fields_nine" class="trigger">
                            <label class="label-dental">Crowding</label>
                        </div>
                        <div id="hiddenortho_fields_nine" class="hidden">
                            <select name="Crowding" id="hidden2ortho_nine">
                                <option value="">--Select--</option>
                                <option value="Upper arch">Upper arch</option>
                                <option value="Lower arch">Lower arch</option>
                                <option value="Both arches">Both arches</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_ten" name="" data-trigger="hiddenortho_fields_ten" class="trigger">
                            <label class="label-dental">Crossbite</label>
                        </div>
                        <div id="hiddenortho_fields_ten" class="hidden">
                            <select name="Crossbite" id="hidden2ortho_six">
                                <option value="">--Select--</option>
                                <option value="Anterior">Anterior</option>
                                <option value="Posterior">Posterior</option>
                            </select>
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_eleven" name="" data-trigger="hiddenortho_fields2_eleven" class="trigger">
                            <label class="label-dental">Overjet</label>
                        </div>
                        <div id="hiddenortho_fields2_eleven" class="hidden">
                            <textarea id="hidden2ortho_eleven" name="Overjet" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hidden2ortho_eleven" name="Overjet"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxortho_twelve" data-trigger="hiddenortho_fields2_twelve" class="trigger">
                            <label class="label-dental">Scissors Bite</label>
                        </div>
                        <div id="hiddenortho_fields2_twelve" class="hidden field-btm">
                            <textarea id="hiddenortho_twelve" name="Scissors_Bite" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hiddenortho_twelve" name="Scissors_Bite"> -->
                        </div>
                        <input type="hidden"  name="dental_fldhead" value="Orthodoxtic Finding">
                        <input type="hidden" name="dental_teeth" value="{{$teeth}}">
                        <div><button class="btn btn-success">Save</button></div>
                    </form>
                </div>
                <div class="tab-pane fade" id="dentalanomolies" role="tabpanel">
                    <form action="{{route('dental.teethData')}}" method="POST" style=" margin-left: 16px">
                        @csrf
                        <div class="mt-2">
                            <input type="checkbox" id="checkboxdental_one" name="" data-trigger="hiddendental_fields2_one" class="trigger">
                            <label class="label-dental">Hypodontia</label>
                        </div>
                        <div id="hiddendental_fields2_one" class="hidden">
                            <textarea id="hidden2dental_one" name="Hypodontia" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hidden2dental_one" name="hidden"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxdental_two" name="" data-trigger="hiddendental_fields2_two" class="trigger">
                            <label class="label-dental">Super Numerary Teeth</label>
                        </div>
                        <div id="hiddendental_fields2_two" class="hidden">
                            <textarea id="hidden2dental_two" name="Super_Numerary_Teeth" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hidden2dental_two" name="hidden"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxdental_three" data-trigger="hiddendental_fields2_three" class="trigger">
                            <label class="label-dental">Small Teeth</label>
                        </div>
                        <div id="hiddendental_fields2_three" class="hidden">
                            <textarea id="hiddendental_three" name="Small_Teeth" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hiddendental_three" name="hidden"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxdental_four" name="" data-trigger="hiddendental_fields_four" class="trigger">
                            <label class="label-dental">Malformed Teeth</label>
                        </div>
                        <div id="hiddendental_fields_four" class="hidden">
                            <textarea id="hiddendental_four" name="Malformed_Teeth" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hiddendental_four" name="hidden"> -->
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxdental_five" name="" data-trigger="hiddendental_fields_five" class="trigger">
                            <label class="label-dental">Short Abnormal Roots</label>
                        </div>
                        <div id="hiddendental_fields_five" class="hidden">
                            <textarea id="hiddendental_five" name="Short_Abnormal_Roots" class="ck-eye" ></textarea>
                            <!-- <input type="text" id="hiddendental_five" name="hidden"> -->
                        </div>
                        <input type="hidden" name="dental_fldhead" value="Dental Anomolies">
                        <input type="hidden"  name="dental_teeth" value="{{$teeth}}">
                        <div><button class="btn btn-success">Save</button></div>
                    </form>
                </div>
                <div class="tab-pane fade" id="cephalometric" role="tabpanel">
                    <form action="{{route('dental.teethData')}}" method="POST" style=" margin-left: 16px">
                        @csrf
                        <div class="mt-2">
                            <input type="checkbox" id="checkboxcepha" name="" data-trigger="hiddencepha_fields2" class="trigger">
                            <label class="label-dental">SNA</label>
                        </div>
                        <div id="hiddencepha_fields2" class="hidden">
                            <input type="number" id="hidden2cepha" name="Sna" class="number" onkeypress="return isNumberKey(event)">
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxcepha_one" name="" data-trigger="hiddencepha_fields2_one" class="trigger">
                            <label class="label-dental">SNB</label>
                        </div>
                        <div id="hiddencepha_fields2_one" class="hidden">
                            <input type="number" id="hidden2cepha_one" name="Snb" class="number" onkeypress="return isNumberKey(event)">
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxcepha_two" name="" data-trigger="hiddencepha_fields2_two" class="trigger">
                            <label class="label-dental">ANB</label>
                        </div>
                        <div id="hiddencepha_fields2_two" class="hidden">
                            <input type="number" id="hidden2cepha_two" name="Anb" class="number" onkeypress="return isNumberKey(event)">
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxcepha_three" data-trigger="hiddencepha_fields2_three" class="trigger">
                            <label class="label-dental">Upper Incisor to Mandibular Plane Angle</label>
                        </div>
                        <div id="hiddencepha_fields2_three" class="hidden">
                            <input type="number" id="hiddencepha_three" name="Upper_Incisor_To_Mandibular_Plane_Angle" class="number" onkeypress="return isNumberKey(event)">
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxcepha_four" name="" data-trigger="hiddencepha_fields_four" class="trigger">
                            <label class="label-dental">Lower Incisor to Mandibular Plane Angle</label>
                        </div>
                        <div id="hiddencepha_fields_four" class="hidden">
                            <input type="number" id="hiddencepha_four" name="Lower_Incisor_To_Mandibular_Plane_Angle" class="number" onkeypress="return isNumberKey(event)">
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxcepha_five" name="" data-trigger="hiddencepha_fields_five" class="trigger">
                            <label class="label-dental">Maxillary Mandibular Plane Angle</label>
                        </div>
                        <div id="hiddencepha_fields_five" class="hidden">
                            <input type="number" id="hiddencepha_five" name="Maxillary_Mandibular_Plane_Angle" class="number" onkeypress="return isNumberKey(event)">
                        </div>

                        {{-- next form --}}

                        <div class="mt-2">
                            <input type="checkbox" id="checkboxcepha_six" name="" data-trigger="hiddencepha_fields_six" class="trigger">
                            <label class="label-dental">Lower Face Height (%)</label>
                        </div>
                        <div id="hiddencepha_fields_six" class="hidden btm">
                            <input type="text" id="hiddencepha_six" name="Lower_Face_Height" class="number" onkeypress="return isNumberKey(event)">
                        </div>
                        <input type="hidden"  name="dental_fldhead" value="Cephalometric Finding">
                        <input type="hidden" name="dental_teeth" value="{{$teeth}}">
                        <div><button class="btn btn-success">Save</button></div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // CKEDITOR.replace('hidden_one');
    // CKEDITOR.replace('hidden_five');
    // CKEDITOR.replace('hidden2retro_one');
    // CKEDITOR.replace('hidden2retro_two');
    // CKEDITOR.replace('hiddenretro_three');
    // CKEDITOR.replace('hiddenretro_four');
    // CKEDITOR.replace('hiddenretro_five');
    // CKEDITOR.replace('hiddenretro_six');
    // CKEDITOR.replace('hidden2ortho_eleven');
    // CKEDITOR.replace('hiddenortho_twelve');
    // CKEDITOR.replace('hidden2dental_one');
    // CKEDITOR.replace('hidden2dental_two');
    // CKEDITOR.replace('hiddendental_three');
    // CKEDITOR.replace('hiddendental_four');
    // CKEDITOR.replace('hiddendental_five');

    $('.trigger').on('click',function(){
        //alert('trigger clicked');
        //// $(this).children(".hidden").removeClass("hidden");
        $($(this).find("div")[0]).removeClass("hidden");
    });
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>