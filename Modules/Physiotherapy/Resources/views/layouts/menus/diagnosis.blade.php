@php
    $encounter_id = (isset($enpatient) && $enpatient->fldencounterval)? $enpatient->fldencounterval : '';
    $pat_findings_physiotherapy = \App\Utils\Physiotherapyhelpers::getPatFindings($encounter_id);

@endphp
<div id="diagnosis" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="">
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Provisional Diagnosis:</h4>
                            </div>
                            <div>
                                @if(isset($enable_freetext) and $enable_freetext  == true)
                                    <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" onclick="Provisionaldiagnosisfreetext.displayModal()">Free</a>
                                @endif
                                @if(isset($patient) and $patient->fldptsex == 'Female')
                                    <a href="javascript:void(0);" class="btn btn-warning" id="pro_obstetric" data-toggle="modal" data-diagnosistype="Provisional" data-target="#diagnosis-obstetric-modal">OBS</a>
                                @endif

                                <a href="javascript:void(0);" class="btn btn-success"  id="pro_icd_diagnosis" data-toggle="modal" data-diagnosistype="Provisional" data-target="#provisionalicddiagnosis">ICD</a>

                                <a href="javascript:void(0);" class="btn btn-danger" id="delete__provisional_item">Delete</a>

                                {{--<a href="javascript:void(0);" class="btn btn-danger" id="deletealdiagno"><i class="ri-delete-bin-6-line"></i></a>--}}
                            </div>
                        </div>

                        <div class="iq-card-body">
                            <form action="" class="form-horizontal">
                                <div class="form-group mb-0">
                                    <select class="form-control" multiple id="provisional_delete">
                                        @if(isset($pat_findings_physiotherapy))
                                            @foreach($pat_findings_physiotherapy as $provisional)
                                                @if($provisional->fldtype == 'Provisional Diagnosis')
                                                    <option value="{{ $provisional->fldid }}">{{ $provisional->fldcode }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </form>
                        </div>
                        {{--<div class="form-group mb-0">--}}
                            {{--<textarea name="provisional_diagnosis_textarea" id="js-provisional-diagnosis-textarea" class="form-control" rows="10"></textarea>--}}
                        {{--</div><br>--}}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="">
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Final Diagnosis:</h4>
                            </div>

                            <div>
                                @if(isset($enable_freetext) and $enable_freetext  == true)
                                    <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" onclick="Finaldiagnosisfreetext.displayModal()">Free</a>
                                @endif

                                @if(isset($patient) and $patient->fldptsex == 'Female')
                                    <a href="javascript:void(0);" class="btn btn-warning" id="final_obstetric" data-toggle="modal"  data-diagnosistype="Final" data-target="#diagnosis-obstetric-modal">OBS</a>
                                @endif

                                <a href="javascript:void(0);" class="btn btn-success" data-toggle="modal" id="final_icd_diagnosis" data-diagnosistype="Final" data-target="#provisionalicddiagnosis">ICD</a>

                                <a href="javascript:void(0);" class="btn btn-danger" id="delete__final_item">Delete</a>

                                {{--<a href="javascript:void(0);" class="btn btn-danger" id="deletealdiagno"><i class="ri-delete-bin-6-line"></i></a>--}}
                            </div>
                        </div>

                        <div class="iq-card-body">
                            <form action="" class="form-horizontal">
                                <div class="form-group mb-0">
                                    <select class="form-control" multiple id="final_delete">
                                        @if(isset($pat_findings_physiotherapy))
                                            @foreach($pat_findings_physiotherapy as $provisional)
                                                @if($provisional->fldtype == 'Final Diagnosis')
                                                    <option value="{{ $provisional->fldid }}">{{ $provisional->fldcode }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="col-sm-6">
                    <div class="">
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Past:</h4>
                            </div>

                            {{--<div>--}}
                                {{--<a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" onclick="Pastdiagnosisfreetext.displayModal()">Free</a>--}}

                                {{--<a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" data-target="#pastobstetricdiagnosis">OBS</a>--}}

                                {{--<a href="javascript:void(0);" class="btn btn-success">ICD</a>--}}

                                {{--<a href="javascript:void(0);" class="btn btn-danger" id="delete__past_item">Delete</a>--}}

                                {{--<a href="javascript:void(0);" class="btn btn-danger" id="deletealdiagno"><i class="ri-delete-bin-6-line"></i></a>--}}
                            {{--</div>--}}
                        </div>

                        <div class="iq-card-body">
                            <form action="" class="form-horizontal">
                                <div class="form-group mb-0">
                                    <select class="form-control" multiple id="past_delete">
                                        @if(isset($pat_findings_physiotherapy))
                                            @foreach($pat_findings_physiotherapy as $diagnosis)
                                                @if($diagnosis->fldtype == 'Provisional Diagnosis' || $diagnosis->fldtype == 'Final Diagnosis')
                                                    <option value="{{ $diagnosis->fldid }}">{{ $diagnosis->fldcode }}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option value="">No Diagnosis Found</option>
                                        @endif
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">

                </div>
                {{--<div class="col-sm-12">--}}
                    {{--<div class="form-group form-row align-items-center mt-3">--}}
                        {{--<div class="col-sm-3">--}}
                            {{--<button type="add" id="js-diagnosis-add-btn"  class="btn btn-primary {{ $disableClass }}" type="button">--}}
                                {{--<i class="fa fa-check"></i>&nbsp;Save--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#pro_icd_diagnosis').click(function() {

            var dignosistype = $(this).data('diagnosistype');

            $('#diagnosis_type').val(dignosistype);

        });

        $('#final_icd_diagnosis').click(function() {
            var diagnosistype = $(this).data('diagnosistype');

            $('#diagnosis_type').val(diagnosistype);
        });

        $('#pro_obstetric').click(function() {
            var dignosistype = $(this).data('diagnosistype');

            $('#diagnosis_type_obs').val(dignosistype);
        });

        $('#final_obstetric').click(function() {
            var dignosistype = $(this).data('diagnosistype');

            $('#diagnosis_type_obs').val(dignosistype);
        });



    });
</script>
