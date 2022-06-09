<div class="modal fade" id="duplicate_items" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
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
                        @php $duplicates = \App\Utils\Variablehelpers::getDuplicateitems(); @endphp
                        <table class="table table-hover table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <td>S.N.</td>
                                    <td>Itemname (surname)</td>
                                    <td>Occurence</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($duplicates as $duplicate)
                                    @if($duplicate->count > 1)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $duplicate->flditemname }}</td>
                                            <td>{{ $duplicate->count }}</td>
                                        </tr>
                                    @endif
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


