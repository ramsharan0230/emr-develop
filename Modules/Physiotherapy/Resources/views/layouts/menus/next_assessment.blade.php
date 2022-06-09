@php
    $encounter_id = (isset($enpatient) && $enpatient->fldencounterval)? $enpatient->fldencounterval : '';

    $nextAssessment = \App\Utils\Physiotherapyhelpers::getExamgeneral($encounter_id, 'Next Assessment');

@endphp
<div id="next_assessment" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="">
                        <div class="iq-card-header d-flex justify-content-between p-0">
                            <div class="iq-header-title">
                                <h4 class="card-title">Next Assessment:</h4>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <textarea name="next_assessment_textarea" id="next_assessment_textarea" class="form-control" rows="10">{{ (isset($nextAssessment) && $nextAssessment != NULL) ? $nextAssessment->flddetail : '' }}</textarea>
                        </div><br>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            {{--<h4 class="card-title">Followup date</h4>--}}
                        </div>
                    </div>
                    <div class="form-group form-row align-it-center">
                            <div class="col-sm-3">
                                <label for="">Followup Date</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="followup_date" id="followup_date" readonly>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-phar">
                                    <a href="javascript:;" data-toggle="modal" data-target="#nepali_followup_date" id="followup_date_bs"><i class="far fa-calendar-alt fa-2x" id="pickdate"></i></a>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="col-sm-12">
                    <div class="form-group mt-3">
                        
                            <button type="add" id="js-next-assessment-add-btn"  class="btn btn-primary btn-action float-right {{ $disableClass }}" type="button" url="{{ route('physiotherapy.nextAssessment.save') }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                <i class="fa fa-check"></i>&nbsp;Save
                            </button>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{asset('js/nepali.datepicker.v2.2.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/nepali.datepicker.v2.2.min.css')}}" />

<script type="text/javascript">
    $('#followup_date_bs').nepaliDatePicker({

        npdMonth    : true,
        npdYear     : true,
        npdYearCount: 100,
        // disableDaysAfter: '1',
        onChange    : function(){
            var datebs = $('#followup_date_bs').val();
            $.ajax({
                type    : 'post',
                url     : '{{ route("patient.request.menu.nepalitoenglish") }}',
                data    : {date:datebs,},
                success: function (response) {
                    $('#followup_date').val(response);

                }
            });
        }

    });
</script>