<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Vital Examination</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="">BP(mm/Hg)</label>
                        <div class="er-input">
                            <input type="text" class="form-control @if(isset($systolic_bp->fldrepquanti) &&  $systolic_bp->fldrepquanti <=  $systolic_bp->fldlow) lowline @endif  @if(isset($systolic_bp->fldrepquanti) &&  $systolic_bp->fldrepquanti >=  $systolic_bp->fldhigh) highline @endif   remove_zero_to_empty" id="sys_bp_emergency" placeholder="" vital_type="sys_bp" sys_bp="Systolic BP" value="{{ isset($systolic_bp->fldrepquanti) ?  $systolic_bp->fldrepquanti : 0  }}">&nbsp;
                            <input type="text" class="form-control @if(isset($diasioli_bp->fldrepquanti) &&  $diasioli_bp->fldrepquanti <=  $diasioli_bp->fldlow) lowline @endif  @if(isset($diasioli_bp->fldrepquanti) &&  $diasioli_bp->fldrepquanti >=  $diasioli_bp->fldhigh) highline @endif   remove_zero_to_empty" id="dia_bp_emergency" placeholder="" vital_type="dia_bp" dia_bp="Diastolic BP" value="{{ isset($diasioli_bp->fldrepquanti) ? $diasioli_bp->fldrepquanti : 0  }}">

                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">Pulse Rate(B/min)</label>
                        <input type="text" class="form-control @if(isset($pulse->fldrepquanti) &&  $pulse->fldrepquanti <=  $pulse->fldlow) lowline @endif  @if(isset($pulse->fldrepquanti) &&  $pulse->fldrepquanti >=  $pulse->fldhigh) highline @endif   remove_zero_to_empty" id="pulse_rate_emergency" placeholder="" pulse_rate="Pulse Rate"  vital_type="pulse_rate"  value="{{ isset($pulse->fldrepquanti) ?  $pulse->fldrepquanti : 0 }}">

                    </div>
                </div>


                <div class="col-sm-2 p-0">
                    <div class="form-group">
                        <label for="">Resp Rate(/min)</label>
                        <input type="text" class="form-control @if(isset($respiratory_rate->fldrepquanti) &&  $respiratory_rate->fldrepquanti <=  $respiratory_rate->fldlow) lowline @endif  @if(isset($respiratory_rate->fldrepquanti) &&  $respiratory_rate->fldrepquanti >=  $respiratory_rate->fldhigh) highline @endif   remove_zero_to_empty"  vital_type="respi"  id="respi_emergency" placeholder="" respi="Respiratory Rate" value="{{ isset($respiratory_rate->fldrepquanti) ? $respiratory_rate->fldrepquanti : 0 }}">

                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="">Temp(F)</label>
                        <input type="text" class="form-control  @if(isset($temperature->fldrepquanti) &&  $temperature->fldrepquanti <=  $temperature->fldlow) lowline @endif  @if(isset($temperature->fldrepquanti) &&  $temperature->fldrepquanti >=  $temperature->fldhigh) highline @endif  remove_zero_to_empty" id="pulse_rate_rate_emergency"  vital_type="pulse_rate_rate"  placeholder="" pulse_rate_rate="Temperature (F)" value="{{ isset($temperature->fldrepquanti) ?  $temperature->fldrepquanti : 0 }}">

                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="">GRBS(mg/dl)</label>
                        <input type="text" class="form-control @if(isset($grbs->fldrepquanti) &&  $grbs->fldrepquanti <=  $grbs->fldlow) lowline @endif  @if(isset($grbs->fldrepquanti) &&  $grbs->fldrepquanti >=  $grbs->fldhigh) highline @endif   remove_zero_to_empty" id="grbs" placeholder="" vital_type="gcs"  grbs="GRBS" value="{{ isset($grbs->fldrepquanti) ? $grbs->fldrepquanti : 0 }}">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label for="">SPO2(%)</label>
                        <input type="text" class="form-control @if(isset($o2_saturation->fldrepquanti) &&  $o2_saturation->fldrepquanti <=  $o2_saturation->fldlow) lowline @endif  @if(isset($o2_saturation->fldrepquanti) &&  $o2_saturation->fldrepquanti >=  $o2_saturation->fldhigh) highline @endif   remove_zero_to_empty" id="saturation_emergency"  vital_type="saturation"  placeholder="" saturation="O2 Saturation" value="{{ isset($o2_saturation->fldrepquanti) ? $o2_saturation->fldrepquanti : 0 }}">

                    </div>
                </div>

            </div>
            <div class="d-flex justify-content-center mt-3">
                <a href="javascript:;" type="button" class="btn btn-primary rounded-pill disableInsertUpdate" url="{{ route('insert_essential_exam_emergency') }}" id="save_essential_emergency">
                Vital Save</a>
                <input type="hidden" id="check_vital" url="{{route('check_vital_emergency')}}">
            </div>
        </div>
    </div>
</div>

<script>
    $("#pulse_rate_emergency,#pulse_rate_rate_emergency,#sys_bp_emergency,#dia_bp_emergency,#respi_emergency,#saturation_emergency,#grbs").on("focusout", function(){
        var value = $(this).val();
        // var vital = $(this).attr('id');
        var vital = $(this).attr('vital_type');
        var $this = $(this);
        var url = $('#check_vital').attr('url');
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {value:value,vital:vital},

            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    $this.removeClass('highline');
                    $this.removeClass('lowline');

                    $this.addClass(data.success.message);

                } else {
                    showAlert("Something went wrong!!");
                }
            }
        });


    });
</script>
