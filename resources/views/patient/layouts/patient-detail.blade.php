@php
    $patientData = Helpers::getPatientDetails();
@endphp
<form class="form-horizontal" style="background-color: #ceebee">
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group form-row">
                <label class="col-lg-3 col-sm-4">Name:</label>
                <div class="col-lg-9 col-sm-8">
                    <label>{{ $patientData->fullname }}</label>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group form-row">
                <label class="col-lg-5 col-sm-7">Gender:</label>
                <div class="col-lg-7 col-sm-5">
                    <label>{{ $patientData->fldptsex }}</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-row">
                <label class="col-lg-4 col-sm-5">Religion:</label>
                <div class="col-lg-8 col-sm-7">
                    <label>{{ $patientData->fldethnicgroup }}</label>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group form-row">
                <label class="col-lg-3 col-sm-4">MRN:</label>
                <div class="col-lg-9 col-sm-8">
                    <label>123456758</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3 col-lg-2 pr-0">
            <label>Age: {{ $patientData->fldagestyle }} / {{ \Carbon\Carbon::parse($patientData->fldptbirday)->format('Y-m-d') }}</label>
            {{-- <label>Age: {{ \Carbon\Carbon::parse($patientData->fldptbirday)->age }}Yr/ {{ \Carbon\Carbon::parse($patientData->fldptbirday)->format('Y-m-d') }}</label> --}}
        </div>

        <div class="col-sm-3">
            <div class="form-group form-row">
                <label class="col-lg-4 col-sm-6">Nationality:</label>
                <div class="col-lg-8 col-sm-6">
                    <label>{{ $patientData->fldcountry }}</label>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group form-row">
                <label class="col-lg-7 col-sm-10">Blood-Grp:</label>
                <div class="col-lg-5 col-sm-2">
                    <label>{{ $patientData->fldbloodgroup }}</label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-row">
                <label class="col-lg-4 col-sm-6">Mobile no:</label>
                <div class="col-lg-8 col-sm-6">
                    <label>{{ $patientData->fldptcontact }}</label>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group form-row">
                <label class="col-lg-4 col-sm-6">Nationality no:</label>
                <div class="col-lg-8 col-sm-6">
                    <label>{{ $patientData->fldnationalid }}</label>
                </div>
            </div>
        </div>
    </div>
</form>
