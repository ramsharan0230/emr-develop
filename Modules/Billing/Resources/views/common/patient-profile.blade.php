<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-9">
                    <form>
                        <input type="hidden" id="fldencounterval"
                               value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif "/>
                        <input type="hidden" id="flduserid" class="current_user"
                               value="{{Helpers::getCurrentUserName()}}"/>
                        <input type="hidden" id="fldcomp" value="{{ Helpers::getCompName() }}">
                        <input type="hidden" name="req_segment" id="req_segment" value="{{$segment}}">
                        @if (Request::segment(1) == 'dispensingForm')
                            <div class="form-group">

                                <div class="">
                                    <label for="" class="control-label ">Current Department:</label>

                                </div>
                                <div class="">

                                    <label for=""
                                           class="control-label "><b>{{ Session::get('selected_user_hospital_department')->name }}</b></label>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row mb-0 align-items-end">
                            <div class="col-lg-3 col-sm-3">
                                <label for="" class="control-label ">Encounter ID</label>
                                <input type="text" name="encounter_id" id="js-encounter-id-input" class="form-control"
                                       placeholder="Enter Encounter ID"/>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <label for="" class="control-label ">Patient ID</label>
                                <input type="text" name="patient_details" class="form-control"
                                       placeholder="Search Patient ID"/>
                            </div>

                            <div class="col-sm-2 col-lg-2">
                                <button type="submit" id="js-submit-button" class="btn btn-primary btn-block"><i
                                        class="fa fa-search"></i> &nbsp;Search
                                </button>
                            </div>
                            {{--                            @if (Request::segment(1) != 'dispensingForm')--}}
                            {{--                                <div class="col-sm-3 col-lg-3">--}}
                            {{--                                    <input type="checkbox" id="show-temporary-items" onclick="showTemporaryBill()">--}}
                            {{--                                    <label for="show-temporary-items">Show Credit Items</label>--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}

                            @if (Request::segment(1) == 'dispensingForm')

                                <div class="col-sm-2 col-lg-2">
                                    <button type="button" class="btn btn-primary btn-block"
                                            id="js-dispensing-online-request-button">Online Request
                                    </button>
                                </div>
                            @endif

                        </div>

                    </form>

                </div>
                <div class="col-sm-3">
                    @if(Route::is('billing.display.form'))
                        <a href="{{ route('reset.encounter.billing') }}"
                           class="btn btn-outline-primary float-right mb-1"><i class="fa fa-sync"></i>&nbsp;Reset</a>
                    @endif
                    <a href="javascript:" class="btn btn-primary float-right mr-1" data-toggle="modal"
                       data-target="#new-user-model"><i class="fa fa-plus"></i>&nbsp;Create New Patient</a>&nbsp;
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="patient-profile">
                <div class="patient-profile">
                    <div class="form-row">
                        {{-- <div class="col-md-2 text-center">
                         <div class="profile-img traicolor" id="traicolor">

                             @php
                             $segment = Request::segment(1);
                             /*$image = \App\Utils\Helpers::getPatientImage($segment);*/
                             @endphp
                             --}}{{--@if(isset($image) and !empty($image))
                             <img src="{{ $image->fldpic }}" alt="">
                             @else
                             <img src="{{ asset('assets/images/dummy-img.jpg')}}" alt="">
                             @endif--}}{{--

                             --}}{{--                                <a href="#" class="upload-profile {{ $disableClass }}" onclick="imagePop.displayModal()"><i class="ri-camera-2-fill"></i></a>--}}{{--
                         </div>
                     </div>--}}
                        <div class="col-md-3">
                            <div class="profile-detail">
                                <h4 class="patient-name">{{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{ isset($patient) ? $patient->fldptnamefir . ' ' . $patient->fldmidname . ' '. $patient->fldptnamelast:'' }}</h4>
                                <p>
                                    Pat ID: <span>@if(isset($enpatient)){{ $enpatient->fldpatientval }}@endif</span> /
                                    EncID: <span>@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif</span>
                                </p>

                                <input size="1" type="hidden" name="encounter_id" id="encounter_id" placeholder=""
                                       value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="profile-detail">
                                @php
                                    if(isset($patient)){
                                        $year = \Carbon\Carbon::createFromDate($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%y years');
                                        $month = \Carbon\Carbon::createFromDate($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%m months');
                                        $days = \Carbon\Carbon::createFromDate($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%d day');
                                        $hours = \Carbon\Carbon::createFromDate($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%h hours');
                                    }


                                @endphp
                                <p>Age/Sex: @if(isset($patient)) {{ $patient->fldagestyle }} @endif
                                    {{-- @if(isset($years) && $years == 'Years')
                                        @php $bday = $patient->fldptbirday; @endphp
                                        @if(isset($patient)    ){{ \Carbon\Carbon::parse($bday)->age }} Years @endif
                                    @endif
                                    @if(isset($years) && $years == 'Months')
                                        @php $bday = $patient->fldptbirday; @endphp
                                        @if(isset($patient)){{ \Carbon\Carbon::parse($bday)->diff(\Carbon\Carbon::now())->format('%m') }}Months @endif
                                    @endif
                                    @if(isset($years) && $years == 'Days')
                                        @php $bday = $patient->fldptbirday; @endphp
                                        @if(isset($patient)){{ \Carbon\Carbon::parse($bday)->diff(\Carbon\Carbon::now())->format('%d') }} Days @endif
                                    @endif
                                    @if(isset($years) && $years == 'Hours')
                                        @php $bday = $patient->fldptbirday; @endphp
                                        @if(isset($patient)){{ \Carbon\Carbon::parse($bday)->diff(\Carbon\Carbon::now())->format('%H') }} Hours @endif
                                    @endif --}}
                                    / <span
                                        id="js-inpatient-gender-input">@if(isset($patient)){{ $patient->fldptsex }}@endif</span>
                                </p>
                                <p>Address: @if(isset($patient)){{ $patient->fldptaddvill }}
                                    , {{ $patient->fldptadddist }}@endif</p>
                                {{--<div class="profile-form form-group form-row align-items-center">
                                    <label for="" class="control-label col-sm-3 mb-0">Height:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="heightvalue" value="@if(isset($body_height)){{ $height }}@endif" disabled>

                                    </div>
                                    <div class="col-sm-3">
                                        <select name="heightrate" id="heightrate" class="form-control" disabled>
                                            <option value="1" @if(isset($heightrate) && $heightrate=='cm' ) selected=selected @endif>cm</option>
                                            <option value="2" @if(isset($heightrate) && $heightrate=='m' ) selected=selected @endif>m</option>
                                        </select>

                                    </div>
                                    <div class="col-sm-2">

                                        --}}{{--                                        <a href="javascript:;" id="save_height" class="{{ $disableClass }}" url="{{ route('save_height') }}"><i class="ri-check-fill"></i></a>--}}{{--
                                    </div>
                                </div>
                                <div class="profile-form form-group form-row align-items-center">
                                    <label for="" class="control-label col-sm-5 mb-0">Weight(Kg):</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="weight" id="weight" class="form-control" value="@if(isset($body_weight)){{ $body_weight->fldrepquali }}@endif" disabled>

                                    </div>
                                    <div class="col-sm-2">
                                        --}}{{--                                        <a href="javascript:;" id="save_weight" class="{{ $disableClass }}" url="{{ route('save_weight') }}"><i class="ri-check-fill"></i></a>--}}{{--
                                    </div>
                                </div>
                                <p>BMI: <span id="bmi">@if(isset($bmi)){{$bmi}}@endif</span></p>
                                <p>Status: <span>Registered</span></p>--}}
                                @if(isset($enpatient->patientInfo->fldnhsiid) && $enpatient->patientInfo->fldnhsiid)
                                @php

                                    $allowedamt = \DB::table('tblpatient_insurance_details')->where('fldencounterval',$enpatient->fldencounterval)->pluck('fldallowedamt')->first();


                                @endphp
                                 Allowed Amount: <span id="allowedamtpat">@if(isset($allowedamt)) {{$allowedamt}}@endif</span>
                                @endif
                                

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="profile-detail">

                                {{--<div class="profile-form form-group form-row align-items-center">
                                    <label for="" class="control-label col-sm-3 mb-0">Consult:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="consulting_aar" placeholder="" value="@if(isset($enpatient)){{ $enpatient->flduserid }} @elseif(Helpers::getCurrentRole($segment) == '1') @endif" readonly>

                                    </div>
                                    <div class="col-sm-2">
                                        --}}{{--                                        <a href="javascript:;" class="{{ $disableClass }}" data-toggle="modal" data-target="#consultant_list"><i class="ri-stethoscope-fill"></i></a>--}}{{--
                                    </div>
                                </div>--}}
                                <p>DOReg: <span
                                        id="js-inpatient-dor-input">@if(isset($enpatient)){{ $enpatient->fldregdate }}@endif</span>
                                </p>
                                <p>Location: <span
                                        id="get_related_fldcurrlocat">@if(isset($enpatient)){{ $enpatient->fldcurrlocat }}@endif @if(isset($enbed))
                                            /  {{ $enbed->fldbed }}@endif</span></p>
                                @if(isset($enpatient->patientInfo->fldnhsiid) && $enpatient->patientInfo->fldnhsiid)
                                <p>NHSI ID: <span id="nhsiid">@if(isset($enpatient->patientInfo->fldnhsiid)){{$enpatient->patientInfo->fldnhsiid}}@endif</span></p>
                                @endif
                               


                                @if($route == 'admin/laboratory/addition' || $route == 'patient' || $route == 'eye' || $route == 'dental')

                                @else
                                    <div class="form-group mt-3">
                                        {{--                                        <a href="javascript:;" data-toggle="modal" class="btn btn-primary btn-sm" data-target="#assign-bed-emergency">Transfer</a>--}}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="profile-detail">
                                <div class="profile-form form-group form-row align-items-center">
                                    <input type="hidden" id="user_billing_mode"
                                           value="@if(isset($enpatient) && isset($enpatient->fldbillingmode) ) {{$enpatient->fldbillingmode}} @endif"
                                           disabled>

                                    @if($route == 'admin/laboratory/addition')
                                        <label for="" class="control-label col-sm-3 mb-0">Billing:</label>
                                        <input type="text" name="billingmode" class="form-input yellow"
                                               value="@if(isset($enpatient) && isset($enpatient->fldbillingmode) ) {{$enpatient->fldbillingmode}} @endif"
                                               readonly>
                                    @else
                                        @if(isset($billingset))
                                            <label for="" class="control-label col-sm-3 mb-0">Billing:</label>
                                            <div class="col-sm-9">
                                                <select name="billingmode" id="billingmode" class="form-control"
                                                        url="{{route('save_billingmode')}}" disabled>
                                                    <option value=""></option>
                                                    @foreach($billingset as $b)

                                                        <option value="{{$b->fldsetname}}"
                                                                @if(isset($enpatient) && ($enpatient->fldbillingmode === $b->fldsetname)) selected="selected" @endif >{{$b->fldsetname}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        @endif
                                    @endif
                                </div>

                                <div class="profile-form form-group form-row align-items-center">
                                    @if(Route::is('billing.display.form') || Route::is('dispensingForm'))
                                        @php
                                            $discounts = Helpers::getDiscounts(isset($enpatient)? $enpatient->fldbillingmode : null);


                                        @endphp
                                        @if(Route::is('dispensingForm'))
                                            @if(isset($patDosingData) and count($patDosingData) > 0)
                                        
                                                @php
                                                    $discountMode = $patDosingData[0]->discountmode;
                                                @endphp
                                            @else
                                                @php
                                                    if ($discountMode == ""){
                                                        $discountMode = isset($enpatient)?$enpatient->flddisctype:"";
                                                    }
                                                @endphp
                                            @endif
                                            <label for="" class="col-sm-3 mb-0">Discount</label>
                                            <div class="col-sm-9">
                                                <select name="discount_scheme" id="discount-scheme-change"
                                                        class="form-control js-registration-discount-scheme col-sm-9 mb-0" {{ (isset($countPatbillData) and (int)$countPatbillData) > 0 ? 'disabled' : ''}}>
                                                    <option value="">--Select--</option>
                                                     @foreach($discounts as $discount)
                                                        <option value="{{ $discount->fldtype }}" {{ (isset($enpatient) && $discountMode === $discount->fldtype) ? 'selected' : '' }} >{{ $discount->fldtype }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        @if(Route::is('billing.display.form'))
                                            @if(isset($patbillingdata) and count($patbillingdata) > 0)
                                                @php
                                                    $discountMode = $patbillingdata[0]->discount_mode;
                                                    
                                                @endphp
                                            @else
                                                @php
                                                    if ($discountMode == ""){
                                                        $discountMode = isset($enpatient)?$enpatient->flddisctype:"";
                                                    }
                                                @endphp
                                            @endif
                                            <label for="" class="col-sm-3 mb-0">Discount</label>
                                            <div class="col-sm-9">
                                                <select name="discount_scheme" id="discount-scheme-change"
                                                        class="form-control js-registration-discount-scheme col-sm-9 mb-0" >
                                                    <option value="">--Select--</option>
                                                     @foreach($discounts as $discount)
                                                        <option value="{{ $discount->fldtype }}" {{ (isset($enpatient) && $discountMode === $discount->fldtype) ? 'selected' : '' }} >{{ $discount->fldtype }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                    @endif
                                </div>
                              
                                @if(isset($enpatient->fldclaimcode) && $enpatient->fldclaimcode)
                                    Claim Code: <span id="nhsiid">@if(isset($enpatient->fldclaimcode)) {{$enpatient->fldclaimcode}}@endif</span>
                                    @endif
                       

                            @if($route == 'admin/laboratory/addition')
                            @else
                                <!--                                    <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" value="Inside" class="custom-control-input" id="fldinside" @if(isset($enpatient) && ($enpatient->fldinside == '1')) checked @endif name="fldinside" disabled=""/>
                                        <label class="custom-control-label">Patient Inside</label>
                                    </div>-->
                            @endif

                            <!-- <div class="form-group  mt-2">
                                <label class="control-label">Billing Mode</label>
                                <select class="form-control" id="js-dispensing-billingmode-select">
                                        @if(isset($enpatient))
                                @if($enpatient->fldbillingmode == 'HealthInsuranceProvider')
                                    <option value="General">General</option>
                                    <option value="HealthInsuranceProvider" selected>HealthInsuranceProvider</option>
@else
                                    <option value="General" selected>General</option>
@endif
                            @endif
                                </select>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function changeDiscountModePatient() {
        discountMode = $("#discount-scheme-change").val();
        encounterId = $("#encounter_id").val();
        $.ajax({
            url: "{{ route('billing.display.change.discount.mode') }}",
            type: "POST",
            data: {
                encounter_id: encounterId,
                discountMode: discountMode
            },
            success: function (data) {
                // console.log(data);
                if (data.status) {
                    showAlert('Discount mode changed.');
                } else {
                    showAlert("{{ __('messages.error') }}", 'error');
                }

            }
        });
    }

    $(document).ready(function () {
        setTimeout(function () {
            $("#discount-scheme-change").select2();
            $('')
        }, 1000);
        $("#billingmode").change(function () {

            var encounter_id = $('#encounter_id').val();

            var billingmode = $('#billingmode option:selected').text();

            var url = $(this).attr("url");
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    billingmode: billingmode,
                    encounter_id: encounter_id
                },
                success: function (data) {
                    // console.log(data);
                    if ($.isEmptyObject(data.error)) {
                        location.reload(true);
                        showAlert('Information Saved !')
                    } else {
                        showAlert("Something went wrong!!", 'error');
                    }
                }
            });
        });
    });
</script>
