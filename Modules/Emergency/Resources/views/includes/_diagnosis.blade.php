<div class="col-sm-4">
    <div class="iq-card iq-card-block  iq-card-stretch iq-card-height">
        <div class="iq-card-header p-1 d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Diagnosis</h4>
            </div>
            <div class="allergy-add">
                @if(isset($enable_freetext) and $enable_freetext  == 1)
                <a href="javascript:void(0);" class="iq-bg-primary mr-1" onclick="diagnosisfreetextEmergency.displayModal()"><i class="ri-add-fill"></i></a>
                @else
                <a href="javascript:void(0);" class="iq-bg-secondary mr-1 "><i class="ri-add-fill"></i></a>
                @endif

                @if(isset($patient) and $patient->fldptsex == 'Male')
                <a href="javascript:void(0);" class="iq-bg-secondary  mr-1"><i class="ri-add-fill"></i></a>
                @else
                <a href="javascript:void(0);" class="iq-bg-primary mr-1" onclick="obstetric.displayModal()"><i class="ri-add-fill"></i></a>
                @endif
                <a href="javascript:void(0);" class="iq-bg-primary mr-1" data-toggle="modal" data-target="#diagnosis-emergency"><i class="ri-add-fill"></i></a>
{{--                <a href="javascript:void(0);" class="iq-bg-warning  mr-1"><i class="ri-information-fill"></i></a>--}}
                <a href="javascript:void(0);" class="iq-bg-danger  mr-1" id="deletealdiagno-emergency"><i class="ri-delete-bin-5-fill"></i></a>
                <!-- <a href="#" class="iq-bg-primary"><i class="ri-add-fill"></i></a>
                <a href="#" class="iq-bg-secondary"><i class="ri-add-fill"></i></a>
                <a href="#" class="iq-bg-warning"><i class="ri-information-fill"></i></a>
                <a href="#" class="iq-bg-danger"><i class="ri-delete-bin-5-fill"></i></a> -->
            </div>
        </div>
        <div class="iq-card-body">
            <form action="" class="form-horizontal">
                <div class="form-group mb-0">

                    <select name="" id="select-multiple-diagno" class="form-control" multiple>
                        @if(isset($patdiago) and count($patdiago) > 0)
                        @foreach($patdiago as $patdiag)
                        <option value="{{$patdiag->fldid}}">{{$patdiag->fldcode}}</option>
                        @endforeach
                        @else
                        <option value="">No Diagnosis Found</option>
                        @endif
                    </select>
                </div>
            </form>
        </div>
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
            <form id="emergency-diagnosis">
                <!-- @csrf -->
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
                                    <a href="#" class="button btn btn-danger" id="closesearchgroup-emergency"> <i class="far fa-window-close"></i></a>
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
                                @if(isset($diagnosiscategory) and count($diagnosiscategory) > 0)
                                <div class="icd-datatable">
                                    <table class="datatable table table-bordered table-striped table-hover">
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
                                </div>
                                @endif
                            </div>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-row align-items-center">
                            <label for="" class="col-sm-2">Search</label>
                            <div class="col-sm-10">
                               <input type="text" name="search_diagnosis_sublist" id="search_diagnosis_sublist" placeholder="Search" class="form-control">
                           </div>
                       </div>
                       <div class="form-group table-scroll-icd" >
                        <table class=" table table-bordered table-striped table-hover" id="diagnosubcatlist">
                            <thead>
                                <th>Code</th>
                                <th>Name</th>
                            </thead>
                            <tbody id="sublist">

                            </tbody>
                        </table>
                    </div>
                    <div class="form-row">
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
            <!-- <input type="submit" name="submit" id="submitdiagnosis" class="btn btn-primary" value="Save changes"> -->
            <button type="button" class="btn btn-primary" id="" onclick="updateEmergencyDiagnosis()">Submit</button>
        </div>
    </form>
</div>
</div>
</div>
<div class="col-sm-4">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title d-flex align-items-center">
                <h4 class="card-title">Past Digonosis</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <div class="form-group mb-0 past-patdiagno">
                <ul>
                    @if(isset($past_patdiagno) and count($past_patdiagno) > 0)
                    @foreach($past_patdiagno as $past_diagno)
                    <li class="list-group-item">{{$past_diagno->fldcode}}</li>
                    @endforeach
                    @else
                    <li class="list-group-item">No Diagnosis Found</li>
                    @endif
                </ul>
            </div>
        </div>
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
<script type="text/javascript">

    function updateEmergencyDiagnosis(){
        // alert('diagn')
        var url = "{{route('emergency.diagnosisStore')}}";

        $.ajax({
            url: url,
            type: "POST",
            data:  $("#emergency-diagnosis").serialize(),"_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                $('#select-multiple-diagno').empty().append(response);
                $('#diagnosis-emergency').modal('hide');
                showAlert('Data Added !!');
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
</script>
<!-- Modal Obstetric -->
@include('outpatient::modal.diagnosis-obstetric-modal')