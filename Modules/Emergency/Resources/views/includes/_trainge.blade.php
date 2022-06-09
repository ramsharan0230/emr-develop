<style>
    .btm{
        width:48px;
    }

    .btm:hover{
        cursor: pointer;
    }
</style>
<div class="col-sm-10">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Triage </h4>
            </div>
        </div>
        <div class="iq-card-body">
            <div class="emergency-trainge " id="painsg">

                <div class="circle" style="display:none"></div>
                <div class="box__color">
                    <div class="pain-emoji"><a href="javascript:;" pain="No Pain" class="save_pain"
                                               url="{{route('save.pain')}}">
                            <img src="{{asset('assets/images/no-pain.png')}}"
                                 @if(isset($inserted_pain) && ($inserted_pain->fldreportquali == "No Pain")) class="circle" @endif ></a>
                    </div>
                    <label class="pain-scale-label">No Pain</label>
                </div>
                <div class="box__color">
                    <div class="pain-emoji">
                        <a href="javascript:;" pain="Mud" class="save_pain" url="{{route('save.pain')}}">
                            <img src="{{asset('assets/images/mild.png')}}"
                                 @if(isset($inserted_pain) && ($inserted_pain->fldreportquali == "Mud")) class="circle" @endif></a>
                    </div>
                    <label class="pain-scale-label">Mild</label>
                </div>
                <div class="box__color">
                    <div class="pain-emoji">
                        <a href="javascript:;" pain="Moderate" class="save_pain" url="{{route('save.pain')}}">
                            <img src="{{asset('assets/images/moderate.png')}}"
                                 @if(isset($inserted_pain) && ($inserted_pain->fldreportquali == "Moderate")) class="circle" @endif></a>
                    </div>
                    <label class="pain-scale-label">Moderate</label>
                </div>
                <div class="box__color">
                    <div class="pain-emoji">
                        <a href="javascript:;" pain="Nevere" class="save_pain" url="{{route('save.pain')}}">
                            <img src="{{asset('assets/images/severe.png')}}"
                                 @if(isset($inserted_pain) && ($inserted_pain->fldreportquali == "Nevere")) class="circle" @endif>
                        </a>
                    </div>
                    <label class="pain-scale-label">Severe</label>
                </div>
                <div class="box__color">
                    <div class="pain-emoji">
                        <a href="javascript:;" pain="Very Nevere" class="save_pain" url="{{route('save.pain')}}">
                            <img src="{{asset('assets/images/very-severe.png')}}"
                                 @if(isset($inserted_pain) && ($inserted_pain->fldreportquali == "Very Nevere")) class="circle" @endif></a>
                    </div>
                    <label class="pain-scale-label">Very Severe</label>
                </div>
                <div class="box__color">
                    <div class="pain-emoji">
                        <a href="javascript:;" pain="Worst Pain" class="save_pain" url="{{route('save.pain')}}">
                            <img src="{{asset('assets/images/worst-pain.png')}}"
                                 @if(isset($inserted_pain) && ($inserted_pain->fldreportquali == "Worst Pain")) class="circle" @endif></a>
                    </div>
                    <label class="pain-scale-label">Worst Pain</label>
                </div>


                <div class="box__color__er mt-1">
                   
                    <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline mt-1">
                        <input type="radio" id="customRadio-1" name="triage_color" value="008000"
                               class="custom-control-input bg-primary change_triage_color">
                        <label class="custom-control-label" for="customRadio-1"> A[Alert]
                        <div class=" btm bg-success pt-4 text-center rounded"></div>
                        </label>
                    </div>
                </div>
                <div class="box__color__er2 mt-1">
                   
                    <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline mt-1">
                        <input type="radio" id="customRadio-2" name="triage_color" value="FFFF00"
                               class="custom-control-input bg-primary change_triage_color">
                        <label class="custom-control-label" for="customRadio-2"> V[Verbal]
                        <div class=" btm bg-yellow pt-4 text-center rounded"></div>
                        </label>
                    </div>
                </div>

                <div class="box__color__er2 mt-1">
                    
                    <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline mt-1">
                        <input type="radio" id="customRadio-3" name="triage_color" value="FFFF00" class="custom-control-input bg-primary change_triage_color">
                        <label class="custom-control-label" for="customRadio-3"> P[Pain]
                        <div class=" btm orange pt-4 text-center rounded"></div>
                        </label>
                    </div>

                </div>
                <div class="box__color__er3 mt-1">
                  
                    <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline mt-1">
                        <input type="radio" id="customRadio-4" name="triage_color" value="FF0000"
                               class="custom-control-input bg-primary change_triage_color">
                        <label class="custom-control-label" for="customRadio-4"> U(Unresponsive)
                        <div class=" btm red pt-4 text-center rounded"></div>
                        </label>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<script>
    $(".save_pain").click(function () {
        var active = document.querySelector(".circle");

        active.classList.remove("circle");

        var url = $(this).attr('url');
        var pain = $(this).attr('pain');
        var fldencounterval = $("#fldencounterval").val();
        var cur = $(this);


        var formData = {
            pain: pain,
            fldencounterval: fldencounterval,

        };


        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {


                showAlert("Information saved!!");
                cur.find('img').addClass('circle');

            }
        });
    });
</script>
