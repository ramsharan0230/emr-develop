@extends('frontend.layouts.master')
@section('content')


<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Narcotic Sales Report
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
                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">From:</label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" name="from_date" value="{{isset($date) ? $date : ''}}" id="from_date" class="form-control nepaliDatePicker" />
                                </div>

                            </div>


                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-2">To:</label>
                                <div class="col-sm-10">
                                    <input type="text" autocomplete="off" name="to_date" id="to_date"  value="{{isset($date) ? $date : ''}}" class="form-control nepaliDatePicker" />
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <button type="button" class="btn btn-primary rounded-pill refresh" id="refresh"><i class="ri-refresh-line"></i>&nbsp;Refresh</button>

                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportNarcoticReport()"><i class="fas fa-code"></i>&nbsp;
                                Export</a>&nbsp;
                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportNarcoticExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                Export To Excel</a>&nbsp;
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
            <!-- <div class="res-table table-sticky-th">
                <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light"> -->

                        <div class="table-responsive table-sticky-th">
                            <table class="table table-bordered table-hover table-striped text-center">
                                <thead class="thead-light">
                            <tr><td>S.N.</td>
                                <td>Patient Details</td>
                                <td>Medicine Name</td>
                                <td>Prescribed By</td>
                                <td>Dispensed By</td>
                                <td>Bill No.</td>
                                <td>Date and Time</td>
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

@endsection


<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
    $(document).ready(function() {

        $('.refresh').on('click', function(e) {

            var fromdate = $("#from_date").val();
            var todate = $("#to_date").val();

           
            if (fromdate != '' && todate != '') {

                $.ajax({
                    url: '{{route("narcotic-sales-report")}}',
                    type: "GET",
                    data: {
                        fromdate: fromdate,
                        todate: todate
   
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


            }else{
                alert('From and To Date Required!!');
            }

        });

    });

 

    function exportNarcoticReport() {

        
        var fromdate = $("#from_date").val();
        var todate = $("#to_date").val();

        if (fromdate != '' && todate != '') {
            var urlReport = baseUrl + "/pharmacy-sales/narcotic-sales-report-pdf?fromdate=" + fromdate + "&todate=" + todate + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport);
        } else {
            alert('From and To Date Required!!');
        }
    }

    function exportNarcoticExcel() {


        var fromdate = $("#from_date").val();
        var todate = $("#to_date").val();

        if (fromdate != '' && todate != '') {
            var urlReport = baseUrl + "/pharmacy-sales/narcotic-sales-report-excel?fromdate=" + fromdate + "&todate=" + todate + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport);
        } else {
            alert('From and To Date Required!!');
        }
    }

</script>