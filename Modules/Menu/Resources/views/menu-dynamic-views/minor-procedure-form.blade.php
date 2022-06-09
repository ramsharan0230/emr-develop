<form id="minor-procedure-complete-submit" class="minor-procedure-form" method="post">
    @csrf
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Name</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control input_disabled" value="{{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) )  ? $encounterData->fldrank:''}} {{  $encounterDataPatientInfo->fldptnamefir }} {{ $encounterDataPatientInfo->fldmidname }} {{  $encounterDataPatientInfo->fldptnamelast }}">
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Address</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="minor-procedureAddress" value="{{ $encounterDataPatientInfo->fldptaddvill .', '. $encounterDataPatientInfo->fldptadddist }}">
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Gender</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" value="{{ $encounterDataPatientInfo->fldptsex }}">
                </div>
            </div>

            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Bed No</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" value="{{ \App\Utils\Helpers::getBedNumber($encounterId) }}">
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="modal-nav">
                <nav>
                    <ul class="nav nav-tabs " id="nav-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="minor-procedure-current-tab" data-toggle="tab" href="#minor-procedure-current" role="tab" aria-controls="minor-procedure-current" aria-selected="true">Current</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="minor-procedure-complete-tab" data-toggle="tab" href="#minor-procedure-complete" role="tab" aria-controls="minor-procedure-complete" aria-selected="false">Completed</a>
                        </li>

                    </ul>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    {{--current--}}
                    <div class="tab-pane fade show active" id="minor-procedure-current" role="tabpanel" aria-labelledby="minor-procedure-current-tab">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group form-row align-items-center">
                                    <label for="minor-procedure-department" class="col-sm-3">Procedure</label>
                                    <div class="col-sm-9">
                                        <select name="minor_procedure" class="form-control">
                                            <option value="">Select Procedure</option>
                                            @if(count($serviceCost))
                                                @foreach($serviceCost as $dept)
                                                    <option value="{{ $dept->flditemname }}">{{ $dept->flditemname }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="billing" class="col-sm-3">Billing</label>

                                    <div class="col-sm-9">
                                        <input type="text" name="billing" id="minor-procedure-billing" class="form-control" value="{{ Helpers::getBillingMode($encounterId)}}" readonly>
                                    </div>

                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="minor-procedure-department" class="col-sm-3">Payable To</label>
                                    <div class="col-sm-9">
                                        {{--<input type="text" name="payable_to_add" class="form-input " id="payable_to_add">
                                            <div class="input-group-append">
                                                <a href="javascript:;">
                                                    <span class="input-group-text"><img src="{{ asset('assets/images/user-1.png') }}" alt=""></span>
                                        </a>
                                    </div>--}}
                                        <select name="payable_to_add" class="form-control" id="payable_to_add">
                                            <option value="">Select</option>
                                            @if(count($payable_to))
                                                @foreach($payable_to as $user)
                                                    <option value="{{ $user->flduserid }}">{{ $user->firstname.' '. $user->lastname }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    <!-- <img src="{{asset('assets/images/telephone.png')}}" alt=""> -->

                                    </div>

                                </div>
                                <div class="form-group">
                                    <a href="javascript:;" onclick="minorProcedure.addMinorProcedureWaiting()" class="btn btn-primary"><i class="ri-add-line"></i></a>
                                    <button class="btn btn-primary"><a href="javascript:;" onclick="minorProcedure.addMinorProcedureCleared()">Save</a></button>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <textarea name="minor_Procedure_Comment" class="form-control" id="minorProcedureEditor"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="res-table">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>DateTime</th>
                                            <th>Minor Procedure</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody class="body-minor-procedure-complete-list">
                                        @if(count($patGeneralWaiting))
                                            @foreach($patGeneralWaiting as $con)
                                                <tr>
                                                    <td>{{ $con->fldtime }}</td>
                                                    <td>{{ $con->flditem }}</td>
                                                    <td><a href="javascript:;" onclick="minorProcedure.deleteminorProcedure('{{ $con->fldid }}')"><img src="{{ asset('images/cancel.png') }}" alt="Delete" style="width: 16px;"></a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--end current--}}
                    {{--complete--}}
                    <div class="tab-pane fade" id="minor-procedure-complete" role="tabpanel" aria-labelledby="minor-procedure-complete-tab">
                        <div class="col-md-12">
                            <div class="res-table">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>DateTime</th>
                                        <th>Procedure</th>
                                        <th>Report</th>
                                    </tr>
                                    </thead>
                                    <tbody class="minor-procedure-complete-list">
                                    @foreach($patGeneralCleared as $cleared)
                                        <tr>
                                            <td>{{ $cleared->fldtime }}</td>
                                            <td>{{ $cleared->flditem }}</td>
                                            <td>{{ $cleared->fldreportquali }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{--end complete--}}
            </div>
        </div>

    </div>

</form>

{{--<button type="button" class="btn btn-secondary btn-sm onclose" data-dismiss="modal">Close</button>--}}

<script type="text/javascript">
    CKEDITOR.replace('minorProcedureEditor', {
        height: '100px',
        width: '100%',
    });

    $(document).ready(function () {
        $('#minor-procedure-billing').val($('#billingmode').val());
    })
    var minorProcedure = {
        addMinorProcedureWaiting: function () {
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            CKEDITOR.instances.minorProcedureEditor.updateElement();
            $.ajax({
                url: '<?php echo e(route('patient.menu.request.minor.procedure.waiting.add')); ?>',
                type: "POST",
                data: $('#minor-procedure-complete-submit').serialize(),
                success: function (response) {
                    // console.log(response);
                    $('#minor-procedure-complete-submit')[0].reset();
                    $('.body-minor-procedure-complete-list').empty();
                    $('.body-minor-procedure-complete-list').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
        addMinorProcedureCleared: function () {
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            CKEDITOR.instances.minorProcedureEditor.updateElement();
            $.ajax({
                url: '<?php echo e(route('patient.menu.request.minor.procedure.cleared.add')); ?>',
                type: "POST",
                data: $('#minor-procedure-complete-submit').serialize(),
                success: function (response) {
                    // console.log(response);
                    $('#minor-procedure-complete-submit')[0].reset();
                    $('.body-minor-procedure-complete-list').empty();
                    $('.minor-procedure-complete-list').empty();
                    $('.minor-procedure-complete-list').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
        deleteminorProcedure: function (fldid) {
            if (fldid == "") {
                alert('Field id empty.');
                return false;
            }

            var confirmDelete = confirm('Delete?');
            if (confirmDelete == false) {
                return false;
            }
            var encounter_id = $('#encounter_id').val();
            $.ajax({
                url: '<?php echo e(route('patient.menu.request.minor.procedure.delete')); ?>',
                type: "POST",
                data: {
                    fldid: fldid,
                    encounter_id: encounter_id
                },
                success: function (response) {
                    // console.log(response);
                    $('.body-minor-procedure-complete-list').empty();
                    $('.body-minor-procedure-complete-list').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }
</script>
<style>
    .modal-body {
        position: relative;
        overflow-y: auto;
        max-height: 650px;
    }
</style>
