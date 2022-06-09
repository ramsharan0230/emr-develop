@extends('frontend.layouts.master')
<style>

.img-ms-form{
    max-width:80px;
    width:100%;
    height: auto ;
}

</style>
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Pharmacy Return Form(Please type bill numeric number only no need to type prefix and suffix)
                        </h3>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-row">
                                <label class="col-md-1">Return By:</label>
                                <div class="col-md-1">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="queryColumn" id="invoice" value="invoice" checked>
                                        <label class="form-check-label" for="invoice">Invoice</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="queryColumn" id="encounter" value="encounter">
                                        <label class="form-check-label" for="encounter">Encounter</label>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="js-returnform-queryvalue-input">&nbsp;
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary btn-sm-in" id="js-returnform-show-btn">Submit&nbsp;<i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                    &nbsp;
                                    <button class="btn btn-primary btn-sm-in" id="js-returnform-bill-return-btn">Return bill</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-row">
                                <label class="col-md-3">Full Name:</label>
                                <div class="col-md-9">
                                    <input type="text" readonly id="js-returnform-fullname-input" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-row">
                                <label class="col-md-4 text-center">Address</label>
                                <div class="col-md-8">
                                    <input type="text" readonly id="js-returnform-address-input" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-row">
                                <label class="col-md-3">Gender</label>
                                <div class="col-md-9">
                                    <input type="text" readonly id="js-returnform-gender-input" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-row">
                                <label class="col-md-1">Particulars</label>
                                <div class="col-md-11">
                                    <select id="js-returnform-particulars-select" class="form-control">
                                        <option value="" disabled selected>--Select--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-row">
                                <label  class="col-md-3">Expiry</label>
                                <div class="col-md-9">
                                    <input readonly type="text" id="js-returnform-expiry-input" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-row">
                                <label class="col-md-3">Quantity</label>
                                <div class="col-md-9">
                                    <input readonly type="text" id="js-returnform-qty-input" class="form-control" placeholder="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-row">
                                <label  class="col-md-5">Return Quantity</label>
                                <div class="col-md-7 er-input">
                                    <input type="number" id="js-returnform-retqty-input" class="form-control col-12" placeholder="0"> &nbsp;&nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-row">
                                <label class="col-md-2">Reason:</label>
                                <div class="col-md-10">
                                    <input type="text" id="js-returnform-reason-input" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-row">
                                <label class="col-md-3">Authorized By</label>
                                <div class="col-md-9">
                                    <input type="text" id="js-authorizedby-input" class="form-control" placeholder="Enter Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="ml-1">Bill Type</label>
                                <div class="col">
                                    <b id="billType"></b>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <label class="ml-1">Payment Mode</label>
                                <div class="col">
                                    <b id="paymentMode"></b>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="js-returnform-fldstockno-input" value="">
                        <input type="hidden" id="js-returnform-id-input" value="">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm-in float-right" id="js-returnform-return-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                          New
                        </h3>
                    </div>
                 </div>
                <div class="iq-card-body">
                    <div class="res-table">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>DateTime</th>
                                    <th>Category</th>
                                    <th>Particulars</th>
                                    <th>Rate</th>
                                    <th>QTY</th>
                                    <th>Tax%</th>
                                    <th>Disc%</th>
                                    <th>Total</th>
                                    <th>User</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="js-returnform-return-tbody"></tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">

                                <div class="form-row">
                                    <div class="col-sm-4  pay-rad checked-bak" id="cash_payment" onclick="getRadioFunction('Cash')">
                                        <div class="custom-control custom-radio custom-control-inline" >
                                            <input type="radio" id="Cash" name="payment_mode" class="custom-control-input payment_mode" value="Cash" checked>
                                            <label class="custom-control-label" for=""> Cash</label>
                                        </div>
                                         <div class="img-ms-form">
                                            <img src="{{ asset('new/images/cash-2.png')}}"   class="img-ms-form" alt="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pay-rad" id="credit_payment" onclick="getRadioFunction('Credit')">
                                        <div class="custom-control custom-radio custom-control-inline" >
                                            <input type="radio" id="Credit" name="payment_mode" class="custom-control-input payment_mode" value="Credit">
                                            <label class="custom-control-label" for=""> Credit </label>
                                        </div>
                                         <div class="img-ms-form">
                                            <img src="{{ asset('new/images/credit-3.png')}}"   class="img-ms-form" alt="">
                                        </div>
                                    </div>

                                </div>

                        </div>
                        <div class="col-md-4 offset-sm-4">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <th class="text-right">SubTotal:</th>
                                    <th class="text-right" id="sub-total-data"></th>
                                </tr>
                                <tr>
                                    <th class="text-right">Discount Amount:</th>
                                    <th class="text-right" id="discount-total"></th>
                                </tr>

                                <tr>
                                    <th class="text-right">Total:</th>
                                    <th class="text-right" id="grand-total-data"></th>
                                </tr>
                                <tr>
                                    <th class="text-right">Enable Refund (%)</th>
                                    <th class="text-right">
                                        <input type="checkbox" id="js-toggle-refund-percentage">
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-right">Refund (%):</th>
                                    <th class="text-right">
                                        <span id="return-percentage-span">0</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-right">Refund:</th>
                                    <th class="text-right">
                                        <input type="text" id="return-amount" class="form-control" value="0" readonly>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button id="saveAndBill" class="btn btn-primary float-right">Save and Bill</button>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                          Saved
                        </h3>
                    </div>
                 </div>
                <div class="iq-card-body">
                    <div class="res-table">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>DateTime</th>
                                    <th>Category</th>
                                    <th>Particulars</th>
                                    <th>Rate</th>
                                    <th>QTY</th>
                                    <th>Txa%</th>
                                    <th>Disc%</th>
                                    <th>Total</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody id="js-returnform-saved-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="js-general-change-quantity">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="patient-modal-title">Change Quantity</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="closeinfo">&times;
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Enter Quantity</label>
                        <input type="hidden" id="js-change-quantity-fldid">
                        <input type="hidden" id="js-change-quantity-fldrate">
                        <input type="text" class="form-control" id="js-change-quantity-fldquantity">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="js-change-quantity-save">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
