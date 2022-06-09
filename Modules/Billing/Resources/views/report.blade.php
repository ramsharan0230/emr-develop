@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Billing report
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

                                <div class="col-lg-2 col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">From:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" readonly/>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <!--  <div class="col-sm-2">
                                             <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                         </div> -->
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">To:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" readonly/>
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>

                                </div>
                                <div class="col-lg-2 col-sm-3">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="" value="enc" name="search_type" class="custom-control-input"/>
                                            <label class="custom-control-label" for=""> ENCID</label>
                                        </div>
                                        &nbsp;&nbsp;
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="" value="user" name="search_type" class="custom-control-input"/>
                                            <label class="custom-control-label" for="">User</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <input type="text" name="search_type_text" class="form-control" placeholder="Search By Encounter/User/Invoice No"/>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-3">
                                    <div class="form-group form-row">
                                        <div class="col-sm-5">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="" value="invoice" name="search_type" class="custom-control-input"/>
                                                <label class="custom-control-label" for=""> Invoice</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
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
                                        <input type="text" name="seach_name" class="form-control" placeholder="Patient First Name"/>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-3">
                                    <div class="form-group form-row">
                                        <select name="cash_credit" id="cash_credit" class="form-control">
                                            <option value="">--Payment Type--</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Credit">Credit</option>
                                            {{-- <option value="Card">Card</option>
                                            <option value="Fonepay">Fonepay </option> --}}
                                        </select>
                                    </div>

                                    <div class="form-group form-row">
                                        <select name="billing_mode" id="billing-mode" class="form-control">
                                            <option value="">--Billing Mode--</option>
                                            @if(isset($billingset))
                                                @foreach($billingset as $b)
                                                    <option value="{{$b->fldsetname}}">{{$b->fldsetname}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                </div>
                                <div class="col-lg-3 col-sm-3">
                                    <div class="form-group form-row">
                                        <div class="col-sm-9">
                                            <select name="report_type" id="" class="form-control">
                                                <option value="">--Bill Type--</option>
                                                <option value="CAS">Cash Billing</option>
                                                <option value="DEP">Cash Deposit</option>
                                                <option value="CRE">Cash Return</option>
                                                <option value="RET">Pharmacy Return</option>
                                                <option value="PHM">Pharmacy Sales</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="" name="quantity" class="custom-control-input"/>
                                                <label class="custom-control-label" for="">QTY</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <div class="col-sm-9">
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
                                        <div class="col-sm-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="" name="amount" class="custom-control-input"/>
                                                <label class="custom-control-label" for=""> AMT</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-6">Patient Department</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="patient_department" id="patient_department">
                                                <option value="%">%</option>
                                                @if(isset($departments) and count($departments) > 0)
                                                    @foreach($departments as $de)
                                                        <option value="{{$de->flddept}}">{{$de->flddept}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Packages</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="package" id="package">
                                                <option value="">--Package--</option>
                                                @if(isset($packages) and count($packages) > 0)
                                                    @foreach($packages as $package)
                                                        <option value="{{$package}}">{{$package}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Doctor</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="doctor" id="doctor">
                                                <option value="">--Doctor--</option>
                                                @forelse($doctors as $key => $doctor)
                                                    <option value="{{$key}}">{{$doctor}}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Payment Mode</label>
                                        <div class="col-sm-8">
                                            <select name="payment_mode" id="payment_mode" class="form-control">
                                                <option value="">--Payment mode--</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Credit">Credit</option>
                                                <option value="Card">Card</option>
                                                <option value="Fonepay">Fonepay </option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div>
                                <div class="col-sm-5 float-right">
                                    <div class="d-flex float-right dropdown show">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchBillingDetail()"><i class="fa fa-filter"></i>&nbsp;
                                            Filter</a>&nbsp;

                                        @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('tax-report-button-billing'))))

                                            <a href="javascript:void(0);" type="button" id="dropdownMenuLink" class="btn btn-primary btn-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><i class="fa fa-file-pdf"></i>&nbsp;
                                            TAX Report</a>&nbsp;

                                        @endif

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" id="servicetaxreport" href="#">Report</a>
                                            <a class="dropdown-item" id="servicetaxreportexport" href="#">Export To Excel</a>

                                        </div>

                                        {{-- <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportBillingReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                            Export</a>&nbsp;

                                        </div> --}}
                                        @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-pdf-button-billing'))))
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
                                        @endif

                                        {{-- <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportBillingDetailReportToExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                            Export Detail Report</a> --}}
                                        {{-- <billing-report-export-notification :userinfo="{{ auth('admin_frontend')->user() }}"> </billing-report-export-notification> --}}
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
                        <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" id="chart-tab-two" data-toggle="tab" href="#chart" role="tab" aria-controls="profile" aria-selected="false">Chart:QTY</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="amt-tab-two" data-toggle="tab" href="#amt-two" role="tab" aria-controls="contact" aria-selected="false">Chart:AMT</a>
                            </li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                <div class="table-responsive res-table table-sticky-th">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Invoice</th>
                                            <th>EnciD</th>
                                            <th>Name</th>
                                            <th>OldDepo</th>
                                            <th>TotAmt</th>
                                            <th>TaxAmt</th>
                                            <th>DiscAmt</th>
                                            <th>NetTot</th>
                                            <th>RecAMT</th>
                                            <th>TotalDepo</th>
                                            <th>User</th>
                                            <th>PaymentMode</th>
                                            <th>Billing mode</th>
                                            <th>DiscGroup</th>
                                            <th> Doctor </th>
                                            <th>Payment mode </th>
                                        </tr>
                                        </thead>
                                        <tbody id="billing_result">
                                        @if(isset($results) and count($results) > 0)
                                            @forelse($results as $k=>$r)
                                                @php
                                                    $datetime = explode(' ', $r->fldtime);
                                                    $enpatient = \App\Encounter::where('fldencounterval',$r->fldencounterval)->with('patientInfo')->first();
                                                     $fullname = (isset($enpatient->patientInfo) and !empty($enpatient->patientInfo)) ? $enpatient->patientInfo->fldfullname : '';
                                                     $sn = $k+1;
                                                @endphp
                                                <tr data-billno="{{$r->fldbillno}}" class="bill-list">
                                                    <td>{{$sn}}</td>
                                                    <td><a href="javascript:void(0);" class="btn btn-primary bill" data-bill="'.$r->fldbillno.'"><i class="fas fa-print"></i></a></td>
                                                    <td>{{$datetime[0]}}</td>
                                                    <td>{{$datetime[1]}}</td>
                                                    <td>{{$r->fldbillno}}</td>
                                                    <td>{{$r->fldencounterval}}</td>
                                                    <td>{{$fullname}}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->fldprevdeposit) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->flditemamt) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->fldtaxamt) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->flddiscountamt) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->fldchargedamt) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->fldreceivedamt) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->fldcurdeposit) }}</td>
                                                    <td>{{$r->flduserid}}</td>
                                                    <td>{{$r->payment_mode}}</td>
                                                    <td>{{Helpers::getBillingModeByBillno($r->fldbillno)}}</td>
                                                    <td>{{$r->flddiscountgroup}}</td>
                                                    <td> </td>
                                                    <td> </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="20" class="text-center">
                                                        <em>No data available in table ...</em>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab-two">
                                <div id="qty-chart"></div>
                            </div>
                            <div class="tab-pane fade" id="amt-two" role="tabpanel" aria-labelledby="amt-tab-two">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-body">
                                    <div class="row">
                                        <table class="table table-striped table-hover table-bordered" id="sum">


                                        </table>
                                        <div class="col-sm-6">
                                            <div class="form-group form-row">
                                                <label for="" class="">From:</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="from_bill" id="from_bill" value=""/>
                                                </div>
                                                <label for="" class="">To:</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="to_bill" id="to_bill" value=""/>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 text-right">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary btn-action" onclick="reset()"><i class="fa fa-sync"></i>&nbsp;Reset</button>&nbsp;
                                                @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('user-list-button-billing'))))
                                                    <button type="button" class="btn btn-primary btn-action" onclick="userlist.displayModal()"><i class="fa fa-list"></i>&nbsp;User</button>&nbsp;
                                                @endif
                                                @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('invoice-report-button-billing'))))
                                                    <button type="button" class="btn btn-primary btn-action" onclick="invoiceReport()"><i class="fa fa-file"></i>&nbsp;Invoice</button>
                                                @endif
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

            var url = "{{route('searchBillingDetail')}}";

            $.ajax({
                url: url + "?page=" + page,
                type: "get",
                data: $("#billing_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    $('#billing_result').empty().html(response.html)
                    $('#sum').empty().html(response.sumhtml)
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
            var urlReport = baseUrl + "/billing/service/export-billing-report-excel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport);
        }

        function exportBillingDetailReportToExcel() {
            var data = $("#billing_filter_data ").serialize();
            var urlReport = baseUrl + "/billing/service/export-billing-detail-report-excel?" + data + "&action=" + "Report";
            window.open(urlReport);

        }

        function exportBillingReport() {
            var data = $("#billing_filter_data ").serialize();
            // alert(data);
            var urlReport = baseUrl + "/billing/service/export-billing-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport);
        }
    </script>
@endpush


