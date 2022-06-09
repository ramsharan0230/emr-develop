@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Deposit report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="deposit_filter_data">
                            <div class="row">
                                <div class="col-lg-3 col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-2">From:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-2">To:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Last Status:</label>
                                        <div class="col-sm-8">
                                            <select name="last_status" id="lastStatus" class="form-control">
                                                <option value="%">%</option>
                                                <option value="Recorded">Recorded</option>
                                                <option value="Registered">Registered</option>
                                                <option value="Admitted">Admitted</option>
                                                <option value="Discharged">Discharged</option>
                                                <option value="LAMA">LAMA</option>
                                                <option value="Refer">Refer</option>
                                                <option value="Death">Death</option>
                                                <option value="Absconder">Absconder</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Deposit Type:</label>
                                        <div class="col-sm-8">
                                            <select name="type" id="typeSelect" class="form-control">
                                                <option value="All">All</option>
                                                <option value="RE Deposit">RE Deposit</option>
                                                <option value="OP Deposit">OP Deposit</option>
                                                <option value="Pharmacy Deposit">Pharmacy Deposit</option>
                                                <option value="Admission Deposit">Admission Deposit</option>
                                                <option value="Deposit Refund">Deposit Refund</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-2 col-sm-2">
                                    <div class="form-group">
                                        <input type="checkbox" value="1" name="expense" id="expense"/> Expense
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" value="1" name="payment" id="payment"/> Payment
                                    </div>
                                </div> --}}

                                <div class="col-lg-3 col-sm-4">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <div class="form-group">
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchDepositDetail()"><i class="fa fa-search"></i>&nbsp;
                                                Search</a>
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDepositReport()"><i class="fas fa-file-pdf"></i>&nbsp;
                                                Pdf</a>
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDepositReportExcel()"><i class="fa fa-code"></i>&nbsp;
                                                Export</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group" style="margin-top: -15px;">
                                        <div class="d-flex justify-content-between">
                                            <div class="form-group">
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDepositReportExcel()"><i class="fa fa-code"></i>&nbsp;
                                                    Export</a>
                                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportDepositReportExcelNew()"><i class="fa fa-code"></i>&nbsp;Export New</a>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                            </li>
                            <!--                      <li class="nav-item">
                                                     <a class="nav-link" id="chart-tab-two" data-toggle="tab" href="#chart" role="tab" aria-controls="profile" aria-selected="false">Chart:Expense</a>
                                                  </li>
                                                  <li class="nav-item">
                                                     <a class="nav-link" id="amt-tab-two" data-toggle="tab" href="#amt-two" role="tab" aria-controls="contact" aria-selected="false">Chart:Payment</a>
                                                  </li>-->
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                <div class="table-responsive res-table" style="max-height: none;">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>SN</th>
                                            <th>Patient ID</th>
                                            <th>DEPO_DEPOSITTYPE</th>
                                            <th>STATUS</th>
                                            <th>INPATIENTID</th>
                                            <th>PATIENTNAME</th>
                                            {{-- <th>Credit</th> --}}
                                            <th>DEPOSITNO</th>
                                            <th>DEPOSITDATE</th>
                                            <th>DEPOSITCOLN</th>
                                            <th>DEPOSITREFUND</th>
                                            <th>FINALBILLAMT</th>
                                        </tr>
                                        </thead>
                                        <tbody id="deposit_result">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--                          <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab-two">
                                                            <div id="qty-chart"></div>
                                                      </div>
                                                      <div class="tab-pane fade" id="amt-two" role="tabpanel" aria-labelledby="amt-tab-two">
                                                      </div>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                searchDepositDetail(page);
            });
        });

        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,

        });
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,

        });

        function exportDepositReport() {
            if(!$('#expense').is(":checked")){
                var expense = 0;
            }else{
                var expense = 1;
            }
            if(!$('#payment').is(":checked")){
                var payment = 0;
            }else{
                var payment = 1;
            }
            var urlReport = baseUrl + "/depositForm/deposit-report/pdf?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&lastStatus=" + $('#lastStatus').val() + "&type=" + $('#typeSelect').val() + '&expense=' + expense + '&payment=' + payment;
            window.open(urlReport, '_blank');
        }

        function exportDepositReportExcel() {
            if(!$('#expense').is(":checked")){
                var expense = 0;
            }else{
                var expense = 1;
            }
            if(!$('#payment').is(":checked")){
                var payment = 0;
            }else{
                var payment = 1;
            }
            var urlReport = baseUrl + "/depositForm/deposit-report/excel?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&lastStatus=" + $('#lastStatus').val() + "&type=" + $('#typeSelect').val() + '&expense=' + expense + '&payment=' + payment;
            window.open(urlReport);
        }

        function exportDepositReportExcelNew() {
            var urlReport = baseUrl + "/depositForm/deposit-report-new/excel?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&lastStatus=" + $('#lastStatus').val() + "&type=" + $('#typeSelect').val();
            window.open(urlReport);
        }

        function searchDepositDetail(page) {
            var url = "{{route('searchDepositDetail')}}";
            if (url !== undefined) {
                url = url + "?page=" + page;
            }
            $.ajax({
                url: url,
                type: "GET",
                data: $("#deposit_filter_data").serialize(),
                success: function (response) {
                    if (response.data.status) {
                        if(!$('#expense').is(":checked")){
                            $('#expenseth').hide();
                        }else{
                            $('#expenseth').show();
                        }
                        if(!$('#payment').is(":checked")){
                            $('#paymentth').hide();
                        }else{
                            $('#paymentth').show();
                        }
                        $('#deposit_result').html(response.data.html)
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        // $(document).on('click','#chart-tab-two', function(){

        //   // Load the Visualization API and the piechart package.
        //     google.charts.load('current', {'packages':['corechart']});

        //     // Set a callback to run when the Google Visualization API is loaded.
        //     google.charts.setOnLoadCallback(drawChart);

        //     function drawChart() {
        //         var url = "{{route('getQuantityChartDetail')}}";
        //         $.ajax({
        //           url: url,
        //           type: "POST",
        //           data:  $("#billing_filter_data").serialize(),"_token": "{{ csrf_token() }}",
        //           dataType: "json", // type of data we're expecting from server
        //           async: false // make true to avoid waiting for the request to be complete
        //           }).done(function (jsonData) {
        //             console.log(jsonData);
        //           // Create our data table out of JSON data loaded from server.
        //           var data = new google.visualization.DataTable(jsonData);

        //           // Instantiate and draw our chart, passing in some options.
        //           var chart = new google.visualization.PieChart(document.getElementById('qty-chart'));

        //           var options = {
        //               title: 'Monthly Shares of phpocean susbscribers - total of 759 user',
        //               width: 800,
        //               height: 440,
        //               pieHole: 0.4,
        //             };

        //           chart.draw(data, options);
        //           }).fail(function (jq, text, err) {
        //               console.log(text + ' - ' + err);
        //           });

        //     };

        // });
        // function printInvoice(billno){
        //     data = $('#billing_filter_data').serialize();
        //     var urlReport = baseUrl + "/billing/service/billing-invoice?billno=" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
        //     window.open(urlReport, '_blank');
        // }

        // $(document).on('click','#chart-tab-two', function(){

        //   // Load the Visualization API and the piechart package.
        //     google.charts.load('current', {'packages':['corechart']});

        //     // Set a callback to run when the Google Visualization API is loaded.
        //     google.charts.setOnLoadCallback(drawChart);

        //     function drawChart() {
        //         var url = "{{route('getQuantityChartDetail')}}";
        //         $.ajax({
        //           url: url,
        //           type: "POST",
        //           data:  $("#billing_filter_data").serialize(),"_token": "{{ csrf_token() }}",
        //           dataType: "json", // type of data we're expecting from server
        //           async: false // make true to avoid waiting for the request to be complete
        //           }).done(function (jsonData) {
        //             var data = new google.visualization.DataTable(jsonData);
        //             // data.addColumn('string', 'Encounter');
        //             // data.addColumn('number', 'Quantity');

        //             // jsonData.each(function (row) {
        //             //     data.addRow([
        //             //       row.Encounter,
        //             //       row.Quantity
        //             //     ]);
        //             //   });
        //           var chart = new google.visualization.LineChart(document.getElementById('qty-chart'));
        //           chart.draw(data, {
        //             width: 400,
        //             height: 240
        //           });

        //           // chart.draw(data, options);
        //           }).fail(function (jq, text, err) {
        //               console.log(text + ' - ' + err);
        //           });
        //     };

        // });


    </script>
@endsection

