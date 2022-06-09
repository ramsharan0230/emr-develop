@extends('frontend.layouts.master')
@section('content')

<style type="text/css">
    .claimdata{
        
        overflow-x: auto;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Health Insurance Claim
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">

                    <div class="row">
                        <div class="col-lg-3 col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">From:</label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" name="from_date" value="{{isset($date) ? $date : ''}}" id="from_date" class="form-control nepaliDatePicker" />
                                </div>


                            </div>


                        </div>
                         <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="searchtype" id="searchtype" value="0" onclick="refresh()">
                                <label class="form-check-label">
                                    Patient Name
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="searchtype" id="searchtype" value="1" onclick="refresh()" checked>
                                <label class="form-check-label">
                                    Patient Insurance ID
                                </label>
                            </div>
                            
                        </div>

                        <div class="col-lg-3 col-sm-3">
                            <div class="form-group form-row">
                                
                                <div class="col-sm-9">
                                    <input type="text" name="txtsearch" value="" id="txtsearch" class="form-control"  />
                                </div>


                            </div>


                        </div>

                       
                    </div>

                    {{-- //here --}}

                      <div class="row">
                            <div class="col-lg-3 col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">To:</label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" class="form-control nepaliDatePicker" />
                                </div>

                            </div>
                            </div>

                            <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="searchtype" id="searchtype" value="2" onclick="refresh()">
                                <label class="form-check-label">
                                    Encounter ID
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="searchtype" id="searchtype" value="3" onclick="refresh()">
                                <label class="form-check-label">
                                    Patient ID
                                </label>
                            </div>
                            
                        </div>

                        <div class="col-lg-3 col-sm-3">
                            <div class="form-group form-row">

                                {{-- <div class="col-sm-9">
                                    <select name="billingmode" value="" id="billingmode" class="form-control">
                                    <option>-- Select Billing Mode --</option>
                                    @foreach ($billingmode as $billingmodes)
                                        <option value="{{$billingmodes}}">{{$billingmodes}}</option>
                                    @endforeach
                                </div> --}}

                                <div class="col-sm-9">
                                    Billing Mode: 
                                    <select name="billingmode" value="" id="billingmode" class="form-control">
                                    <option value="">-- Select Billing Mode --</option>
                                    @foreach ($billingmode as $billingmodes)
                                        <option value="{{$billingmodes}}">{{$billingmodes}}</option>
                                    @endforeach
                                    </select>

                                </div>
 
                            </div>


                        </div>

                        <div class="col-sm-3">
                         <div class="form-group form-row  float-left">
                            <button type="button" class="btn btn-primary rounded-pill refresh" id="refresh"><i class="ri-refresh-line"></i>&nbsp;Refresh</button>
                        
                            {{-- <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportPurchaseReport()"><i class="fa fa-file-pdf"></i>&nbsp; --}}
                                {{-- Export</a>&nbsp; --}}
                        </div>
                        </div>

                        </div>
                </div>

            </div>
        </div>
    </div>
    
  

    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">

            <div class="iq-card-body">

                <div class="table-responsive table-sticky-th">
                    <table class="table table-bordered table-hover table-striped text-center">
                        <thead class="thead-light">
                            <tr>
                                {{-- <th></th> --}}
                                <th>S.N.</th>
                                <th>Date</th>
                                <th>Patient ID</th>
                                <th>Insurance ID</th>
                                <th>Encounter ID</th>
                                <th>Patient Details</th>
                                <th>Eligible Amount</th>
                                <th>Used Amount</th>
                                <th>Bill Type</th>
                                <th>Diagnosis</th>
                                <th>Actions</th>

                            </tr>
                        </thead>

                        <tbody id="item_result">

                         
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

        <div class="modal fade" id="diagnosis" tabindex="-1" role="dialog" aria-labelledby="allergicdrugsLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <input type="hidden" id="patient_id" class="patient_id" name="patient_id">
                
                    <div class="modal-header">
                        <h5 class="modal-title" id="allergicdrugsLabel">ICD10 Database</h5>
                        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="opd-diagnosis">
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Group</label>
                                        <div class="col-sm-8">
                                            <select name="" id="diagnogroup" class="form-control">
                                                <option value="">--Select Group--</option>
                                                @if(isset($diagnosisgroup) and count($diagnosisgroup) > 0)
                                                    @foreach($diagnosisgroup as $dg)
                                                        <option value="{{$dg->fldgroupname}}">{{$dg->fldgroupname}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Groups Not Available</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="javascript:void(0);" class=" button btn btn-primary" id="searchbygroup"><i class="ri-refresh-line"></i></a>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#" class="button btn btn-danger" id="closesearchgroup"><i class="ri-close-fill"></i></a>
                                        </div>
                                    </div>
                                    <div id="diagnosiss">
                                        <div class="form-group form-row align-items-center">
                                            <!-- <label for="" class="col-sm-2">Search</label> -->
                                            <!-- <div class="col-sm-10">
                                                <input type="text" name="" palceholder="Search" class="form-control">
                                            </div> -->
                                        </div>
                                        <div class="icd-datatable">
                                            <table class="table table-bordered table-striped table-hover" id="top-req ">
                                                <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                </tr>
                                                </thead>
                                                <tbody id="diagnosiscat">
                                                @forelse($diagnosiscategory as $dc)
                                                    <tr>
                                                        <td><input type="checkbox" class="dccat" name="dccat" value="{{$dc['code']}}"></td>
                                                        <td>{{$dc['code']}}</td>
                                                        <td>{{$dc['name']}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            <em>No data available in table ...</em>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Search</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="search_diagnosis_sublist" id="search_diagnosis_sublist" placeholder="Search" class="form-control">
                                        </div>
                                    </div>
                                    <div class="table-responsive table-scroll-icd">
                                        <table class=" table table-bordered table-striped table-hover" id=" top-req">
                                            <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                            </tr>
                                            </thead>
                                            <tbody id="sublist">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group form-row align-items-center mt-2">
                                        <label for="" class="col-sm-2">Code</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="code" id="code" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Text</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="diagnosissubname" id="diagnosissubname" class="form-control">
                                            <input type="hidden" name="patient_id">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitallergydrugs" onclick="updateDiagnosis()">Submit</button>
                            <!-- <input type="submit" name="submit" id="submitallergydrugs" class="btn btn-primary" value="Submit"> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>


<div class="modal fade" id="mediumModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title claimtitle" id="claimtitle"></h4>
                <label for="" class="col-sm-2">Encounter ID</label>
                <input type="text" name="enc" class="enc" id="enc">
                <input type="hidden" name="bill" class="bill" id="bill">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="table-responsive table-sticky-th claimdata">
                <table class="table table-bordered table-hover table-striped text-center">
                    <thead class="thead-light modalheader">
                    
                    </thead>

                    <tbody id="modal_result">

                    
                    </tbody>

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary rounded-pill btnclaim" id="btnclaim">Claim</button>
                
            </div>
        </div>
    </div>
</div>

@endsection


@push('after-script')

<script src="{{ asset('/js/claim.js') }}"></script>

<script>

    $(document).ready(function() {

        $('.refresh').on('click', function(e) {

            var fromdate = $("#from_date").val();
            var todate = $("#to_date").val();
            var searchtype = document.querySelector('input[name="searchtype"]:checked').value;
            var billingmode = $("#billingmode").val();
            var txtsearch = $('#txtsearch').val();

 

            if (fromdate != '' && todate != '') {

                $.ajax({
                    url: '{{route("claim-report")}}',
                    type: "GET",
                    data: {
                        fromdate: fromdate,
                        todate: todate,
                        searchtype: searchtype,
                        txtsearch: txtsearch,
                        billingmode: billingmode
                    },
                    success: function(response) {

                        console.log(response.data.status);

                        if(response.data.status == true){
                            $('#item_result').html(response.data.html);
                            showAlert('Data Retrieved');
                        }else{
                            showAlert('Something went wrong!','error');
                        }

                    },

                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            } else {
                alert('From and To Date Required!!');
            }

        });


    
    });

    $("#item_result").on('click','#diagnosisdata',function(){

        var currentRow = $(this).closest("tr");
        var enc = currentRow.find(".fldencounterval").html();

        $('#patient_id').val(enc);

        $('#diagnosis').modal('show');

    });

    $(document).on('click','.dropdown-item',function(){

        //var test = $(this).data('value');
            var currentRow = $(this).closest("tr");
            var enc = currentRow.find(".fldencounterval").html();
            
            //var actiontype  = currentRow.find(".action").html();

            //alert(enc);

            //var gallery_data = $(this).closest('.gallery-category').find('h2[data-gallery]').data("gallery");

            //var link = currentRow.closest('.dropdown .dropdown-menu a').attr('data-value');
           // var link = $(this).closest('.js-registration-list-view a').attr('data-value');
            //console.log(link);

            //alert(link);

            $('.enc').val(enc);

            //var actiontype = $('.action').find(":selected").text();
            //var actiontype = $('.action option:selected').val();
            var actiontype = $(this).data('value');
            var urlsuffix = "-bill";

            //var urltype = "claim-".concat(actiontype,urlsuffix);

            if(actiontype == 'view'){
                var urltype = '{{route("claim-view-bills")}}';
                $('.claimtitle').val('View Bill');
            }else if(actiontype == 'claim'){
                var urltype = '{{route("claim-claim-bill")}}';
            }else if(actiontype == 'noninsbill'){
                var urltype = '{{route("claim-nonins-bill")}}';
            }else if(actiontype == 'billupload'){
                var urltype = '{{route("claim-bill-upload")}}';
            }else{
                
            }

            if(urltype){

                $.ajax({
                url: urltype,
                type: 'GET',
                data: {
                    enc: enc
                },
                success: function(response){

                    if(response.data.status == true && actiontype == 'claim' ){
                        $('.modalheader').html(response.data.header);
                        $('#modal_result').html(response.data.html);

                        $('#mediumModal').modal("show");
                        $('.btnclaim').show();

                    }else if(response.data.status == true){
                        $('.modalheader').html(response.data.header);
                        $('#modal_result').html(response.data.html);

                        $('#mediumModal').modal("show");
                        $('.btnclaim').hide();
                        
                    }else{
                        showAlert('Something went wrong!','error');
                    }

                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }


            });

            }
          

    });



      

 

   // function exportPurchaseReport() {

       // var data = $("#purchase_data").serialize();
      //  var fromdate = $("#from_date").val();
      //  var todate = $("#to_date").val();
       // var reporttype = document.querySelector('input[name="reporttype"]:checked').value;

       // if (fromdate != '' && todate != '') {
        //    var urlReport = baseUrl + "/inventory/purchase-vat-report-pdf?fromdate=" + fromdate + "&todate=" + todate + "&reporttype=" + reporttype + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

        //    window.open(urlReport);
       // } else {
          //  alert('From and To Date Required!!');
       // }
    //}

     function updateDiagnosis() {
            // alert('diagn');
            var url = "{{route('diagnosisStoreclaim')}}";
            if ($('#encounterid').val() == "") {
                alert('Please choose patient encounter.');
                return false;
            }
            $("#opd-diagnosis").append($("#patient_id"));

            $.ajax({
                url: url,
                type: "POST",
                data: $("#opd-diagnosis").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    // response.log()
                    console.log(response);
                    $('#diagnosistext').html(response);
                    $('#diagnosis').modal('hide');
                    showAlert('Data Added !!');
                    // if ($.isEmptyObject(data.error)) {
                    //     showAlert('Data Added !!');
                    //     $('#allergy-freetext-modal').modal('hide');
                    // } else
                    //     showAlert('Something went wrong!!', 'error);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

    function refresh(){
        var fromdate = $("#from_date").val();
            var todate = $("#to_date").val();
            var reporttype = document.querySelector('input[name="reporttype"]:checked').value;

            if (fromdate != '' && todate != '') {


                $.ajax({
                    url: '{{route("purchase-vat-report")}}',
                    type: "GET",
                    data: {
                        fromdate: fromdate,
                        todate: todate,
                        reporttype: reporttype
                    },
                    success: function(response) {
                        $('#item_result').html(response.data.html);
                        
                        showAlert('Data Retrieved');

                    },

                    error: function(xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            } else {
                alert('From and To Date Required!!');
            }
    }
</script>

@endpush