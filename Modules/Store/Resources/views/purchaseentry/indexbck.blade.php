@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

    <section class="cogent-nav">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#purchaseEntry" role="tab" aria-controls="home" aria-selected="true"><span></span>Purchase Entry</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
{{--            navbar   --}}
            <nav class="navbar navbar-expand-lg">
                <div class="navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">File</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownFile">
                                <a class="dropdown-item" href="{{ route('inventory.purchase-entry.index') }}">Blank</a>
                                <a class="dropdown-item" href="{{ route('inventory.purchase-entry.savetopdf') }}" target="_blank">Save</a>
                                <a class="dropdown-item" href="javascript:void(0);" >Print</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

{{--            navbar end   --}}

            <div class="tab-pane fade show active" id="purchaseEntry" role="tabpanel" aria-labelledby="home-tab">
                <div class="container">
                    <div class="profile-form">
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <div class="group__box half_box">
                                    <div class="col-sm-6">
                                        <input type="text" name="purchase_entry_date_nepali" id="purchase_entry_date_nepali" class="f-input-date full-width" title="Purchase Date Nepali" readonly>
                                    </div>
                                    <div class="box__icon">
                                        <a href="#"><img src="{{asset('assets/images/calendar.png')}}" width="23px;"></a>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="date" name="purchase_entry_date_english" value="" id="purchase_entry_date_english" class="f-input-date full-width" title="Purchase Date english">
                                    </div>

                                </div>

                                <div class="group__box half_box">
                                    <div class="box__input" style="flex: 0 0 100%;">
                                        <select readonly="" name="fldpurtype" id="fldpurtype" title="Purchase Type">
                                            <option value=""></option>
                                            <option value="Cash Payment">Cash Payment</option>
                                            <option value="Credit Payment">Credit Payment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <input type="checkbox" name="">&nbsp;&nbsp;
                                    <div class="box__input" style="flex: 0 0 92%;">
                                        <select name="fldroute" id="fldroute">
                                            <option value=""></option>
                                            <option value="anal/vaginal" >anal/vaginal</option>
                                            <option value="extra" >extra</option>
                                            <option value="eye/ear" >eye/ear</option>
                                            <option value="fluid" >fluid</option>
                                            <option value="injection" >injection</option>
                                            <option value="liquid" >liquid</option>
                                            <option value="msurg" >msurg</option>
                                            <option value="oral" >oral</option>
                                            <option value="ortho" >ortho</option>
                                            <option value="resp" >resp</option>
                                            <option value="suture" >suture</option>
                                            <option value="topical" >topical</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="group__box half_box" style="margin-left: 50px;">
                                    <div class="radio-1">&nbsp;&nbsp;
                                        <input type="checkbox" name="">
                                        <label>Purchase Restriction</label>&nbsp;&nbsp;

                                        <input type="checkbox" name="showall" id="showall">
                                        <label>Show All Entry</label>
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <div class="box__input" style="flex: 0 0 30%;">
                                        <input type="text" name="fldbillno" id="fldbillno" title="Invoice Number">
                                    </div>
                                    @php $suppliernames = \App\Utils\Inventoryhelpers::getAllActiveSuppliers(); @endphp
                                    <div class="box__input" style="flex: 0 0 70%;">
                                        <select name="fldsuppname" id="fldsuppname">
                                            <option value="" ></option>
                                            @forelse($suppliernames as $suppliername)
                                                <option value="{{ $suppliername->fldsuppname }}" > {{ $suppliername->fldsuppname }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <div class="box__input" style="flex: 0 0 100%;">
                                        <select readonly="" name="fldstockid" id="fldstockid" class="select2medicinelist">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="group__box half_box">
                                    <div class="radio-1">&nbsp;&nbsp;
                                        <input type="radio" value="generic" name="genericbrand" checked>
                                        <label>Generic</label>&nbsp;&nbsp;

                                        <input type="radio" value="brand" name="genericbrand">
                                        <label>Brand</label>

                                        <input type="hidden" id="genericbrandvalue" value="generic">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <div class="box__input" style="flex: 0 0 100%;">
                                        <input type="text" name="fldsuppaddress" id="fldsuppaddress">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <div class="box__input" style="flex: 0 0 40%;">
                                        <input type="" name="">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="expiry_date_nepali" id="expiry_date_nepali" class="f-input-date full-width">
                                    </div>
                                    <div class="box__icon">
                                        <a href="#"><img src="{{asset('assets/images/calendar.png')}}" width="23px;"></a>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="date" name="expiry_date_english" id="expiry_date_english" class="f-input-date full-width" title="Expiry date english">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="group__box half_box">
                                    <label class="col-5 col-form-label col-form-label-sm">Total Cost:</label>
                                    <div class="box__input" style="flex: 0 0 57%;">
                                        <input type="" name="">
                                    </div>
{{--                                    <a href=""><img src="{{asset('assets/images/calculator.png')}}" width="23px;"></a>--}}
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-5 col-form-label col-form-label-sm">Profit %:</label>
                                    <div class="box__input" style="flex: 0 0 57%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-5 col-form-label col-form-label-sm">Total QTY:</label>
                                    <div class="box__input" style="flex: 0 0 57%;">
                                        <input type="" name="">
                                    </div>
{{--                                    <a href=""><img src="{{asset('assets/images/calculator.png')}}" width="23px;"></a>--}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="group__box half_box">
                                    <label class="col-6 col-form-label col-form-label-sm">Max R Price:</label>
                                    <div class="box__input" style="flex: 0 0 48%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-6 col-form-label col-form-label-sm">Cash Disc:</label>
                                    <div class="box__input" style="flex: 0 0 48%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-6 col-form-label col-form-label-sm">Cash Bonus %:</label>
                                    <div class="box__input" style="flex: 0 0 48%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="group__box half_box">
                                    <label class="col-6 col-form-label col-form-label-sm">QTY Bonus:</label>
                                    <div class="box__input" style="flex: 0 0 48%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-6 col-form-label col-form-label-sm">Carry Cost:</label>
                                    <div class="box__input" style="flex: 0 0 48%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-6 col-form-label col-form-label-sm">Net Unit Cost:</label>
                                    <div class="box__input" style="flex: 0 0 48%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="group__box half_box">
                                    <label class="col-6 col-form-label col-form-label-sm">Dish Unit Cost:</label>
                                    <div class="box__input" style="flex: 0 0 48%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-6 col-form-label col-form-label-sm">Curr Sell Price:</label>
                                    <div class="box__input" style="flex: 0 0 48%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                                <div class="group__box half_box">
                                    <label class="col-6 col-form-label col-form-label-sm">New Sell Price:</label>
                                    <div class="box__input" style="flex: 0 0 48%;">
                                        <input type="" name="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-scroll-md table-responsive">
                                <table class="table table-sm" id="purhaseitemtable">

                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            {{-- next group --}}
                            <div class="group__box half_box">
                                <div class="box__label__modal">
                                    <label class="col-12">SubTotal:</label>
                                </div>&nbsp;
                                <div class="box__input"  style="flex: 0 0 10%;">
                                    <input type="" name="">
                                </div>
                                <div class="box__label__modal">
                                    <label class="col-12">Discount:</label>
                                </div>&nbsp;
                                <div class="box__input"  style="flex: 0 0 10%;">
                                    <input type="" name="">
                                </div>
                                <div class="box__label__modal">
                                    <label class="col-12">Total Tax:</label>
                                </div>&nbsp;
                                <div class="box__input"  style="flex: 0 0 10%;">
                                    <input type="" name="">
                                </div>
                                <div class="box__label__modal">
                                    <label class="col-12">Total Amt:</label>
                                </div>&nbsp;
                                <div class="box__input"  style="flex: 0 0 10%;">
                                    <input type="" name="">
                                </div>
                                <div class="box__label__modal">
                                    <label class="col-12">Ref No:</label>
                                </div>&nbsp;
                                <div class="box__input"  style="flex: 0 0 10%;">
                                    <input type="" name="">
                                </div>&nbsp;&nbsp;
                                <a href="#" class="btn default-btn f-btn-icon-r"><i class="fas fa-code"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function() {

            var fldcomp = "{{ $fldcomp }}";

            function select2loading() {
                setTimeout(function() {

                    $('.select2medicinelist').select2({
                        placeholder : ''
                    });
                }, 3000);
            }
            select2loading();

            // purchase datepicker scripts

                @php $currentDate = Carbon\Carbon::now()->format('Y-m-d'); @endphp
                var currentdate = "{{ $currentDate }}";
                $('#purchase_entry_date_english').val(currentdate);

                $('#purchase_entry_date_english').change(function() {

                    var engdate = $(this).val();

                    EnglishToNepaliForPurchase(engdate);

                });

                function EnglishToNepaliForPurchase(engdate) {
                    $.ajax({
                        url: '{{ route('inventory.englishtonepali') }}',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'engdate': engdate,
                        },
                        success: function(res) {
                            $('#purchase_entry_date_nepali').val(res.nepalidate);
                        }

                    });
                }

                EnglishToNepaliForPurchase(currentdate);

                $('#purchase_entry_date_nepali').nepaliDatePicker({

                    npdMonth	: true,
                    npdYear		: true,
                    npdYearCount: 100,
                    onChange	: function(){
                    var neppurdate = $('#purchase_entry_date_nepali').val();

                    $.ajax({
                        type	: 'post',
                        url		: '{{ route('inventory.nepalitoenglish') }}',
                        dataType : 'json',
                        data	: {
                            '_token' : '{{ csrf_token() }}',
                            'nepdate' : neppurdate,
                        },
                        success: function (res) {
                            $('#purchase_entry_date_english').val(res.englishdate);
                        }
                    });
                }
                 });

            // purchase enddatepickers script

            // for changing suppliers address and listing the purchase list
            $('#fldsuppname').change(function() {
                var suppliername = $(this).val();
                var fldpurtype = $('#fldpurtype').val();
                var fldbillno = $('#fldbillno').val();

                $.ajax({
                   type : 'post',
                   url : '{{ route('inventory.purchase-entry.supplieraddress') }}',
                   dataType : 'json',
                   data : {
                       '_token': '{{ csrf_token() }}',
                       'fldsuppname' : suppliername,
                       'fldpurtype' : fldpurtype,
                       'fldbillno' : fldbillno,
                       'fldcomp'   : fldcomp
                    },
                    success: function(res) {
                        if(res.message == 'error'){
                            showAlert(res.errormessage);
                        } else if(res.message == 'success') {
                            $('#fldsuppaddress').val(res.fldsuppaddress);
                            $('#purhaseitemtable').html(res.table);
                        }
                    }

                });

            });

            $('input[type=radio][name=genericbrand]').change(function() {
                var value = $(this).val();
                $('#genericbrandvalue').val(value);
            });

            $('#fldroute').change(function() {
                var fldroute = $(this).val();

                if(fldroute == '') {
                    alert('please select option');
                    return false;
                }

                var genericbrand = $('#genericbrandvalue').val();

                $.ajax({
                   type : 'post',
                   url : '{{ route('inventory.purchase-entry.getmedicine') }}',
                   dataType : 'json',
                   data : {
                       '_token': '{{ csrf_token() }}',
                       'fldroute' : fldroute,
                       'genericbrand' : genericbrand
                   },
                   success: function(res) {
                       if(res.message == 'error'){
                        showAlert(res.errormessage);
                       } else if(res.message == 'success') {
                           $('.select2medicinelist').html(res.html);
                           select2loading();
                           $('#purchase_entry_date_nepali').attr('disabled', true);
                           $('#purchase_entry_date_english').attr('disabled', true);
                           $('#fldpurtype').attr('disabled', true);
                           $('#fldbillno').attr('disabled', true);
                           $('#fldsuppname').attr('disabled', true);
                           $('#fldsuppaddress').attr('disabled', true);

                       }
                   }
               });
            });


            // expirydatepickers scripts
                function minDateForExpiryEnglish() {
                    var dtToday = new Date();

                    var month = dtToday.getMonth() + 1;
                    var day = dtToday.getDate();
                    var year = dtToday.getFullYear();
                    if(month < 10)
                        month = '0' + month.toString();
                    if(day < 10)
                        day = '0' + day.toString();

                    var maxDate = year + '-' + month + '-' + day;

                    $('#expiry_date_english').attr('min', maxDate);
                }

                minDateForExpiryEnglish();

                $('#expiry_date_english').val(currentdate);

                $('#expiry_date_english').change(function() {

                    var engdate = $(this).val();
                    EnglishToNepaliForExpiry(engdate);

                });

                function EnglishToNepaliForExpiry(engdate) {
                    $.ajax({
                        url: '{{ route('inventory.englishtonepali') }}',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'engdate': engdate,
                        },
                        success: function(res) {
                            $('#expiry_date_nepali').val(res.nepalidate);
                        }

                    });
                }

                EnglishToNepaliForExpiry(currentdate);

                $('#expiry_date_nepali').nepaliDatePicker({

                    npdMonth	: true,
                    npdYear		: true,
                    npdYearCount: 100,
                    disableDaysBefore: '1',
                    onChange	: function(){
                        var neppurdate = $('#expiry_date_nepali').val();

                        $.ajax({
                            type	: 'post',
                            url		: '{{ route('inventory.nepalitoenglish') }}',
                            dataType : 'json',
                            data	: {
                                '_token' : '{{ csrf_token() }}',
                                'nepdate' : neppurdate,
                            },
                            success: function (res) {
                                $('#expiry_date_english').val(res.englishdate);
                            }
                        });
                    }
                });
            // expirydatepickers scripts

                $('#showall').click(function() {
                    if($(this).is(":checked") === true) {
                        $.ajax({
                            type : 'post',
                            url : '{{ route('inventory.purchase-entry.showall') }}',
                            dataType : 'json',
                            data : {
                                '_token' : '{{ csrf_token() }}',
                                'fldcomp' : fldcomp
                            },
                            success : function(res) {
                                if(res.message == 'error'){
                                    showAlert(res.errormessage);
                                } else if(res.message == 'success') {
                                  $('#purhaseitemtable').html(res.table);
                                }
                            }
                        });
                    } else {

                        $('#purhaseitemtable').empty();
                    }

                });
        });
    </script>

@stop
