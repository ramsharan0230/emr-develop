@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

    <section class="cogent-nav">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#patient" role="tab" aria-controls="home" aria-selected="true">Patient</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#usersignature" role="tab">User Signature</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#opd-settings" role="tab">OPD Settings</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="patient" role="tabpanel" aria-labelledby="home-tab">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Patient Settings</h4>
                        </div>
                    </div>
                    <form action="{{ route('setting.opd.report.data.dynamic.save') }}" method="post">
                        @csrf
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-row">
                                    <div class="col-3" style="flex: 0 0 23%;">
                                        <label>Name:</label>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" name="Patient_Info_Display">
                                            <option value="">Select</option>
                                            <option value="FirstName+SurName">FirstName+SurName</option>
                                            <option value="FirstNameOnly">FirstName Only</option>
                                            <option value="***SurName">***SurName</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-3" style="flex: 0 0 23%;">
                                        <label>Date:</label>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" name="Date_Format">
                                            <option value="">Select</option>
                                            <option value="ShortDate">ShortDate</option>
                                            <option value="MediumDate">MediumDate</option>
                                            <option value="LongDate">LongDate</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-row">
                                    <div class="col-3" style="flex: 0 0 23%;">
                                        <label>Age:</label>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" name="Age_Format">
                                            <option value="">Select</option>
                                            <option value="Numerical">Numerical</option>
                                            <option value="AgeInYears">AgeInYears</option>
                                            <option value="YearMonth">YearMonth</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-3" style="flex: 0 0 23%;">
                                        <label>Calender:</label>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" name="Date_AD_BS">
                                            <option value="">Select</option>
                                            <option value="AD">AD Date</option>
                                            <option value="BS">BS Date</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-row">
                                    <div class="col-3" style="flex: 0 0 28%;">
                                        <label>Flag Normal:</label>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" name="Flag_text_style">
                                            <option value="">Select</option>
                                            <option value="Bold">Bold</option>
                                            <option value="Italic">Italic</option>
                                            <option value="UnderLine">UnderLine</option>
                                            <option value="Bold+UnderLine">Bold+UnderLine</option>
                                            <option value="Bold+Italic">Bold+Italic</option>
                                            <option value="Red Color">Red Color</option>
                                            <option value="Superscript">Superscript</option>
                                            <option value="None">None</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>

                </div>
            </div>
            <div class="tab-pane" id="usersignature" role="tabpanel">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Patient Settings</h4>
                        </div>
                    </div>
                    <form action="{{ route('setting.opd.report.data.dynamic.save') }}" method="post">
                        @csrf
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-row">
                                    <div class="col-3">
                                        <label>Left col:</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="" name="" class="form-control">

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-3">
                                        <label>Mid col:</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="" name="" class="form-control">

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-3">
                                        <label>Show Popup:</label>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" name="sampleauto">
                                            <option value="">Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-row">
                                    <div class="col-3">
                                        <label>Right col:</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="" name="" class="form-control">

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-3">
                                        <label>Sign Size Wild:</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="" name="" class="form-control">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-row">
                                    <div class="col-3">
                                        <label>HT:</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="" name="" class="form-control">

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-3">
                                        <label>Active col:</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="" name="" class="form-control">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
            <div class="tab-pane" id="opd-settings" role="tabpanel">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Patient Settings</h4>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-row">
                                <form action="{{ route('setting.opd.report.save') }}" method="post">
                                    @csrf
                                    <div class="col-3">
                                        <label>OPD Options:</label>
                                    </div>
                                    @php
                                        $OPDOptions = Options::get('opd_pdf_options') ? unserialize(Options::get('opd_pdf_options')) : [];
                                    @endphp
                                    <div class="col-12 row">
                                        <div class="col-3">
                                            <label for="content_1">
                                                <input type="checkbox" id="content_1" name="content_1" value="Advice on Discharge" {{ array_key_exists('content_1', $OPDOptions)?'checked':'' }}>
                                                content_1 - Advice on Discharge
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_2">
                                                <input type="checkbox" id="content_2" name="content_2" {{ array_key_exists('content_2', $OPDOptions)?'checked':'' }} value="Bed Transitions">
                                                content_2 - Bed Transitions
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_3">
                                                <input type="checkbox" id="content_3" name="content_3" {{ array_key_exists('content_3', $OPDOptions)?'checked':'' }} value="Cause of Admission">
                                                content_3 - Cause of Admission
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_4">
                                                <input type="checkbox" id="content_4" name="content_4" {{ array_key_exists('content_4', $OPDOptions)?'checked':'' }} value="Clinical Findings">
                                                content_4 - Clinical Findings
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_5">
                                                <input type="checkbox" id="content_5" name="content_5" {{ array_key_exists('content_5', $OPDOptions)?'checked':'' }} value="Clinical Notes">
                                                content_5 - Clinical Notes
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_12">
                                                <input type="checkbox" id="content_12" name="content_12" {{ array_key_exists('content_12', $OPDOptions)?'checked':'' }} value="Condition at Discharge">
                                                content_12 - Condition at Discharge
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_14">
                                                <input type="checkbox" id="content_14" name="content_14" {{ array_key_exists('content_14', $OPDOptions)?'checked':'' }} value="Consultations">
                                                content_14 - Consultations
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_15">
                                                <input type="checkbox" id="content_15" name="content_15" {{ array_key_exists('content_15', $OPDOptions)?'checked':'' }} value="Course of Treatment">
                                                content_15 - Course of Treatment
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_16">
                                                <input type="checkbox" id="content_16" name="content_16" {{ array_key_exists('content_16', $OPDOptions)?'checked':'' }} value="Delivery Profile">
                                                content_16 - Delivery Profile
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_17">
                                                <input type="checkbox" id="content_17" name="content_17" {{ array_key_exists('content_17', $OPDOptions)?'checked':'' }} value="Demographics">
                                                content_17 - Demographics
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_18">
                                                <input type="checkbox" id="content_18" name="content_18" {{ array_key_exists('content_18', $OPDOptions)?'checked':'' }} value="Discharge examinations">
                                                content_18 - Discharge examinations
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_19">
                                                <input type="checkbox" id="content_19" name="content_19" {{ array_key_exists('content_19', $OPDOptions)?'checked':'' }} value="Discharge Medication">
                                                content_19 - Discharge Medication
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_20">
                                                <input type="checkbox" id="content_20" name="content_20" {{ array_key_exists('content_20', $OPDOptions)?'checked':'' }} value="Drug Allergy">
                                                content_20 - Drug Allergy
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_21">
                                                <input type="checkbox" id="content_21" name="content_21" {{ array_key_exists('content_21', $OPDOptions)?'checked':'' }} value="Equipments Used">
                                                content_21 - Equipments Used
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_22">
                                                <input type="checkbox" id="content_22" name="content_22" {{ array_key_exists('content_22', $OPDOptions)?'checked':'' }} value="Essential examinations">
                                                content_22 - Essential examinations
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_23">
                                                <input type="checkbox" id="content_23" name="content_23" {{ array_key_exists('content_23', $OPDOptions)?'checked':'' }} value="Extra Procedures">
                                                content_23 - Extra Procedures
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_24">
                                                <input type="checkbox" id="content_24" name="content_24" {{ array_key_exists('content_24', $OPDOptions)?'checked':'' }} value="Final Diagnosis">
                                                content_24 - Final Diagnosis
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_25">
                                                <input type="checkbox" id="content_25" name="content_25" {{ array_key_exists('content_25', $OPDOptions)?'checked':'' }} value="IP Monitoring">
                                                content_25 - IP Monitoring
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_26">
                                                <input type="checkbox" id="content_26" name="content_26" {{ array_key_exists('content_26', $OPDOptions)?'checked':'' }} value="Initial Planning">
                                                content_26 - Initial Planning
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_27">
                                                <input type="checkbox" id="content_27" name="content_27" {{ array_key_exists('content_27', $OPDOptions)?'checked':'' }} value="Investigation Advised">
                                                content_27 - Investigation Advised
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_28">
                                                <input type="checkbox" id="content_28" name="content_28" {{ array_key_exists('content_28', $OPDOptions)?'checked':'' }} value="Laboratory Tests">
                                                content_28 - Laboratory Tests
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_29">
                                                <input type="checkbox" id="content_29" name="content_29" {{ array_key_exists('content_29', $OPDOptions)?'checked':'' }} value="Major Procedures">
                                                content_29 - Major Procedures
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_30">
                                                <input type="checkbox" id="content_30" name="content_30" {{ array_key_exists('content_30', $OPDOptions)?'checked':'' }} value="Medication History">
                                                content_30 - Medication History
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_31">
                                                <input type="checkbox" id="content_31" name="content_31" {{ array_key_exists('content_31', $OPDOptions)?'checked':'' }} value="Medication Used">
                                                content_31 - Medication Used
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_32">
                                                <input type="checkbox" id="content_32" name="content_32" {{ array_key_exists('content_32', $OPDOptions)?'checked':'' }} value="Minor Procedures">
                                                content_32 - Minor Procedures
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="Name">
                                                <input type="checkbox" id="Name" name="Name" {{ array_key_exists('Name', $OPDOptions)?'checked':'' }} value="OPD Sheet">
                                                Name - OPD Sheet
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_34">
                                                <input type="checkbox" id="content_34" name="content_34" {{ array_key_exists('content_34', $OPDOptions)?'checked':'' }} value="Occupational History">
                                                content_34 - Occupational History
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_35">
                                                <input type="checkbox" id="content_35" name="content_35" {{ array_key_exists('content_35', $OPDOptions)?'checked':'' }} value="Personal History">
                                                content_35 - Personal History
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_36">
                                                <input type="checkbox" id="content_36" name="content_36" {{ array_key_exists('content_36', $OPDOptions)?'checked':'' }} value="Planned Procedures">
                                                content_36 - Planned Procedures
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_37">
                                                <input type="checkbox" id="content_37" name="content_37" {{ array_key_exists('content_37', $OPDOptions)?'checked':'' }} value="Prominent Symptoms">
                                                content_37 - Prominent Symptoms
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_38">
                                                <input type="checkbox" id="content_38" name="content_38" {{ array_key_exists('content_38', $OPDOptions)?'checked':'' }} value="Provisional Diagnosis">
                                                content_38 - Provisional Diagnosis
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_39">
                                                <input type="checkbox" id="content_39" name="content_39" {{ array_key_exists('content_39', $OPDOptions)?'checked':'' }} value="Radiological Findings">
                                                content_39 - Radiological Findings
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_40">
                                                <input type="checkbox" id="content_40" name="content_40" {{ array_key_exists('content_40', $OPDOptions)?'checked':'' }} value="Social History">
                                                content_40 - Social History
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_41">
                                                <input type="checkbox" id="content_41" name="content_41" {{ array_key_exists('content_41', $OPDOptions)?'checked':'' }} value="Structured examinations">
                                                content_41 - Structured examinations
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_42">
                                                <input type="checkbox" id="content_42" name="content_42" {{ array_key_exists('content_42', $OPDOptions)?'checked':'' }} value="Surgical History">
                                                content_42 - Surgical History
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_43">
                                                <input type="checkbox" id="content_43" name="content_43" {{ array_key_exists('content_43', $OPDOptions)?'checked':'' }} value="Therapeutic Planning">
                                                content_43 - Therapeutic Planning
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_44">
                                                <input type="checkbox" id="content_44" name="content_44" {{ array_key_exists('content_44', $OPDOptions)?'checked':'' }} value="Treatment Advised">
                                                content_44 - Treatment Advised
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="content_33">
                                                <input type="checkbox" id="content_33" name="content_33" {{ array_key_exists('content_33', $OPDOptions)?'checked':'' }} value="Triage examinations">
                                                content_33 - Triage examinations
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="HeaderType">
                                                <input type="checkbox" id="HeaderType" name="HeaderType" {{ array_key_exists('HeaderType', $OPDOptions)?'checked':'' }} value="True">
                                                HeaderType - True
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="BodyType">
                                                <input type="checkbox" id="BodyType" name="BodyType" {{ array_key_exists('BodyType', $OPDOptions)?'checked':'' }} value="True">
                                                BodyType - True
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label for="FooterType">
                                                <input type="checkbox" id="FooterType" name="FooterType" {{ array_key_exists('FooterType', $OPDOptions)?'checked':'' }} value="True">
                                                FooterType - True
                                            </label>
                                        </div>
                                    </div>
                                    <div class="">
                                        <input type="submit" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@stop
