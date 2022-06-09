@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Off Time report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="billing_filter_data">
                            <div class="row">

                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-2">From:</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" readonly/>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="time" class="form-control" id="from_time" name="from_time" value="16:00:00">
                                        </div>
                                        <!--  <div class="col-sm-2">
                                             <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                         </div> -->
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-2">To:</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" readonly/>
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="time" class="form-control" id="to_time" name="to_time" value="09:00:00">
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>

                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="" value="enc" name="search_type" class="custom-control-input"/>
                                            <label class="custom-control-label" for=""> ENCID</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-3">
                                            <input type="radio" id="" value="user" name="search_type" class="custom-control-input"/>
                                            <label class="custom-control-label" for="">User</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-3">
                                            <input type="radio" id="" value="invoice" name="search_type" class="custom-control-input"/>
                                            <label class="custom-control-label" for=""> Invoice</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <input type="text" name="search_type_text" class="form-control" placeholder="Search By Encounter/User/Invoice No"/>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-sm-12">
                                            <select name="department" id="" class="form-control department">
                                                <option value="">--Department--</option>
                                                @if($hospital_department)
                                                    @forelse($hospital_department as $dept)
                                                        @if($dept->departmentData)
                                                            <option value="{{ $dept->departmentData->fldcomp }}">{{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})</option>
                                                    @endif
                                                @empty

                                                @endforelse
                                            @endif
                                            <!-- <option value="Male"></option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <div class="col-sm-12">
                                            <select name="item_type" id="" class="form-control">
                                                <option value="">--Item Wise--</option>
                                                <option value="Diagnostic Tests">Diagnostic Tests</option>
                                                <option value="Equipment">Equipment</option>
                                                <option value="Extra Items">Extra Items</option>
                                                <option value="General Services">General Services</option>
                                                <option value="Medicines">Medicines</option>
                                                <option value="Other Items">Other Items</option>
                                                <option value="Radio Diagnostics">Radio Diagnostics</option>
                                                <option value="Surgicals">Surgicals</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="float-right">
                                    <div class="d-flex float-right dropdown show">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchBillingDetail()"><i class="fa fa-filter"></i>&nbsp;
                                            Filter</a>&nbsp;

                                        {{-- @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('tax-report-button-billing'))))

                                            <a href="javascript:void(0);" type="button" id="dropdownMenuLink" class="btn btn-primary btn-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><i class="fa fa-file-pdf"></i>&nbsp;
                                            TAX Report</a>&nbsp;

                                        @endif --}}

                                        {{-- <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" id="servicetaxreport" href="#">Report</a>
                                            <a class="dropdown-item" id="servicetaxreportexport" href="#">Export To Excel</a>

                                        </div> --}}


                                        {{-- @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-pdf-button-billing'))))
                                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportBillingReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                                Export</a>&nbsp;
                                        @endif
                                        @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-excel-button-billing'))))
                                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportBillingReportToExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                                Export To Excel</a>&nbsp;
                                        @endif
                                        @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-detail_report-button-billing'))))
                                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportBillingDetailReportToExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                                Export Detail Report</a>
                                        @endif --}}

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="d-flex  flex-row justify-content-end" id="">
                            {{-- <input type="text" class="form-control" id="search-table" placeholder="Search" autocomplete="false" style="width:35%;"> --}}
                            <div class="d-flex flex-row">
                                 @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-pdf-button-billing'))))
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary" onclick="exportBillingReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                        PDF</a>&nbsp;
                                @endif
                                @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-excel-button-billing'))))
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary" onclick="exportBillingReportToExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                        Excel</a>
                                @endif
                            </div>
                        </div>
                        <div class="tab-content" id="myTabContent-1">


                                <div id="billing_result">
                                    <table  style="width: 100%" id="table"
                                    class="table expandable-table custom-table table-bordered table-striped mt-c-15"
                                     data-show-columns="true" data-search="true" data-show-toggle="true"
                                        data-pagination="true"
                                        data-resizable="true"
                                    >
                                        <thead class="thead-light">
                                        <tr>
                                            <th>SN</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Invoice</th>
                                            <th>EnciD</th>
                                            <th>Name</th>
                                            <th>Particulars</th>
                                            <th>Rate</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                        </thead>
                                        <tbody >
                                       

                                        </tbody>
                                    </table>
                                </div>



                        <div class="col-sm-12 mt-3">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-body">
                                    <div class="row"  id="subtabsumle">
                                        <div class="table table-bordered">
                                            <table style="width: 100%" id="">
                                                {{-- <div id="sum"> --}}
                                                    <tr>
                                                        {{-- <td>Deposit: Rs. __</td> --}}
                                                        {{-- <td>Deposit refund: Rs. __</td> --}}
                                                        <td>Subtotal: Rs.__</td>
                                                        <td>Tax: Rs. __</td>
                                                        <td>Discount: Rs. __</td>
                                                        <td>Total Amount: Rs.__</td>
                                                    </tr>
                                                {{-- </div> --}}
                                            </table>
                                        </div>
                                        {{-- <div class="col-sm-6 p-0">
                                            <div class="form-group form-row">
                                                <label for="" class="">From</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="from_bill" id="from_bill" value=""/>
                                                </div>
                                                <label for="" class="">To</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="to_bill" id="to_bill" value=""/>
                                                </div>&nbsp;
                                                <button type="button" class="btn btn-primary" onclick="reset()"><i class="fa fa-sync"></i>&nbsp;Reset</button>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 text-right p-0">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary btn-action" onclick="userDetail.displayreport()"><i class="fa fa-file"></i>&nbsp;Dept Wise Report</button>&nbsp;
                                                <button type="button" class="btn btn-primary btn-action" onclick="userlist.displayModal()"><i class="fa fa-file"></i>&nbsp;User Collection Report</button>
                                                <button type="button" class="btn btn-primary btn-action" onclick="invoiceReport()"><i class="fa fa-file"></i>&nbsp;Invoice Report</button>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    @include('billing::modal.user-list')
    @include('billing::modal.user-bill')
@endsection
@push('after-script')
    <!-- am core JavaScript -->
    <script src="{{ asset('new/js/core.js') }}"></script>
    <!-- am charts JavaScript -->
    <script src="{{ asset('new/js/charts.js') }}"></script>
    {{-- Apex Charts --}}
    <script src="{{ asset('js/apex-chart.min.js') }}"></script>
    <!-- am animated JavaScript -->
    <script src="{{ asset('new/js/animated.js') }}"></script>
    <!-- am kelly JavaScript -->
    <script src="{{ asset('new/js/kelly.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#table').bootstrapTable()
            setTimeout(function () {
                $(".department").select2();
                $("#doctor").select2();
                $("#patient_department").select2();
                $("#package").select2();
            }, 1500);
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                searchBillingDetail(page);
            });

            $("#servicetaxreport").on("click",function(){

                var fromdate = $("#from_date").val();
                var todate = $("#to_date").val();
                var deptcomp = $(".department").val();

                if(deptcomp != ''){
                    if (fromdate != '' && todate != '') {
                        var urlReport = baseUrl + "/billing/service/service-tax-report-pdf?fromdate=" + fromdate + "&todate=" + todate + "&deptcomp=" + deptcomp + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

                        window.open(urlReport);
                    } else {
                        alert('From and To Date Required!!');
                    }
                }else{
                    alert('Please Select Department!!');
                }

                });


            $("#servicetaxreportexport").on("click",function(){

                var fromdate = $("#from_date").val();
                var todate = $("#to_date").val();
                var deptcomp = $(".department").val();

                if(deptcomp != ''){
                    if (fromdate != '' && todate != '') {
                        var urlReport = baseUrl + "/billing/service/service-tax-export-pdf?fromdate=" + fromdate + "&todate=" + todate + "&deptcomp=" + deptcomp + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

                        window.open(urlReport);
                    } else {
                        alert('From and To Date Required!!');
                    }
                }else{
                    alert('Please Select Department!!');
                }



            });


            $("#billing-mode").on("change", function() {
                $.ajax({
                    url: "{{route('billing.package')}}",
                    type: "post",
                    data: { "value": $(this).val() || "", "_token": "{{ csrf_token() }}" },
                    success: function (response) {
                        const newOptions = JSON.parse(response);
                        var $el = $("#package");
                        $el.empty(); // remove old options
                        $.each(newOptions, function(key,value) {
                        $el.append($("<option></option>")
                            .attr("value", value).text(key));
                        });
                    },
                    error: function (xhr, status, error) {
                        var option = $('<option></option>').attr("value", "").text("--Package--");
                        $("#package").empty().append(option);
                    }
                });
            })
        });

        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_from_date').val(BS2AD($('#from_date').val()));
            }
        });

        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_to_date').val(BS2AD($('#to_date').val()));
            }
        });


        function searchBillingDetail(page) {

            var url = "{{route('filter.offline/report')}}";

            $.ajax({
                url: url + "?page=" + page,
                type: "get",
                data: $("#billing_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    $('#billing_result').empty().html(response.html)
                    $('#myTableResponse').bootstrapTable()
                    $('#subtabsumle').empty().html(response.summary)
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }



        $(document).on('click', '#chart-tab-two-bk', function () {
            var chartByQuantity;
            var colorForAll = ['#FFA500', '#B8860B', '#BDB76B', '#F0E68C', '#9ACD32'
                , '#ADFF2F', '#008000', '#66CDAA', '#8FBC8F', '#008080', '#00CED1', '#7FFFD4', '#4682B4'
                , '#1E90FF', '#00008B', '#4169E1', '#9370DB'
                , '#9932CC', '#EE82EE', '#C71585', '#644e35', '#FFFACD', '#A0522D'
                , '#808000', '#778899', '#0a6258', '#A9A9A9'];
            $.ajax({
                url: '{{ route("getQuantityChartDetail") }}',
                type: "POST",
                data: $("#billing_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response, status, xhr) {
                    // chartByQuantity.destroy();
                    console.log(response)
                    var options = {
                        series: [{
                            name: "Encounters",
                            data: response.encounters
                        }],
                        chart: {
                            height: 350,
                            type: 'line',
                            zoom: {
                                enabled: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'straight'
                        },
                        title: {
                            text: 'Encounters Against Quantity',
                            align: 'left'
                        },
                        grid: {
                            row: {
                                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                opacity: 0.5
                            },
                        },
                        xaxis: {
                            categories: response.quantity,
                        }
                    };

                    chartByQuantity = new ApexCharts(
                        document.querySelector("#qty-chart"),
                        options
                    );

                    chartByQuantity.render();

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });

        $(document).on('click', '.bill', function () {
            var data = $(this).data('bill');
            var urlReport = baseUrl + "/billing/service/billing-invoice?billno=" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            if (data.startsWith('RET'))
                urlReport = baseUrl + "/billing/service/displayReturnBilling?invoice_number=" + data;
            else if (data.startsWith('DEP'))
                urlReport = baseUrl + "/depositForm/printBill?fldbillno=" + data;

            window.open(urlReport, '_blank');
        });

        $(document).on('click', '.bill-list', function () {
            var billno = $(this).data('billno');
            if ($('#from_bill').val() != '') {
                $('#to_bill').val(billno);
            } else {
                $('#from_bill').val(billno);
            }
        });

        function reset() {
            $('#to_bill').val('');
            $('#from_bill').val('');
        }

        var userlist = {
            displayModal: function () {

                if ($('#from_bill').val() == "" || $('#to_bill').val() == "") {
                    alert('Please choose bill range.');
                    return false;
                }
                $.ajax({
                    url: '{{ route('billing.user.list') }}',
                    type: "POST",
                    data: {
                        frombill: $('#from_bill').val(),
                        tobill: $('#to_bill').val()
                    },
                    success: function (response) {
                        // console.log(response);
                        $('#user-list-modal').modal('show');
                        $('#userform').html(response);


                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            },
        }

        function invoiceReport() {
            if ($('#from_bill').val() == "" || $('#to_bill').val() == "") {
                alert('Please choose bill range.');
                return false;
            }

            var frombill = $('#from_bill').val();
            var tobill = $('#to_bill').val();
            var fromdate = $('#from_date').val();
            var todate = $('#to_date').val();
            // alert(data);
            var urlReport = baseUrl + "/billing/service/billing-invoice-list?frombill=" + frombill + "&tobill=" + tobill + "&fromdate=" + fromdate + "&todate=" + todate + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport, '_blank');
        }

        function groupsReport() {
            if ($('#from_bill').val() == "" || $('#to_bill').val() == "") {
                alert('Please choose bill range.');
                return false;
            }

            var frombill = $('#from_bill').val();
            var tobill = $('#to_bill').val();
            var fromdate = $('#from_date').val();
            var todate = $('#to_date').val();
            // alert(data);
            var urlReport = baseUrl + "/billing/service/billing-group-report?frombill=" + frombill + "&tobill=" + tobill + "&fromdate=" + fromdate + "&todate=" + todate + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport, '_blank');
        }

        function exportBillingReportToExcel() {
            var data = $("#billing_filter_data ").serialize();
            // alert(data);
            var urlReport = baseUrl + "/offtime/offlinereport/export-billing-offline-report-excel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport);
        }

        function exportBillingDetailReportToExcel() {
            var data = $("#billing_filter_data ").serialize();
            var urlReport = baseUrl + "/offtime/offlinereport/export-offlinereport-billing-detail-report-excel?" + data + "&action=" + "Report";
            window.open(urlReport);

        }

        function exportBillingReport() {
            var data = $("#billing_filter_data ").serialize();
            // alert(data);
            var urlReport = baseUrl + "/offtime/offlinereport/export-offlinereport-billing-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport);
        }
    </script>
@endpush


