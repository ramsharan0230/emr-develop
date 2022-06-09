<div class="modal fade" id="consultant-modal">
    <div class="modal-dialog modal-xl" id="csize">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="consultant-modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="consultant-form-container">
                    <div class="consultant-form-data"></div>
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

    var consActivity = {
        Consultationplan: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('Consultation Plan');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog modal-lg');
            $.ajax({
                url: '{{ route('display.userposting.form.activity.consultant') }}',
                type: "POST",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },
        CompExam: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('Comp Exam List');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog modal-lg');
            $.ajax({
                url: '{{ route('display.compexam.form.activity.consultant') }}',
                type: "POST",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },
        DeptExam: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('Department Examination');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog modal-lg');
            $.ajax({
                url: '{{ route('display.deptexam.form.activity.consultant') }}',
                type: "POST",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },

        
    }
    
    var consultationReport = {
        SearchEncModal: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('Encounter List');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.searchenc.form.servicedata.consultant') }}',
                type: "POST",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },
        SearchNameModal: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('CogentEMR');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.searchname.form.servicedata.consultant') }}',
                type: "POST",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },
        
    }

    var visitReport = {

        SearchNameModal: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('CogentEMR');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.consultation.view.report.search.form') }}',
                type: "get",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },

    }

    var consultationTransition = {

        SearchNameModal: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('CogentEMR');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.consultation.transition.search.form') }}',
                type: "get",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },

    }

    var consultationIpEvets = {

        SearchNameModal: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('CogentEMR');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.consultation.ip.events.search.form') }}',
                type: "get",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },

    }

    var consultationProcedureReport = {

        SearchNameModal: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('CogentEMR');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.consultation.procedure.search.form') }}',
                type: "get",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },

    }

    var consultationEquipment = {

        SearchNameModal: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('CogentEMR');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.consultation.equipment.search.form') }}',
                type: "get",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },

    }

    var consultationConfinement = {

        SearchNameModal: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('CogentEMR');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.consultation.confinement.search.form') }}',
                type: "get",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },

    }

    var deptexam = {
        addProcModal: function () {
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('Add Variable');
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.proc.add.form.activity.consultant') }}',
                type: "POST",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
        },

        addOption: function () {
            // alert('sdfsd0');
            var dept = $('#proc_department').val();
            var opt = $('#option').val();
            var test = $('#exam_label').val();
            if(dept === '' || opt === ''){
                alert('Please select department and option type');
                return false;
            }
            $('.consultant-form-data').empty();
            $('.consultant-modal-title').text('Add Variable'); 
            $('#csize').removeClass('modal-dialog modal-xl');
            $('#csize').addClass('modal-dialog');
            $.ajax({
                url: '{{ route('display.qualtiative.option.activity.consultant') }}',
                type: "POST",
                data: {"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.consultant-form-data').html(response);
                    $('#test').val(test);
                    $('#sub_test').val(dept);
                    $('#option_type').val(opt);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#consultant-modal').modal('show');
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
