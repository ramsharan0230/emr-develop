<style type="text/css">
    .list-body tr td {
        width: 10%;
    }
</style>
<div class="modal" id="js-general-test-group-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Select requested Test<span id="js-header"> Group</span></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-9">
                        <input type="text" id="js-general-modal-search-input" class="form-control form-input">&nbsp;
                        <input type="hidden" id="js-general-test-group-modulename-modal">
                        <input type="hidden" id="js-general-test-group-type-modal">
                        <input type="hidden" id="js-general-test-group-encounterId-modal">
                    </div>
                    <div class="col-sm-3">
                        <button class="btn btn-primary btn-sm" onclick="testGroup.save()">Save</button> 
                    </div>
                    <div class="col-sm-12 mt-2">
                       <table class="table-1 fluids table-bordered table-responsive"  style="max-height: 300px;overflow-y: scroll;">
                            <tbody id="js-general-test-group-list-tbody" class="list-body"></tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script type="text/javascript">
    $('#js-general-modal-search-input').keyup(function() {
        var searchText = $(this).val().toUpperCase();
        $.each($('#js-general-test-group-list-tbody tr td'), function(i, e) {
            var tdText = $(e).text().trim().toUpperCase();

            if (tdText.search(searchText) >= 0)
                $(e).show();
            else
                $(e).hide();
        });
    });
    $('#js-general-test-group-modal').on('hidden.bs.modal', function () {
        $('#js-general-test-group-list-tbody').html('');
        $('#js-general-modal-search-input').val('');
    });
</script>
@endpush
