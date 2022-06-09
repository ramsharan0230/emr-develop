<style>
  #billData h2,
  #billData h4 {
      line-height: 1.3rem !important;
  }

  </style>


<div class="modal fade bd-example-modal-lg" id="user-discharge-clearance-bill">
    <div class="modal-dialog modal-lg " style="max-width: 80%;">
        <div class="modal-content">
          {{-- @dump('dfdf'); --}}
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Bill Detail</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-user-list">
                {{-- <form id="billData">

                </form> --}}
                <div id="dischargebillData">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" id="printReport" onclick="printReport()">Print</button> --}}
                {{-- <button type="button" data-encounter_id="" data-route="" data-billno="" class="btn btn-primary" id="printBillInformation" >Print</button> --}}
                <button type="button" class="btn btn-primary printBillInformation"  class="btn btn-primary" id="printBillInformation"  >Print</button>


            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // function printReport(){
    //     // alert('add allergy drugs');

    //     var url = "{{route('allergydrugstore')}}";
    //     $.ajax({
    //         url: url,
    //         type: "POST",
    //         data:  $("#userform").serialize(),"_token": "{{ csrf_token() }}",
    //         success: function(response) {
    //             // response.log()
    //             // console.log(response);
    //             $('#select-multiple-aldrug').empty().append(response);
    //             $('#allergicdrugs').modal('hide');
    //             showAlert('Data Added !!');
    //             // if ($.isEmptyObject(data.error)) {
    //             //     showAlert('Data Added !!');
    //             //     $('#allergy-freetext-modal').modal('hide');
    //             // } else
    //             //     showAlert('Something went wrong!!');
    //         },
    //         error: function (xhr, status, error) {
    //             var errorMessage = xhr.status + ': ' + xhr.statusText;
    //             console.log(xhr);
    //         }
    //     });
    // }

    // $('#checkAll').click(function () {
    //      $('.user-list').prop('checked', true);
    //  });

    // $(document).bind('click','#selectall',function(){
    //     if($('.user-list').is(":checked")){
    //         $('.user-list').prop('checked',false)
    //     }else{
    //         $('.user-list').prop('checked',true)
    //     }

    // })
    $(document).on('click','#selectAll', function(){

      $("input[type=checkbox]").prop("checked", $(this).prop("checked"));
    });

    $("input[type=checkbox]").click(function() {
      if (!$(this).prop("checked")) {
        $("#selectAll").prop("checked", false);
      }
    });

    function printReport(){
         var data = $("#userform").serialize();
         var type = $('#cash_credit').val();
         var fromdate = $('#from_date').val();
         var todate = $('#to_date').val();
         var eng_from_date = $('#eng_from_date').val();
         var eng_to_date = $('#eng_to_date').val();
           var urlReport = baseUrl + "/billing/service/billing-user-report?" + data + "&type="+type+"&from="+fromdate+"&eng_from_date="+eng_from_date+"&eng_to_date="+eng_to_date+"&todate="+todate+"&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

           window.open(urlReport, '_blank');
    }
</script>
