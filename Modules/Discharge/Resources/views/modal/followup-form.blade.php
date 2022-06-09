  <form>
      <div class="row">
        <div class="col-md-12">
         <!-- <div class="form-group form-row align-items-center">
            <label for="name" class="col-md-6">Date</label>
            <div class="col-sm-10">
              <input type="text" name="date" id="datetimepicker12" class="mb-3">
            </div>
        </div> -->
          
            <div id="datetimepicker12" class="mb-3"></div>
        </div>
        <div class="clearfix"></div><br/>
        <div class="col-md-12 top-req">
            <div class="row">
               <div class="col-md-5">
                  <div class="form-group form-row align-items-center">
                      <label for="name" class="col-md-2">Time</label>
                      <div class="col-sm-10">
                          <input type='text' class="form-control" id="time" name="time" value="@if(isset($time)){{ $time }}@endif" />
                      </div>
                  </div>
                  <div class="form-group form-row align-items-center">
                      <label for="name" class="col-md-2">Date</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="followup_date" value="@if(isset($date)){{ $date }}@endif" id="followup_date" autocomplete="off">
                      </div>
                  </div>
              </div>
              <div class="col-md-2">
                 <div class="form-group-phar">
                      <a href="javascript:;" data-toggle="modal" data-target="#nepali_followup_date" id="followup_date_bs"><i class="far fa-calendar-alt fa-2x" id="pickdate"></i></a>
                      
                  </div>
              </div>
              <div class="col-md-5">
                <div class="form-group form-row align-items-center">
                    <label for="name" class="col-md-2">After</label>
                      <div class="col-sm-8">
                          <input type="text" name="days" class="form-control" id="days" placeholder="" value="" >
                      </div>
                      <label for="name" class="col-md-2">Days</label>
                  </div>
                  <!-- <div class="form-group-phar">
                    <label for="name" class="form-label col-md-2">Days</label>
                      <div class="col-sm-10">
                          <input type="text" name="days" class="form-control form-control-sm" placeholder="" value="sunday" >
                      </div>
                  </div> -->
              </div>
            </div>
        </div>
        <div class="col-md-12">
          <div class="form-group text-right">
              <a href="javascript:;" class="btn btn-primary" onclick="showTestDate()">Save</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
        </div>
      </div>
   </form>
    <script type="text/javascript" src="{{asset('js/nepali.datepicker.v2.2.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/nepali.datepicker.v2.2.min.css')}}" />
    <script type="text/javascript">
        $(function () {
          var cdate = $('#followup_date').val();
          $('#followup_date').val(cdate);
            // $('#datetimepicker12').datepicker({
            //    // useCurrent: false,
            //     changeMonth: true, 
            //     changeYear: true,
            //     dateFormat: 'yy-mm-dd',
            //     inline: true,
            //     sideBySide: true
            // });
             $('#followup_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                yearRange: "-73:-0"
               // npdYearCount: 10 // Options | Number of years to show
            });
            $('#time').timepicker({
                format: 'LT'
            });

        });
        // $('#datetimepicker12').data('#followup_date');

        function showTestDate(){
          var value = window.document.getElementById("followup_date").value;
          // alert(value);
          var days = $('#days').val();
          if(days.length > 0){
            var fdate = addDays(BS2AD(value), days);
            // alert(fdate)
            var day = fdate.getDate();
            var month = fdate.getMonth()+1;
            var year = fdate.getFullYear();
            var newdate=(year+ "-" + month + "-" + day);
            var time = $('#time').val();
            if(time !=''){
              var datetime = AD2BS(newdate)+' '+time;
            }else{
              var datetime = AD2BS(newdate)+' 00:00:00';
            }
            
            // alert(datetime);
            $("#followup_date").val(datetime);
          }else{
             var time = $('#time').val();
             var datetime = value+' '+time;
             // alert(datetime);
             $("#followup_date").val(datetime);
             var newdate = BS2AD(datetime);
          }
         // alert(datetime);
         var newdatetime = $("#followup_date").val();
          if(newdate){
            // alert(newdate);
                $.ajax({
                    url: '{{ route('patient.outcome.menu.updateFollowupdate') }}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val(),date: newdate},
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert("Information saved!!");
                            // location.reload(); Commented by anish ansari because it was realoading the form and it used in Discharge. if needed please uncomment
                        } else {
                          showAlert("Something went wrong!!");
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }else{
                return false;
            }
        }

        function addDays(date, days) {
            // alert(date);
          var result = new Date(date);
          // alert(result);
          // result.setDate(result.getDate() + days);
          result.setTime(result.getTime() +  (days * 24 * 60 * 60 * 1000));
          return result;
        }

        $(".removecurrent").on("click", function(){

            $(".nepalidate_followup").modal("hide");
            // $("#myModal").on("hidden.bs.modal",function(){
            // $("#file-modal").modal("show");
            // });
        });

       //  $("#followup_date").on("click", function(){
       //    var engdate = $('#billing_date').val();
       //    var res = engdate.split(" ");
       //    $.ajax({
       //        type    : 'post',
       //        url     : '{{ route("patient.request.menu.englishtonepali") }}',
       //        data    : {date:res[0],},
       //        success: function (response) {
       //            $('#followup_date_bs').val(response);
       //        }
       //    });

       // });
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
                        // $('#datetimepicker12').val(response);

                    }
                });
            }

        });

    </script>
