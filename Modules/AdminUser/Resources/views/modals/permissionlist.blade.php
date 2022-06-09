
<div class="modal fade bd-example-modal-lg"  id="permission-detail-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Permission Detail</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="form-data-user-list">
                <div id="permissiondetail">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">

    $('#permission-detail-modal').on('shown.bs.modal', function (e) {
        var groupId = $(e.relatedTarget).data('groupid');
        $.ajax({
            type : 'post',
            url : "{{ route('admin.user.group.permission.listview') }}",
            data :{
                '_token' : "{{ csrf_token() }}",
                'group_id' : groupId,
            },
            success : function(response)
            {
                $('#permissiondetail').html(response.htmlview);
            }
        })
    })


</script>
