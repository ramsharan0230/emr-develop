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

                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">Medicine</label>
                                <div class="col-sm-9">
                                    <select class="select select2" name="medicine_name" id="medicineName">
                                        <option value="">---chose medicine---</option>
                                        @forelse ($medicines as $key =>  $medicine)                       
                                            <option value="{{ $medicine->name }}">{{ $medicine->name }}</option>
                                        @empty
                                            
                                        @endforelse
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div>
                            <button type="button" class="btn btn-primary btn-action refresh" id="refresh"><i class="ri-refresh-line"></i>&nbsp;Refresh</button>&nbsp;
                        </div>
                        <div>
                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportNarcoticReport()"><i class="fas fa-code"></i>&nbsp;
                                Export</a>&nbsp;
                        </div>
                        <div>
                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportNarcoticExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                Export To Excel</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch">
                <div class="iq-card-body">
                    <div id="responseReport">
                        <table  style="width: 100%" 
                                    id="myTable1" data-show-columns="true" data-search="true" data-show-toggle="true"
                                    data-pagination="true"
                                    data-resizable="true"
                                    >
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Date</th>
                                            <th>Batch No and Exp date</th>
                                            <th>Medicine </th>
                                            <th>Qty. Receive</th>
                                            <th>Suppliers Name</th>
                                            <th>Patients name</th>
                                            <th>Quantiy Dispensed</th>
                                            <th>Prescribed By</th>
                                            <th>Name and sign of recipient</th>
                                            <th>Dispensers name and sign </th>
                                            <th>Qty. in Stock </th>
                                            <th>Remarks</th>
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

          //initilizing data table
          $('#myTable1').bootstrapTable()

        $('.refresh').on('click', function(e) {

            var fromdate = $("#from_date").val();
            var todate = $("#to_date").val();
            var medicine = $("#medicineName").val();

           
            if (fromdate != '' && todate != '') {

                $.ajax({
                    url: '{{route("narcotic-sales-report")}}',
                    type: "GET",
                    data: {
                        fromdate: fromdate,
                        todate: todate,
                        medicine_name : medicine,
   
                    },
                    success: function(response) {
                        console.log(response.data.html)
                        // $('#item_result').html(response.data.html);
                        $('#responseReport').html(response.data.html);
                        
                        $('#myTableResponse').bootstrapTable()
  
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
        var medicine = $("#medicineName").val();

        if (fromdate != '' && todate != '') {
            // var urlReport = baseUrl + "/pharmacy-sales/narcotic-sales-report-pdf?fromdate=" + fromdate + "&todate=" + todate + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            // window.open(urlReport);
            $.post("{{ route('ajax.narcotic-sales-report-pdf') }}",{
                'fromdate' : fromdate ,
                'todate' : todate ,
                'medicine_name' : medicine,
                '_token' : "{{ csrf_token() }}"
             }, function (response) {
                var w = window.open('about:blank');
                w.document.open();
                // w.document.write(response.data.html);
                w.document.write(response);
                w.document.close();
            });

        } else {
            alert('From and To Date Required!!');
        }
    }

    function exportNarcoticExcel() {


        var fromdate = $("#from_date").val();
        var todate = $("#to_date").val();
        var medicine = $("#medicineName").val();

        if (fromdate != '' && todate != '') {
            // var urlReport = baseUrl + "/pharmacy-sales/narcotic-sales-report-excel?fromdate=" + fromdate + "&todate=" + todate + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}"+"&medicine_name="+medicine ;
            $.ajax({
                'url' :"{{ route('narcotic-sales-report-excel') }}",
                'type' : 'get',
                'cache': false,
                'data' : {
                    'fromdate' : fromdate ,
                    'todate' : todate ,
                    'medicine_name' : medicine,
                    '_token' : "{{ csrf_token() }}"
                },
                'xhrFields':{
                    responseType: 'blob'
                },
              
             
                success :function (response) {

                    // var a = document.createElement("a");
                    // a.href = response.file; 
                    // a.download = response.name;
                    // document.body.appendChild(a);
                    // a.click();
                    // a.remove();
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(response);
                    link.download = `Invoice_details_report.xlsx`;
                    link.click(); 
                },
            });

            // window.open(urlReport);
        } else {
            alert('From and To Date Required!!');
        }
    }

</script>