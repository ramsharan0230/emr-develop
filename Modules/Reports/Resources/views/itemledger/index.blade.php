@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Item Ledger report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="item_ledger_filter_data">
                    <div class="row">
                        <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-3 col-lg-2">Form:</label>
                                    <div class="col-sm-9  col-lg-10">
                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" />
                                        <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                    </div>

                                </div>

                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                        <input type="radio"  value="generic" name="orderBy" class="custom-control-input" />
                                        <label class="custom-control-label" for=""> Generic</label>
                                    </div>
                                    &nbsp;
                                    <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                        <input type="radio"  value="brand" name="orderBy" class="custom-control-input" checked/>
                                        <label class="custom-control-label" for="">Brand</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row">

                                    <div class="col-sm-8">
                                        <select class="form-control select2" id="js-itemledger-medicine-input" name="search_medecine">
                                        <option value="">--Select--</option>
                                        @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->fldstockid }}"
                                            data-route="{{ $medicine->fldroute }}"
                                            data-flditemtype="{{ $medicine->fldcategory }}"
                                            data-fldnarcotic="{{ $medicine->fldnarcotic }}"
                                            data-fldpackvol="{{ $medicine->fldpackvol }}"
                                            data-fldvolunit="{{ $medicine->fldvolunit }}"
                                            data-fldstockno="{{ $medicine->fldstockno }}"
                                            fldqty="{{ $medicine->fldqty }}"
                                        > {{ $medicine->fldbrand }} </option>
                                        @endforeach
                                    </select>
                                    </div>
                                     <div class="col-sm-4">
                                        <input type="text" name="route" id="route" class="form-control" readonly="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-3 col-lg-2">To:</label>
                                    <div class="col-sm-9 col-lg-10">
                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" />
                                        <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-3">
                                <div class="form-group form-row">
                                    <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                        <input type="radio" name="medcategory" value="Medicines" class="custom-control-input" checked id="js-dispensing-medicines-radio">
                                        <label for="js-dispensing-medicines-radio" class="custom-control-label"> Medicines </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                        <input type="radio" name="medcategory" value="Surgicals" class="custom-control-input" id="js-dispensing-surgicals-radio">
                                        <label for="js-dispensing-surgicals-radio" class="custom-control-label"> Surgicals </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                        <input type="radio" name="medcategory" value="Extra Items" class="custom-control-input" id="js-dispensing-extraitems-radio">
                                        <label for="js-dispensing-extraitems-radio" class="custom-control-label"> Extra Items </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                               <div class="form-group">
                                    <select name="department"  id="js-itemledger-department" class="form-control department select2">
                                        <option value="">--Select Department--</option>
                                        @if($hospital_department)
                                            @forelse($hospital_department as $dept)
                                                <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                                            @empty
                                            @endforelse
                                        @endif
                                                {{-- @if($hospital_department)
                                                    @forelse($hospital_department as $dept)
                                                        <option value="{{ $dept->departmentData->fldcomp }}">{{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})</option>
                                                    @empty

                                                    @endforelse
                                                @endif --}}
                                        <!-- <option value="Male"></option> -->
                                    </select>
                               </div>
                            </div>


                       <div class="col-sm-12">
                            <div class="d-flex justify-content-center">
                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchLedgerReport()"><i class="fa fa-sync"></i>&nbsp;
                            Refresh</a>&nbsp;

                             <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportItemLedgerPdf()"><i class="fa fa-file-pdf"></i>&nbsp;
                            Export To Pdf
                             </a>&nbsp;

                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportItemLedgerExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                            Export To Excel</a>
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
                   </ul>
                   <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                            <div class="table-responsive res-table">
                                    <table class="table table-striped table-hover table-bordered ">
                                        <thead class="thead-light">
                                            <tr>

                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Ref No</th>
                                                <th>Rec/Pur Qty</th>
                                                <th>Qty Issue</th>
                                                <th>Bal Qty</th>
                                                <th>Rate</th>
                                                <th>Rec/Pur Amt</th>
                                                <th>Issue Amt</th>
                                                <th>Bal Val</th>
                                                <th>Expiry</th>
                                                <th>Batch</th>


                                            </tr>
                                        </thead>
                                        <tbody id="item_ledger_result">




                                        </tbody>
                                    </table>
                            </div>
                          </div>

                    </div>
                    <!-- <div class="col-sm-12" id="myDIV">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">
                                 <div class="row">

                                    <div class="col-lg-2 col-sm-3">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-3">Form:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="from_bill" id="from_bill" value="" />

                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-lg-2 col-sm-3">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-3">To:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="to_bill" id="to_bill" value="" />

                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-lg-2 col-sm-3">
                                        <div class="form-group form-row">

                                            <div class="col-sm-9">
                                                <button type="btn btn-primary" onclick="reset()">Reset</button>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-lg-2 col-sm-3">
                                        <div class="form-group form-row">

                                            <div class="col-sm-9">
                                                <button type="btn btn-primary" onclick="userlist.displayModal()">User</button>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>     -->
                </div>
            </div>
        </div>
    </div>
