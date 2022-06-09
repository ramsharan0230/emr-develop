@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Credit Clearance
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form id="credit-clearance-form">
                            <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">From Date:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="from_date" id="from_date" value="" autocomplete="off">
                                            <input type="hidden" name="eng_from_date" id="eng_from_date" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">To Date:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="to_date" id="to_date" value="" autocomplete="off">
                                            <input type="hidden" name="eng_to_date" id="eng_to_date" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Encounter ID:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="encounter_id" id="encounter_id" placeholder="Encounter Number">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Bill Number:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="bill_number" id="bill_number" placeholder="Bill Number">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-4">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="type" value="all" id="all-radio" class="custom-control-input">
                                        <label for="all-radio" class="custom-control-label"> All Credit </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="type" value="paid" id="paid-radio" class="custom-control-input">
                                        <label for="paid-radio" class="custom-control-label"> Paid </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="type" value="nonpaid" id="nonpaid-radio" class="custom-control-input" checked>
                                        <label for="nonpaid-radio" class="custom-control-label"> Non Paid </label>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-4">Billing Mode:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select name="billingmode" id="billingmode" class="form-control">
                                                <option value="">--Billing Mode--</option>
                                                @if(isset($billingset))
                                                    @foreach($billingset as $b)
                                                        <option value="{{$b->fldsetname}}">{{$b->fldsetname}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-row">
                                        @php
                                            $discounts = Helpers::getDiscounts(null);

                                        @endphp
                                        <label for="" class="col-sm-4">Discount:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select name="discount_scheme" id="discount-scheme-change" class="form-control js-registration-discount-scheme">
                                                <option value="">--Discount--</option>
                                                @foreach($discounts as $discount)
                                                    <option value="{{ $discount->fldtype }}">{{ $discount->fldtype }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-4">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="bill_type" value="all" id="alltype-radio" class="custom-control-input" checked>
                                        <label for="alltype-radio" class="custom-control-label"> All </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="bill_type" value="pharmacy" id="pharmacy-radio" class="custom-control-input">
                                        <label for="pharmacy-radio" class="custom-control-label"> Pharmacy </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="bill_type" value="services" id="services-radio" class="custom-control-input">
                                        <label for="services-radio" class="custom-control-label"> Services </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <button type="button" class="btn btn-primary btn-action" onclick="searchBill()"><i class="fa fa-search"></i>&nbsp;Search</button>
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
                        <div class="form-group">
                            <div class="table-responsive res-table">
                                <table class="table table-striped table-hover table-bordered ">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center"></th>
                                        <th class="text-center">Encounter Number</th>
                                        <th class="text-center">Patient Name</th>
                                        <th class="text-center">Bill type</th>
                                        <th class="text-center">Total Amt</th>
                                        <th class="text-center">Discount Amt</th>
                                        <th class="text-center">Due Amt</th>
                                        <th class="text-center">Deposit</th>
                                        <th class="text-center">Action</th>
                                    </tr>

                                    </thead>
                                    <tbody id="credit-clearance-data">

                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('coreaccount::modal.item-list')
    <script>
        $(window).ready(function () {
            $('#to_date').val(AD2BS('{{date('Y-m-d')}}'));
            $('#from_date').val(AD2BS('{{date('Y-m-d')}}'));
            $('#eng_from_date').val('{{date('Y-m-d')}}');
            $('#eng_to_date').val('{{date('Y-m-d')}}');
        })

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

        function searchBill() {
            // alert('Search BIll');
            $.ajax({
                url: baseUrl + '/account/creditclearance/searchBill',
                type: "POST",
                data: $('#credit-clearance-form').serialize(),
                success: function (response) {
                    $('#credit-clearance-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        $(document).on('click', '.creditbill', function () {
            var encounter = $(this).data('encounter');
            // alert(encounter);
            $.ajax({
                url: '{{ route('billing.item.list') }}',
                type: "POST",
                data: {
                    encounter_id: encounter,
                },
                success: function (response) {
                    // console.log(response);
                    $('#item-list-modal').modal('show');
                    $('.form-data-item-list').html(response);


                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        });

    </script>
@endsection
