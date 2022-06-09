
<form id="obstetric-request-submit" class="obstetric-form container" method="post">

    <!-- @csrf -->
    @php
    $encounterData = $encounter[0];
    $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">
    <input type="hidden" name="fldinput" value="Obstetric">
    <input type="hidden" name="patfinding" value="@if($patfinding !=''){{$patfinding->fldid}}@else 0 @endif">
    <div class="modal-body">

        <div class="form-group form-row">
            <label for="" class="col-sm-2 col-form-label "> Name</label>
            <div class="col-md-10">
                <input type="text" readonly class="form-control" id="patientname" value="@if(isset($patient) and $patient !=''){{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{ $patient->fldptnamefir }} {{ $patient->fldmidname }} {{ $patient->fldptnamelast }} @endif">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="form-group form-row">
                    
                    <div class="col-4  mt-2">
                        <input type="radio" name="case" class="case" value="1" checked>
                        <label class="">&nbsp;Primi</label>
                    </div>
                        <div class="col-4 mt-2">
                        <input type="radio" name="case" class="case" value="2">
                        <label class="">&nbsp;Multigravida</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-row">
                    <label for="" class="col-sm-4 col-form-label "> Gravida</label>
                    <div class="col-md-8">
                        <input type="number" class="form-control changepp" name="gravida" id="gravida" value="@if(isset($gravida) and !empty($gravida)){{$gravida->fldreportquanti}}@else 1 @endif" onchange="updateInput(value)">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-row">
                    <label for="" class="col-sm-3 col-form-label "> Parity</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control changepp" name="parity" id="parity" value="@if(isset($parity) and $parity !=''){{$parity->fldreportquanti}}@else 0 @endif" onchange="updateInput(value)">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-row">
                    <label for="" class="col-sm-4 col-form-label "> Abortion</label>
                    <div class="col-md-8">
                        <input type="number" class="form-control changepp" name="abortion" id="abortion" value="@if(isset($abortion) and $abortion !=''){{$abortion->fldreportquanti}}@else 0 @endif" onchange="updateInput(value)">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-row">
                    <label for="" class="col-sm-3 col-form-label ">Living</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control changepp" name="living" id="living" value="@if(isset($living) and $living !=''){{$living->fldreportquanti}}@else 0 @endif" onchange="updateInput(value)">
                    </div>
                </div>
            </div>
        </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-row">
                            <label for="" class="col-sm-4 col-form-label "> LMP</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control changepp nepalidate" name="lmp_bs" id="lmp_bs" value="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-row">
                            <input type="text" class="form-control changepp datepicker" name="lmp_ad" id="lmp_ad" value="@if(isset($lmp) and $lmp !='') {{$lmp->fldreportquali}} @else 0 @endif">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-row">
                            <label for="" class="col-sm-4 col-form-label "> EDD</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control changepp nepalidate" name="edd_bs" id="edd_bs" value="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-row">
                            <input type="text" class="form-control changepp " name="edd_ad" id="edd_ad" value="@if(isset($edd) and $edd !='') {{$edd->fldreportquali}} @else 0 @endif">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-row">
                            <label for="" class="col-sm-4 col-form-label "> Gestation</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control changepp" name="gestationweek" id="gestationweek" value="@if(isset($gestationweek) and $gestationweek !='') {{$gestationweek}} @endif" onchange="updateInput(value)">
                            </div>
                            <label for="" class="col-sm-3 col-form-label width_label"> Weeks</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-row">
                            <div class="col-sm-9">
                                <input type="text" class="form-control changepp" name="gestationdays" id="gestationdays" value="@if(isset($gestationdays) and $gestationdays !='') {{$gestationdays}}@endif" onchange="updateInput(value)">
                            </div>
                            <label for="" class="col-sm-3 col-form-label "> Days</label>
                        </div>
                    </div>
                </div>

                <div class="form-group form-row">
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
                <div class="form-group form-row">
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
                <div class="form-group form-row" id="pastpregdiv" style="display: none;">
                    <label for="" class="col-sm-3 col-form-label changepp"> Past Preg</label>
                    <div class="col-md-9">
                        <input type="text" name="pastpreg"  class="form-control changepp" id="pastpreg" value="{{isset($past_pregnancy) and $past_pregnancy !=''?$past_pregnancy->fldreportquali:''}}" onchange="updateInput(value)">
                    </div>
                    <!-- <div class="col-md-12">
                        <div class="form-group form-row">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="" class="col-sm-3 col-form-label "> Past Preg</label>
                                </div>
                                
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="form-group form-row">
                    <textarea class="form-control" rows="10" cols="50" style="width: 100%;height: 95px;" id="obsdesc" name="obsdesc">{{ isset($patfinding->fldcode) && $patfinding->fldcode != ' ' ? $patfinding->fldcode :'G0 P0 with o weeks of pregnancy' }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group form-row">
                    <div class="col-md-4">
                        <label for="" class="form-label "> EDD = LMP +</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control width_input auto_width" name="totaldays" id="totaldays" value="{{Options::get('edd_days')}}" readonly="">
                    </div>
                    <div class="col-md-2">
                        <label for="" class="form-label width_label"> days</label>
                    </div>
                    <div class="col-md-2">
                            
                                <input type="radio" name="format" class="format" value="1" checked>
                                <label class="radio-inline radio-unset">&nbsp;A
                            </label><br/>
                            
                                <input type="radio" name="format" class="format" value="2">
                                <label class="radio-inline radio-unset">&nbsp;B
                            </label>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary update_btn" id="js-obstetric-diagnosis-update-btn" onclick="updateObstetric()">Update</button>
                    </div>
                </div>

            </div>
        </form>

        <script type="text/javascript" src="{{asset('js/nepali.datepicker.v2.2.min.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('css/nepali.datepicker.v2.2.min.css')}}"/>

        <script type="text/javascript">
           $(document).ready(function () {
            $('.case').on('change', function(){
                var caseValue = $("input[name='case']:checked").val();

                if(caseValue == 1){
               // alert('case valeu'); 
               $('#obsdesc').val('');
               var gravida = 1;
               var parity = 0;
               var abortion = 0;
               var living = 0;
               $('#gravida').val(gravida);
               $('#parity').val(parity);
               $('#abortion').val(abortion);
               $('#living').val(living);
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
                if(presentation !=''){
                    var presentationtext = ' with '+presentation+' Presentation';
                }else{
                    var presentationtext = '';
                }

                if(ls){
                    var lstext =' in '+ls+' of labor';
                }else{
                    var lstext = ''
                }

                if(pastpreg.length>0){
                    var pptext = ' With '+pastpreg;
                }else{
                    var pptext = '';
                }
                finaltext = obsdesc+presentationtext+lstext+pptext;

                $('#obsdesc').val(finaltext);
                $('#pastpregdiv').hide();
            }else{
                // alert('case val2'); 
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
                if(presentation !=''){
                    var presentationtext = ' with '+presentation+' Presentation';
                }else{
                    var presentationtext = '';
                }

                if(ls){
                    var lstext =' in '+ls+' of labor';
                }else{
                    var lstext = ''
                }

                if(pastpreg.length>0){
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
        if(presentation.length !==0){
            var presentationtext = ' with '+presentation+' Presentation';
        }else{
            var presentationtext = '';
        }

        if(ls){
            var lstext =' in '+ls+' of labor';
        }else{
            var lstext = '';
        }
       
        if(pastpreg.length>0){
            // alert('dfgdfds');
            var pptext = ' With '+pastpreg;
        }else{
            // alert('else');
            var pptext = '';
        }
        // alert(pastpreg.length)
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


            if(presentation !=''){
                var presentationtext = ' with '+presentation+' Presentation';
            }else{
                var presentationtext = '';
            }

            if(ls){
                var lstext =' in '+ls+' of labor';
            }else{
                var lstext = ''
            }

            if(pastpreg.length>0){
                var pptext = ' With '+pastpreg;
            }else{
                var pptext = '';
            }
            finaltext = obsdesc+presentationtext+lstext+pptext;

            $('#obsdesc').val(finaltext);

        });




    function addDays(date, days) {
        // alert(date);
        // alert(days);
      var result = new Date(date);
      // alert(result.getDate());
      result.setDate(result.getDate() + parseInt(days));
      var d = result.getDate();
      var m =  result.getMonth();
      m += 1;
      var y = result.getFullYear();

      var newdate=(d+ "-" + m + "-" + y);
      // alert(newdate);
      return newdate;
  }
  $(document).ready(function () {

    $('#lmp_ad').on('change', function(){

        var lmpdate = $(this).val();
        var ndate = lmpdate.split("-").reverse().join("-");
            // alert(ndate);
            var totaldval = $('#totaldays').val();
            var edd = addDays(ndate,totaldval);
            // alert(edd);
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
            // alert(Difference_In_Days);
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


                if(presentation !=''){
                    var presentationtext = ' with '+presentation+' Presentation';
                }else{
                    var presentationtext = '';
                }

                if(ls){
                    var lstext =' in '+ls+' of labor';
                }else{
                    var lstext = ''
                }

                if(pastpreg.length>0){
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


                if(presentation !=''){
                    var presentationtext = ' with '+presentation+' Presentation';
                }else{
                    var presentationtext = '';
                }

                if(ls){
                    var lstext =' in '+ls+' of labor';
                }else{
                    var lstext = ''
                }

                if(pastpreg.length>0){
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
    $('.nepalidate').nepaliDatePicker();
    $('#lmp_bs').nepaliDatePicker({

        npdMonth: true,
        npdYear: true,
        npdYearCount: 100,
        disableDaysAfter: '1',
        onChange: function () {
            var datebs = $('#lmp_bs').val();

            $.ajax({
                type: 'post',
                url: '{{ route("patient.nepalidate.convert") }}',
                data: {date: datebs},
                success: function (response) {
                    $('#lmp_ad').val(response);
                    $("#lmp_ad").trigger("change");
                }
            });

        }

    });

    $('#edd_bs').nepaliDatePicker({

        npdMonth: true,
        npdYear: true,
        npdYearCount: 100,
        disableDaysAfter: '1',
        onChange: function () {
            var datebs = $('#edd_bs').val();
            $.ajax({
                type: 'post',
                url: '{{ route("patient.nepalidate.convert") }}',
                data: {date: datebs},
                success: function (response) {
                    $('#edd_ad').val(response);
                    $("#edd_ad").trigger("change");
                }
            });

        }

    });


});
</script>
<script type="text/javascript">
    function updateObstetric(){
        // alert('here');
         // data :   $("form").serialize();
         // console.log(data);
                var url = "{{ route('patient.obstetric.form.save.waiting') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data:  $("#obstetric-request-submit").serialize(),"_token": "{{ csrf_token() }}",
                    success: function(response) {
                        // response.log()
                        // console.log(response);
                        $('#select-multiple-diagno').empty().append(response);
                        $('#diagnosis-obstetric-modal').modal('hide');
                        showAlert('Data Added !!');
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