var billcsrftoken = "{{ csrf_token() }}";
</script>

<script src="{{asset('js/dispensing_form.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#js-toggle-refund-percentage').on('click', function () {
            var spanHtml = '0';
            if ($(this).prop('checked'))
                spanHtml = '<input type="text" id="return-percentage" class="form-control" placeholder="0">';
            else {
                var total = $('#grand-total-data').text() || '';
                total = Number(total) || 0;
                $('#return-amount').val(total);
            }

            $('span#return-percentage-span').html(spanHtml);
        })
        $('#js-returnform-particulars-select').select2();
    });

    function getRadioFunction(value){
        if (value == "Cash") {
            $('#cash_payment').addClass('checked-bak');
            $('#credit_payment').removeClass('checked-bak');
            $('#card_payment').removeClass('checked-bak');
            $('#fonepay_payment').removeClass('checked-bak');

        } else if (value == "Credit") {
            $('#cash_payment').removeClass('checked-bak');
            $('#credit_payment').addClass('checked-bak');
            $('#card_payment').removeClass('checked-bak');
            $('#fonepay_payment').removeClass('checked-bak');
        } else{
            $('#cash_payment').addClass('checked-bak');
            $('#credit_payment').removeClass('checked-bak');
            $('#card_payment').removeClass('checked-bak');
            $('#fonepay_payment').removeClass('checked-bak');
        }
    }
</script>
<script>

    $('.img-ms-form').click(function() {

    $(this).parent().find('input[type=radio]').prop('checked', true);

    });
    $('.pay-rad').click(function() {

    $(this).closest('.checked-bak').find('input[type=radio]').prop('checked', true);

    });
</script>
@endpush
