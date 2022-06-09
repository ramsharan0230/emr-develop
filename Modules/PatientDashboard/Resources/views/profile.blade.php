@extends('patient.layouts.master')

@section('content')
<div class="main-container">
    <div class="main-content">
        <!-- <div class="nav_sec">

        </div> -->
        <div class="row">
            <div class="col-md-12">
                <div class="topspce">
                    {{--<div class="row">
                        <div class="col-md-4">
                            <h4 class="pages_title">Laboratory Reports</h4>
                        </div>
                    </div>--}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mainContainer">
                                <div class="user_bg"></div>
                                <div class="user_profile">
                                    <img class="rounded-circle profile-pic" src="{{ asset('images/sanjeet.jpg') }}" alt="User Avatar" width="110">
                                </div>
                                <div class="user_name">
                                    <h4>General Patient</h4>
                                </div>
                                <div class="card-view">

                                    <div>
                                        {{--<button type="button"
                                                class="mb-2 btn btn-sm btn-pill btn-outline-primary edit-details"
                                                style="">
                                            <i class="ri-user-add-fill pic-icon"></i>
                                            Upload</button>--}}
                                    </div>
                                </div>
                                <hr>
                                <div class="nums">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <h3 class="patient_id_num">
                                                    {{ $patientData->patientInfo->fldpatientval }}
                                                </h3>
                                                <span>Patient ID</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center borderLeft">
                                                <h3 class="patient_id_num">
                                                    {{ $patientData->fldencounterval }}
                                                </h3>
                                                <span>ENC ID</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mainContainer">
                                <div class="header_card">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Basic Details</h4>
                                    </div>
                                </div>
                                <div class="main_body">
                                    <div class="row">
                                        <div class="col-5">Full Name:</div>
                                        <div class="col-7">{{ $patientData->patientInfo->fullname }}</div>
                                        <div class="col-5">Gender:</div>
                                        <div class="col-7">{{ $patientData->patientInfo->fldptsex }}</div>
                                        <div class="col-5">Age:</div>
                                        <div class="col-7">{{ $patientData->patientInfo->fldagestyle }}</div>
                                        {{-- <div class="col-7">{{ \Carbon\Carbon::parse($patientData->patientInfo->fldptbirday)->age }}</div> --}}
                                        <div class="col-5">Nationality:</div>
                                        <div class="col-7">{{ $patientData->patientInfo->fldcountry }}</div>
                                        <div class="col-5">Blood Group:</div>
                                        <div class="col-7">{{ $patientData->patientInfo->fldbloodgroup }}</div>
                                        <div class="col-5">Mobile Number:</div>
                                        <div class="col-7"><b><a href="tel:001-2351-25612" style="color: #5a6169;">{{ $patientData->patientInfo->fldptcontact }}</a></b></div>
                                        <div class="col-5">Location:</div>
                                        <div class="col-7">USA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mainContainer patient_information">
                                <div class="header_card">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Patient
                                            Information</h4>
                                    </div>
                                </div>
                                <div class="wrapper_details">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-12 b-r">
                                            <div class="form-group">
                                                <label>Height</label>
                                                <p class="viewInfo">{{ $height }} {{ $heightrate }}</p>

                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12 b-r">
                                            <div class="form-group">
                                                <label>Weight</label>
                                                <p class="viewInfo">76 kg</p>
                                                <!-- <p class="viewInfo">{{ $body_weight }} Kg</p> -->
                                            </div>
                                        </div>
                                        <hr class="hide_mv">
                                        <div class="col-md-3 col-sm-6 col-12 b-r">
                                            <div class="form-group">
                                                <label>BMI</label>
                                                <p class="viewInfo">{{ $bmi }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Consult</label>
                                                <p class="viewInfo">{{ $patientData->patientInfo->flduserid }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="hide_mv">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-12 b-r">
                                            <div class="form-group">
                                                <label>DoReg</label>
                                                <p class="viewInfo">{{ $patientData->patientInfo->fldregdate }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12 b-r">
                                            <div class="form-group">
                                                <label>Location</label>
                                                <p class="viewInfo">{{ $patientData->patientInfo->fldptaddvill .', '.  $patientData->patientInfo->fldptadddist }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12 b-r">
                                            <div class="form-group">
                                                <label>Billing</label>
                                                <p class="viewInfo">{{ $patientData->patientInfo->fldbillingmode }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <p class="viewInfo">{{ $patientData->patientInfo->fldadmission }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="fldinside" @if(isset($enpatient) && ($enpatient->fldinside == '1')) checked @endif/>
                                                <label class="fldinside custom-control-label" for="customCheck1">
                                                    Patient Inside</label>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <div class="mainContainer patient_information">
                                <div class="header_card">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Vital Exam</h4>
                                    </div>
                                </div>
                                <div class="wrapper_details">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-12  b-r">
                                            <div class="form-group">
                                                <label>Pulse Rate</label>
                                                <p class="viewInfo">{{ $exams->where('fldhead', 'Pulse Rate')->first()->fldrepquanti??'' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12  b-r">
                                            <div class="form-group">
                                                <label>Syst BP</label>
                                                <p class="viewInfo">{{ $exams->where('fldhead', 'Systolic BP')->first()->fldrepquanti??'' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12  b-r">
                                            <div class="form-group">
                                                <label>Diast BP</label>
                                                <p class="viewInfo">{{ $exams->where('fldhead', 'Diastolic BP')->first()->fldrepquanti??'' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Resp BP</label>
                                                <p class="viewInfo">{{ $exams->where('fldhead', 'Respiratory Rate')->first()->fldrepquanti??'' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="hide_mv">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-12  b-r">
                                            <div class="form-group">
                                                <label>S P O2</label>
                                                <p class="viewInfo">{{ $exams->where('fldhead', 'O2 Saturation')->first()->fldrepquanti??'' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Temp</label>
                                                <p class="viewInfo">{{ $exams->where('fldhead', 'Temperature (F)')->first()->fldrepquanti??'' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('after-script')

@endpush
