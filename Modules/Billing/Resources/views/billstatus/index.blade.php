@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Bill Status
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <input type="hidden" name="cancellationCurrentPage" id="cancellationCurrentPage">
                    <form id="billstatus_filter_data">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-2">From:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}" />
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="" class="col-sm-2">To:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Category:</label>
                                <div class="col-sm-8">
                                    <select name="category" id="category" class="form-control">
                                        <option value="%">%</option>
                                        <option value="Diagnostic Tests">Diagnostic Tests</option>
                                        <option value="General Services">General Services</option>
                                        <option value="Procedures">Procedures</option>
                                        <option value="Equipment">Equipment</option>
                                        <option value="Radio Diagnostics">Radio Diagnostics</option>
                                        <option value="Other Items">Other Items</option>
                                        <option value="Medicines">Medicines</option>
                                        <option value="Surgicals">Surgicals</option>
                                        <option value="Extra Items">Extra Items</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Comp:</label>
                                <div class="col-sm-8">
                                    <select name="comp" id="comp" class="form-control department">
                                        <option value="%">%</option>
                                        {{-- @if($hospital_department)
                                            @forelse($hospital_department as $dept)
                                                <option value="{{ isset($dept->departmentData->fldcomp) ? $dept->departmentData->fldcomp : "%" }}">{{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})</option>
                                            @empty
                                            @endforelse
                                        @endif --}}
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

                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Encounter:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="encounter" id="encounter" class="form-control">
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Invoice No.:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="invoiceno" id="invoiceno" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="searchBillStatus()"><i class="fa fa-check"></i>&nbsp;
                                Refresh</a>&nbsp;
                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportBillStatus()"><i class="fas fa-file-pdf"></i>&nbsp;
                                Pdf</a>
                            {{-- <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportBillStatusExcel()"><i class="fa fa-code"></i>&nbsp;
                                Export</a> --}}
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="billStatusDiv">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive res-table" style="max-height: none;">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Encounter</th>
                                    <th>Patient Name</th>
                                    <th>Particulars</th>
                                    <th>Rate</th>
                                    <th>Qty</th>
                                    <th>Tax %</th>
                                    <th>Disc %</th>
                                    <th>Total</th>
                                    <th>Entry Date</th>
                                    <th>Invoice</th>
                                    <th>Status</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="billstatus_result">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="cancelledBillDiv">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <h6 class="card-title">
                        Cancelled Patient Bills
                    </h6>
                    <div class="table-responsive res-table" style="max-height: none;">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Encounter</th>
                                    <th>Patient Name</th>
                                    <th>Particulars</th>
                                    <th>Rate</th>
                                    <th>Qty</th>
                                    <th>Tax %</th>
                                    <th>Disc %</th>
                                    <th>Total</th>
                                    <th>Entry Date</th>
                                    <th>Invoice</th>
                                    <th>Status</th>

                                </tr>
                            </thead>
                            <tbody id="billcanceled_result">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="referral_list" tabindex="-1" role="dialog" aria-labelledby="referral_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="referral_listLabel" style="text-align: center;">Select Referral User</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body res-table">
                <div class="row">
                    <div class="col-8 mb-2">
                        <input type="text" class="form-control" id="searchReferral" onkeyup="myFunctionSearchReferral(this)" placeholder="Search..">
                    </div>
                </div>
                <input type="hidden" name="referral-flid" id="referral-flid">
                <table id="referralSearchtable" class="table table-bordered table-hover table-striped text-center">
                @if(count($referralUsers))
                    @foreach($referralUsers as $user)
                    <tr data-user="{{ $user->fldusername }}">
                        <td style="text-align: left;">
                            <div class="custom-control custom-radio">
                            <input type="radio" name="referralUser" value="{{ $user->flduserid }}" class="custom-control-input">
                            <label class="custom-control-label">{{ $user->fldusername }}</label>
                        </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveReferral">Save</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="payable_list" tabindex="-1" role="dialog" aria-labelledby="payable_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payable_listLabel" style="text-align: center;">Select Payable User</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body res-table">
                <div class="row">
                    <div class="col-8 mb-2">
                        <input type="text" class="form-control" id="searchPayable" onkeyup="myFunctionSearchPayable(this)" placeholder="Search..">
                    </div>
                </div>
                <input type="hidden" name="payable-flid" id="payable-flid">
                <table id="payableSearchtable" class="table table-bordered table-hover table-striped text-center">
                @if(count($payableUsers))
                    @foreach($payableUsers as $user)
                    <tr data-user="{{ $user->fldusername }}">
                        <td style="text-align: left;">
                            <div class="custom-control custom-radio">
                            <input type="radio" name="payableUser" value="{{ $user->flduserid }}" class="custom-control-input">
                            <label class="custom-control-label">{{ $user->fldusername }}</label>
                        </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="savePayable">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script type="text/javascript">
    $( document ).ready(function() {
        $("#billStatusDiv").on('click', '.pagination a', function(event){
          event.preventDefault();
          var page = $(this).attr('href').split('page=')[1];
          searchBillStatus(page);
         });

        $("#cancelledBillDiv").on('click', '.pagination a', function(event){
          event.preventDefault();
          var page = $(this).attr('href').split('cancellation_per=')[1];
          searchCancelledBill(page);
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

    function exportBillStatus(){
        var urlReport = baseUrl + "/billing/bill-status/pdf?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&category=" + $('#category').val() + "&comp=" + $('#comp').val() + "&encounter=" + $('#encounter').val() + "&invoiceno=" + $('#invoiceno').val();
        window.open(urlReport, '_blank');
    }

    function exportBillStatusExcel(){
        var urlReport = baseUrl + "/billing/bill-status/excel?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&category=" + $('#category').val() + "&comp=" + $('#comp').val() + "&encounter=" + $('#encounter').val() + "&invoiceno=" + $('#invoiceno').val();
        window.open(urlReport);
    }

    function searchBillStatus(page){
        var url = "{{route('searchBillStatus')}}";
        $.ajax({
            url: url+"?page="+page+"&cancellation_per=1",
            type: "GET",
            data:  $("#billstatus_filter_data").serialize(),
            success: function(response) {
                if(response.data.status){
                    $('#billstatus_result').html(response.data.html);
                    $('#cancellationCurrentPage').val(response.data.cancellationCurrentPage);
                    if(page == undefined){
                        $('#billcanceled_result').html(response.data.cancelledHtml);
                    }
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function searchCancelledBill(page){
        var url = "{{route('searchCancelledBillStatus')}}";
        $.ajax({
            url: url+"?cancellation_per="+page,
            type: "GET",
            data:  $("#billstatus_filter_data").serialize(),
            success: function(response) {
                if(response.data.status){
                    $('#billcanceled_result').html(response.data.html);
                    $('#cancellationCurrentPage').val(response.data.cancellationCurrentPage);
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function myFunctionSearchReferral() {
        var input = $("#searchReferral").val().toUpperCase();
        var rows = $('#referralSearchtable tr');
        rows.each(function(){
            if( $(this).attr('data-user').toUpperCase().match(input) === null ){
                $(this).hide();
            }
            else{
                $(this).show();
            }
        });
    }

    function myFunctionSearchPayable() {
        var input = $("#searchPayable").val().toUpperCase();
        var rows = $('#payableSearchtable tr');
        rows.each(function(){
            if( $(this).attr('data-user').toUpperCase().match(input) === null ){
                $(this).hide();
            }
            else{
                $(this).show();
            }
        });
    }

    $(document).on('click','.editReferral',function(){
        var referral = $(this).attr('data-referral');
        $("input:radio[name='referralUser']").prop('checked', false);
        $('#referral-flid').val("");
        $('#referral-flid').val($(this).attr('data-fldid'));
        if(referral != ""){
            $("input:radio[name='referralUser'][value='" + referral + "']").prop('checked', true);
        }
        $('#referral_list').modal('show');
    });

    $(document).on('click','.editPayable',function(){
        var payable = $(this).attr('data-payable');
        $("input:radio[name='payableUser']").prop('checked', false);
        $('#payable-flid').val("");
        $('#payable-flid').val($(this).attr('data-fldid'));
        if(payable != ""){
            $("input:radio[name='payableUser'][value='" + payable + "']").prop('checked', true);
        }
        $('#payable_list').modal('show');
    });

    $(document).on('click','#saveReferral',function(){
        var selectedReferral = $("input:radio[name='referralUser']:checked").val();
        var patBill = $('#referral-flid').val();
        if(selectedReferral != ""){
            var url = "{{route('bill.status.saveReferral')}}";
            $.ajax({
                url: url,
                type: "POST",
                data:  {
                            selectedReferral: selectedReferral,
                            patBill: patBill,
                            _token: "{{ csrf_token() }}"
                        },
                success: function(response) {
                    if(response.data.status){
                        showAlert('Referral user updated successfully!');
                        $('#referral_list').modal('hide');
                        $('#billstatus_result tr[data-fldid='+patBill+']').find(' td:eq(13)').text(selectedReferral);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    });


    $(document).on('click','#savePayable',function(){
        var selectedPayable = $("input:radio[name='payableUser']:checked").val();
        var patBill = $('#payable-flid').val();
        if(selectedPayable != ""){
            var url = "{{route('bill.status.savePayable')}}";
            $.ajax({
                url: url,
                type: "POST",
                data:  {
                            selectedPayable: selectedPayable,
                            patBill: patBill,
                            _token: "{{ csrf_token() }}"
                        },
                success: function(response) {
                    if(response.data.status){
                        showAlert('Payable user updated successfully!');
                        $('#payable_list').modal('hide');
                        $('#billstatus_result tr[data-fldid='+patBill+']').find(' td:eq(12)').text(selectedPayable);
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    });

    $(document).on('click','.cancelPatbill',function(){
        if(!confirm("Do you really want to cancel the patient bill?")){
           return false;
        }
        var patBill = $(this).attr('data-fldid');
        var url = "{{route('bill.status.cancelPatbill')}}";
        $.ajax({
            url: url,
            type: "POST",
            data:  {
                        patBill: patBill,
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val(),
                        category: $('#category').val(),
                        comp: $('#comp').val(),
                        encounter: $('#encounter').val(),
                        invoiceno: $('#invoiceno').val(),
                        cancellation_per: $('#cancellationCurrentPage').val(),
                        _token: "{{ csrf_token() }}"
                    },
            success: function(response) {
                if(response.data.status){
                    showAlert('Patient bill cancelled successfully!');
                    $('#billstatus_result tr[data-fldid='+patBill+']').remove();
                    $('#billcanceled_result').html(response.data.html);
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });
</script>
@endpush

