<style type="text/css">
    .form-group-inner.custom-11 select {
    width: 100%;
    height: 200px;
}
</style>
@php
    $encounterData = $encounter[0];
    $encounterDataPatientInfo = $encounter[0]->patientInfo;
@endphp
<div class="form-group row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="col-sm-2 col-form-label col-form-label-sm">Name</label>
            <div class="col-sm-8">
                <input type="text" name="searchName" class="form-control form-control-sm" id="name" placeholder="Name" value="@if(isset($patient)) {{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{  $patient->fldptnamefir }} {{ $patient->fldmidname }} {{  $patient->fldptnamelast }}@endif" readonly>
            </div>
        </div>
    </div>
    <!-- <div class="col-md-3">
        <div class="form-group">

            <input type="checkbox" name="keypad" class="col-sm-2" id="keypad" style="margin-top: 10px;">

            <label for="Keypad" class="col-sm-8 col-form-label col-form-label-sm">Display Keypad</label>
        </div>


    </div> -->
    <div class="col-md-3">
        <div class="form-group">
            <label for="bedno" class="col-sm-3 col-form-label col-form-label-sm">Gender</label>
            <div class="col-sm-8">
                <input type="text" name="gender" class="col-sm-8 form-control form-control-sm" id="gender" placeholder="Gender" value="@if(isset($encounterDataPatientInfo)){{ $encounterDataPatientInfo->fldptsex }}@endif" >
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-4">
        <div class="form-group-inner custom-11">
        <select name="" id="select-multiple-diagno" class="form-input" multiple>
            @if(isset($patdiago) and count($patdiago) > 0)
                @foreach($patdiago as $patdiag)
                    <option value="{{$patdiag->fldid}}">{{$patdiag->fldcode}}<a class="right_del" href="{{route('deletepatfinding',$patdiag->fldid)}}" onclick="return confirm('Are you sure you want to delete this Allergic Drug?');"><i class="fas fa-trash-alt"></i></a></option>
                @endforeach
            @else
                <option value="">No Diagnosis Found</option>
        @endif
        <!-- <option value="1">Abnormal Microbiolog finding in special</option>
            <option value="2">Cloudy (hemodialysis) (peritoneal daily</option>
            <option value="3">Lower Abdominal pain, Unspecified</option>
            <option value="4">Mero</option> -->
        </select>
    </div>
    </div>
</div>
