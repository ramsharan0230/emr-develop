<div class="modal fade" id="consultant-group-modal">
    <div class="modal-dialog modal-xl" id="cpgsize">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="consultant-group-modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="consultant-group-form-container">
                    <div class="consultant-group-form-data"></div>
                </div>

            </div>
            <i class="glyphicon glyphicon-chevron-left"></i>
            <!-- Modal footer -->
            {{--<div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>--}}

        </div>
    </div>
</div>

<script>
// consGroup.ProcGroup
    var consGroup = {
        // alert('sdfsdfs');
       ProcGroup: function () {
            $('.consultant-group-form-data').empty();
            $('.consultant-group-modal-title').text('Extra Procedure');
            $('#cpgsize').removeClass('modal-dialog modal-xl');
            $('#cpgsize').addClass('modal-dialog modal-lg');
            $.ajax({
                url: '{{ route('display.procgroup.form.group.consultant') }}',
                type: "POST",
                data: {comp:$('#target').val(),"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-group-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-group-modal').modal('show');
        },
    }
    
    
    

</script>
<style>
    .file-form-container {
        min-height: 100px;
        max-height: 650px;
        /*overflow: scroll;*/
    }
</style>
