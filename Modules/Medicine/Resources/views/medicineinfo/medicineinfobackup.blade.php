@extends('frontend.layouts.master')
<style type="text/css">
    /*style.css*/

    body {
        margin: 0;
        padding: 0;
        font-family: "Ubuntu", sans-serif;
        font-size: 16px;
        color: #181818;
        background-color: #efebe7;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
        margin: 0;
    }

    ol,
    ul {
        margin: 0;
        padding: 0;
    }

    a {
        color: #181818;
    }

    a:hover {
        color: #181818;
    }
    /* ===== Navigation Tabs ===== */

    .cogent-nav .nav-tabs .nav-link.active {
        color: #181818;
        background-color: #efebe7;
        border-color: #a7a5a3 #a7a5a3 transparent;
        border-radius: 0;
        font-weight: 500;
    }

    .cogent-nav .nav-tabs {
        border-bottom: 1px solid #a7a5a3;
    }

    .cogent-nav .tab-content .navbar {
        padding: 0 0.5rem;
        border-bottom: 1px solid #d6d3cf;
    }

    .cogent-nav .tab-content .nav-link {
        padding: 0.2rem 0.5rem;
    }

    .patient-head {
        display: flex;
    }

    .patient-head span {
        display: inline-block;
        background-color: blue;
        width: 85%;
        margin-left: 10px;
        height: 16px;
        margin-top: 6px;
    }

    .profile-form {
        background-color: #e9e5e1;
        border: 1px solid #a7a5a3;
        border-radius: 3px;
        padding: 5px 0;
    }

    .form-group {
        margin-bottom: 5px;
        display: flex;
    }

    .form-input {
        height: 26px;
        border-radius: 0;
        border: 1px solid #a7a5a3;
        padding: 5px;
        width: 350px;
        vertical-align: middle;
    }

    .parent-row.row {
        margin-left: 0;
    }

    .form-group label {
        border: 1px solid #a7a5a3;
        padding: 0;
        width: 110px;
        margin-right: 10px;
        margin-bottom: 0;
    }

    .prof-img img,
    .form-group-inner img {
        width: 22px;
        margin-left: 5px;
    }

    .form-control.name {
        font-weight: 600;
    }

    .form-group-inner.low label {
        width: 40px;
    }

    .form-group-inner input {
        width: 230px;
    }

    .form-group-inner.low {
        margin-left: 20px;
    }

    .form-group-inner.low input {
        width: 100px;
    }

    .form-group-inner.custom input {
        width: 100px;
    }

    .form-group-inner.low img {
        width: 24px;
        border: 1px solid;
        padding: 4px;
        background: #fff;
    }

    .input-group-text {
        background-color: #fff;
        border-radius: 0;
        padding: 0 3px;
        border-color: #a7a5a3;
        border-left: none;
    }

    .input-group-text img {
        width: 18px;
    }

    .form-group-inner.custom-1 label {
        width: 90px;
    }

    .form-group-inner.custom-1 {
        display: flex;
    }

    .form-group-inner.custom-1 .input-group {
        width: auto;
    }

    .form-group-inner.custom-1 input {
        width: 120px;
    }

    .form-group-inner.custom-2 label {
        width: 85px;
    }

    .form-group-inner.custom-2 input {
        width: 120px;
    }

    .form-group-inner.custom-1 img {
        margin-left: 0;
        margin-right: 5px;
    }

    .form-group-inner.custom-3 {
        display: flex;
    }

    .form-group-inner.custom-3 label {
        width: 90px;
    }

    .form-group-inner.custom-3 input {
        width: 168px;
        margin-right: 8px;
    }

    .form-group-inner.custom-2 {
        display: flex;
        flex-wrap: wrap;
    }

    .form-group-inner.custom-2 img {
        width: 20px;
        height: 20px;
    }

    .form-group-inner.custom-4 label {
        width: 90px;
    }

    .form-group-inner.custom-4 input {
        width: 75px;
    }

    .form-group-inner.custom-4 img {
        width: 20px;
        margin-right: 11px;
    }

    .yellow {
        background-color: #ffff9f;
    }

    .parent-row.row [class*="col-"] {
        padding: 0 10px;
    }

    .form-group-inner.custom-5 label {
        width: 220px;
        margin-right: 55px;
    }

    .custom-font {
        font-family: "Orbitron", sans-serif;
        text-align: center;
    }

    .form-group-inner.custom-6 p {
        display: inline-block;
        margin-right: 50px;
    }

    .form-group-inner.custom-6 label {
        width: 140px;
    }

    .form-group-inner.custom-6 input {
        width: auto;
        vertical-align: middle;
    }

    .form-group-inner.custom-6 .chekbox-label {
        width: auto;
        border: none;
    }

    .form-group-inner.custom-6 img {
        width: 16px;
    }

    select.form-input {
        padding: 0;
        width: auto;
    }

    .form-group-inner.custom-7 .select-1 {
        width: 250px;
    }

    .form-group-inner.custom-7 input {
        width: 50px;
    }

    .form-group-inner.custom-7 .select-2 {
        width: 60px;
    }

    .form-group-inner.custom-7 .select-3 {
        width: 115px;
    }

    .form-group-inner.custom-7 button {
        border: 1px solid #a7a5a3;
        text-align: center;
        width: 38px;
        height: 25px;
        border-radius: 3px;
    }

    .form-group-inner.custom-7 button img {
        width: 15px;
        margin-left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .res-table {
        width: 100%;
        height: 205px;
        overflow: auto;
        background-color: #fff;
        border: 1px solid #a7a5a3;
    }

    .table-1,
    .table-2 {
        width: 700px;
        background-color: #fff;
    }

    .table-1 th,
    .table-1 td,
    .table-2 th,
    .table-2 td {
        vertical-align: top;
        padding: 0 5px;
        border: 1px solid #a7a5a3;
    }

    .table-1 img,
    .table-2 img {
        width: 16px;
    }

    .table-1 tr th,
    .table-1 tr td:first-child,
    .table-2 tr th,
    .table-2 tr td:first-child {
        background-color: #efebe7;
    }

    .table-1 tr td:nth-child(1),
    .table-2 tr td:nth-child(1) {
        width: 20px;
    }

    .table-1 tr td:nth-child(2) {
        width: 210px;
    }

    .table-1 tr td:nth-child(3),
    .table-1 tr td:nth-child(4) {
        width: 75px;
    }

    .table-1 tr td:nth-child(5),
    .table-1 tr td:nth-child(6) {
        width: 25px;
    }

    .table-1 tr td:nth-child(7) {
        width: 150px;
    }

    .table-1 tr td:nth-child(8) {
        width: 70px;
    }

    .co-rate {
        display: flex;
        flex-wrap: wrap;
    }

    .pulse {
        border: 1px solid #a7a5a3;
        border-radius: 3px;
        padding: 3px;
        width: 36.111%;
    }

    .form-group-inner.custom-8 label {
        width: 90px;
    }

    .form-group-inner.custom-8 img {
        width: 24px;
        border: 1px solid #a7a5a3;
        padding: 3px;
        background: #fff;
    }

    .form-group-inner.custom-8 select {
        width: 150px;
    }

    .form-group-inner.custom-8 input {
        width: 50px;
    }

    .pulse-nxt {
        width: 63.889%;
    }

    .form-group-inner.custom-9 select {
        width: 100%;
        height: 70px;
    }

    .form-group-inner.custom-9,
    .form-group-inner.custom-11 {
        width: 100%;
    }

    .form-group-inner.custom-10 label {
        width: 140px;
        margin-right: 50px;
    }

    .form-group-inner.custom-10 img {
        width: 16px;
    }

    .form-group-inner.custom-11 select {
        width: 100%;
        height: 85px;
    }

    .form-group-inner.custom-11 select option,
    .form-group-inner.custom-9 select option {
        padding: 4px 0;
    }

    .form-group-inner.custom-12 {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }

    .form-group-inner.custom-12 button {
        border: 1px solid #a7a5a3;
    }

    .form-group-inner.custom-13 label {
        width: 200px;
        margin-right: 50px;
    }

    .form-group-inner.custom-13 .radio-1 {
        border: 1px solid #a7a5a3;
        display: inline-block;
    }

    .form-group-inner.custom-13 .radio-1 input {
        width: auto;
    }

    .form-group-inner.custom-13 .radio-1 label {
        width: auto;
        border: none;
    }

    .form-group-inner.custom-13 img {
        margin-right: 10px;
    }

    .form-group-inner.custom-14 .select-01 {
        width: 250px;
    }

    .form-group-inner.custom-14 .select-02 {
        width: 150px;
    }

    .form-group-inner.custom-14 input {
        width: 80px;
    }

    .form-group-inner.custom-14 button {
        border: 1px solid #a7a5a3;
        text-align: center;
        width: 38px;
        height: 25px;
        border-radius: 3px;
    }

    .form-group-inner.custom-14 button img {
        width: 15px;
        margin-left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .table-2 tr td:nth-child(3) {
        width: 20px;
    }

    .table-2 tr td:nth-child(3) span {
        width: 15px;
        height: 15px;
        display: inline-block;
    }

    .table-2 tr td:nth-child(3) span.green {
        background-color: #90b966;
    }

    .table-2 tr td:nth-child(3) span.red {
        background-color: #d94a44;
    }

    .table-2 tr td:nth-child(2),
    .table-2 tr td:nth-child(4) {
        width: 200px;
    }

    .table-2 tr td:nth-child(5) {
        width: 20px;
    }

    .table-2 tr td:nth-child(6) {
        width: 145px;
    }

    .chief-comp .row {
        margin: 0 -10px;
    }

    .chief-comp .row [class*="col-"] {
        padding: 0 10px;
    }

    .form-group-inner.custom-15 label {
        width: 200px;
    }

    .form-group-inner.custom-15 img {
        width: 18px;
        margin-right: 5px;
    }

    .form-group-inner.custom-15 button {
        border: 1px solid #a7a5a3;
    }

    .tab-1 .tab-content {
        border: 1px solid #a7a5a3;
        padding: 5px 0 0;
        min-height: 245px;
        margin-top: -1px;
    }

    .tab-2 {
        padding: 10px 0;
    }

    .tab-2 button {
        width: 150px;
        margin-bottom: 20px;
        border: 1px solid #a7a5a3;
    }
    /*style.css ends*/
    /*dietary css start*/

    .dietary-table {
        background-color: white;
        width: 100%;
    }

    .dietary-td {
        padding: 1px;
        border: 0px solid #a7a5a3;
    }

    .dietarytable {
        height: 633px;
        width: 100%;
        background-color: white;
    }

    .form-inner {
        width: 100%;
    }

    .form-select-dietary {
        width: 72%;
    }

    .input-small-dietary {
        padding: 0;
        width: 226px;
    }

    .select-2 {
        width: 50%;
    }

    .select-3 {
        width: 226px;
    }

    .label-big {
        width: 100% !important;
        text-align: center;
    }

    .label-big-form {
        width: 98% !important;
        text-align: center;
    }

    .form-input-small {
        width: 30% !important;
    }

    .form-label-small {
        width: 154px !important;
    }

    .padding-none {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .form-input-big {
        width: 590px;
    }

    .form-inner-right {
        width: 100%;
        margin-left: 125px;
    }

    .form-input-mid {
        width: 183px;
    }

    .form-text {
        width: 91%;
        height: 150px;
    }

    .btn-deafult {
        border: 1px solid #b9b9b9 !important;
        background-color: #efeeee !important;
    }
    /*progress bar*/

    .progress {
        height: 5px;
        background: #c3bebe;
        border-radius: 0;
        box-shadow: none;
        margin-bottom: 10px;
    }

    .grey {
        height: 6px !important;
        background-color: #cccaca !important;
        margin-top: 12px;
    }

    .progress .progress-bar-dietary {
        box-shadow: none;
        position: relative;
        -webkit-animation: animate-positive 2s;
        animation: animate-positive 2s;
    }

    .btn-dietary {
        width: 99%;
    }

    .form-group {
        width: 100%;
    }

    .form-inner img {
        width: 16px;
    }
    /*dietary css ends */
</style>
@section('content')

    <section class="cogent-nav">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="outPatient" data-toggle="tab" href="#out-patient" role="tab" aria-controls="home" aria-selected="true"><span></span> Medicine Info</a>
            </li>
        </ul>
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-4">
                    @include('medicine::layouts.includes.medicinelisting')
                </div>

                <div class="col-md-8">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #efebe7;">
                        <li class="nav-item" >
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#strengthandlabeling" role="tab" aria-controls="home" aria-selected="true">Strength and Labelling</a>
                        </li>
                        <li class="nav-item" >
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#brandinformation" role="tab" aria-controls="profile" aria-selected="false">Brand Information</a>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent">

                        {{--Strength and Labelling--}}

                        <div class="tab-pane fade show active" id="strengthandlabeling" role="tabpanel" aria-labelledby="home-tab" style="border:1px solid #a7a5a3;">
                            <div class="container-fluid">
                                <form action="{{ route('medicines.generic.add') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="form-inner">
                                                <label class="form-label">Generic Name</label>
                                                @php $codes = \App\Utils\Medicinehelpers::getAllCodes(); @endphp
                                                <input type="text" name="fldcodename" value="{{ old('fldcodename') }}" class="form-input-big" placeholder="" style="width: 562px !important;" required>

                                                {{--                                    <select name="fldcodename" class="form-select-dietary select2genericname" required>--}}
                                                {{--                                        <option value=""></option>--}}
                                                {{--                                        @forelse($codes as $code)--}}
                                                {{--                                            <option value="{{ $code->fldcodename }}" data-id="{{ $code->fldcodename }}" {{ (old('fldcodename') && old('fldcodename') == $code->fldcodename) ? 'selected' : ''}}>{{ $code->fldcodename }}</option>--}}
                                                {{--                                        @empty--}}
                                                {{--                                        @endforelse--}}
                                                {{--                                    </select>&nbsp;--}}
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#code_modal"><img src="{{asset('assets/images/plus.png')}}"></a>
                                                @include('medicine::layouts.modal.code')
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 padding-none">
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Dosage Form</label>
                                                    <select name="fldrecaddoseunit" >
                                                        <option value=""></option>
                                                        <option value="anal/vaginal" {{ old('fldrecaddoseunit') == 'anal/vaginal' ? 'selected' : '' }}>anal/vaginal</option>
                                                        <option value="eye/ear" {{ old('fldrecaddoseunit') == 'eye/ear' ? 'selected' : '' }}>eye/ear</option>
                                                        <option value="fluid" {{ old('fldrecaddoseunit') == 'fluid' ? 'selected' : '' }}>fluid</option>
                                                        <option value="injection" {{ old('fldrecaddoseunit') == 'injection' ? 'selected' : '' }}>injection</option>
                                                        <option value="liquid" {{ old('fldrecaddoseunit') == 'liquid' ? 'selected' : '' }}>liquid</option>
                                                        <option value="oral" {{ old('fldrecaddoseunit') == 'oral' ? 'selected' : '' }}>oral</option>
                                                        <option value="resp" {{ old('fldrecaddoseunit') == 'resp' ? 'selected' : '' }}>resp</option>
                                                        <option value="topical" {{ old('fldrecaddoseunit') == 'topical' ? 'selected' : '' }}>topical</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 padding-none">
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 79px;">Strength</label>
                                                    <input type="number" step="any" name="fldrecpeddose" value="{{ old('fldrecpeddose') }}" placeholder="0" style="width: 3em;">
                                                    <input type="text" name="strength" value="" placeholder="" size="14">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 padding-none">
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Min Age (yrs)</label>
                                                    <input type="number" step="any" name="fldrecpeddose" value="{{ old('fldrecpeddose') }}" placeholder="0" style="width: 5em;">
                                                </div>
                                            </div>
                                            <div class="col-md-6 padding-none">
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 79px;">Reference</label>
                                                    <input type="text" name="strength" value="" placeholder="" size="20">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 padding-none">
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Compatability</label>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target=""><img src="{{asset('assets/images/plus.png')}}"></a>
                                                </div>
                                            </div>
                                            <div class="col-md-6 padding-none">
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 79px;"> <i class="fa fa-search"></i> Help</label>
                                                    <input type="text" name="strength" value="" placeholder="" size="20">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 padding-none">
                                                <div class="form-inner">
                                                    <button><img src="{{asset('assets/images/tick.png')}}" width="20px">&nbsp;&nbsp;Add</button>
                                                    <button><img src="{{asset('assets/images/edit.png')}}" width="20px">&nbsp;&nbsp;Edit</button>
                                                </div>
                                            </div>
                                            <div class="col-md-6 padding-none text-center">
                                                <div class="form-inner">
                                                    <button><img src="{{asset('assets/images/delete.png')}}" width="20px">&nbsp;&nbsp;Delete</button>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-5 padding-none">
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 12em">Sub Route</label>
                                                    <select name="fldrecaddoseunit">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 12em">Osmolality(mOsm/L)</label>
                                                    <input type="number" step="any" name="fldrecpeddose" value="{{ old('fldrecpeddose') }}" placeholder="0" style="width: 6em;">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 12em">Final Strength(mg/mL)</label>
                                                    <input type="number" step="any" name="fldrecpeddose" value="{{ old('fldrecpeddose') }}" placeholder="0" style="width: 6em;">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 12em">Energy(KCal/mg)</label>
                                                    <input type="number" step="any" name="fldrecpeddose" value="{{ old('fldrecpeddose') }}" placeholder="0" style="width: 6em;">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 12em">Injection Informations</label>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#med_category_modal"><img src="{{asset('assets/images/plus.png')}}"></a>
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 12em">Med Counseling</label>
                                                </div>
                                            </div>
                                            <div class="col-md-7 padding-none text-center">
                                                <div class="form-inner">
                                                    <nav>
                                                        <div class="nav nav-tabs" id="nav-tab" role="tablist" style="background-color: #efebe7;">
                                                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">OP Label</a>
                                                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">IP Label</a>
                                                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">UD Label</a>
                                                        </div>
                                                    </nav>
                                                    <div class="tab-content" id="nav-tabContent">
                                                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                                            <textarea name="oplabel" id=oplabel" cols="52" rows="4"></textarea>
                                                        </div>
                                                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                                            <textarea name="iplabel" id="iplabel" cols="52" rows="4"></textarea>
                                                        </div>
                                                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                                            <textarea name="udlabel" id="udlabel" cols="52" rows="4"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <textarea name="flddrugdetail" style="min-height: 200px;" cols="100">{!! old('flddrugdetail') !!}</textarea>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 padding-none">
                                            <button><img src="{{asset('assets/images/plus.png')}}" width="16px">&nbsp;&nbsp;Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>

                        {{--Brand Information--}}
                        <div class="tab-pane fade" id="brandinformation" role="tabpanel" aria-labelledby="profile-tab" style="border:1px solid #a7a5a3;">
                            <div class="container-fluid">
                                <form action="{{ route('medicines.generic.add') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-7 padding-none">
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Brand Name</label>
                                                    <input type="text" name="strength" value="" placeholder="" size="20">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Pack Volume</label>
                                                    <input type="number" step="any" name="" value="" placeholder="0" style="width: 3em;">
                                                    <input type="text" name="strength" value="" placeholder="" size="14">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Dosage Form</label>
                                                    @php
                                                        $dosageforms = \App\Utils\Medicinehelpers::getAllDosageForms();
                                                    @endphp
                                                    <select name="fldcategory" class="select2DosageForms">
                                                        <option value=""></option>
                                                        @forelse($dosageforms as $dosageform)
                                                            <option value="{{ $dosageform->flforms }}" data-id="{{ $dosageform->fldid }}" {{ (old('fldcategory') == $dosageform->flforms) ? 'selected' : ''}}>{{ $dosageform->flforms }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>&nbsp;
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#med_category_modal"><img src="{{asset('assets/images/plus.png')}}"></a>
                                                    @include('medicine::layouts.modal.dosageform')
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Standard</label>
                                                    <input type="text" name="strength" value="" placeholder="" size="20">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Manufacturer</label>
                                                    <select name="fldcategory" class="">
                                                        <option value=""></option>
                                                        <option value="">Something here</option>
                                                    </select>&nbsp;
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Preservatives</label>
                                                    <input type="text" name="strength" value="" placeholder="" size="20">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label">Description</label>
                                                    <textarea name="flddrugdetail" style="min-height: 200px;" cols="50">{!! old('flddrugdetail') !!}</textarea>
                                                </div>
                                            </div>


                                            <div class="col-md-5 padding-none">
                                                <div class="form-inner" style="height: 30px;">

                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 149px;">Current Stock</label>
                                                    <input type="text" name="strength" value="" placeholder="" size="10" disabled>
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 149px;">Minimum Stock</label>
                                                    <input type="number" step="any" name="" value="" placeholder="0" min="0" style="width: 8em;">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 149px;">Maximum Stock</label>
                                                    <input type="number" step="any" name="" value="" placeholder="0" min="0" style="width: 8em;">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 149px;">Lead Time (Days)</label>
                                                    <input type="number" step="any" name="" value="" placeholder="0" min="0" style="width: 8em;">
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 149px;">Tax Code</label>
                                                    @php $tax_codes = \App\Utils\Medicinehelpers::getAllTaxGroup() @endphp
                                                    <select name="fldcategory" class="">
                                                        <option value=""></option>
                                                        @forelse($tax_codes as $tax_code)
                                                            <option value="{{ $tax_code->fldgroup }}">{{ $tax_code->fldgroup }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>&nbsp;
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 149px;">Narcotic Dispensing</label>
                                                    <select name="fldcategory" class="">
                                                        <option value=""></option>
                                                        <option value="No">No</option>
                                                        <option value="Yes">Yes</option>
                                                    </select>&nbsp;
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 149px;">Allow Table Break</label>
                                                    <select name="fldcategory" class="">
                                                        <option value=""></option>
                                                        <option value="No">No</option>
                                                        <option value="Yes">Yes</option>
                                                    </select>&nbsp;
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 149px;">Default label Name</label>
                                                    <select name="fldcategory" class="">
                                                        <option value=""></option>
                                                        <option value="Both">Both</option>
                                                        <option value="Brand">Brand</option>
                                                        <option value="Generic">Generic</option>
                                                    </select>&nbsp;
                                                </div>
                                                <div class="form-inner">
                                                    <label for="" class="form-label" style="width: 149px;">Current Status</label>
                                                    <select name="fldcategory" class="">
                                                        <option value=""></option>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                    </select>&nbsp;
                                                </div>
                                                <br>
                                                <div class="form-inner">
                                                    <button><img src="{{asset('assets/images/tick.png')}}" width="25px">&nbsp;&nbsp;Add</button>
                                                    <button><img src="{{asset('assets/images/edit.png')}}" width="25px">&nbsp;&nbsp;Edit</button>
                                                    <button><img src="{{asset('assets/images/delete.png')}}" width="25px">&nbsp;&nbsp;Delete</button>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <textarea name="flddrugdetail" style="min-height: 200px;" cols="100">{!! old('flddrugdetail') !!}</textarea>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 padding-none">
                                            <button><img src="{{asset('assets/images/plus.png')}}" width="16px">&nbsp;&nbsp;Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

    <form id="delete_form" method="POST">
        @csrf
        @method('delete')
    </form>

    <script>
        $(function() {

            function select2loading() {
                setTimeout(function() {
                    $('.select2DosageForms').select2({
                        placeholder : 'select dosage'
                    });
                }, 4000);
            }

            select2loading();

            $('#genericnameaddaddbutton').click(function() {
                var genericname = $('#genericnamefield').val();


                if(genericname != '') {
                    $.ajax({
                        type : 'post',
                        url  : '{{ route('medicines.addgeneric') }}',
                        dataType : 'json',
                        data : {
                            '_token': '{{ csrf_token() }}',
                            'fldcodename': genericname,
                        },
                        success: function (res) {

                            showAlert(res.message);
                            if(res.message == 'Generic Name added successfully.') {
                                $('#genericnamefield').val('');
                                var deleteroutename = "{{ url('/medicines/deletegeneric') }}/"+encodeURIComponent(genericname);
                                $('#genericnamelistingmodal').append('<li class="generic-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="generic_item" data-href="'+deleteroutename+'" data-id="'+genericname+'">'+genericname+'</li>');
                            }

                        }
                    });
                } else {
                    alert('Generic Name is required');
                }
            });

            // selecting category item
            $('#genericnamelistingmodal').on('click', '.generic_item', function() {
                $('#genericnametobedeletedroute').val($(this).data('href'));
                $('#genericidtobedeleted').val($(this).data('id'));
            });

            // deleting selected category item
            $('#genericnamedeletebutton').click(function() {
                var deletegenericroute = $('#genericnametobedeletedroute').val();
                var deletegenericid = $('#genericidtobedeleted').val();

                if(deletegenericroute == '') {
                    alert('no generic info selected, please select the generic info.');
                }

                if(deletegenericroute != '') {
                    var really = confirm("You really want to delete this Generic Info?");
                    if(!really) {
                        return false
                    } else {
                        $.ajax({
                            type : 'delete',
                            url : deletegenericroute,
                            data : {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {
                                showAlert(res);
                                if(res == 'Generic Info deleted successfully.') {
                                    $("#genericnamelistingmodal").find(`[data-href='${deletegenericroute}']`).parent().remove();
                                    $('#genericnametobedeletedroute').val('');
                                    $('#genericidtobedeleted').val('');
                                }
                            }
                        });
                    }
                }
            });

            // adding category

            $('#dosageaddbutton').click(function() {
                var dosagename = $('#dosageformfield').val();


                if(dosagename != '') {
                    $.ajax({
                        type : 'post',
                        url  : '{{ route('medicines.adddosageform') }}',
                        dataType : 'json',
                        data : {
                            '_token': '{{ csrf_token() }}',
                            'flforms': dosagename,
                        },
                        success: function (res) {

                            showAlert(res.message);
                            if(res.message == 'Dosage Form added successfully.') {
                                $('#dosageformfield').val('');
                                var deleteroutename = "{{ url('/medicines/deletedosageform') }}/"+res.fldid;
                                $('#dosagelistingmodal').append('<li class="dosage-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="dosage_item" data-href="'+deleteroutename+'" data-id="'+res.fldid+'">'+res.flforms+'</li>');
                                $('.select2DosageForms').append('<option value="'+res.flforms+'" data-id="'+res.fldid+'">'+res.flforms+'</option>');
                                select2loading();
                            }

                        }
                    });
                } else {
                    alert('Dosage Form Name is required');
                }
            });

            // selecting category item
            $('#dosagelistingmodal').on('click', '.dosage_item', function() {
                $('#dosagetobedeletedroute').val($(this).data('href'));
                $('#dosageidtobedeleted').val($(this).data('id'));
            });

            // deleting selected category item
            $('#dosagedeletebutton').click(function() {
                var deletedosageroute = $('#dosagetobedeletedroute').val();
                var dosageidtobedeleted = $('#dosageidtobedeleted').val();

                if(deletedosageroute == '') {
                    alert('no Dosage selected, please select the Dosage.');
                }

                if(deletedosageroute != '') {
                    var really = confirm("You really want to delete this Dosage?");
                    if(!really) {
                        return false
                    } else {
                        $.ajax({
                            type : 'delete',
                            url : deletedosageroute,
                            data : {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {
                                showAlert(res);
                                if(res == 'Dosage deleted successfully.') {
                                    $("#dosagelistingmodal").find(`[data-href='${deletedosageroute}']`).parent().remove();
                                    $(".select2DosageForms").find(`[data-id='${dosageidtobedeleted}']`).remove();
                                    $('#dosagetobedeletedroute').val('');
                                    $('#categoryidtobedeleted').val('');
                                    select2loading();
                                }
                            }
                        });
                    }
                }
            });
        })
    </script>
@endsection
