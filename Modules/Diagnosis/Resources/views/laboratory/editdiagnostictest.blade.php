@extends('frontend.layouts.master')
@push('after-styles')
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
            padding: 4px;
            border: 1px solid #a7a5a3;
        }

        .dietary-table tr:hover {
            background-color: #cccccc;
        }

        .dietarytable {
            height: 620px;
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

        .backgroundtestname {
            background-color: #efebe7;
        }

        /*dietary css ends */
    </style>
@endpush
@section('content')

    <section class="cogent-nav">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="outPatient" data-toggle="tab" href="#out-patient" role="tab" aria-controls="home" aria-selected="true"><span></span> Diagnostic Master / Diagnostic Test</a>
            </li>
        </ul>
        <div class="container-fluid">
            <form action="{{ route('diagnostictest.update', encrypt($test->fldtestid)) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="row">
                    <div class="col-md-4">
                        @include('diagnosis::layouts.includes.laboratorylisting')
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="form-group">
                                <div class="form-inner">
                                    <label class="form-label">Test Name</label>
                                    <input type="text" name="fldtestid" id="fldtestid" value="{{ $test->fldtestid }}" class="form-input-big backgroundtestname" placeholder="" style="width: 562px !important;" required readonly>
                                    <a href="javascript:void(0)" id="editfldtestid"><img src="{{asset('assets/images/edit.png')}}"></a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-inner">
                                    <label class="form-label">Category</label>
                                    @php
                                        $pathocategorytype = $categorytype;
                                        $pathocategories = \App\Utils\Diagnosishelpers::getPathoCategory($categorytype);
                                    @endphp
                                    <select name="fldcategory" class="form-select-dietary select2categoryname" required>
                                        <option value=""></option>
                                        @forelse($pathocategories as $pathocategory)
                                            <option value="{{ $pathocategory->flclass }}" data-id="{{ $pathocategory->fldid }}" {{ ((old('fldcategory') && old('fldcategory') == $pathocategory->flclass) || $pathocategory->flclass == $test->fldcategory) ? 'selected' : ''}}>{{ $pathocategory->flclass }}</option>
                                        @empty
                                        @endforelse
                                    </select>&nbsp;
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#category_modal"><img src="{{asset('assets/images/add.png')}}" style="width: 23px;"></a>
                                    @include('diagnosis::layouts.modal.category')
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-inner">
                                    <label class="form-label">Sys Constant</label>
                                    @php
                                        $sysconstcategory = $categorytype;
                                        $sysconsts = \App\Utils\Diagnosishelpers::getallSysConstant($sysconstcategory);
                                    @endphp
                                    <select name="fldsysconst" class="form-select-dietary select2sysconstant">
                                        <option value=""></option>
                                        <option value="Calcium_Total_Serum" {{ ((old('fldsysconst') && old('fldsysconst') == 'Calcium_Total_Serum') || $test->fldsysconst == 'Calcium_Total_Serum') ? 'selected' : ''}}>Calcium_Total_Serum</option>
                                        <option value="Chloride_Serum" {{ ((old('fldsysconst') && old('fldsysconst') == 'Chloride_Serum') || $test->fldsysconst == 'Chloride_Serum') ? 'selected' : ''}}>Chloride_Serum</option>
                                        <option value="Creatinine_Serum" {{ ((old('fldsysconst') && old('fldsysconst') == 'Creatinine_Serum') || $test->fldsysconst == 'Creatinine_Serum') ? 'selected' : ''}}>Creatinine_Serum</option>
                                        <option value="Glucose_Serum" {{ ((old('fldsysconst') && old('fldsysconst') == 'Glucose_Serum') || $test->fldsysconst == 'Glucose_Serum') ? 'selected' : ''}}>Glucose_Serum</option>
                                        <option value="Hemoglobin_Blood" {{ ((old('fldsysconst') && old('fldsysconst') == 'Hemoglobin_Blood') || $test->fldsysconst == 'Hemoglobin_Blood') ? 'selected' : ''}}>Hemoglobin_Blood</option>
                                        <option value="Potassium_Serum" {{ ((old('fldsysconst') && old('fldsysconst') == 'Potassium_Serum') || $test->fldsysconst == 'Potassium_Serum') ? 'selected' : ''}}>Potassium_Serum</option>
                                        <option value="Sodium_Serum" {{ ((old('fldsysconst') && old('fldsysconst') == 'Sodium_Serum') || $test->fldsysconst == 'Sodium_Serum') ? 'selected' : ''}}>Sodium_Serum</option>
                                        <option value="Urea_Nitrogen_Blood" {{ ((old('fldsysconst') && old('fldsysconst') == 'Urea_Nitrogen_Blood') || $test->fldsysconst == 'Urea_Nitrogen_Blood') ? 'selected' : ''}}>Urea_Nitrogen_Blood</option>
                                        <option value="pH_Blood" {{ ((old('fldsysconst') && old('fldsysconst') == 'pH_Blood') || $test->fldsysconst == 'pH_Blood') ? 'selected' : ''}}>pH_Blood</option>
                                        @forelse($sysconsts as $sysconst)
                                            <option value="{{ $sysconst->fldsysconst }}" data-sysconstant="{{ $sysconst->fldsysconst }}" {{ ((old('fldsysconst') && old('fldsysconst') == $sysconst->fldsysconst) || $test->fldsysconst == $sysconst->fldsysconst) ? 'selected' : ''}}>{{ $sysconst->fldsysconst }}</option>
                                        @empty
                                        @endforelse
                                    </select>&nbsp;
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#sysconstant_modal"><img src="{{asset('assets/images/add.png')}}" style="width:23px;"></a>
                                    @include('diagnosis::layouts.modal.sysconstant')
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-inner">
                                    <label class="form-label">Specimen</label>
                                    @php
                                        $sampletypes = \App\Utils\Diagnosishelpers::getallSampletype();
                                    @endphp
                                    <select name="fldspecimen" class="form-select-dietary select2specimen" required>
                                        <option value=""></option>
                                        @forelse($sampletypes as $sampletype)
                                            <option value="{{ $sampletype->fldsampletype }}" data-id="{{ $sampletype->fldid }}" {{ ((old('fldspecimen') && old('fldspecimen') == $sampletype->fldsampletype) || $sampletype->fldsampletype == $test->fldspecimen ) ? 'selected' : ''}}>{{ $sampletype->fldsampletype }}</option>
                                        @empty
                                        @endforelse
                                    </select>&nbsp;
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#specimen_modal"><img src="{{asset('assets/images/add.png')}}" style="width:23px;"></a>
                                    @include('diagnosis::layouts.modal.specimen')
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-inner">
                                    <label class="form-label">Collection</label>
                                    <input type="text" name="fldcollection" value="{{ $test->fldcollection }}" class="form-input-big" placeholder="">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 padding-none">
                                    <div class="form-inner">
                                        <label for="" class="form-label">Sensitivity</label>
                                        <input type="number" step="any" name="fldsensitivity" value="{{ $test->fldsensitivity }}" class="form-input-small" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left: 124px;">
                                    <div class="form-inner">
                                        <label for="" class="form-label">Specificity</label>
                                        <input type="number" step="any" name="fldspecificity" value="{{ $test->fldspecificity }}" class="form-input-small" placeholder="0">
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-6 padding-none">
                                    <div class="form-inner">
                                        <label class="form-label">Data Type</label>
                                        <select name="fldtype" class="select-3" id="data_type" required>
                                            <option value=""></option>
                                            <option value="Qualitative" {{ ((old('fldtype') && old('fldtype') == 'Qualitative') || $test->fldtype == 'Qualitative') ? 'selected' : ''}}>Qualitative</option>
                                            <option value="Quantitative" {{ ((old('fldtype') && old('fldtype') == 'Quantitative') || $test->fldtype == 'Quantitative') ? 'selected' : ''}}>Quantitative</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left: 212px;">
                                    <div class="form-inner">
                                        <label class="form-label text-center"><i class="fa fa-list"></i> Comments</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 padding-none">
                                    <div class="form-inner">
                                        <label class="form-label">Input Mode</label>
                                        <select name="fldoption" class="select-3" id="input_mode">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left: 212px;">
                                    <div class="form-inner">
                                        <label class="form-label text-center"> <i class="fa fa-info"></i> Options</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 padding-none">
                                    <div class="form-inner">
                                        <label for="" class="form-label">Outliers</label>
                                        <label for="" class="form-label">Ref Range </label>
                                        <input type="number" step="any" name="fldcritical" value="{{ $test->fldcritical }}" class="form-input-small" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left: 124px;">
                                    <div class="form-inner">
                                        <label for="" class="form-label">X Ref Range</label>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6 padding-none">
                                    <div class="form-inner">
                                        <label class="form-label">Description</label>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left: 124px;">
                                    <div class="form-inner">
                                        <label for="" class="form-label">Chemicals</label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-inner">
                                    <textarea name="flddetail" class="form-text">{!! $test->flddetail !!}</textarea>
                                </div>
                            </div>

                        </div>

                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="form-inner">
                                    <label class="form-label">Footnote</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-inner">
                                    <textarea name="fldcomment" class="form-text">{!! $test->fldcomment !!}</textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2 padding-none" style="float: right;">
                                <button><img src="{{asset('assets/images/edit.png')}}" width="16px">&nbsp;&nbsp;Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <form id="delete_form" method="POST">
        @csrf
        @method('delete')
    </form>

    <script>
        $(function () {

            function select2loading() {
                setTimeout(function () {
                    $('.select2categoryname').select2({
                        placeholder: 'select category'
                    });

                    $('.select2sysconstant').select2({
                        placeholder: 'select sys constant'
                    });

                    $('.select2specimen').select2({
                        placeholder: 'select specimen'
                    });
                }, 4000);
            }

            select2loading();

            $('#genericnameaddaddbutton').click(function () {
                var genericname = $('#genericnamefield').val();


                if (genericname != '') {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('medicines.addgeneric') }}',
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'fldcodename': genericname,
                        },
                        success: function (res) {

                            showAlert(res.message);
                            if (res.message == 'Generic Name added successfully.') {
                                $('#genericnamefield').val('');
                                var deleteroutename = "{{ url('/medicines/deletegeneric') }}/" + encodeURIComponent(genericname);
                                $('#genericnamelistingmodal').append('<li class="generic-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="generic_item" data-href="' + deleteroutename + '" data-id="' + genericname + '">' + genericname + '</li>');
                            }

                        }
                    });
                } else {
                    alert('Generic Name is required');
                }
            });

            // selecting category item
            $('#genericnamelistingmodal').on('click', '.generic_item', function () {
                $('#genericnametobedeletedroute').val($(this).data('href'));
                $('#genericidtobedeleted').val($(this).data('id'));
            });

            // deleting selected category item
            $('#genericnamedeletebutton').click(function () {
                var deletegenericroute = $('#genericnametobedeletedroute').val();
                var deletegenericid = $('#genericidtobedeleted').val();

                if (deletegenericroute == '') {
                    alert('no generic info selected, please select the generic info.');
                }

                if (deletegenericroute != '') {
                    var really = confirm("You really want to delete this Generic Info?");
                    if (!really) {
                        return false
                    } else {
                        $.ajax({
                            type: 'delete',
                            url: deletegenericroute,
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {
                                showAlert(res);
                                if (res == 'Generic Info deleted successfully.') {
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

            $('#categoryaddaddbutton').click(function () {
                var categoryname = $('#categorynamefield').val();
                var fldcategory = $('#fldcategoryfield').val();


                if (categoryname != '') {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('addpathocategory') }}',
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'flclass': categoryname,
                            'fldcategory': fldcategory
                        },
                        success: function (res) {

                            showAlert(res.message);
                            if (res.message == 'Category added successfully.') {
                                $('#categorynamefield').val('');
                                var deleteroutename = "{{ url('/diagnosis/deletecategory') }}/" + res.fldid;
                                $('#categorylistingmodal').append('<li class="category-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="category_item" data-href="' + deleteroutename + '" data-id="' + res.fldid + '">' + res.flclass + '</li>');
                                // var selectcategoryoptions = '<option value=""></option>';
                                {{--setTimeout(function() {--}}
                                {{--    @php--}}
                                {{--        $categorytype = 'Test';--}}
                                {{--        $pathocategoriesafteradd = \App\Utils\Diagnosishelpers::getPathoCategory($categorytype);--}}
                                {{--    @endphp--}}
                                {{--        @forelse($pathocategoriesafteradd as $pathocategoryadd)--}}
                                {{--        selectcategoryoptions += '<option value="{{ $pathocategoryadd->flclass }}">{{ $pathocategoryadd->flclass }}</option>';--}}
                                {{--    @empty--}}
                                {{--    @endforelse--}}
                                {{--    console.log(selectcategoryoptions);--}}
                                {{--    $('.select2categoryname').html(selectcategoryoptions);--}}
                                {{--}, 5000);--}}

                                $('.select2categoryname').append('<option value="' + res.flclass + '" data-id="' + res.fldid + '">' + res.flclass + '</option>');
                                select2loading();
                            }

                        }
                    });
                } else {
                    alert('Category Name is required');
                }
            });

            // adding sysconstant

            $('#sysconstantaddbutton').click(function () {
                var fldsysconst = $('#sysconstantnamefield').val();
                var fldcategory = $('#fldcategoryfieldsysconstant').val();

                if (fldsysconst != '') {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('addsysconstant') }}',
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'fldsysconst': fldsysconst,
                            'fldcategory': fldcategory
                        },
                        success: function (res) {
                            showAlert(res.message);
                            if (res.message == 'Sys Constant added successfully.') {
                                $('#sysconstantnamefield').val('');
                                var deleteroutenamesysconst = "{{ url('/diagnosis/deletesysconstant') }}/" + encodeURIComponent(fldsysconst);
                                $('#sysconstantlistingmodal').append('<li class="sysconstantlist" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="sysconst_item" data-href="' + deleteroutenamesysconst + '" data-sysconstant="' + fldsysconst + '">' + fldsysconst + '</li>');
                                $('.select2sysconstant').append('<option value="' + fldsysconst + '" data-fldsysconst="' + fldsysconst + '">' + fldsysconst + '</option>');
                                select2loading();
                            }

                        }
                    });
                } else {
                    alert('Sys Constant Name is required');
                }
            });

            // specimen adding

            $('#specimenaddbutton').click(function () {
                var fldsampletype = $('#specimennamefield').val();

                if (fldsampletype != '') {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('addspecimen') }}',
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'fldsampletype': fldsampletype,
                        },
                        success: function (res) {
                            showAlert(res.message);
                            if (res.message == 'Specimen added successfully.') {
                                $('#specimennamefield').val('');
                                var deleteroutenamespecimen = "{{ url('/diagnosis/deletespecimen') }}/" + res.fldid;
                                $('#sampletypelisting').append('<li class="sampletypelist" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="sampletype_item" data-href="' + deleteroutenamespecimen + '" data-id="' + res.fldid + '">' + res.fldsampletype + '</li>');
                                $('.select2specimen').append('<option value="' + res.fldsampletype + '" data-id="' + res.fldid + '">' + res.fldsampletype + '</option>');
                                select2loading();
                            }

                        }
                    });
                } else {
                    alert('Specimen Name is required');
                }
            });

            // selecting category item
            $('#categorylistingmodal').on('click', '.category_item', function () {
                $('#categorytobedeletedroute').val($(this).data('href'));
                $('#categoryidtobedeleted').val($(this).data('id'));
            });

            // deleting selected category item
            $('#categorydeletebutton').click(function () {
                var deletecategoryroute = $('#categorytobedeletedroute').val();
                var deletecategoryid = $('#categoryidtobedeleted').val();

                if (deletecategoryroute == '') {
                    alert('no category selected, please select the category.');
                }

                if (deletecategoryroute != '') {
                    var really = confirm("You really want to delete this category?");
                    if (!really) {
                        return false
                    } else {
                        $.ajax({
                            type: 'delete',
                            url: deletecategoryroute,
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {
                                showAlert(res.message);
                                if (res.message == 'success') {
                                    alert(res.successmessage);
                                    $("#categorylistingmodal").find(`[data-href='${deletecategoryroute}']`).parent().remove();
                                    $(".select2categoryname").find(`[data-id='${deletecategoryid}']`).remove();
                                    $('#categorytobedeletedroute').val('');
                                    $('#categoryidtobedeleted').val('');
                                } else if (res.message == 'error') {
                                    showAlert(res.errorMessage);
                                }
                            }
                        });
                    }
                }
            });

            //selecting sysconstant item
            $('#sysconstantlistingmodal').on('click', '.sysconst_item', function () {
                $('#sysconstanttobedeletedroute').val($(this).data('href'));
                $('#sysconstanttobedeleted').val($(this).data('sysconstant'));
            });

            // deleting selected sysconstant item
            $('#sysconstantdeletebutton').click(function () {
                var deletesysconstantroute = $('#sysconstanttobedeletedroute').val();
                var deletesysconstant = $('#sysconstanttobedeleted').val();

                if (deletesysconstantroute == '') {
                    alert('no sys constant selected, please select the sysconstant.');
                }

                if (deletesysconstantroute != '') {
                    var really = confirm("You really want to delete this sysconstant?");
                    if (!really) {
                        return false
                    } else {
                        $.ajax({
                            type: 'delete',
                            url: deletesysconstantroute,
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {

                                if (res.message == 'success') {
                                    showAlert(res.successmessage)
                                    $("#sysconstantlistingmodal").find(`[data-href='${deletesysconstantroute}']`).parent().remove();
                                    $(".select2sysconstant").find(`[data-sysconstant='${deletesysconstant}']`).remove();
                                    $('#sysconstanttobedeletedroute').val('');
                                    $('#sysconstanttobedeleted').val('');
                                } else if (res.message == 'error') {
                                    showAlert(res.errormessage);
                                }
                            }
                        });
                    }
                }
            });

            // selecting specimen item
            $('#sampletypelisting').on('click', '.sampletype_item', function () {
                $('#sampletypetobedeletedroute').val($(this).data('href'));
                $('#sampletypeidtobedeleted').val($(this).data('id'));
            });

            // deleting selected specimen item
            $('#specimendeletebutton').click(function () {
                var deletespecimenroute = $('#sampletypetobedeletedroute').val();
                var deletespecimenid = $('#sampletypeidtobedeleted').val();

                if (deletespecimenroute == '') {
                    alert('no specimen selected, please select the specimen.');
                }

                if (deletespecimenroute != '') {
                    var really = confirm("You really want to delete this specimen?");
                    if (!really) {
                        return false
                    } else {
                        $.ajax({
                            type: 'delete',
                            url: deletespecimenroute,
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {
                                if (res.message == 'success') {
                                    showAlert(res.successmessage);
                                    $("#sampletypelisting").find(`[data-href='${deletespecimenroute}']`).parent().remove();
                                    $(".select2specimen").find(`[data-id='${deletespecimenid}']`).remove();
                                    $('#sampletypetobedeletedroute').val('');
                                    $('#sampletypeidtobedeleted').val('');
                                } else if (res.message == 'error') {
                                    showAlert(res.errormessage);
                                }
                            }
                        });
                    }
                }
            });

            // input mode options according to data type

            function datatypechange(datatype) {
                if (datatype == 'Qualitative') {
                    var options = `<option value="No Selection" {{ ($test->fldoption == 'No Selection') ? 'selected' : '' }}>No Selection</option>
                                        <option value="Single Selection" {{ ($test->fldoption == 'Single Selection') ? 'selected' : '' }}>Single Selection</option>
                                        <option value="Dichotomous"  {{ ($test->fldoption == 'Dichotomous') ? 'selected' : '' }}>Dichotomous</option>
                                        <option value="Clinical Scale"  {{ ($test->fldoption == 'Clinical Scale') ? 'selected' : '' }}>Clinical Scale</option>
                                        <option value="Text Addition"  {{ ($test->fldoption == 'Text Addition') ? 'selected' : '' }}>Text Addition</option>
                                        <option value="Text Reference"  {{ ($test->fldoption == 'Text Reference') ? 'selected' : '' }}>Text Reference</option>
                                        <option value="Visual Input"  {{ ($test->fldoption == 'Visual Input') ? 'selected' : '' }}>Visual Input</option>
                                        <option value="Custom Components"  {{ ($test->fldoption == 'Custom Components') ? 'selected' : '' }}>Custom Components</option>
                                        <option value="Left and Right"  {{ ($test->fldoption == 'Left and Right') ? 'selected' : '' }}>Left and Right</option>
                                        <option value="Date Time"  {{ ($test->fldoption == 'Date Time') ? 'selected' : '' }}>Date Time</option>`;

                    $('#input_mode').html(options);
                } else if (datatype == 'Quantitative') {
                    var options = `<option value="No Selection">No Selection</option>`;

                    $('#input_mode').html(options);
                }
            }

            $('#data_type').change(function () {
                var datatype = $(this).val();

                datatypechange(datatype);

            });

            var olddatatype = '{{ $test->fldtype }}';

            datatypechange(olddatatype);

            // end input mode options according to data type

            // validation error message

                @if($errors->any())
            var validation_error = '';

            @foreach($errors->all() as $error)
                validation_error += '{{ $error }} \n';
            @endforeach

            showAlert(validation_error);
                @endif


                @if(Session::has('success_message'))
            var successmessage = '{{ Session::get('success_message') }}';
            showAlert(successmessage);
                @endif

                @if(Session::has('error_message'))
            var errormessage = '{{ Session::get('error_message') }}';
            showAlert(errormessage);
            @endif

            $('.deletediagnostictest').click(function () {
                var really = confirm("You really want to delete this diagnostic test?");
                var href = $(this).data('href');
                if (!really) {
                    return false
                } else {
                    $('#delete_form').attr('action', href);
                    $('#delete_form').submit();
                }
            });

            $('#editfldtestid').click(function () {
                $('#fldtestid').prop("readonly", (_, val) => !val);
                $("#fldtestid").toggleClass("backgroundtestname", "addOrRemove");
            });
        })
    </script>
@endsection
