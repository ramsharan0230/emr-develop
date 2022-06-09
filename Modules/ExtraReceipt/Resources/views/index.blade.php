@extends('frontend.layouts.master')
@section('content')
@php
$segment = Request::segment(1);

@endphp
@if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
@php
$disableClass = 'disableInsertUpdate';
@endphp
@else
@php
$disableClass = '';
@endphp
@endif
@php
$segment = Request::segment(1);
if($segment == 'admin'){
$segment2 = Request::segment(2);
$segment3 = Request::segment(3);
if(!empty($segment3))
$route = 'admin/'.$segment2 . '/'.$segment3;
else
$route = 'admin/'.$segment2;

}else{
$route = $segment;
}
@endphp
<div class="container-fluid">
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif

    @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif
    <div class="row">
        @include('extrareceipt::common.patient-profile')
        <div class="col-sm-12">

            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                {{--<div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Sales Mode Outstanding</h4>
                        </div>
                    </div>--}}
                <div class="iq-card-body">

                    <form action="javascript:;" method="post">
                        @csrf
                        <input type="hidden" id="user_billing_mode" value="@if(isset($enpatient) && isset($enpatient->fldbillingmode) ) {{$enpatient->fldbillingmode}} @endif" disabled>
                        <input type="hidden" name="__encounter_id" value="{{ isset($enpatient)?$enpatient->fldencounterval:'' }}">
                        <div class="form-horizontal border-bottom">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-5">Transaction Date</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input type="date" name="transaction_payment_date" id="transaction_payment_date" class="form-control">
                                                {{--<div class="input-group-append">
                                                        <div class="input-group-text"><i class="ri-calendar-2-fill"></i></div>
                                                    </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <lable class="col-sm-4">Warehouse</lable>
                                        <div class="col-sm-8">
                                            <select class="form-control">
                                                <option value="0">---select---</option>
                                                <option value="1">Main Store</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label class="col-sm-5">Payment Mode</label>
                                        <div class="col-sm-7">
                                            <select name="payment_mode" id="payment_mode" class="form-control">
                                                <option value="Cash">Cash</option>
                                                <option value="Credit">Credit</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Fonepay">Fonepay</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Payment Mode</label>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="payment_mode" value="cash" class="custom-control-input" checked>
                                                <label class="custom-control-label" id="payment_mode_cash">Cash</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="payment_mode" value="credit" class="custom-control-input">
                                                <label class="custom-control-label" id="payment_mode_credit">Credit</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="payment_mode" value="cheque" class="custom-control-input">
                                                <label class="custom-control-label" id="payment_mode_cheque">Cheque</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="payment_mode" value="other" class="custom-control-input">
                                                <label class="custom-control-label" id="payment_mode_other">Other</label>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center" id="expected_date">
                                        <label class="col-sm-5">Expected Payment Date</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input type="date" name="expected_payment_date" id="expected_payment_date" placeholder="DD/MM/YYY" class="form-control">
                                                {{--<div class="input-group-append">
                                                        <div class="input-group-text"><i class="ri-calendar-2-fill"></i></div>
                                                    </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--if cash--}}
                        <div class="form-horizontal ">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center" id="payment_mode_party">
                                        <label class="col-sm-3">Sent To</label>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="sent_to" value="customer" class="custom-control-input" checked>
                                                <label class="custom-control-label" id="payment_customer">Customer</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="sent_to" value="office" class="custom-control-input">
                                                <label class="custom-control-label" id="payment_office">Office</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="cheque_number" id="cheque_number" placeholder="Cheque Number" class="form-control">
                                        <input type="text" name="other_reason" id="other_reason" placeholder="Reason" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select name="bank_name" id="bank-name" class="form-control">
                                            <option value="">Select Bank</option>
                                            @if(count($banks))
                                            @forelse($banks as $bank)
                                            <option value="{{ $bank->fldbankname }}">{{ $bank->fldbankname }}</option>
                                            @empty

                                            @endforelse

                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="office_name" id="office_name" placeholder="Office Name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                            </div>
                        </div>
                        {{--end if cash--}}
                        <div class="from-horizontal ">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio custom-control-inline d-none">
                                            <input type="radio" name="item_type" class="custom-control-input item_type" value="service" checked>
                                            <label class="custom-control-label">Service Item</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline  float-right mb-3">
                                            <button class="btn btn-primary" type="button" onclick="getSoldItem()">Add <i class="ri-add-line"></i></button>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="d-flex justify-content-center w-100 pb-3">
                                        <button class="btn btn-primary" type="button" onclick="getSoldItem()">Add <i class="ri-add-line"></i></button>
                                    </div>--}}
                            </div>
                            <div class="res-table">
                                <div id="billing-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>S.N.</th>
                                                <th style="width: 60%;">Items</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Rate</th>
                                                <th class="text-center">Dis%</th>
                                                <th class="text-center">Tax%</th>
                                                <th class="text-center">Total Amount</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="6">No Items Added</td>
                                            </tr>
                                        </tbody>
                                        <thead class="thead-light">
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>Total</th>
                                                <th colspan="2" class="text-right"></th>
                                                <th colspan="2" class="text-right"></th>
                                                <th class="text-right table-bill-total"></th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 offset-sm-8">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th class="text-right">SubTotal:</th>
                                                <th class="text-right" id="sub-total-data"></th>
                                            </tr>
                                            <tr>
                                                <th class="text-right">Discount:</th>
                                                <th><input type="text" name="" placeholder="0.00" class="form-control ml-auto text-right" style="width: 100px;" {{ $discount != 0?"readonly":"" }}></th>
                                            </tr>
                                            <tr>
                                                <th class="text-right">Discount Amount:</th>
                                                <th class="text-right" id="discount-total"></th>
                                            </tr>
                                            <tr>
                                                <th class="text-right">Total:</th>
                                                <th class="text-right" id="grand-total-data"></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group pt-2">
                            {{-- <button type="submit" class="btn btn-primary float-right">Payment Done/Save</button>--}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="encounter_list" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="encountercall" action="{{$route}}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Choose Encounter ID</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="ajax_response_encounter_list">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" id="submitencounter_list" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content")
            }
        });
        var fldencounterval = $("#fldencounterval").val();
        getPatientProfileColor();
    });

    jQuery(function($) {
        hideAll();
        setTimeout(function() {
            $("#bank-name").select2();
            $('#bank-name').next(".select2-container").hide();
        }, 1500);
        /*On click payment modes*/
        $(document).on('click', '#payment_customer', function(event) {
            $('#office_name').hide();
        });
        $(document).on('click', '#payment_office', function(event) {
            // hideAll();
            $('#office_name').show();
        });
        $(document).on('click', '#payment_mode_credit', function(event) {
            hideAll();
            $('#expected_date').show();
        });
        $(document).on('click', '#payment_mode_cheque', function(event) {
            hideAll();
            $('#cheque_number').show();
            $("#payment_mode_party").show();
            /*$("#agent_list").show();*/
            $('#bank-name').next(".select2-container").show();
        });
        $(document).on('click', '#payment_mode_other', function(event) {
            hideAll();
            $('#other_reason').show();
        });
        $(document).on('click', '#payment_mode_cash', function(event) {
            hideAll();
            $("#payment_mode_party").show();
            /*$("#agent_list").show();*/
        });
        /* End On click payment modes*/


        document.getElementById('transaction_payment_date').valueAsDate = new Date();

        document.getElementById('expected_payment_date').valueAsDate = new Date();
        document.getElementById('transaction_payment_date').max = new Date().toISOString().split("T")[0];
        document.getElementById('expected_payment_date').min = new Date().toISOString().split("T")[0];
    });

    function hideAll() {
        $('#payment_mode_party').hide();
        $('#office-name').hide();
        $('#bank-name').next(".select2-container").hide();
        /*$('#agent_list').hide();*/
        $('#expected_date').hide();
        $('#cheque_number').hide();
        $('#office_name').hide();
        $('#other_reason').hide();
    }

    function getSoldItem() {
        item_type = $("input[name='item_type']:checked").val();
        billingMode = $("#user_billing_mode").val();
        $.ajax({
            url: "{{ route('extra.receipt.get.items.by.service.or.inventory') }}",
            type: "POST",
            data: {
                item_type: item_type,
                billingMode: billingMode
            },
            success: function(data) {
                // console.log((data));
                $('.file-modal-title').empty().text(item_type);
                $('.file-form-data').empty().append(data);
                $('.modal-dialog').addClass('modal-lg');
                $('.modal-footer').hide();
                // console.log(data);
                $('#file-modal').modal('show');
            }
        });
    }

    function saveServiceCosting() {
        $.ajax({
            url: "{{ route('extra.receipt.save.items.by.service') }}",
            type: "POST",
            data: $("#pharmacy-form").serialize(),
            success: function(data) {
                console.log(data);
                if (data.status === true) {
                    $("#billing-body").empty().append(data.message.tableData);
                    $("#sub-total-data").empty().append(data.message.total);
                    $("#table-bill-total").empty().append(data.message.total);
                    $("#grand-total-data").empty().append(data.message.total);
                    $('#file-modal').modal('hide');
                    showAlert('Added successfully.');
                }
            }
        });
    }
</script>
@if(Session::get('display_generated_invoice'))
<script>
    var params = {
        encounter_id: "{{Session::get('billing_encounter_id')}}",
        invoice_number: "{{Session::get('invoice_number')}}"
    };
    var queryString = $.param(params);
    window.open("{{ route('billing.display.invoice') }}?" + queryString, '_blank');
</script>
@endif
@endpush