<div class="modal fade bd-example-modal-lg" id="edit__routine" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="patientID" name="patient_id" value="@if(isset($patient) and $patient !='') {{ $patient_id }} @endif">
                <h5 class="inpatient__modal_title" style="text-align: center;">Dosing Record</h5>
                <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="group__box">
                                <div class="box__label">
                                    <label>Name</label>
                                </div>
                                <div class="box__input">
                                    <input type="text" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{ $patient->fldptnamefir }} {{ $patient->fldmidname }} {{ $patient->fldptnamelast }}@endif">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="group__box half_box3">
                                        <div class="box__label" style="flex: 0 0 50%">
                                            <label>DisplayKeyPad</label>
                                        </div>
                                        <div class="box__input" style="flex: 0 0 50%">
                                            <input type="checkbox" style="display: inline-block;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group__box half_box3">
                                        <div class="box__label" style="flex: 0 0 50%">
                                            <label>Gender</label>
                                        </div>
                                        <div class="box__input" style="flex: 0 0 50%">
                                            <input type="text" value="@if(isset($patient)){{ $patient->fldptsex }}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <ul class="medicine__list">

                            </ul>
                            <button type="button" class="f-btn f-btn-md f-btn-icon-r flex-basis"><i class="fa fa-code"></i>&nbsp;Report</button>
                            <button type="button" class="f-btn f-btn-md f-btn-icon-r flex-basis"><i class="fa fa-code"></i>&nbsp;Export</button>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="group__box half_box3">
                                        <div class="box__label" style="flex: 0 0 70%">
                                            <label class="m-name"></label>
                                        </div>
                                        <div class="box__input" style="flex: 0 0 30%">
                                            <input type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="group__box half_box3">
                                        <div class="box__label" style="flex: 0 0 40%">
                                            <label>Tab</label>
                                        </div>
                                        <div class="box__input" style="flex: 0 0 60%">
                                            <button type="submit" class="f-btn f-btn-md f-btn-icon-b"><i class="fa fa-plus"></i>&nbsp;Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="group__box half_box3">
                                        <div class="box__label" style="flex: 0 0 20%">
                                            <label>Regimen</label>
                                        </div>
                                        <div class="box__label" style="flex: 0 0 50%">
                                            <label class="m-route"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="group__box half_box3">
                                        <div class="box__label" style="flex: 0 0 40%">
                                            <label>TotDose</label>
                                        </div>
                                        <div class="box__input" style="flex: 0 0 60%">
                                            <input type="text" class="m-dose">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="res-table" style="height:300px;">
                                        <table class="table-1 routine__table">
                                            <thead>
                                                <tr>
                                                    <th>---</th>
                                                    <th>DateTime</th>
                                                    <th>Particulars</th>
                                                    <th>Qty</th>
                                                    <th>Unit</th>
                                                    <th>---</th>
                                                    <th>Dose</th>
                                                </tr>
                                            </thead>
                                            <tbody class="">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="group__box half_box3">
                                    <div class="box__label" style="flex: 0 0 70%">
                                        <label>Count (Today)</label>
                                    </div>
                                    <div class="box__input" style="flex: 0 0 30%">
                                        <input type="text" value="0">
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