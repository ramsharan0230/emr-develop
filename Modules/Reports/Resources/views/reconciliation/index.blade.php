@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Reconciliation report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="reconciliation_filter_data">
                    <div class="row">
                        <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-3 col-lg-2">Form:</label>
                                    <div class="col-sm-9  col-lg-10">
                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" readonly="" />
                                        <input type="hidden" name="eng_from_date" id="eng_from_date" value="{{date('Y-m-d')}}">
                                    </div>

                                </div>

                            </div>
                           
                            <div class="col-sm-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-3 col-lg-2">To:</label>
                                    <div class="col-sm-9 col-lg-10">
                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" readonly="" />
                                        <input type="hidden" name="eng_to_date" id="eng_to_date" value="{{date('Y-m-d')}}">
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-4">
                               <div class="form-group">
                                    <select name="department"  id="js-itemledger-department" class="form-control department">
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
                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="searchReconciliationReport()"><i class="fa fa-sync"></i>&nbsp;
                            Refresh</a>&nbsp;

                             

                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportReconciliationReportExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                            Export To Excel</a>
                            <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="exportReconciliationSummaryReportExcel()"><i class="fa fa-file-pdf"></i>&nbsp;
                            Summary Report</a>&nbsp;
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
                                                <th rowspan="5">Date</th>
                                                <th rowspan="5">Total Sales</th>
                                                <th rowspan="5">Credit Sales</th>
                                                <th rowspan="5">Paid Sales</th>
                                                <th rowspan="5">VAT</th>
                                                <th colspan="2" style="text-align: center;">Collection</th>
                                                <th colspan="2" style="text-align: center;">Deposit Received</th>
                                                <th colspan="1">Deposit </th>
                                                <th colspan="1">Direct</th>
                                                <th colspan="1">Cash Sales</th>
                                                <th colspan="1">Collection for</th>
                                                <th rowspan="2"> Diff</th>
                                            </tr>
                                            <tr>
                                                <th>Cash & Card</th>
                                                <th>Bank</th>

                                                <th>Cash & Card</th>
                                                <th>Bank</th>

                                                <th>Adjustment</th>
                                                <th>Adjustment</th>

                                                <th>for the period</th>
                                                <th>Cash Sales</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="reconciliation_result">



                                        </tbody>
                                    </table>
                            </div>
                          </div>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('after-script')

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


    function searchReconciliationReport(){

        

        if($('#js-itemledger-department').val() ===''){
            alert('Select Department');
            return false;
        }
        var url = "{{route('search.reconciliation')}}";

        $.ajax({
            url: url,
            type: "post",
            data:  $("#reconciliation_filter_data").serialize(),"_token": "{{ csrf_token() }}",
            success: function(response) {
                $('#reconciliation_result').html(response)
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }



    function exportReconciliationSummaryReportExcel(){
        var data = $("#reconciliation_filter_data").serialize();
       // alert(data);
       var urlReport = baseUrl + "/reconciliation/reconciliation-summary-report-excel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


       window.open(urlReport);
    }
    function exportReconciliationReportExcel(){
        var data = $("#reconciliation_filter_data").serialize();
       // alert(data);
       var urlReport = baseUrl + "/reconciliation/reconciliation-report-excel?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


       window.open(urlReport);
    }

        
</script>
@endpush


