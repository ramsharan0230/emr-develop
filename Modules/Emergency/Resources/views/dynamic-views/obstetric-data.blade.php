<form action="{{ route('emergency.obstetric.form.save.waiting') }}"  class="laboratory-form container" method="post">
    @csrf
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">
    <input type="hidden" name="fldinput" value="Obstetric">
    <input type="hidden" name="patfinding" value="@if($patfinding !=''){{$patfinding->fldid}}@else 0 @endif">
    <div class="modal-body">

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label ">Name</label>
            <div class="col-md-10">
                <input type="text" readonly class="form-control" id="patientname" value="@if(isset($patient) and $patient !=''){{ Options::get('system_patient_rank')  == 1 && (isset($encounter[0])) && (isset($encounter[0]->fldrank) ) ?$encounter[0]->fldrank:''}} {{ $patient->fldptnamefir }} {{ $patient->fldmidname }} {{ $patient->fldptnamelast }} @endif">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="form-group">
                   <label class="radio-inline radio-unset"  style="margin-left: 15px;"><input type="radio" name="case" class="form-check-input case" value="1" checked>Primi</label>

                    <label class="radio-inline radio-unset" style="margin-left: 60px;"><input type="radio" name="case" class="form-check-input case" value="2">Multigravida</label>
                </div>  
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label "> Gravida</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control changepp" name="gravida" id="gravida" value="@if(isset($gravida) and $gravida !='') {{$gravida->fldreportquanti}} @else 1 @endif" onchange="updateInput(value)">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label "> Parity</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control changepp" name="parity" id="parity" value="@if(isset($parity) and $parity !='') {{$parity->fldreportquanti}} @else 0 @endif" onchange="updateInput(value)">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label "> Abortion</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control changepp" name="abortion" id="abortion" value="@if(isset($abortion) and $abortion !='') {{$abortion->fldreportquanti}} @else 0 @endif" onchange="updateInput(value)">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label ">Living</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control changepp" name="living" id="living" value="@if(isset($living) and $living !='') {{$living->fldreportquanti}} @else 0 @endif" onchange="updateInput(value)">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label "> LMP</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control changepp datepicker" name="lmp_ad" id="lmp_ad" value="@if(isset($lmp) and $lmp !='') {{$lmp->fldreportquali}} @else 0 @endif">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control changepp" name="lmp_bs" id="lmp_bs" value="0">
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label "> EDD</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control changepp " name="edd_ad" id="edd_ad" value="@if(isset($edd) and $edd !='') {{$edd->fldreportquali}} @else 0 @endif">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control changepp" name="edd_bs" id="edd_bs" value="0">
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label "> Gestation</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control changepp" name="gestationweek" id="gestationweek" value="@if(isset($gestationweek) and $gestationweek !='') {{$gestationweek}} @else 30 @endif" onchange="updateInput(value)">
                    </div>
                    <label for="" class="col-sm-3 col-form-label width_label"> Weeks</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <input type="text" class="form-control changepp" name="gestationdays" id="gestationdays" value="@if(isset($gestationdays) and $gestationdays !='') {{$gestationdays}} @else 1 @endif" onchange="updateInput(value)">
                    </div>
                    <label for="" class="col-sm-6 col-form-label "> Days</label>
                </div>
            </div>
        </div>

        <div class="form-group row mt-2">
            <label for="" class="col-sm-3 col-form-label"> Presentation</label>
            <div class="col-md-9">
                <select class="form-control " name="presentation" id="presentation" style="width: 100%;" onchange="updateInput(value)">
                    <option value="">--Select--</option>
                    <option value="Cephalic" {{ isset($presentation->fldreportquali) and $presentation->fldreportquali == 'Cephalic' ? 'selected' :'' }} >Cephalic</option>
                    <option value="Breech" {{ isset($presentation->fldreportquali) and $presentation->fldreportquali == 'Breech' ? 'selected' :'' }}>Breech</option>
                    <option value="Face" {{ isset($presentation->fldreportquali) and $presentation->fldreportquali == 'Face' ? 'selected' :'' }}>Face</option>
                    <option value="Cord"{{ isset($presentation->fldreportquali) and $presentation->fldreportquali == 'Cord' ? 'selected' :'' }}>Cord</option>
                    <option value="Shoulder" {{ isset($presentation->fldreportquali) and $presentation->fldreportquali == 'Shoulder' ? 'selected' :'' }}>Shoulder</option>
                </select>
            </div>
        </div>
        <div class="form-group row mt-2">
            <label for="" class="col-sm-3 col-form-label changepp"> Labor Status</label>
            <div class="col-sm-9">
                <select class="form-control" name="labor_status" id="labor_status" style="width:100%;" onchange="updateInput(value)">
                    <option value="">--Select--</option>
                    <option value="Latent phase" {{ isset($labor_status->fldreportquali) && $labor_status->fldreportquali == 'Latent phase' ? 'selected' :'' }}>Latent phase</option>
                    <option value="Active phase" {{ isset($labor_status->fldreportquali) && $labor_status->fldreportquali == 'Active phase' ? 'selected' :'' }}>Active phase</option>
                    <option value="Second Stage" {{ isset($labor_status->fldreportquali) && $labor_status->fldreportquali == 'Second Stage' ? 'selected' :'' }}>Second Stage</option>
                    <option value="None" {{ isset($labor_status->fldreportquali) && $labor_status->fldreportquali == 'None' ? 'selected' :'' }}>None</option>
                </select>
            </div>
        </div>
        <div class="row mt-2" id="pastpregdiv" style="display: none;">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="form-label "> Past Preg</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="pastpreg"  class="form-input width_input changepp" id="pastpreg" value="{{isset($past_pregnancy) and $past_pregnancy !=''?$past_pregnancy->fldreportquali:''}}" onchange="updateInput(value)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <textarea class="form-control" rows="10" cols="50" style="width: 100%;height: 95px;" id="obsdesc" name="obsdesc">{{ isset($patfinding->fldcode) && $patfinding->fldcode != ' ' ? $patfinding->fldcode :'G0 P0 with o weeks of pregnancy' }}</textarea>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" class="form-label "> EDD = LMP +</label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-input width_input auto_width" name="totaldays" id="totaldays" value="{{Options::get('edd_days')}}" readonly="">
                        </div>
                        <div class="col-md-2">
                            <label for="" class="form-label width_label"> days</label>
                        </div>
                        <div class="col-md-2">
                            <form>
                                <label class="radio-inline radio-unset">
                                    <input type="radio" name="format" class="format" value="1" checked>A
                                </label>
                                <label class="radio-inline radio-unset">
                                    <input type="radio" name="format" class="format" value="2">B
                                </label>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <input type="submit" class="default-btn update_btn" name="submit" value="Update">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<script type="text/javascript">
   $(document).ready(function () {
        $('.case').on('change', function(){
            var caseValue = $("input[name='case']:checked").val();
            if(caseValue == 1){
               $('#gravida').val(1);
               $('#parity').val(0);
               $('#abortion').val(0);
               $('#living').val(0);
               $('#obsdesc').val('');
                var gravida = 1;
                var parity = 0;
                var abortion = 0;
                var living = 0;
                var gweek = $('#gestationweek').val();
                var gdays = $('#gestationdays').val();
                var presentation = $('#presentation').val();
                // alert(presentaion);
                var ls = $('#labor_status').val();
                var pastpreg = $('#pastpreg').val();
                var lmpdatead = $("#lmp_ad").val();

                var radioValue = $("input[name='format']:checked").val();
                if(radioValue == 1){
                    var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' With '+gweek+' Week & '+gdays+' days of pregnancy';
                }else{
                    var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' At '+gweek+' + '+gdays+' WOG';
                }
                    if(presentation){
                    var presentationtext = ' with '+presentation+' Presentation';
                    }else{
                        var presentationtext = '';
                    }

                    if(ls){
                        var lstext =' in '+ls+' of labor';
                    }else{
                        var lstext = ''
                    }

                    if(pastpreg){
                        var pptext = ' With '+pastpreg;
                    }else{
                        var pptext = '';
                    }
                    finaltext = obsdesc+presentationtext+lstext+pptext;

                    $('#obsdesc').val(finaltext);
                    $('#pastpregdiv').hide();
            }else{
               $('#pastpregdiv').show();

               $('#obsdesc').val('');
                var gravida = $('#gravida').val();
                var parity = $('#parity').val();
                var abortion = $('#abortion').val();
                var living = $('#living').val();
                var gweek = $('#gestationweek').val();
                var gdays = $('#gestationdays').val();
                var presentation = $('#presentation').val();
                // alert(presentaion);
                var ls = $('#labor_status').val();
                var pastpreg = $('#pastpreg').val();
                var lmpdatead = $("#lmp_ad").val();

                var radioValue = $("input[name='format']:checked").val();
                if(radioValue == 1){
                    var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' With '+gweek+' Week & '+gdays+' days of pregnancy';
                }else{
                    var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' At '+gweek+' + '+gdays+' WOG';
                }
                    if(presentation){
                    var presentationtext = ' with '+presentation+' Presentation';
                    }else{
                        var presentationtext = '';
                    }

                    if(ls){
                        var lstext =' in '+ls+' of labor';
                    }else{
                        var lstext = ''
                    }

                    if(pastpreg){
                        var pptext = ' With '+pastpreg;
                    }else{
                        var pptext = '';
                    }
                    finaltext = obsdesc+presentationtext+lstext+pptext;

                    $('#obsdesc').val(finaltext);
            }
        });
    });

    function updateInput(e){
        // alert(e);
        $('#obsdesc').val('');
        var gravida = $('#gravida').val();
        var parity = $('#parity').val();
        var abortion = $('#abortion').val();
        var living = $('#living').val();
        var gweek = $('#gestationweek').val();
        var gdays = $('#gestationdays').val();
        var presentation = $('#presentation').val();
        // alert(presentaion);
        var ls = $('#labor_status').val();
        var pastpreg = $('#pastpreg').val();
        var lmpdatead = $("#lmp_ad").val();

        var radioValue = $("input[name='format']:checked").val();
        if(radioValue == 1){
            var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' With '+gweek+' Week & '+gdays+' days of pregnancy';
        }else{
            var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' At '+gweek+' + '+gdays+' WOG';
        }
            if(presentation){
            var presentationtext = ' with '+presentation+' Presentation';
            }else{
                var presentationtext = '';
            }

            if(ls){
                var lstext =' in '+ls+' of labor';
            }else{
                var lstext = ''
            }

            if(pastpreg){
                var pptext = ' With '+pastpreg;
            }else{
                var pptext = '';
            }
            finaltext = obsdesc+presentationtext+lstext+pptext;

            $('#obsdesc').val(finaltext);


    }

    $("input:radio[name=format]").change(function() {
        var radioValue = $("input[name='format']:checked").val();

        // if(radioValue == 1){
            $('#obsdesc').val('');
            var gravida = $('#gravida').val();
            var parity = $('#parity').val();
            var abortion = $('#abortion').val();
            var living = $('#living').val();
            var gweek = $('#gestationweek').val();
            var gdays = $('#gestationdays').val();
            var presentation = $('#presentation').val();
            // alert(presentaion);
            var ls = $('#labor_status').val();
            var pastpreg = $('#pastpreg').val();
            var lmpdatead = $("#lmp_ad").val();
            if(radioValue == 1){
                var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' With '+gweek+' Week & '+gdays+' days of pregnancy';
            }else{
                var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' At '+gweek+' + '+gdays+' WOG';
            }


            if(presentation){
                var presentationtext = ' with '+presentation+' Presentation';
            }else{
                var presentationtext = '';
            }

            if(ls){
                var lstext =' in '+ls+' of labor';
            }else{
                var lstext = ''
            }

            if(pastpreg){
                var pptext = ' With '+pastpreg;
            }else{
                var pptext = '';
            }
            finaltext = obsdesc+presentationtext+lstext+pptext;

            $('#obsdesc').val(finaltext);

    });

    function addDays(date, days) {
      var result = new Date(date);
      result.setDate(result.getDate() + days);
      var d = result.getDate();
        var m =  result.getMonth();
        m += 1;
        var y = result.getFullYear();

      var newdate=(d+ "-" + m + "-" + y);

      return newdate;
    }
    $(document).ready(function () {

        $('#lmp_ad').on('change', function(){

            var lmpdate = $(this).val();
            var ndate = lmpdate.split("-").reverse().join("-");
            var totaldval = $('#totaldays').val();
            var edd = addDays(ndate,totaldval);
            $('#edd_ad').val(edd);

            // To set two dates to two variables
            var tdate = edd.split("-").reverse().join("-");
            var date1 = new Date(tdate);
            var date2 = new Date(ndate);
            // alert(date1);
            // To calculate the time difference of two dates
            var Difference_In_Time = date1.getTime() - date2.getTime();

            // To calculate the no. of days between two dates
            var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

            var weeks = Difference_In_Days/7;

            if(Number.isInteger(weeks)){

                 $('#gestationweek').val(weeks);
                $('#gestationdays').val(0);
                var gweek = weeks;
                var gdays = 0;
            }else{

                var strArray = weeks.toString().split(".");

                var days = Difference_In_Days - (strArray[0]*7);
                var totd = Math.round(days);

                $('#gestationweek').val(strArray[0]);
                $('#gestationdays').val(totd);
                var gweek = strArray[0];
                var gdays = totd;
            }
            var radioValue = $("input[name='format']:checked").val();

                $('#obsdesc').val('');
                var gravida = $('#gravida').val();
                var parity = $('#parity').val();
                var abortion = $('#abortion').val();
                var living = $('#living').val();

                var presentation = $('#presentation').val();
                // alert(presentaion);
                var ls = $('#labor_status').val();
                var pastpreg = $('#pastpreg').val();
                var lmpdatead = $("#lmp_ad").val();
                if(radioValue == 1){
                    var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' With '+gweek+' Week & '+gdays+' days of pregnancy';
                }else{
                    var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' At '+gweek+' + '+gdays+' WOG';
                }


                if(presentation){
                    var presentationtext = ' with '+presentation+' Presentation';
                }else{
                    var presentationtext = '';
                }

                if(ls){
                    var lstext =' in '+ls+' of labor';
                }else{
                    var lstext = ''
                }

                if(pastpreg){
                    var pptext = ' With '+pastpreg;
                }else{
                    var pptext = '';
                }
                finaltext = obsdesc+presentationtext+lstext+pptext;

                $('#obsdesc').val(finaltext);

              $.ajax({
                 type: 'post',
                 url: '{{route("patient.date.convert")}}',
                 data: {
                     date:ndate,
                 },
                 success: function (response) {
                     $('#lmp_bs').val(response);

                 }
             });

              $.ajax({
                 type: 'post',
                 url: '{{route("patient.date.convert")}}',
                 data: {
                     date:tdate,
                 },
                 success: function (response) {
                     $('#edd_bs').val(response);

                 }
             });

        });

        $('#edd_ad').on('change', function(){
            // alert('sdfsdfs');
            var lmpdate = $('#lmp_ad').val();
            var ndate = lmpdate.split("-").reverse().join("-");
            var edd = $(this).val();

            // To set two dates to two variables
            var tdate = edd.split("-").reverse().join("-");
            var date1 = new Date(tdate);
            var date2 = new Date(ndate);
            // alert(date1);
            // To calculate the time difference of two dates
            var Difference_In_Time = date1.getTime() - date2.getTime();

            // To calculate the no. of days between two dates
            var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

            var weeks = Difference_In_Days/7;
            if(Number.isInteger(weeks)){
                 $('#gestationweek').val(weeks);
                $('#gestationdays').val(0);
                var gweek = weeks;
                var gdays = 0;
            }else{
                var strArray = weeks.toString().split(".");
                var days = Difference_In_Days - (strArray[0]*7);
                var totd = Math.round(days);
                $('#gestationweek').val(strArray[0]);
                $('#gestationdays').val(totd);
                var gweek = strArray[0];
                var gdays = totd;
            }

                var radioValue = $("input[name='format']:checked").val();

                $('#obsdesc').val('');
                var gravida = $('#gravida').val();
                var parity = $('#parity').val();
                var abortion = $('#abortion').val();
                var living = $('#living').val();

                var presentation = $('#presentation').val();
                // alert(presentaion);
                var ls = $('#labor_status').val();
                var pastpreg = $('#pastpreg').val();
                var lmpdatead = $("#lmp_ad").val();
                if(radioValue == 1){
                    var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' With '+gweek+' Week & '+gdays+' days of pregnancy';
                }else{
                    var obsdesc = 'G'+gravida+' '+'P'+parity+'+'+abortion+' '+'L'+living+' At '+gweek+' + '+gdays+' WOG';
                }


                if(presentation){
                    var presentationtext = ' with '+presentation+' Presentation';
                }else{
                    var presentationtext = '';
                }

                if(ls){
                    var lstext =' in '+ls+' of labor';
                }else{
                    var lstext = ''
                }

                if(pastpreg){
                    var pptext = ' With '+pastpreg;
                }else{
                    var pptext = '';
                }
                finaltext = obsdesc+presentationtext+lstext+pptext;

                $('#obsdesc').val(finaltext);

        });

        var dateToday = new Date();
        $('.datepicker').datepicker({
            maxDate: dateToday,
            changeMonth: true,
            changeYear: true,
            dateFormat:'dd-mm-yy',
            yearRange: "-100:+0",

        });

        $('#edd_ad').datepicker({
            // maxDate: '+30Y',
            changeMonth: true,
            changeYear: true,
            dateFormat:'dd-mm-yy',
            yearRange: '1920:2032',

        });
    });
</script>