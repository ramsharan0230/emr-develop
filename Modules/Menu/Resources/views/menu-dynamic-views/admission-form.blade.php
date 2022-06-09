<form action="{{ route('patient.laboratory.form.save.waiting') }}" id="consultation-request-submit" class="consultation-form container" method="post">
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
                    <input type="text" readonly class="form-control input_disabled" value="{{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{ $encounterDataPatientInfo->fldptnamefir }} {{ $encounterDataPatientInfo->fldmidname }} {{  $encounterDataPatientInfo->fldptnamelast }}">
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <label for="name" class="col-sm-2">Address</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="consultationAddress" value="{{ $encounterDataPatientInfo->fldptaddvill .', '. $encounterDataPatientInfo->fldptadddist }}">
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
                    <input type="text" readonly class="form-control" value="{{ Helpers::getBedNumber($encounterId) }}">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <nav>
                    <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="consultation-requested-tab" data-toggle="tab" href="#consultation-requested" role="tab" aria-controls="consultation-requested" aria-selected="true">Requested</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="consultation-request-tab" data-toggle="tab" href="#consultation-request" role="tab" aria-controls="consultation-request" aria-selected="false">Completed</a>
                        </li>

                    </ul>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    {{--requested--}}
                    <div class="tab-pane fade show active" id="consultation-requested" role="tabpanel" aria-labelledby="consultation-requested-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-row align-items-center">
                                    <label for="consultation-department" class="col-sm-3">Department</label>
                                    <div class="col-sm-9">
                                        <select name="consultationDeartment" class="form-control">
                                            <option value="">Select Department</option>
                                            @if(count($department))
                                            @foreach($department as $dept)
                                            <option value="{{ $dept->flddept }}">{{ $dept->flddept }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="consultation-department" class="col-sm-3">Consultant</label>
                                    {{-- <input type="text" class="form-input" id="consulting_add">--}}
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <select name="consultant_id" id="consulting_add" class="form-control">
                                                <option value=""></option>
                                                @if(count($consultation))
                                                @foreach($consultation as $con)
                                                <option value="{{$con->flduserid}}">{{$con->firstname . ' '. $con->lastname}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <div class="input-group-append">
                                                <a href="javascript:;">
                                                    <span class="input-group-text"><i class="ri-user-fill"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <img src="{{asset('assets/images/telephone.png')}}" alt=""> -->
                                </div>
                                <div class="froum-group form-row align-items-center">
                                    <label class="col-sm-3">Comment:</label>
                                    <div class="col-md-9">
                                        <textarea name="consultation_comment" class="form-control" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-row align-items-center">
                                    <label for="consultation-date" class="col-sm-2">&nbsp;Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="consultation_date" class="form-control" id="datepickerConsultation">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="consultation-billing" class="col-sm-2">Billing</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="billing_mode" value="{{ $encounterData->fldbillingmode }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <a href="javascript:;" class="btn btn-primary" onclick="consultation.addConsultation()">Add</a>
                                </div>
                            </div>
                        </div>
                        <div class="res-table mt-2">
                            <table class="table table-hover table-striped table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>DateTime</th>
                                        <th>Consultation</th>
                                        <th>Comment</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="body-consultation-request-list">
                                    @if(count($consult_list))
                                    @foreach($consult_list as $con)
                                    <tr>
                                        <td>{{ $con->fldconsulttime }}</td>
                                        <td>{{ $con->fldconsultname }}</td>
                                        <td>{{ $con->fldcomment }}</td>
                                        <td><a href="javascript:;" onclick="consultation.deleteConsultation('{{ $con->fldid }}')" class="text-danger" style="font-size: 18px;"><i class="ri-close-fill"></i></td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--end requested--}}
                    {{--request--}}
                    <div class="tab-pane fade" id="consultation-request" role="tabpanel" aria-labelledby="consultation-request-tab">
                        <div class="res-table">
                            <table class="table table-hover table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ConsultTime</th>
                                        <th>Consultation</th>
                                        <th>Consultant</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="body-consultation-request-list">
                                    @if(count($consult_list_complete))
                                    @foreach($consult_list_complete as $con)
                                    <tr>
                                        <td>{{ $con->fldconsulttime }}</td>
                                        <td>{{ $con->fldconsultname }}</td>
                                        <td>{{ $con->user? $con->user->firstname .' '.  $con->user->lastname:''  }}</td>
                                        <td>{{--<a href="javascript:;" onclick="consultation.deleteConsultation('{{ $con->fldid }}')"><img src="{{ asset('images/cancel.png') }}" alt="Delete" style="width: 12px;"></a>--}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--end request--}}
                </div>
            </div>
        </div>

    </div>

</form>

<script type="text/javascript">
    $('#datepickerConsultation').datetimepicker({
        minDate: dateToday,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        // yearRange: "-100:+0",
    }).datetimepicker('setDate', new Date());
</script>