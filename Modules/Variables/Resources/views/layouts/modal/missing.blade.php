<div class="modal fade" id="missing_items" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="encounter_listLabel">Items Duplicate in Grouping List</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="res-table">
                        @php $missingsurnames = \App\Utils\Variablehelpers::getMissingSurnameinEthicgroupfrompatientInfo(); @endphp
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                            <tr>
                                <td>S.N.</td>
                                <td>lastname (surname)</td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($missingsurnames as $missingsurname)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $missingsurname->SurName }}</td>
                                </tr>

                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


