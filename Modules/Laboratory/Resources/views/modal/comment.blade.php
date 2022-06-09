<div class="modal fade" id="sampleCommentLong" tabindex="-1" role="dialog" aria-labelledby="sampleCommentLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="javascript:;" method="post" id="sampling-comment-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sampleCommentLongTitle">Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <input type="hidden" id="fldid_comment" name="fldid">
                        <textarea name="comment" id="comment_old"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addCommentSample()">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


@push('after-script')
    <script>
        function labPrintingNote(fldid, comment) {
            $("#fldid_comment").val(fldid);
            CKEDITOR.instances['comment_old'].setData(comment);
            $("#sampleCommentLong").modal('toggle');
        }

        function addCommentSample() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            $.ajax({
                url: "{{ route('laboratory.verify.comment.add') }}",
                type: "POST",
                data: $("#sampling-comment-form").serialize(),
                success: function (response) {
                    $('#js-printing-samples-tbody tr[is_selected="yes"] i.fa-sticky-note').attr("onclick", "labPrintingNote('" + $('#fldid_comment').val() + "', '" + CKEDITOR.instances['comment_old'].getData().slice(0, -1) + "')");
                    showAlert(response.message);
                    $("#sampleCommentLong").modal('toggle');
                }
            });
        }
        CKEDITOR.replace('comment_old', {
            height: 200
        });
    </script>
@endpush