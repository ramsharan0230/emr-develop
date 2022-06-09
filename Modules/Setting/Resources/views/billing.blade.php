 @extends('frontend.layouts.master')
 @section('content')
 <style>
 /* .img-thumbnail:hover{
     transform: scale(1.5);
 } */
 </style>
 <div class="container-fluid">
     {{-- @include('frontend.common.alert_message') --}}


     <div class="row">
         <div class="col-sm-12">
             <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                 <div class="iq-card-body">
                     <div class="nav-box">
                         <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                         <li class="nav-item">
                                 <a class="nav-link active" id="old-patient-tab" data-toggle="tab" href="#bill-setting" role="tab" aria-selected="true">Bill Settings</a>
                             </li>

                             <li class="nav-item">
                                 <a class="nav-link " id="new-patient-tab" data-toggle="tab" href="#other-setting" role="tab" aria-controls="new-patient" aria-selected="false"><span id="js-new-patient-span">Other setting</a>
                             </li>

                         </ul>

                     </div>
                     <div class="tab-content" id="myTabContent-2">

                     <!-- start bill setting tabs content -->
                     <div class="tab-pane fade show active" id="bill-setting" role="tabpanel" aria-labelledby="bill-setting">
                            <form action="{{ route('billing.setting.type') }}" method="POST">
                            {{ csrf_field() }}
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                            <label class="specs_label">Bill Type <span class="text-danger">*</span>:</label>
                                            <select name="bill_type" class="form-control select2 bill-type" required>
                                                <option value="">--Select--</option>
                                                <option value="Service-Billing-Header">Service Billing</option>
                                                <option value="Pharmacy-Billing-Header">Pharmacy Billing</option>
                                                <option value="Deposit-Billing-Header">Deposit Billing</option>
                                                <option value="Discharge-Billing-Header">Discharge Billing</option>
                                                <option value="Return-Billing-Header">Return Billing</option>
                                            </select>
                                    </div>

                                </div>

                                <div class="row test" style="display:none;">
                                    <div class="form-group col-sm-4">
                                        <label class="specs_label">Header Type <span class="text-danger">*</span>:</label>
                                    </div>
                                </div>

                                <div class="row test" style="display:none;">
                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="header" id="header1" value="header1">
                                            <label class="form-check-label" for="header1">
                                            <img src="{{asset('new/images/header1.png')}}" alt="header1" class="img-thumbnail">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="header" id="header2" value="header2">
                                            <label class="form-check-label" for="flexRadioDisabled">
                                            <img src="{{asset('new/images/header2.png')}}" alt="header2" class="img-thumbnail">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="header" id="header3" value="header3">
                                            <label class="form-check-label" for="header3">
                                            <img src="{{asset('new/images/header3.png')}}" alt="header3" class="img-thumbnail">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4 test" style="display:none;">
                                    <div class="form-group col-sm-4">
                                        <label class="specs_label">Total Type <span class="text-danger">*</span>:</label>
                                    </div>
                                </div>

                                <div class="row test" style="display:none;">
                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total" id="total1" value="total1">
                                            <label class="form-check-label" for="total1">
                                            <img src="{{asset('new/images/total1.png')}}" alt="" class="img-thumbnail">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="total" id="total2" value="total2">
                                            <label class="form-check-label" for="flexRadioDisabled">
                                            <img src="{{asset('new/images/total2.png')}}" alt="" class="img-thumbnail">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-action float-right">Save</button>
                            </form>
                         </div>
                           <!-- end bill setting tabs content -->


                         <!-- New Patient tabs content -->
                         <div class="tab-pane fade " id="other-setting" role="tabpanel" aria-labelledby="other-setting">
                             <div class="row">
                                 <!-- <div class="col-sm-4">
                                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">
                                                Discharge Clearance Bill Print
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body">
                                        <form action="{{ route('billing.setting.save') }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <input type="radio" name="discharge_clearance_bill_format" id="discharge_clearance_bill_format_detailed" value="detailed" {{ Options::get('discharge_clearance_bill_format') === 'detailed' ? 'checked' : '' }}>
                                                <label for="discharge_clearance_bill_format_detailed">
                                                    Detailed
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <input type="radio" name="discharge_clearance_bill_format" id="discharge_clearance_bill_format_summary" value="summary" {{ Options::get('discharge_clearance_bill_format') === 'summary' ? 'checked' : '' }}>
                                                <label for="discharge_clearance_bill_format_summary">
                                                    Summary
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>-->

                                 <div class="col-sm-4">
                                     <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                         <div class="iq-card-header d-flex justify-content-between">
                                             <div class="iq-header-title">
                                                 <h4 class="card-title">
                                                     Cashier Form Billing Toggle Show
                                                 </h4>
                                             </div>
                                         </div>
                                         <div class="iq-card-body">
                                             <form action="{{ route('billing.setting.save.toggle.billing') }}" method="post">
                                                 @csrf
                                                 <div class="form-group">
                                                     <input type="radio" name="display_billing_toggle" id="display_billing_toggle_yes" value="1" {{ Options::get('display_billing_toggle') === '1' ? 'checked' : '' }}>
                                                     <label for="display_billing_toggle_yes">
                                                         Yes
                                                     </label>
                                                 </div>
                                                 <div class="form-group">
                                                     <input type="radio" name="display_billing_toggle" id="display_billing_toggle_no" value="0" {{ Options::get('display_billing_toggle') === '0' ? 'checked' : '' }}>
                                                     <label for="display_billing_toggle_no">
                                                         No
                                                     </label>
                                                 </div>
                                                 <button type="submit" class="btn btn-primary">Save</button>
                                             </form>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="col-sm-4">
                                     <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                         <div class="iq-card-header d-flex justify-content-between">
                                             <div class="iq-header-title">
                                                 <h4 class="card-title">
                                                     Emergency Share
                                                 </h4>
                                             </div>
                                         </div>
                                         <div class="iq-card-body">
                                             <form action="{{ route('billing.setting.save.emergencydrshare') }}" method="post">
                                                 @csrf
                                                 <div class="form-group">
                                                     <input type="radio" name="emergency_drshare_hospital" id="emergency_drshare_hospital" value="1" {{ Options::get('emergency_drshare_hospital') === '1' ? 'checked' : '' }}>
                                                     <label for="emergency_drshare_hospital">
                                                         Yes
                                                     </label>
                                                 </div>
                                                 <div class="form-group">
                                                     <input type="radio" name="emergency_drshare_hospital" id="emergency_drshare_hospital" value="0" {{ Options::get('emergency_drshare_hospital') === '0' ? 'checked' : '' }}>
                                                     <label for="emergency_drshare_hospital">
                                                         No
                                                     </label>
                                                 </div>
                                                 <button type="submit" class="btn btn-primary">Save</button>
                                             </form>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="col-sm-4">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">
                                    Ageing Report Interval
                                    </h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <form action="{{ route('ageing.setting.interval.save') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="ageing_interval" id="ageing_interval" value="{{ Options::get('ageing_interval') }}" >
                                        <p id="interval"></p>
                                    </div>

                                    <button id="saveinterval" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                    </div>



                             </div>

                         </div>

                         <!-- old patient tabs content -->




                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 @endsection
 @push('after-script')
 <script>
        $( document ).ready(function() {
            $(document).on('keyup', '#ageing_interval', function(event){
                var $in = $(this).val();
                let x = parseInt($in) + parseInt($in);
                let y = parseInt(x) + parseInt($in);
                let z = parseInt(y) + parseInt($in);
                var zin = 0+'-'+$in+'days '+ $in+'-'+x+'days ' +x+'-'+y+'days '+ y+'-'+z+'days '+ z+'+days ';

                $('#interval').html(zin);

            });

            $(document).on('click', '#saveinterval', function(event){
            event.preventDefault();
            var url = "{{route('ageing.setting.interval.save')}}";
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    ageing_interval: $('#ageing_interval').val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    showAlert("Added Successfully", "success");
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }

            });


        });
    });
        </script>
    <script>

        $(".bill-type").change(function () {
            var bill_type = $(".bill-type").val();
            if(bill_type != '')
            {
                $(".test").show();
            }
            else
            {
                $(".test").hide();
            }
            if(bill_type == "Service-Billing-Header")
            {
                var bill_type_value = "{{Options::get('Service-Billing-Header')}}";
                var bill_type_total = "{{Options::get('Service-Billing-Total')}}";
                if(bill_type_value)
                $('#'+bill_type_value).prop('checked',true);
                else{
                $('#header1').prop('checked',false);
                $('#header2').prop('checked',false);
                $('#header3').prop('checked',false);
                }
                if(bill_type_total)
                $('#'+bill_type_total).prop('checked',true);
                else{
                $('#total1').prop('checked',false);
                $('#total2').prop('checked',false);
                }
            }
            else if(bill_type == "Pharmacy-Billing-Header"){
                var bill_type_value = "{{Options::get('Pharmacy-Billing-Header')}}";
                var bill_type_total = "{{Options::get('Pharmacy-Billing-Total')}}";
                if(bill_type_value)
                $('#'+bill_type_value).prop('checked',true);
                else{
                $('#header1').prop('checked',false);
                $('#header2').prop('checked',false);
                $('#header3').prop('checked',false);
                }
                if(bill_type_total)
                $('#'+bill_type_total).prop('checked',true);
                else{
                $('#total1').prop('checked',false);
                $('#total2').prop('checked',false);
                }
            }
            else if(bill_type == "Deposit-Billing-Header"){
                var bill_type_value = "{{Options::get('Deposit-Billing-Header')}}";
                var bill_type_total = "{{Options::get('Deposit-Billing-Total')}}";
                if(bill_type_value)
                $('#'+bill_type_value).prop('checked',true);
                else{
                $('#header1').prop('checked',false);
                $('#header2').prop('checked',false);
                $('#header3').prop('checked',false);
                }
                if(bill_type_total)
                $('#'+bill_type_total).prop('checked',true);
                else{
                $('#total1').prop('checked',false);
                $('#total2').prop('checked',false);
                }
            }
            else if(bill_type == "Discharge-Billing-Header"){
                var bill_type_value = "{{Options::get('Discharge-Billing-Header')}}";
                var bill_type_total = "{{Options::get('Discharge-Billing-Total')}}";
                if(bill_type_value)
                $('#'+bill_type_value).prop('checked',true);
                else{
                $('#header1').prop('checked',false);
                $('#header2').prop('checked',false);
                $('#header3').prop('checked',false);
                }
                if(bill_type_total)
                $('#'+bill_type_total).prop('checked',true);
                else{
                $('#total1').prop('checked',false);
                $('#total2').prop('checked',false);
                }
            }
            else if(bill_type == "Return-Billing-Header"){
                var bill_type_value = "{{Options::get('Return-Billing-Header')}}";
                var bill_type_total = "{{Options::get('Return-Billing-Total')}}";
                if(bill_type_value)
                $('#'+bill_type_value).prop('checked',true);
                else{
                $('#header1').prop('checked',false);
                $('#header2').prop('checked',false);
                $('#header3').prop('checked',false);
                }
                if(bill_type_total)
                $('#'+bill_type_total).prop('checked',true);
                else{
                $('#total1').prop('checked',false);
                $('#total2').prop('checked',false);
                }
            }
            else{
                $('#header1').prop('checked',false);
                $('#header2').prop('checked',false);
                $('#header3').prop('checked',false);
                $('#total1').prop('checked',false);
                $('#total2').prop('checked',false);
            }
        });
    </script>
@endpush
