<div id="diagnosis" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card-header d-flex">
        <div class="iq-header-title">
            <h4 class="card-title">Diagnosis</h4>
        </div>
        <div class="allergy-add ml-4">
            @if(isset($enable_freetext) and $enable_freetext  == 'Yes')
            <a href="javascript:void(0);" class="iq-bg-primary" data-toggle="modal" data-target="#diagnosisfreetext" onclick="diagnosisfreetext.displayModal()"><i class="ri-add-fill"></i></a>
            @else
            <a href="javascript:void(0);" class="iq-bg-secondary"><i class="ri-add-fill"></i></a>
            @endif
            @if(isset($patient) and $patient->fldptsex == 'Male')
            <a href="javascript:void(0);" class="iq-bg-secondary"><i class="ri-add-fill"></i></a>
            @else
            <a href="#" class="iq-bg-primary" data-toggle="modal" data-target="#obstetricdiagnosis" onclick="obstetric.displayModal()"><i class="ri-add-fill"></i></a>
            @endif
            <a href="javascript:void(0);" class="iq-bg-primary" data-toggle="modal" data-target="#diagnosis_dental"><i class="ri-add-fill"></i></a>
            <a href="javascript:void(0);" class="iq-bg-danger" id="deletealdiagno"><i class="ri-delete-bin-5-fill"></i></a>
             <div class="container" id="delete_diagnosis_popup" style="display: none;">
              <h1>Delete Account</h1>
              <p>Are you sure you want to delete your account?</p>

              <div class="clearfix">
                <button type="button" class="cancelbtn">Cancel</button>
                <button type="button" class="deletebtn">Delete</button>
              </div>
            </div>
            <a href="#" class="iq-bg-warning"><i class="ri-information-fill"></i></a>
                <!-- <a href="#" class="iq-bg-danger"><i class="ri-delete-bin-5-fill"></i></a> -->
        </div>
        </div>
        <div class="form-group mt-3">
            <div class="form-group">
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
        </div>
        <div class="modal fade" id="diagnosis_dental" tabindex="-1" role="dialog" aria-labelledby="diagnosisLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document"> 
                <div class="modal-content">
                    <!-- <input type="hidden" id="patientID" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif"> -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="diagnosisLabel" style="text-align: center;">ICD10 Database</h5>
                        <button type="button" class="close onclosediag" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="opd-diagnosis">
                        
                        <div class="modal-body">
                            <div class="row xcv">
                                <div class="col-md-6">
                                    <div class="form-group form-row">
                                        <div class="col-md-2">
                                            <label for="group">Group</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select id="diagnogroup" class="form-control">
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
                                            <a href="#" class="button btn btn-primary" id="searchbygroup"> <i class="fas fa-sync"></i></a>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="#" class="button btn btn-danger" id="closesearchgroup"> <i class="far fa-window-close"></i></a>
                                        </div>
                                    </div>
                                    <ul class="list-group top-req">
                                        <div id="diagnosiss">
                                            <!-- <div class="form-group form-row align-items-center">
                                                <label for="" class="col-sm-2">Search</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="" id="" palceholder="Search" class="form-control">
                                                </div>
                                            </div> -->
                                            @if(isset($diagnosiscategory) and !empty($diagnosiscategory))
                                            <div  class="icd-datatable">
                                                <table class="datatable table table-bordered table-striped table-hover" id="datatable top-req">
                                                    <thead>
                                                        <th width="10px">S.No</th>
                                                        <th width="10px">Code</th>
                                                        <th>Name</th>
                                                    </thead>
                                                    <tbody id="diagnosiscat">
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
                                            <input type="text" class="form-control" name="search_diagnosis_sublist" id="search_diagnosis_sublist" placeholder="Search">
                                        </div>
                                    </div>
                                    <div class="group-table table-responsive table-scroll-icd">
                                        <table class="  table table-bordered table-striped table-hover" id="diagnosubcatlist">
                                            <thead>
                                                <th>Code</th>
                                                <th>Name</th>
                                            </thead>
                                            <tbody id="sublist">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-row top-req mt-2">
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
                            <button type="button" class="btn btn-primary" id="submitallergydrugs" onclick="updateDentalDiagnosis()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('outpatient::modal.diagnosis-obstetric-modal')
        @include('outpatient::modal.diagnosis-freetext-modal')
    </div>
    <script type="text/javascript">
        var obstetric = {
            displayModal: function (encId) {
                if (globalEncounter === "" && encId === "") {
                    alert('Please select encounter id.');
                    return false;
                }
                if (encId) {
                    encounterLocal = encId;
                } else {
                    encounterLocal = globalEncounter;
                }
                $('.form-data-obstetric').empty();

                $.ajax({
                    url: '{{ route('patient.diagnosis.obstetric') }}',
                    type: "POST",
                    data: {encounterId: $('#encounter_id').val()},
                    success: function (response) {
                    // console.log(response);
                    $('.form-data-obstetric').html(response);
                    $('#diagnosis-obstetric-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });


            },
        }

        function updateDentalDiagnosis(){
        // alert('diagn')
        var url = "{{route('diagnosisStore')}}";

        $.ajax({
            url: url,
            type: "POST",
            data:  $("#opd-diagnosis").serialize(),"_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                $('#select-multiple-diagno').empty().append(response);
                $('#diagnosis_dental').modal('hide');
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

<!-- <div class="collapse" id="diagnosis">
    <div class="mt-3">
    	<div class="form-group">
            <div class="form-group-inner custom-10">
                <label for="" class="form-label">Diagnosis</label>
                <a href="#" class="{{ $disableClass }}" id="deletealdiagno"><img src="{{asset('assets/images/delete.png')}}" alt=""></a>
                <img src="{{asset('assets/images/info-2.png')}}" alt="">
                @if(isset($enable_freetext) and $enable_freetext  == 1)
                    <a href="#" class="{{ $disableClass }}" data-toggle="modal" data-target="#diagnosisfreetext" onclick="diagnosisfreetext.displayModal()"><img src="{{asset('assets/images/add.png')}}" alt=""></a>
                @else
                    <img src="{{asset('assets/images/add-gray.png')}}" alt="">
                @endif
                @if(isset($patient) and $patient->fldptsex == 'Male')
                    <img src="{{asset('assets/images/add-gray.png')}}" alt="">
                @else
                    <a href="#" class="{{ $disableClass }}" data-toggle="modal" data-target="#obstetricdiagnosis" onclick="obstetric.displayModal()"><img src="{{asset('assets/images/add.png')}}" alt=""></a>
                @endif


                <a href="#" class="{{ $disableClass }}" data-toggle="modal" data-target="#diagnosis_dental"><img src="{{asset('assets/images/add.png')}}" alt=""></a>
            </div>
        </div>
        <div class="modal fade" id="diagnosis_dental" tabindex="-1" role="dialog" aria-labelledby="diagnosisLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <input type="hidden" id="patientID" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
                    <div class="modal-header">
                        <h5 class="modal-title" id="diagnosisLabel" style="text-align: center;">ICD10 Database</h5>
                        <button type="button" class="close onclosediag" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{route('diagnosisStore')}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label for="group">Group</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select id="diagnogroup" class="form-input">
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
                                            <a href="#" class="button" id="searchbygroup"> <i class="fas fa-sync"></i></a>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="#" class="button" id="closesearchgroup"> <i class="far fa-window-close"></i></a>
                                        </div>
                                    </div>
                                    <ul class="list-group top-req">
                                        <div id="diagnosiss">
                                            @if(isset($diagnosiscategory) and !empty($diagnosiscategory))
                                               <div  style="overflow-y: scroll; height:400px; border:1px solid #ccc; position: relative;">
                                                    <table class=" datatable table table-bordered table-striped table-hover" id=" top-req">
                                                        <thead>
                                                            <th width="10px">S.No</th>
                                                            <th width="10px">Code</th>
                                                            <th>Name</th>
                                                        </thead>
                                                    <tbody id="diagnosiscat">
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
                                    <div class="group-table" style="overflow-y: scroll; height:300px; border:1px solid #ccc;position: relative;">
                                        <input type="text" name="search_diagnosis_sublist" id="search_diagnosis_sublist" placeholder="Search">
                                        <table class="  table table-bordered table-striped table-hover" id="diagnosubcatlist">
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
                                    <div class="row top-req">
                                        <div class="col-md-2">
                                            <label>Code</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-input" name="code" id="code" disabled="">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-2">
                                            <label>Text</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-input" name="diagnosissubname" id="diagnosissubname">
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
                <select name="" id="select-multiple-diagno" class="form-input" multiple>
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

    </div>
    
</div> -->


