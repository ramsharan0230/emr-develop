<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-6">
                    <input type="hidden" id="fldencounterval" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif "/>
                    <input type="hidden" id="flduserid" class="current_user" value="{{Helpers::getCurrentUserName()}}"/>
                    <input type="hidden" id="fldcomp" value="{{ Helpers::getCompName() }}">
                    <input type="hidden" name="req_segment" id="req_segment" value="{{$segment}}">
                    <form action="{{ route('extra.receipt.index') }}">
                        <div class="form-group row mb-0 align-items-center">
                            <label for="" class="control-label col-sm-3 mb-0">Encounter ID</label>
                            <div class="col-sm-6">
                                <input type="text" name="encounter_id" class="form-control" placeholder="Enter Encounter ID"/>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary">Submit <i class="ri-arrow-right-line"></i></button>
                            </div>
                        </div>
                    </form>

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
                                <input size="1" type="hidden" name="encounter_id" id="encounter_id" placeholder="" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="profile-detail">
                                <p>Age/Sex: @if(isset($patient)) {{$patient->fldagestyle}} @endif
                                    {{-- @if(isset($years) && $years == 'Years')
                                        @if(isset($patient)    ){{ \Carbon\Carbon::parse($patient->fldptbirday)->age }} Years @endif
                                    @endif
                                    @if(isset($years) && $years == 'Months')
                                        @if(isset($patient)){{ \Carbon\Carbon::parse($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%m') }}Months @endif
                                    @endif
                                    @if(isset($years) && $years == 'Days')
                                        @if(isset($patient)){{ \Carbon\Carbon::parse($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%d') }} Days @endif
                                    @endif
                                    @if(isset($years) && $years == 'Hours')
                                        @if(isset($patient)){{ \Carbon\Carbon::parse($patient->fldptbirday)->diff(\Carbon\Carbon::now())->format('%H') }} Hours @endif
                                    @endif --}}
                                    / <span id="js-inpatient-gender-input">@if(isset($patient)){{ $patient->fldptsex }}@endif</span></p>
                                <p>Address: @if(isset($patient)){{ $patient->fldptaddvill }} , {{ $patient->fldptadddist }}@endif</p>
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
                                <p>DOReg: <span id="js-inpatient-dor-input">@if(isset($enpatient)){{ $enpatient->fldregdate }}@endif</span></p>
                                <p>Location: <span id="get_related_fldcurrlocat">@if(isset($enpatient)){{ $enpatient->fldcurrlocat }}@endif @if(isset($enbed))  /  {{ $enbed->fldbed }}@endif</span></p>


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
                                    <input type="hidden" id="user_billing_mode" value="{{(isset($enpatient) && isset($enpatient->fldbillingmode) ) ?$enpatient->fldbillingmode:''}}" disabled>


                                    @if($route == 'admin/laboratory/addition')
                                        <label for="" class="control-label col-sm-3 mb-0">Billing:</label>
                                        <input type="text" name="billingmode" class="form-input yellow" value="{{ (isset($enpatient) && isset($enpatient->fldbillingmode) )?$enpatient->fldbillingmode:''}}" readonly>
                                    @else
                                        @if(isset($billingset))
                                            <label for="" class="control-label col-sm-3 mb-0">Billing:</label>
                                            <div class="col-sm-9">
                                                <select name="billingmode" id="billingmode" class="form-control" url="{{route('save_billingmode')}}" disabled>
                                                    <option value=""></option>
                                                    @foreach($billingset as $b)

                                                        <option value="{{$b->fldsetname}}" @if(isset($enpatient) && ($enpatient->fldbillingmode ==$b->fldsetname) ) selected="selected" @endif >{{$b->fldsetname}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        @endif
                                    @endif
                                </div>
                                @if($route == 'admin/laboratory/addition')
                                @else
                                    <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" value="Inside" class="custom-control-input" id="fldinside" @if(isset($enpatient) && ($enpatient->fldinside == '1')) checked @endif name="fldinside" {{--url="{{route('inside')}}"--}} disabled=""/>
                                        <label class="custom-control-label">Patient Inside</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
