@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Fiscal Year Data
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <form action="" class="row mt-2">
                            @csrf
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-4 col-lg-3">Form:</label>
                                    <div class=" col-lg-9 col-sm-8">
                                        <input type="text" class="form-control" id="from_date" autocomplete="off" required>
                                        <input type="hidden" name="from_date" id="from_date_eng" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-4  col-lg-3">To:</label>
                                    <div class=" col-lg-9 col-sm-8">
                                        <input type="text" class="form-control" id="to_date" autocomplete="off" required>
                                        <input type="hidden" name="to_date" id="to_date_eng" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Field</th>
                                <th>Fiscal Year</th>
                                <th>Bill No.</th>
                                <th>Customer</th>
                                <th>Pan</th>
                                <th>Bill Date</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Taxable Amount</th>
                                <th>Tax Amount</th>
                                <th>Total Amount</th>
                                <th>Sync With IRD</th>
                                <th>Is Bill Printed</th>
                                <th>Is Bill Active</th>
                                <th>Printed Time</th>
                                <th>Entered By</th>
                                <th>Printed By</th>
                                <th>Is Realtime</th>
                                <th>Payment Method</th>
                                <th>VAT Refund Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($fiscal)
                                @forelse($fiscal as $key =>$f)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $f->Fiscal_Year }}</td>
                                        <td>{{ $f->Bill_no }}</td>
                                        <td>{{ $f->Customer_name }}</td>
                                        <td>{{ $f->Customer_pan }}</td>
                                        <td>{{ $f->Bill_Date }}</td>
                                        <td>{{ Helpers::numberFormat($f->Amount) }}</td>
                                        <td>{{ Helpers::numberFormat($f->Discount) }}</td>
                                        <td>{{ Helpers::numberFormat($f->Taxable_Amount) }}</td>
                                        <td>{{ Helpers::numberFormat($f->Tax_Amount) }}</td>
                                        <td>{{ Helpers::numberFormat($f->Total_Amount) }}</td>
                                        <td>{{ $f->Sync_with_IRD }}</td>
                                        <td>{{ $f->IS_Bill_Printed }}</td>
                                        <td>{{ $f->Is_Bill_Active }}</td>
                                        <td>{{ $f->Printed_Time }}</td>
                                        <td>{{ $f->Entered_By }}</td>
                                        <td>{{ $f->Printed_By }}</td>
                                        <td>{{ $f->Is_realtime }}</td>
                                        <td>{{ $f->Payment_Method }}</td>
                                        <td>{{ Helpers::numberFormat($f->VAT_Refund_Amount) }}</td>
                                    </tr>
                                @empty

                                @endforelse
                            @endif
                            </tbody>
                        </table>
                        @if($fiscal)
                            {{ $fiscal->appends(request()->all())->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script>
        $(window).ready(function () {
            $('#to_date').val(AD2BS('{{Request::get('to_date')??date('Y-m-d')}}'));
            $('#from_date').val(AD2BS('{{Request::get('from_date')??date('Y-m-d')}}'));
            $('#to_date_eng').val(BS2AD($('#to_date').val()));
            $('#from_date_eng').val(BS2AD($('#from_date').val()));
            $('#to_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 20,
                onChange: function () {
                    $('#to_date_eng').val(BS2AD($('#to_date').val()));
                }
            });
            $('#from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 20,
                onChange: function () {
                    $('#from_date_eng').val(BS2AD($('#from_date').val()));
                }
            });
        });
    </script>
@endpush


