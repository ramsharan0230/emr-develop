@extends('frontend.layouts.master')
@section('content')

    <style>
        .head {
            font-size: 12px;
            padding: 2px 5px;
            border-radius: 100px;
            background: #e7f1fb;
            font-weight: 500;
        }

        .res-table {
            max-height: 1000px;
        }

        a i {
            margin-top: 4px;
        }
        .search {
            float: left !important;
        }

        .bootstrap-table .fixed-table-container{
        overflow:auto;
    }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <div class="d-flex flex-row align-items-center">
                                <h4 class="card-title">
                                    Billing report
                                </h4>
                                <div class="bredcrumb d-none" id="bredcrumbList">

                                </div>
                            </div>
                        </div>
                        {{-- myFunction(), --}}
                        <button onclick="myFunction(),breadCrumbFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form id="billing_filter_data">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group d-flex align-items-center justify-content-between">
                                        <div class="d-flex flex-row">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="" value="ad_date" name="search_type"  class="custom-control-input dateformat"/>
                                                <label class="custom-control-label" for="">AD</label>
                                            </div>
                                            <div class="custom-control custom-radio ml-3">
                                                <input type="radio" id="" value="bs_date" name="search_type" checked class="custom-control-input dateformat"/>
                                                <label class="custom-control-label" for="">BS</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-outline-primary" onclick="resetInput()"><i class="fa fa-sync"></i>&nbsp;Reset</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-6 nepalicalendar active">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">From</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" readonly/>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">To</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" readonly/>
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 engcalendar d-none">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">From</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" name="from_date" id="from_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"  min="1997-01-01" max="2030-12-31" />
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">To</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" name="to_date" id="to_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"  min="1997-01-01" max="2030-12-31" />
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Department</label>
                                        <div class="col-sm-8">

                                            <select name="department" id="" class="form-control department">
                                                <option value="">--Department--</option>
                                                @if($hospital_department)
                                                    @forelse($hospital_department as $dept)

                                                        @if($dept->departmentData)
                                                            <option value="{{ $dept->departmentData->fldcomp }}" @if(\App\Utils\Helpers::getCompName() == $dept->departmentData->fldcomp) selected @endif>
                                                                {{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})</option>
                                                    @endif
                                                @empty

                                                @endforelse
                                            @endif

                                            <!-- <option value="Male"></option> -->
                                            </select>
                                            {{-- @dd($dept)
                                            @dd(\App\Utils\Helpers::getCompName()) --}}
                                        </div>
                                    </div>
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
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Bill Type</label>
                                        <div class="col-sm-8">

                                            <select name="report_type" id="bill_type" class="form-control">

                                                <option value="">--Bill Type--</option>

                                                <option id="servicebilling" value="CAS" >Service Billing  </option>
                                                <option value="DEP">Deposit Billing  </option>
                                                <option value="CRE">Credit Billing </option>
                                                <option value="PHM">Pharmacy Billing </option>
                                                <option value="RET">Return Billing </option>
                                                <option  value="DISCLR" >Discharge Clearance </option>
                                                <option  value="Refund"  >Refund </option>



                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Item Type</label>
                                        <div class="col-sm-8">
                                            <select name="item_type" id="item_type" class="form-control">
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
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Package Name</label>
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
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="col-sm-12 float-right p-0">
                                    <div class="d-flex float-right dropdown show">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchBillingDetail()"><i class="fa fa-filter"></i>&nbsp;
                                            Filter</a>&nbsp;

                                        @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('tax-report-button-billing'))))

                                            <a href="javascript:void(0);" type="button" id="dropdownMenuLink" class="btn btn-primary btn-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><i class="fa fa-file-pdf"></i>&nbsp;
                                            TAX Report</a>&nbsp;

                                        @endif

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('tax-report-export-report-button-billing'))))
                                                <a class="dropdown-item" id="servicetaxreport" href="#">Report</a>
                                            @endif
                                            @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('tax-report-export-excel-button-billing'))))
                                                <a class="dropdown-item" id="servicetaxreportexport" href="#">Export To Excel</a>
                                            @endif

                                        </div>
                                        <!-- @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-pdf-button-billing'))))
                                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportBillingReport()"><i class="fa fa-file-pdf"></i>&nbsp;
                                                Export to PDF</a>&nbsp;
                                        @endif -->
                                        <!-- @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-excel-button-billing'))))
                                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportBillingReportToExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                                Export To Excel</a>&nbsp;
                                        @endif -->
                                        @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-detail_report-button-billing'))))
                                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportBillingDetailReportToExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                                Export Detail Report</a>
                                        @endif

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
                        <div class="d-flex  flex-row justify-content-between" id="">
                            <input type="text" class="form-control" id="search-table" placeholder="Search" autocomplete="false" style="width:35%;">
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
                                    id="myTable1" data-show-columns="true" data-search="false" data-show-toggle="true"
                                        data-pagination="true"
                                        data-resizable="true"
                                    >
                                        <thead class="thead-light">
                                        <tr>
                                            <th>SN</th>
                                            <th>Bill no.</th>
                                            <th>Bill Type</th>
                                            <th>Bill Date/Time</th>
                                            <th>Encounter no.</th>
                                            <th>Patient Detail</th>
                                            <th>Billing Mode/Discount Mode</th>
                                            <th>Prev. Dept.</th>
                                            <th>Item Amount</th>
                                            <th>Discount Amt</th>
                                            <th>Received Amt</th>
                                            <th>Curr Deposit</th>
                                            <th>Payment Mode</th>
                                            <th>Doctor Detail</th>
                                        </tr>
                                        </thead>
                                        <tbody >
                                        @if(isset($results) and count($results) > 0)
                                            @forelse($results as $k=>$r)
                                                @php
                                                    $datetime = explode(' ', $r->fldtime);
                                                    $enpatient = \App\Encounter::where('fldencounterval',$r->fldencounterval)->with('patientInfo')->first();
                                                     $fullname = (isset($enpatient->patientInfo) and !empty($enpatient->patientInfo)) ? $enpatient->patientInfo->fldfullname : '';
                                                     $sn = $k+1;
                                                @endphp
                                                <tr data-billno="{{$r->fldbillno}}" class="billInfo">
                                                    <td>{{$sn}}</td>

                                                    <td>{{$r->fldbillno}}</td>
                                                    <td>{{$r->fldbilltype}}</td>
                                                    <td>{{$datetime[0]}} - {{$datetime[1]}}</td>


                                                    <td>{{$r->fldencounterval}}</td>
                                                    <td>{{$fullname}}</td>
                                                    <td>{{Helpers::getBillingModeByBillno($r->fldbillno)}}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->fldprevdeposit) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->flditemamt) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->flddiscountamt) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->fldreceivedamt) }}</td>
                                                    <td>{{ \App\Utils\Helpers::numberFormat($r->fldcurdeposit) }}</td>
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



                        <div class="col-sm-12 mt-3">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-body">
                                    <div class="row">
                                        <div class="table table-bordered">
                                            <table style="width: 100%" id="sum">
                                                {{-- <div id="sum"> --}}
                                                    <tr>
                                                        <td>Deposit: Rs. __</td>
                                                        <td>Deposit refund: Rs. __</td>
                                                        <td>Amount: Rs.__</td>
                                                        <td>Tax: Rs. __</td>
                                                        <td>Discount: Rs. __</td>
                                                        <td>Received: Rs.__</td>
                                                    </tr>
                                                {{-- </div> --}}
                                            </table>
                                        </div>
                                        <div class="col-sm-6 p-0">
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
    @include('billing::modal.user-discharge-clearance-bill')
    @include('billing::modal.user-bill')
