@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex ">
                    <div class="iq-header-title col-sm-8 p-0">
                        <h4 class="card-title">
                            Transaction Entry Update
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form action="{{ route('transaction.update') }}" method="post" id="transaction-update-form">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-3">Voucher Entry:</label>
                                    <div class="col-sm-9">
                                        <select name="voucher_entry" id="voucher_entry" class="form-control" readonly>
                                            <option value="">Select</option>
                                            <option value="Journal" {{ $VoucherCode == 'Journal' ? 'selected' : '' }}>Journal</option>
                                            <option value="Payment" {{ $VoucherCode == 'Payment' ? 'selected' : '' }}>Payment</option>
                                            <option value="Receipt" {{ $VoucherCode == 'Receipt' ? 'selected' : '' }}>Receipt</option>
                                            <option value="Contra" {{ $VoucherCode == 'Contra' ? 'selected' : '' }}>Contra</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">Cheque No:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="cheque_number" class="form-control" value="{{ $ChequeNo }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-4">Transaction Date:</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="transaction_date_nepali" value="" class="form-control">
                                        <input type="hidden" name="transaction_date" id="transaction_date_eng" value="{{ $TranDate }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group form-row">
                                    <label for="remarks-textarea" class="col-sm-3">Remarks:</label>
                                    <textarea name="remarks_textarea" class="form-control col-sm-9" rows="3" id="remarks-textarea">{{ $Remarks }}</textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @if($VoucherData)
                                @foreach($VoucherData as $voucher)
                                    <input type="hidden" name="tranID[]" value="{{ $voucher->TranId }}">
                                    <div class="col-sm-3">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-4">Account:</label>
                                            <div class="col-sm-8">
                                                <select name="account_name[]" class="select2">
                                                    <option value="">Select</option>
                                                    @if($accounts)
                                                        @forelse($accounts as $account)
                                                            <option value="{{ $account->AccountNo }}" {{ $voucher->AccountNo == $account->AccountNo ? 'selected' : '' }}>{{ $account->AccountName }}</option>
                                                        @empty
                                                        @endforelse
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-row">
                                            <label for="debit_credit" class="col-sm-4">Dr/Cr:</label>
                                            <div class="col-sm-8">
                                                <select name="debit_credit[]" class="form-control" id="drcr-{{ $voucher->TranId }}">
                                                    <option value="">Select</option>
                                                    <option value="+" {{ $voucher->TranAmount > 0 ? "selected" : '' }}>Dr</option>
                                                    <option value="-" {{ $voucher->TranAmount < 0 ? "selected" : '' }}>Cr</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-4">Amount:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="amount[]" class="form-control" id="amount_for_balance_{{ $voucher->TranId }}" value="{{ abs($voucher->TranAmount) }}" data-id="{{ $voucher->TranId }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-4">Narration:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="Narration[]" class="form-control" value="{{ $voucher->Narration }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script>
        let todayDate = AD2BS($("#transaction_date_eng").val());

        $(window).ready(function () {
            $('#transaction_date_nepali').val(todayDate);
            $('#transaction_date_eng').val(BS2AD($('#transaction_date_nepali').val()));

            $('#transaction_date_nepali').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#transaction_date_eng').val(BS2AD($('#transaction_date_nepali').val()));
                }
            });

            $("#transaction-update-form").submit(function () {
                let balance = 0;
                $("input[id^='amount_for_balance']").each(function () {
                    if ($("#drcr-" + $(this).data('id')).val() == "+") {
                        balance = balance + parseFloat($(this).val());
                    } else {
                        balance = balance - parseFloat($(this).val());
                    }
                });
                if (balance === 0){
                    return true;
                }
                showAlert('Credit and Debit balance not equal', 'error');
                return false;
            });
        });
    </script>
@endpush
