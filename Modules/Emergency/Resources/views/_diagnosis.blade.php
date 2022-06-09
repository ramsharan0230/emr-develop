<!-- Obstetric Diagnosis -->
<div class="form-group">
    <div class="form-group-inner custom-10" style="width: 100%;">
        <label for="" class="form-label">Diagnosis</label>
        <a href="#" class="{{ $disableClass }}" id="deletealdiagno-emergency"><img src="{{asset('assets/images/delete.png')}}" alt="" class="floatright"></a>
        <img src="{{asset('assets/images/info-2.png')}}" alt="" class="floatright">
        @if(isset($enable_freetext) and $enable_freetext  == 1)
        <a href="#" class="{{ $disableClass }}" onclick="diagnosisfreetextEmergency.displayModal()"><img src="{{asset('assets/images/add.png')}}" alt="" class="floatright"></a>
        @else
        <img src="{{asset('assets/images/add-gray.png')}}" alt="" class="floatright">
        @endif
        @if(isset($patient) and $patient->fldptsex == 'Male')
        <img src="{{asset('assets/images/add-gray.png')}}" alt="" class="floatright">
        @else
        <a href="#" class="{{ $disableClass }}" onclick="obstetricEmergency.displayModal()"><img src="{{asset('assets/images/add.png')}}" alt="" class="floatright"></a>
        @endif


        <a href="#" class="{{ $disableClass }}" data-toggle="modal" data-target="#diagnosis-emergency"><img src="{{asset('assets/images/add.png')}}" alt="" class="floatright"></a>
    </div>
</div>
<div class="modal fade" id="diagnosis-emergency" tabindex="-1" role="dialog" aria-labelledby="diagnosisLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <input type="hidden" id="patientID" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
            <div class="modal-header">
                <h5 class="modal-title" id="diagnosisLabel" style="text-align: center;">ICD10 Database</h5>
                <button type="button" class="close onclosediag" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('emergency.diagnosisStore')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-row">
                                <div class="col-md-2">
                                    <label for="group">Group</label>
                                </div>
                                <div class="col-md-8">
                                    <select id="diagnogroup-emergency" class="form-control">
                                        <option value="">--Select Group--</option>
                                        @if(isset($diagnosisgroup) and count($diagnosisgroup) > 0)
                                        @foreach($diagnosisgroup as $dg)
                                        <option value="{{$dg->fldgroupname}}">{{$dg->fldgroupname}}</option>
                                        @endforeach
                                        @else
                                        <option value="">Groups Not Available</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <a href="#" class="button btn btn-primary" id="searchbygroup-emergency"> <i class="fas fa-sync"></i></a>
                                </div>
                                <div class="col-md-1">
                                    <a href="#" class="button btn btn-primary" id="closesearchgroup-emergency"> <i class="far fa-window-close"></i></a>
                                </div>
                            </div>
                            <ul class="list-group">
                                <div id="diagnosiss">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Search</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" palceholder="Search" class="form-control">
                                        </div>
                                    </div>
                                    @if(isset($diagnosiscategory) and count($diagnosiscategory))
                                    <div class="icd-datatable">
                                        <table class=" table table-bordered table-striped table-hover" id="datatable">
                                            <thead>
                                                <th>S.No</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                            </thead>
                                            <tbody id="diagnosiscat-emergency">
                                                @forelse($diagnosiscategory as $dc)
                                                <tr>
                                                    <td><input type="checkbox" class="dccat" name="dccat" value="{{$dc['code']}}"></td>
                                                    <td>{{$dc['code']}}</td>
                                                    <td>{{$dc['name']}}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <em>No data available in table ...</em>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="icd-datatable">
                                            @endif
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Search</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" placeholder="Search" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group table-scroll-icd" >
                                        <table class="datatable table table-bordered table-striped table-hover" id="diagnosubcatlist">
                                            <thead>
                                                <th>Code</th>
                                                <th>Name</th>
                                            </thead>
                                            <tbody id="sublist">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-row mt-2">
                                        <div class="col-md-2">
                                            <label>Code</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="code" id="code" disabled="">
                                        </div>
                                    </div>
                                    <div class="form-row mt-2">
                                        <div class="col-md-2">
                                            <label>Text</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="diagnosissubname" id="diagnosissubname">
                                            <input type="hidden" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                            <input type="submit" name="submit" id="submitdiagnosis" class="btn btn-primary" value="Save changes">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-group-inner custom-11">
                <select name="" id="select-multiple-diagno-emergency" class="form-input" multiple style="height:143px;">
                    @if(isset($patdiago) and count($patdiago) > 0)
                    @foreach($patdiago as $patdiag)
                    <option value="{{$patdiag->fldid}}">{{$patdiag->fldcode}}<a class="right_del" href="{{route('deletepatfinding',$patdiag->fldid)}}" onclick="return confirm('Are you sure you want to delete this Allergic Drug?');"><i class="fas fa-trash-alt"></i></a></option>
                    @endforeach
                    @else
                    <option value="">No Diagnosis Found</option>
                    @endif
                </select>
            </div>
        </div>

        <!-- Modal Free Text -->
        <div class="modal fade" id="diagnosis-freetext-modal-emergency">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Provisional Diagnosis</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="form-data-diagnosis-freetext"></div>
                </div>
            </div>
        </div>
        <!-- Modal Obstetric -->
        <div class="modal fade" id="diagnosis-obstetric-modal-emergency">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Obstetric Diagnosis</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="form-data-obstetric"></div>
                </div>
            </div>
        </div>
<!-- End Obstetric Diagnosis -->