</div>

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

    $( document ).ready(function() {
        setTimeout(function () {
            $("#js-itemledger-medicine-input").select2();
            (".department").select2();

        }, 1500);

    });

    $( document ).ready(function() {

        $(document).on('click', '.pagination a', function(event){
          event.preventDefault();
          var page = $(this).attr('href').split('page=')[1];
          searchBillingDetail(page);
         });
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


    function searchLedgerReport(){

        if($('#js-itemledger-medicine-input').val() ===''){
            alert('Select Medicine');
            return false;
        }

        if($('#js-itemledger-department').val() ===''){
            alert('Select Department');
            return false;
        }
        var url = "{{route('item.ledger-report-list')}}";

        $.ajax({
            url: url,
            type: "post",
            data:  $("#item_ledger_filter_data").serialize(),"_token": "{{ csrf_token() }}",
            success: function(response) {
                if(response.error)
                {
                    showAlert('data not available','error');
                    return false;
                }
                $('#item_ledger_result').html(response)
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }



        function exportItemLedgerPdf(){
            var data = $("#item_ledger_filter_data").serialize();
           // alert(data);
           var urlReport = baseUrl + "/mainmenu/item-ledger-report-pdf?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


           window.open(urlReport, '_blank');
        }
        function exportItemLedgerExcel(){
            var data = $("#item_ledger_filter_data").serialize();
           // alert(data);
           var urlReport = baseUrl + "/mainmenu/export-item-ledger-excel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


           window.open(urlReport);
        }

        $('#js-itemledger-medicine-input').on('change', function(){
            var route = $(this).find(':selected').data('route');
            $('#route').val(route);
        })

        function  getMedicineList() {
            setTimeout(function() {
                var orderBy = $('input[type="radio"][name="orderBy"]:checked').val();
                $.ajax({
                    url: baseUrl + '/mainmenu/item-ledger/getMedicineList',
                    type: "GET",
                    data: {
                        orderBy: orderBy,
                        // is_expired: $('#js-dispensing-isexpired-checkbox').prop('checked'),
                        medcategory: $('input[type="radio"][name="medcategory"]:checked').val(),
                        // billingmode: $('#js-dispensing-billingmode-select').val() || 'General',
                    },
                    dataType: "json",
                    success: function (response) {
                        var trData = '<option value="">--Select--</option>';
                        $.each(response, function(i, medicine) {
                            var fldexpiry = medicine.fldexpiry.split(' ')[0];
                            var fldstockid = (orderBy == 'brand') ? medicine.fldbrand : medicine.fldstockid;
                            var dataAttributes =  " data-route='" + medicine.fldroute + "'";
                            dataAttributes +=  " data-fldstockno='" + medicine.fldstockno + "'";
                            dataAttributes +=  " data-flditemtype='" + medicine.fldcategory + "'";
                            dataAttributes +=  " data-fldnarcotic='" + medicine.fldnarcotic + "'";
                            dataAttributes +=  " data-fldpackvol='" + medicine.fldpackvol + "'";
                            dataAttributes +=  " data-fldvolunit='" + medicine.fldvolunit + "'";
                            dataAttributes +=  " fldqty='" + medicine.fldqty + "'";

                            trData += '<option value="' + medicine.fldstockid + '" ' + dataAttributes + '>';
                            // trData +=  medicine.fldroute + ' | ';
                            trData +=  fldstockid;
                            // trData +=  medicine.fldbatch + ' | ';
                            // trData +=  fldexpiry + ' | QTY ';
                            // trData +=  medicine.fldqty + ' | Rs. ';
                            // trData +=  medicine.fldsellpr;
                            trData += '</option>';
                        });
                        $('#js-itemledger-medicine-input').html(trData).select2();
                    }
                });
            }, 500);
        }
</script>
@endpush


