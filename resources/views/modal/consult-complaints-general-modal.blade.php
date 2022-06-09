<div class="modal fade" id="consultant-complaint-modal">
    <div class="modal-dialog modal-xl" id="ccsize">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="consultant-complaint-modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="consultant-complaint-form-container">
                    <div class="consultant-complaint-form-data"></div>
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

    var complaints = {
        // alert('sdfsdfs');
       listComplaints: function () {
            $('.consultant-complaint-form-data').empty();
            $('.consultant-complaint-modal-title').text('Select Complaints for the selected department');
            $('#ccsize').removeClass('modal-dialog modal-xl');
            $('#ccsize').addClass('modal-dialog modal-lg');
            $.ajax({
                url: '{{ route('display.complaint.list.activity.consultant') }}',
                type: "POST",
                data: {comp:$('#target').val(),"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-complaint-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-complaint-modal').modal('show');
        },
    }
    
    var compexam = {
        addExamination: function () {
            $('.consultant-complaint-form-data').empty();
            $('.consultant-complaint-modal-title').text('Select Examination for the selected department');
            $('#ccsize').removeClass('modal-dialog modal-xl');
            $('#ccsize').addClass('modal-dialog modal-lg');
            var category = $('#examination_category').val();
            // var comp = $('#target').val();
            $.ajax({
                url: '{{ route('display.examination.list.activity.consultant') }}',
                type: "POST",
                data: {comp:$('#target').val(),cat:category,"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-complaint-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-complaint-modal').modal('show');
        },
    }
    var deptexam = {
        addProcModal: function () {
            $('.consultant-complaint-form-data').empty();
            $('.consultant-complaint-modal-title').text('Variable');
            $('#ccsize').removeClass('modal-dialog modal-xl');
            $('#ccsize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.proc.add.form.activity.consultant') }}',
                type: "POST",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-complaint-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-complaint-modal').modal('show');
        },
        
        addOption: function () {
            var label = $('#exam_label').val();
            var procdept = $('#proc_department').val();
            var option = $('#option').val();
            if(label == '' || procdept == '' || option == ''){

                alert('Field Missing');
                return false;
            }else if(option =='Single Selection' || option =='Multiple Selction' || option =='Text Table' ){
                 $('.consultant-complaint-form-data').empty();
                $('.consultant-complaint-modal-title').text('Qualitative Test Options');
                $('#ccsize').removeClass('modal-dialog modal-xl');
                $('#ccsize').addClass('modal-dialog');
                $.ajax({
                    url: '{{ route('display.qualtiative.option.activity.consultant') }}',
                    type: "POST",
                    data: {label:$('#exam_label').val(),dept:$('#proc_department').val(),option:$('#option').val(),"_token": "{{ csrf_token() }}"},
                    success: function (response) {
                        // console.log(response);
                        $('.consultant-complaint-form-data').html(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
                $('#consultant-complaint-modal').modal('show');
            }else{
               alert('Choose right option');
               return false
            }
            
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
