<div class="modal fade" id="general-modal" tabindex="-1" role="dialog" aria-labelledby="general-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title general-modal-title" id="general-modalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="general-form-container">
                    <div class="general-form-data"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var DataviewMenu = {
        sampleModalDisplay: function() {
            $('.general-modal-title').empty();
            $('.general-modal-title').text('SampleID List');
            var encounter_id = $('#encounter_id').val();
            $.ajax({
                url: '{{ route('patient.file.menu.labsample') }}',
                type: "POST",
                data: {encounter_id:encounter_id},
                success: function(response) {
                    // console.log(response);
                    $('.general-form-data').html(response);
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#general-modal').modal('show');
        },  
       
    }

</script>
<style>
    .general-form-container {
        min-height: 100px;
        max-height: 500px;
        /*overflow: scroll;*/
    }
</style>
