@extends('frontend.layouts.master')
@section('content')

    <section class="cogent-nav">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="outPatient" data-toggle="tab" href="#out-patient" role="tab"
                   aria-controls="home" aria-selected="true"><span></span> IP Events</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="out-patient" role="tabpanel" aria-labelledby="home-tab">
                <nav class="navbar navbar-expand-lg">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">File</a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownFile">
                                    <a class="dropdown-item" href="{{ route('reset.encounter') }}">Blank form</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownRequest" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Request</a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownRequest">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="laboratory.displayModal()">Laboratory</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDataEtry" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Data Entry</a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownDataEtry">
                                   <a class="dropdown-item" href="javascript:;" id="triage-essen-exams">Triage Exams</a>
                                </div>
                            </li>          
                        </ul>
                    </div>
                </nav>
                <div class="patient-profile">
                    <div class="container-fluid">
                        <div class="profile-form">
                            <form method="POST">
                                @csrf
                                <div class="parent-row row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group-inner custom-4">
                                                <label for="" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-input">
                                                    <option value="" @if(request()->get('status') == 'all') selected=selected @endif>Exits(All)</option>
                                                    <option value="Discharge" @if(request()->get('status') == 'Discharge') selected=selected @endif>Discharge</option>
                                                    <option value="LAMA" @if(request()->get('status') == 'LAMA') selected=selected @endif>LAMA</option>
                                                    <option value="Refer" @if(request()->get('status') == 'Refer') selected=selected @endif>Refer</option>
                                                    <option value="Death" @if(request()->get('status') == 'Death') selected=selected @endif>Death</option>
                                                    <option value="Absconder" @if(request()->get('status') == 'Absconder') selected=selected @endif>Absconder</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group-inner custom-4">
                                                <label for="" class="form-label">Department</label>
                                                <select name="flddept" id="flddept" class="form-input">
                                                    <option value="">%</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->flddept }}" @if(request()->get('flddept') == $department->flddept) selected=selected @endif>{{ $department->flddept }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="padding-top: 33px;">
                                        <div class="form-group">
                                            <label for="" class="form-label">EncID:</label>
                                            <input type="text" class="form-input" name="date_to" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="padding-top: 33px;">
                                        <div class="form-group">
                                            <input type="text" name="age_from" class="form-input" id="age_from" value="{{ request()->get('age_from') ?: '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="radio" name="type" value="new">New
                                            <input type="radio" name="type" value="printed">Printed
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="marded_printed" value="yes"> Mark Printed on open
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="chief-comp">
                    <div class="container-fluid">
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="res-table" style="height: 400px;">
                                    <table class="table-1" style="width: 100%;">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Index</th>
                                            <th>EncId</th>
                                            <th>DateTIme</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>DOA</th>
                                            <th>LastLocation</th>
                                            <th>LastStatus</th>
                                            <th>Consult</th>
                                        </tr>
                                        @foreach($all_data as $key => $data)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $data->fldencounterval }}</td>
                                            <td>{{ $data->fldtime }}</td>
                                            <td>{{ Options::get('system_patient_rank')  == 1 && (isset($data)) && (isset($data->fldrank) ) ?$data->fldrank:''}} {{ $data->fldptnamefir }} {{ $data->fldmidname }} {{ $data->fldptnamelast }}</td>
                                            <td>{{ $data->age }}</td>
                                            <td>{{ $data->fldptsex }}</td>
                                            <td>{{ $data->fldregdate }}</td>
                                            <td>{{ $data->fldbed }}</td>
                                            <td>{{ $data->fldadmission }}</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