@endsection
@push('after-script')
<script src="{{ asset('js/print.js') }}"> </script>

    <!-- Expandable Table -->
    <script>
        $(function() {
            $('#table').bootstrapTable()
        })
    </script>
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


            // $('.billInfo').click( function () {
            //     alert('f')
            //     userDetail.displayBillModal(this)
            // })

            $(document).on('click', '.billInfo td', function (ev) {
                if($(this).index()==1 || $(this).index()==5 ){
                    ev.preventDefault();
                    // return false;
                }else{
                    // userDetail.displayBillModal(this)
                    userDetail.displayBillModal($(this).closest('tr'))
                }

            })
            // $("tr.billInfo").on("click", "td:not(:nth-child(1))", function(event){
            //     alert("clicked:" + $(this).text());
            // });

            $('.engcalendar').find('#from_date').removeAttr('name')
            $('.engcalendar').find('#to_date').removeAttr('name')
            $('.engcalendar').find('#eng_from_date').removeAttr('name')
            $('.engcalendar').find('#eng_to_date').removeAttr('name')

            // $('input[type=radio][name=search_type]').click(function(){
            $(document).on('click', '.custom-radio', function(){
                console.log('radio box is clicked');
                currentCheckBox = $(this).find('.dateformat');
                console.log( currentCheckBox, currentCheckBox.val())
                // if($(this).val() == 'ad_date'){
                if( currentCheckBox.val() == 'ad_date') {
                    console.log('ad date click')

                    $('.engcalendar').removeClass('d-none')
                    $('.nepalicalendar').addClass('d-none')

                    $('.engcalendar').find('#from_date').attr('name', 'from_date')
                    $('.engcalendar').find('#to_date').attr('name','to_date')
                    $('.engcalendar').find('#eng_from_date').attr('name','eng_from_date')
                    $('.engcalendar').find('#eng_to_date').attr('name','eng_to_date')

                    $('.nepalicalendar').find('#from_date').removeAttr('name')
                    $('.nepalicalendar').find('#to_date').removeAttr('name')
                    $('.nepalicalendar').find('#eng_from_date').removeAttr('name')
                    $('.nepalicalendar').find('#eng_to_date').removeAttr('name')


                }else{
                    console.log('bs date click')

                    $('.engcalendar').find('#from_date').removeAttr('name', )
                    $('.engcalendar').find('#to_date').removeAttr('name', )
                    $('.engcalendar').find('#eng_from_date').removeAttr('name')
                    $('.engcalendar').find('#eng_to_date').removeAttr('name')

                    $('.nepalicalendar').find('#from_date').attr('name', 'from_date')
                    $('.nepalicalendar').find('#to_date').attr('name', 'to_date')
                    $('.nepalicalendar').find('#eng_from_date').attr('name','eng_from_date')
                    $('.nepalicalendar').find('#eng_to_date').attr('name', 'eng_to_date')

                    $('.engcalendar').addClass('d-none')
                    $('.nepalicalendar').removeClass('d-none')
                }

            })



            $(document).on('click', '.printBillInformation', function(){

                $.PrintPlugin({

                        selector : '#billData',
                        remotefetch: {
                            loadFormRemote : true,
                            requestType : "POST",
                            origin : $(this).data('route'),
                            responseProperty : 'printview',
                            payload : {
                                'billno' : $(this).data('billno'),
                                'invoice_number': $(this).data('billno'),
                                'fldbillno' :$(this).data('billno') ,
                                'encounter_id' : $(this).data('encounter_id') ,
                                '_token' : "{{ csrf_token() }}"
                            },
                        },
                        print: function () {
                            // console.log(" i ma printing now");
                        }
                    });
                })



            window.addEventListener('afterprint', (event) => {

            });
            window.onafterprint = (event) => {

                console.log('bla vla after print')
            };



            //initilizing data table
            $('#myTable1').bootstrapTable()
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

            $('.department').on('change', function(){
                if($(this).val() == 'comp02'){
                    $('#servicebilling').css('display', 'none');
                    $('#discharge').css('display', 'none');
                    $('#depositeClr').css('display', 'block');
                }
                else{
                    $('#servicebilling').css('display', 'block');
                    $('#discharge').css('display', 'block');
                    $('#depositeClr').css('display', 'none');
                }

                console.log( 'department value', $(this).val());
            })

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




            var restrictHitOnNull = true
            $("#search-table").keyup( function(e){
                var timeout = null;
                var minlength = 3;
                e.preventDefault();
                // clear last timeout check
                clearTimeout(timeout);
                var value = $(this).val();
                var that = this;
                formData = $("#billing_filter_data").serialize() +"&keyword="+value
                if (value.length >= minlength) {
                    restrictHitOnNull = true ;
                    //
                    // run ajax call 1 second after user has stopped typing
                    //
                    timeout = setTimeout(function() {
                    $.ajax({
                        url: '{{ route("newSearchBillingDetail.keyword") }}' + "?page=" + page,
                        type: "get",
                        data: formData , "_token": "{{ csrf_token() }}", "keyword" : value,
                        success: function (response) {
                            $('#billing_result').html(response.html)
                            $('#myTableResponse').bootstrapTable()
                        },
                        error: function (xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText;
                            console.log(xhr);
                        }
                    });
                    }, 1500);
                }
                if(value.length == 0 && restrictHitOnNull  )
                {
                    restrictHitOnNull = false ;
                    timeout = setTimeout(function() {
                    $.ajax({
                        url: '{{ route("newSearchBillingDetail.keyword") }}' + "?page=" + page,
                        type: "get",
                        data: formData , "_token": "{{ csrf_token() }}", "keyword" : value,
                        success: function (response) {
                            $('#billing_result').html(response.html)
                            $('#myTableResponse').bootstrapTable()
                        },
                        error: function (xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText;
                            console.log(xhr);
                        }
                    });
                    }, 1000);
                }


                var keyword = $(this).val();
                console.log(keyword);


            } )

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

            var url = "{{route('newSearchBillingDetail')}}";

            $.ajax({
                url: url + "?page=" + page,
                type: "get",
                data: $("#billing_filter_data").serialize(), "_token": "{{ csrf_token() }}",
                success: function (response) {
                    // $('#billing_result').empty().html(response.html)
                    $('#billing_result').html(response.html)
                    $('#sum').empty().html(response.sumhtml)
                    $('.bredcrumb').html(response.filterMessage)
                    $('#myTableResponse').bootstrapTable()
                    $('#billing-report-pagination .pagination').removeClass('justify-content-center');
                    $('#billing-report-pagination .pagination').addClass('justify-content-end');


                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function breadCrumbFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                // x.style.display = "block";
                $('#bredcrumbList').removeClass('d-none')
            } else {
                $('#bredcrumbList').addClass('d-none')
            }
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
        function resetInput() {

            $('.department').val(null).trigger('change');
            $('#doctor').val(null).trigger('change');
            $('#item_type').val(null).trigger('change');
            $('#bill_type').val(null).trigger('change');
            $('#package').val(null).trigger('change');
        }



        var userDetail = {
            displayDischageClearancBilleModal: function (e) {
                var billNo = $(e).data('billno');
                var encounterID = $(e).data('fldencounterval');


                $.ajax({
                    url: "{{ route('discharge.clearance.bill') }}",
                    type: "POST",
                    data: {
                        encounter_id: encounterID,
                        billno: billNo,
                    },
                    success: function (response) {
                        console.log(response);
                        $('#user-discharge-clearance-bill').modal('show');
                        $('#dischargebillData').html(response.invoicebill);

                        $('.printBillInformation').attr('data-route', response.route )
                        $('.printBillInformation').attr('data-billno', response.billno )
                        $('.printBillInformation').attr('data-encounter_id', response.encounter_id )



                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            },

            displayBillModal : function(e){
                var billNo = $(e).data('billno');
                var route =  "{{ route('billing.user.report.view') }}";


                if (billNo.startsWith('RET')){
                    console.log('RET bill no')
                    route = "{{ route('billing.displayReturnBilling.view') }}" ;

                }else if (billNo.startsWith('DEP')){
                    console.log('DEP bill no')

                     route = "{{ route('depositForm.printBill.view')}}"
                }
                $.ajax({
                    url: route,
                    type: "POST",
                    data: {
                        _token : "{{csrf_token()}}",

                        billno: billNo,
                        invoice_number: billNo,
                        fldbillno : billNo,

                    },
                    success: function (response) {

                        console.log('route is ',response.route);
                        console.log('bill no is', response.billno);
                        $('#user-bill-modal').modal('show');
                        $('#billData').html(response.invoicebill);
                        $('#user-bill-modal').find('.printBillInformation').removeAttr('data-route')
                        $('#user-bill-modal').find('.printBillInformation').removeAttr('data-billno')
                        $('#user-bill-modal').find('.printBillInformation').removeAttr('data-invoice_number')

                        $('#user-bill-modal').find('.printBillInformation').attr('data-route', response.route )
                        $('#user-bill-modal').find('.printBillInformation').attr('data-billno', response.billno )
                        $('#user-bill-modal').find('.printBillInformation').attr('data-invoice_number', response.billno )



                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });

            },

            displayreport : function()
            {
                window.location.replace("{{ route('department.display.report') }}")
            },
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
                        $('#userform').html();
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


