<form id="equipment-complete-submit" class="equipment-form container-fluid" method="post">
    @csrf
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-row align-items-center">
                    <label for="name" class="col-sm-2">Name</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control input_disabled" value="{{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{  $encounterDataPatientInfo->fldptnamefir }} {{ $encounterDataPatientInfo->fldmidname }} {{  $encounterDataPatientInfo->fldptnamelast }}">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label for="name" class="col-sm-2">Address</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control" id="equipmentAddress" value="{{ $encounterDataPatientInfo->fldptaddvill .', '. $encounterDataPatientInfo->fldptadddist }}">
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

                <div class="form-group row">
                    <label for="name" class="col-sm-2">Bed No</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control" value="{{ Helpers::getBedNumber($encounterId) }}">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="modal-nav">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="equipment-current-tab" data-toggle="tab" href="#equipment-current" role="tab" aria-controls="equipment-current" aria-selected="true">Current</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="equipment-complete-tab" data-toggle="tab" href="#equipment-complete" role="tab" aria-controls="equipment-complete" aria-selected="false">Completed</a>
                        </li>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    {{--current--}}
                    <div class="tab-pane fade show active" id="equipment-current" role="tabpanel" aria-labelledby="equipment-current-tab">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group form-row align-items-center">
                                        <label for="equipment-department" class="col-sm-2">Equipment</label>
                                        <div class="col-sm-10">
                                            <select name="equipmentEquipment" class="form-control">
                                                <option value="">Select Equipment</option>
                                                @if(count($serviceCost))
                                                    @foreach($serviceCost as $sc)
                                                        <option value="{{ $sc->flditemname }}">{{ $sc->flditemname }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-row align-items-center">
                                        <label for="billing" class="col-sm-2">Mode</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="equipment_mode" class="form-control" value="{{ Helpers::getBillingMode($encounterId)}}" readonly>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group form-row align-items-center">
                                        <label for="equipment-department" class="col-sm-2">Payable To</label>
                                        <div class="col-sm-10">
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
                                </div>
                                <div class="col-md-4">
                                    <div class="float-right">
                                        <a href="javascript:;" class="btn btn-primary" onclick="equipmentMenu.addEquipment()"><i class="ri-add-line"></i> Add</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="res-table">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Start Time</th>
                                            <th>Stop Time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="equipment-add">
                                    @if(count($equipmentWaiting))
                                        @php
                                            $count = 1;
                                        @endphp
                                        @foreach($equipmentWaiting as $con)
                                            <tr>
                                                <td>{{ $con->flditem }}</td>
                                                <td><span class="firstTime-{{ $count }}">{{ $con->fldfirsttime }}</span>
                                                    <a href="javascript:;" onclick="equipmentMenu.equipmentStartInsert('{{ $con->fldid }}')" class="{{ $con->fldfirstsave == 1 ?'isDisabled':'' }}">
                                                        <i style="color:green" class="fas fa-play"></i>
                                                    </a>
                                                </td>
                                                <td><span class="secondTime-{{ $count }}">{{ $con->fldsecondtime }}</span>
                                                    <a href="javascript:;" onclick="equipmentMenu.equipmentStop('{{ $con->fldid }}')" class="{{ $con->fldfirstsave == 0 ?'isDisabled':'' }}">
                                                        <i style="color:red" class="fas fa-square"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @php
                                                $count++;
                                            @endphp
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{--end current--}}
                    {{--complete--}}
                    <div class="tab-pane fade" id="equipment-complete" role="tabpanel" aria-labelledby="equipment-complete-tab">
                        <div class="col-sm-12">
                            <div class="res-table">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Start Time</th>
                                            <th>Stop Time</th>
                                        </tr>
                                    </thead>
                                    <tbody id="equipment-complete-table-body">
                                    @if(count($equipmentCleared))
                                        @foreach($equipmentCleared as $con)
                                            <tr>
                                                <td>{{ $con->flditem }}</td>
                                                <td>{{ $con->fldfirsttime }}</td>
                                                <td>{{ $con->fldsecondtime }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{--end complete--}}
                </div>
            </div>
        </div>

    </div>

</form>

{{--<button type="button" class="btn btn-secondary btn-sm onclose" data-dismiss="modal">Close</button>--}}

<script>
    var equipmentMenu = {
        addEquipment: function () {
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '<?php echo e(route('patient.equipment.form.add')); ?>',
                type: "POST",
                data: $('#equipment-complete-submit').serialize(),
                success: function (response) {
                    // console.log(response);
                    $('.equipment-add').empty();
                    $('.equipment-add').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
        equipmentStop: function (fldid) {
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '<?php echo e(route('patient.equipment.form.stop')); ?>',
                type: "POST",
                data: {fldid: fldid, encounter: $('#encounter_id').val(), payableTo:$('#payable_to_add').val()},
                success: function (response) {
                    // console.log(response);
                    $('.equipment-add').empty();
                    $('.equipment-add').append(response);
                    /*display completed data when stop is clicked*/
                    $.ajax({
                        url: '<?php echo e(route('patient.equipment.form.stop.complete')); ?>',
                        type: "POST",
                        data: {encounter: $('#encounter_id').val()},
                        success: function (response) {
                            console.log(response);
                            $('#equipment-complete-table-body').empty();
                            $('#equipment-complete-table-body').append(response);
                        },
                        error: function (xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText;
                            console.log(xhr);
                        }
                    });
                    /*display completed data when stop is clicked*/

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
        equipmentStartInsert: function (fldname) {
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '<?php echo e(route('patient.equipment.form.insert.start')); ?>',
                type: "POST",
                data: {fldname: fldname, encounter: $('#encounter_id').val(),payableTo:$('#payable_to_add').val()},
                success: function (response) {
                    // console.log(response);
                    $('.equipment-add').empty();
                    $('.equipment-add').append(response);
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
    .isDisabled {
        color: currentColor;
        cursor: not-allowed;
        opacity: 0.5;
        text-decoration: none;
    }
</style>


