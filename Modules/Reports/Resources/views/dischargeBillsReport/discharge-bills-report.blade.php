@extends('frontend.layouts.master')
@section('content')
    <style>
    .res-table td ul{
            list-style: none;

    }

    </style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Total Indoor Treatment Charges (Discharge Bills)
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
                                    <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">To:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Department:</label>
                                <div class="col-sm-8">
                                    <select name="comp" id="comp" class="form-control">
                                        <option value="%">%</option>
                                        @if($hospital_department)
                                            @forelse($hospital_department as $dept)
                                                <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                                            @empty
                                            @endforelse
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2">
                            <div class="form-group form-row">
                                <input type="radio" name="serviceType" value="service" style="margin-top: 5px;" checked> Service
                                <input type="radio" name="serviceType" value="pharmacy" style="margin-top: 5px;"> Pharmacy
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="searchEntryDetail()"><i class="fa fa-check"></i>&nbsp;
                            Refresh</a>&nbsp;
                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportDischargeReport()"><i class="fas fa-file-pdf"></i>&nbsp;
                            Pdf</a>
                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportDepositReportExcel()"><i class="fa fa-code"></i>&nbsp;
                            Export</a>
                        </div>
                    </div>
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
                            <div class="table-responsive res-table" style="max-height: none;">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th style="min-width: 200px;">Patient Details</th>
                                            <th style="min-width: 180px;">Deposit Receipt No.</th>
                                            <th style="min-width: 180px;">Invoice No.</th>
                                            <th style="min-width: 180px;">Deposit Refund No.</th>
                                            <th>Deposit Amount</th>
                                            <th>Total Net Bill Amount</th>
                                            <th style="min-width: 100px;">Amount Received After Deposit Adjustment</th>
                                            <th>Discount</th>
                                            <th style="min-width: 100px;">Amount Refund After Deducting Deposit</th>
                                            <th>Remaining Refund</th>
                                            <th>Admitted Date</th>
                                            <th>Discharge Date</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_result">
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
<script type="text/javascript">
    $(window).ready(function () {
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 // Options | Number of years to show
        });
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 // Options | Number of years to show
        });
    })

    $( document ).ready(function() {
        $(document).on('click', '.pagination a', function(event){
          event.preventDefault();
          var page = $(this).attr('href').split('page=')[1];
          searchEntryDetail(page);
         });
    });

    function exportDischargeReport(){
        var urlReport = baseUrl + "/discharge-bills/get-refresh-data?isExport=true" + "&comp=" + $('#comp').val() + "&from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&serviceType=" + $('input[name="serviceType"]:checked').val();
        window.open(urlReport, '_blank');
    }

    function exportDepositReportExcel(){
        var urlReport = baseUrl + "/discharge-bills/get-export-data?comp=" + $('#comp').val() + "&from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&serviceType=" + $('input[name="serviceType"]:checked').val();
        window.open(urlReport);
    }

    function searchEntryDetail(page){
        var url = "{{route('discharge-bills.refreshdata')}}";
        $.ajax({
            url: url+"?page="+page,
            type: "GET",
            data:  {
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val(),
                        comp: $('#comp').val(),
                        serviceType: $('input[name="serviceType"]:checked').val(),
                        _token: "{{ csrf_token() }}"
                    },
            success: function(response) {
                if(response.data.status){
                    $('#table_result').html(response.data.html)
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

</script>
@endsection

