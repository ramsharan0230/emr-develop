

<div class="modal fade" id="final_dliago_group" tabindex="-1" role="dialog" aria-labelledby="allergicdrugsLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <input type="hidden" id="patientID" name="patient_id" value="@if(isset($patient) and $patient !='') {{ $patient_id }} @endif">
                    <div class="modal-header">
                        <h5 class="modal-title" id="allergicdrugsLabel">ICD10 Database</h5>
                        <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="final-diagnosis">
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Group</label>
                                        <div class="col-sm-8">

                                            <select name="" id="diagnogroup" class="form-control">
                                                <option value="">--Select Group--</option>

                                                @if(isset($digno_group) and count($digno_group) > 0)
                                                    @foreach($digno_group as $dg)
                                                        <option value="{{$dg->fldgroupname}}">{{$dg->fldgroupname}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Groups Not Available</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="javascript:void(0);" class=" button btn btn-primary" id="searchbygroup"><i class="ri-refresh-line"></i></a>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#" class="button btn btn-danger" id="closesearchgroup"><i class="ri-close-fill"></i></a>
                                        </div>
                                    </div>
                                    <div id="diagnosiss">
                                        <div class="form-group form-row align-items-center">
                                            <!-- <label for="" class="col-sm-2">Search</label> -->
                                            <!-- <div class="col-sm-10">
                                                <input type="text" name="" id="" palceholder="Search" class="form-control">
                                            </div> -->
                                        </div>
                                        <div class="icd-datatable">
                                            <table class="datatable table table-bordered table-striped table-hover" id="top-req datatable ">
                                                <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                </tr>
                                                </thead>
                                                <tbody id="diagnosiscat">
                                                    @if(isset($digno_group_list))
                                                        @forelse($digno_group_list as $dc)
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
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Search</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="search_diagnosis_sublist" id="search_diagnosis_sublist" placeholder="Search" class="form-control">
                                        </div>
                                    </div>
                                    <div class="table-responsive table-scroll-icd">
                                        <table class=" table table-bordered table-striped table-hover" id=" top-req">
                                            <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                            </tr>
                                            </thead>
                                            <tbody id="sublist" class="sublist">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group form-row align-items-center mt-2">
                                        <label for="" class="col-sm-2">Code</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="code" id="code" class="form-control code" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Text</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="diagnosissubname" id="diagnosissubname" class="form-control diagnosissubname">
                                            <input type="hidden" name="encounter_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="" onclick="updateFinalDiagnosis()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<script type="text/javascript">
    function updateFinalDiagnosis(){
        // alert('diagn')
        var url = '{{route("finalDiagnosisStoreInpatient")}}';

        $.ajax({
            url: url,
            type: "POST",
            data:  $("#final-diagnosis").serialize(),"_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                $('#final_delete').empty().append(response);
                $('#final_dliago_group').modal('hide');
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
