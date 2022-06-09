<div class="modal fade icd_group_modal" id="icd_group_modal" tabindex="-1" role="dialog" aria-labelledby="diagnosisLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="diagnosisLabel">ICD Database</h5>
                <button type="button" class="close onclosediag" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="diagnosiss res-table">
                            @if(isset($icd_group) and count($icd_group) > 0)
                            <table class="datatable-pathology table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                    <th>Particular</th>
                                    <th>Code</th>
                                </thead>
                                <tbody id="icd_group_pathalogy">
                                    @forelse($icd_group as $dc)
                                    <tr rel="{{$dc['code']}}" rel1="{{$dc['name']}}">
                                        <td>{{$dc['name']}}</td>
                                        <td>{{$dc['code']}}</td>
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
