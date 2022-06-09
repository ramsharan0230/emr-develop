@php
    $banks = \App\Banks::all();
@endphp
<div class="form-horizontal">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group form-row align-items-center">
                <label class="col-sm-6">Payment Mode</label>
                <div class="col-sm-6">
                    <select name="payment_mode" id="payment_mode" class="form-control">
                        <option value="Cash">Cash</option>
                        <option value="Credit">Credit</option>
                        <option value="Cheque">Cheque</option>
{{--                        <option value="Fonepay">Fonepay</option>--}}
                        <option value="Other">Other</option>
                    </select>
                </div>
                <!-- <div class="col-sm-9">
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
                </div> -->
            </div>
        </div>
        {{--end if cash--}}
        <div class="col-sm-6" id="expected_date">
            <div class="form-group form-row align-items-center">
                <label class="col-sm-8">Expected Payment Date</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" name="expected_payment_date_nepali" id="expected_payment_date_nepali" class="form-control js-nepaliDatePicker">
                        <input type="hidden" name="expected_payment_date" id="expected_payment_date" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <input type="text" name="cheque_number" id="cheque_number" placeholder="Cheque Number" class="form-control">
                <input type="text" name="other_reason" id="other_reason" placeholder="Reason" class="form-control mt-2">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group form-row">
                <label class="col-sm-6"></label>
                <div class="col-sm-6">
                <select name="bank_name" id="bank_name" class="form-control">
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
            <div class="form-group">
                <input type="text" name="office_name" id="office_name" placeholder="Office Name" class="form-control">
            </div>
        </div>

    </div>
</div>
{{--end if cash--}}
{{--if cash--}}
<!-- <div class="form-horizontal border-bottom pt-3">
    <div class="row">
        <div class="col-sm-12" id="expected_date">
            <div class="form-group form-row align-items-center">
                <label class="col-sm-5">Expected Payment Date</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text" name="expected_payment_date_nepali" id="expected_payment_date_nepali" class="form-control js-nepaliDatePicker">
                        <input type="hidden" name="expected_payment_date" id="expected_payment_date" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <input type="text" name="cheque_number" id="cheque_number" placeholder="Cheque Number" class="form-control">
                <input type="text" name="other_reason" id="other_reason" placeholder="Reason" class="form-control">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <select name="bank_name" id="bank_name" class="form-control">
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
</div> -->
{{--end if cash--}}

@push('after-script')
    <script type="text/javascript">
        jQuery(function ($) {
            hideAll();
            setTimeout(function () {
                $("#bank_name").select2();
                $('#bank_name').next(".select2-container").hide();
            }, 1500);
            /*On click payment modes*/
            $(document).on('click', '#payment_customer', function (event) {
                $('#office_name').hide();
            });
            $(document).on('click', '#payment_office', function (event) {
                $('#office_name').show();
            });
            $(document).on('click', '#payment_mode_credit', function (event) {
                hideAll();
                $('#expected_date').show();
            });
            $(document).on('click', '#payment_mode_cheque', function (event) {
                hideAll();
                $('#cheque_number').show();
                $('#bank_name').next(".select2-container").show();
            });
            $(document).on('click', '#payment_mode_other', function (event) {
                hideAll();
                $('#other_reason').show();
            });
            $(document).on('click', '#payment_mode_cash', function (event) {
                hideAll();
            });

            var nepaliDateConverter = new NepaliDateConverter();
            $('.js-nepaliDatePicker').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    var englishdate = ($('#expected_payment_date_nepali').val()).split('-');
                    englishdate = englishdate[1] + '/' + englishdate[2] + '/' + englishdate[0];
                    englishdate = nepaliDateConverter.bs2ad(englishdate);
                    $('#expected_payment_date').val(englishdate);
                }
            });
            var date = new Date().toISOString().split("T")[0];

            var nepalidate = date.split('-');
            nepalidate = nepalidate[1] + '/' + nepalidate[2] + '/' + nepalidate[0];
            nepalidate = nepaliDateConverter.ad2bs(nepalidate);

            $('#expected_payment_date').val(date);
            $('#expected_payment_date_nepali').val(nepalidate);
        });

        function hideAll() {
            $('#office-name').hide();
            $('#bank_name').next(".select2-container").hide();
            $('#expected_date').hide();
            $('#cheque_number').hide();
            $('#office_name').hide();
            $('#other_reason').hide();
        }
    </script>
@endpush
