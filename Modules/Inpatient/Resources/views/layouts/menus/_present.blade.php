<div id="present" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-6">
                    <form action="" class="form-horizontal">
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-3">
                                <select id="complaintsSelectInpatient" name="flditem" class="form-control select-present select2">
                                    <option value="">--select--</option>
                                    @if(isset($complaint))
                                    @foreach($complaint as $com)
                                    <option value="{{ $com->fldsymptom }}">{{ $com->fldsymptom }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control duration" />
                            </div>
                            <div class="col-sm-3">
                                <select name="duration_type" class="form-control duration_type">
                                    <option disabled="disabled">Duration</option>
                                    <option value="Hours">Hours</option>
                                    <option value="Days">Days</option>
                                    <option value="Weeks">Weeks</option>
                                    <option value="Months">Months</option>
                                    <option value="Years">Years</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="fldreportquali" class="form-control fldreportquali">
                                    <option disabled="disabled">Sides</option>
                                    <option value="Left Side">Left Side</option>
                                    <option value="Right Side">Right Side</option>
                                    <option value="Both Side">Both Side</option>
                                    <option value="Episodes">Episodes</option>
                                    <option value="On/Off">On/Off</option>
                                    <option value="Present">Present</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <button id="inpatient_insert_complaints" url="{{ route('inpatient.insert.complaint')}}" class="btn-sm-in btn btn-primary disableInsertUpdate" type="button">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="res-table">
                        <table class="table table-hovered table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Symptoms</th>
                                    <th>Dura</th>
                                    <th>Side</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Time</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($examgeneral))
                                @foreach($examgeneral as $general)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $general->flditem }}</td>
                                    <td class="{{ $disableClass }} clicked_edit_complaint_duration" permit_user="{{ $general->flduserid }}" data-toggle="modal" data-target="#edit_complaint_duration_inpatient" rel="{{ $general->fldid }}">
                                        @if($general->fldreportquanti <= 24) {{ $general->fldreportquanti }} hr @endif
                                        @if($general->fldreportquanti > 24 && $general->fldreportquanti <=720 ) {{ round($general->fldreportquanti/24,2) }} Days  @endif

                                        @if($general->fldreportquanti > 720 && $general->fldreportquanti <8760) {{ round($general->fldreportquanti/720,2) }} Months @endif
                                        @if($general->fldreportquanti >= 8760) {{ round($general->fldreportquanti/8760) }} Years  @endif
                                    </td>
                                    <td class="{{ $disableClass }} clicked_edit_complaint_side" permit_user="{{ $general->flduserid }}" data-toggle="modal" data-target="#edit_complaint_side_inpatient" rel="{{ $general->fldid }}" rel1="{{ $general->fldreportquali }}">{{ $general->fldreportquali }}</td>
                                    <td>
                                        <a class="{{ $disableClass }} delete_complaints" href="javascript:;" permit_user="{{ $general->flduserid }}" url="{{ route('delete_complaint',$general->fldid) }}"><i class="ri-delete-bin-5-fill"></i></a>
                                    </td>
                                    <td>
                                        <a class="{{ $disableClass }} clicked_edit_complaint" href="javascript:;"
                                        permit_user="{{ $general->flduserid }}" data-toggle="modal" data-target="#edit_complaint_inpatient" rel="{{ $general->fldid }}" old_complaint_detail="{{ strip_tags($general->flddetail) }}"><i class="ri-edit-2-fill"></i></a>
                                    </td>
                                    <td>{{ $general->fldtime }}</td>
                                    <td>{{ strip_tags($general->flddetail) }}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group form-row align-items-center mt-2">
                        <div class="col-sm-11">
                            <label>Cause Of Admission</label>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-sm-in btn-primary {{ $disableClass }}" id="cause_detail_store" url="{{ route('inpatient.store.cause') }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save"><i class="fas fa-check pr-0"></i></button>
                        </div>
                        <div class="col-sm-12">
                            <input type="hidden" name="fldid" id="c_o_a_fldid" value="{{ ($cause_of_admission && $cause_of_admission->fldid) ? $cause_of_admission->fldid : '' }}"/>
                            <input type="text" name="flddetail" cols="30" rows="6" class="form-control " id="details_of_patient" value="{{ ($cause_of_admission && $cause_of_admission->flddetail) ? $cause_of_admission->flddetail : '' }}">
                        </div>

                    </div>
                </div>
                <div class="col-sm-6">
                    <form action="" class="form-horizontal">
                        <div class="form-group form-row align-items-center">
                            <div class="col-sm-11">
                                <select name="patientHistory" id="patientHistory" class="form-control history_flditem">
                                    <option disabled="disabled">Select…</option>
                                    <option value="General Complaints">General Complaints</option>
                                    <option value="History of Illness">History of Illness</option>
                                    <option value="Past History">Past History</option>
                                    <option value="Treatment History">Treatment History</option>
                                    <option value="Medication History">Medication History</option>
                                    <option value="Surgical History">Surgical History</option>
                                    <option value="Family History">Family History</option>
                                    <option value="Personal History">Personal History</option>
                                    <option value="Occupational History">Occupational History</option>
                                    <option value="Social History">Social History</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-sm-in btn-primary {{ $disableClass }}" id="save_history_of_patient" url="{{ route('present.history.save') }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save"><i class="fas fa-check pr-0"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="form-group mb-0">
                        <input type="hidden" class="history_fldid" value="">
                        <input type="hidden" class="history_flduserid" value="{{Helpers::getCurrentUserName()}}">
                        <input type="hidden" class="history_fldcomp" value="{{Helpers::getCompName()}}">
                        <textarea name="history_detail" id="history_detail" class="form-control present-textarea"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('inpatient::layouts.modal.complaint-update')
@include('inpatient::layouts.modal.complaint-duration-update')
@include('inpatient::layouts.modal.complaint-side-update')
