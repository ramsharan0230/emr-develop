<div class="modal fade" id="diagnosis" tabindex="-1" role="dialog" aria-labelledby="diagnosisLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <input type="hidden" id="patientID" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
            <div class="modal-header">
                <h5 class="modal-title" id="diagnosisLabel" style="text-align: center;">ICD10 Database</h5>
                <button type="button" class="close onclosediag" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="opd-diagnosis">
               
                <div class="modal-body">
                    <div class="row">
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
                                    <div class="icd-datatable">
                                        <table class="datatable  table table-bordered table-striped table-hover" id="diagnosiscat top-req datatable">
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
                                    <input type="text" name="search_diagnosis_sublist" id="search_diagnosis_sublist" placeholder="Search" class="form-control">
                                </div>
                            </div>
                            <div class="group-table  table-scroll-icd">
                                <table class="table table-bordered table-striped table-hover" id="diagnosubcatlist datatable">
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
                            <div class="form-group form-row mt-2">
                                <div class="col-md-2">
                                    <label>Code</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="code" id="code" disabled="">
                                </div>
                            </div>
                            <div class="form-group form-row">
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
                    <button type="button" class="btn btn-primary" id="submitallergydrugs" onclick="updateDiagnosis()">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>