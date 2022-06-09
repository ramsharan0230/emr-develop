<div class="modal fade bd-example-modal-lg" id="newProcedureExcel" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <input type="hidden" id="patientID" name="patient_id" value="@if(isset($patient) and $patient !='') {{ $patient_id }} @endif"/>
            <div class="modal-header">
                <h5 class="inpatient__modal_title" id="encounter_listLabel">ICD 10 DB</h5>
                <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('diagnosisStoreInpatient')}}" id="change_action_value">
                @csrf
                <div class="modal-body">
                    <div class="container-fluid">
                        <h5 class="dlagnosis__title">Group</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-row">
                                    <select name="one" class="dropdown-select-inpatient form-control col-8" id="diagnogroup">
                                        <option value="">Select…</option>
                                        @php
                                            $digno_group = \App\Utils\Helpers::getDiagnogroup();
                                        @endphp
                                        @if(isset($digno_group) and count($digno_group) > 0) @foreach($digno_group as $dg)
                                            <option value="{{$dg->fldgroupname}}">{{$dg->fldgroupname}}</option>
                                            @endforeach @else
                                            <option value="">Groups Not Available</option>
                                            @endif 
                                    </select>
                                    <div class="col-sm-2">
                                        <a href="javascript:void(0);" class="button btn btn-primary" id="searchbygroups"> <i class="fas fa-sync"></i></a>
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="#" class="button btn btn-primary" id="closesearchgroup"> <i class="far fa-window-close"></i></a>
                                    </div>
                                </div>
                                <div class="res-table">
                                    <table class="table table-bordered table-hovered table-striped datatable">
                                        <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Code</th>
                                            <th>Name</th>
                                        </tr>
                                        </thead>        
                                        <tbody id="procedureExcel">
                                            @php
                                                $diagnosiscategory = \App\Utils\Helpers::getInitialDiagnosisCategory();
                                            @endphp
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
                                    {{--<nav aria-label="...">
                                        <ul class="pagination">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">1</a>
                                            </li>
                                            <li class="page-item active" aria-current="page">
                                                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>--}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Search</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="search_diagnosis_sublist" id="search_diagnosis_sublist" placeholder="Search">
                                        </div>
                                    </div>
                                <div class="res-table">
                                    <table class="table table-hovered table-bordered table-striped">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tbody id="sublist">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group form-row">
                                        <label>Code</label>
                                        <input type="text" name="code" id="code" disabled="" class="search__field code__f_sm form-control"/>

                                    </div>
                                    <div class="form-group form-row">
                                        <label>Text</label>
                                        <input type="text" name="displayProcedure" id="displayProcedure" class="search__field code__f_md form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="insertExcelProcedure" url="{{ route('insert.newProcedure.freetext') }}">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // $('#newProcedureExcel').on('show.bs.modal', function (e) {
    //     getInitialProcedureCategoryAjaxs();
    //     $('table.datatable').DataTable({
    //       "paging":   false
          
    //   });
    // })
   $('table.datatable').DataTable({
          "paging":   false
          
      });
    
</script>
