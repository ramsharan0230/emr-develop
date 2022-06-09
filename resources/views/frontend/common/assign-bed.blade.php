<style>
    input[name="department_bed"]:checked + label {
        border: 2.1px solid #96ff96;
        box-sizing: border-box;
    }

    .selected-department-bed + label {
        border: 2.1px solid #96ff96;
        box-sizing: border-box;
    }

    .traicolor {
        padding: 3px;
        border-radius: 50%;
    }
</style>

<div class="modal fade" id="assign-bed-emergency">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Assign Bed</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group form-row">
                    <select id="select-department-emergency" class="col-6 form-control" name="select-department-emergency">
                        <option value="">---Select Department---</option>
                        @if(isset($departments))
                            @foreach($departments as $department)
                                <option value="{{ $department->flddept }}" bed1="{{asset('assets/images/bed-2.png')}}" bed2="{{asset('assets/images/bed-1.png')}}">{{ $department->flddept }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group mt-2">
                    <div class="container-fluid">
                        <div class="departments-bed-list row">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                {{-- <a href="javascript:;" id="save-department-bed" url="{{ route('save.department.bed') }}" class="btn btn-primary">Save changes</a> --}}
            </div>
        </div>
    </div>
</div>
{{-- occupied bed modal --}}
<div data-backdrop="static" class="modal fade" id="occupied-bed-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form method="POST" id="assign-bed-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeDeptModalLabel">Do you want to hold current bed?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="occupied-bed-list">

                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="assign-bed-submit-btn" url="{{ route('save.department.bed') }}" type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                    <button id="append-assign-bed-submit-btn" url="{{ route('update.department.bed') }}" type="button"  class="btn btn-success">Yes</button>
                </div>
            </div>
      </form>
  </div>
</div>
{{-- end of occupied bed modal --}}
@push('after-script')
<script>
    $(document).ready(function () {
        $(document).on('click', '.empty-bed', function(event) {
            event.preventDefault();
            let enc_id = $("#encounter_id").val();
            // get occupied beds by the encounter.
            let url = '{{ route("encounter.department-beds", ":ENCOUNTER_VAL") }}';
            url = url.replace(':ENCOUNTER_VAL', enc_id);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                async: true,
                success: function(res) {
                    if (res.data.length) {
                        let li = "";
                        let bed_img = "{{ asset('new/images/bed-occupied.png')}}";
                        $.each(res.data, function(i, v) {
                            li += '<li class="list-group-item" style="display: flex; align-items: center;">\
                                        <img style="width: 39px; height: 100%; margin-right: 7px;" src="'+ bed_img +'" class="img-bed"/>\
                                        <div style="line-height: 15px;">\
                                            <div style="">'+ v.fldbed +'</div>\
                                            <small>'+ v.flddept +'</small>\
                                        </div>\
                                    </li>';
                        });

                        $("#occupied-bed-list").html(li);
                    } else {
                        $("#changeDeptModalLabel").html('Do you want to proceed?');
                        $("#assign-bed-submit-btn").hide();
                    }
                    $("#assign-bed-emergency").addClass("complete");
                    $("#assign-bed-emergency").modal('hide');
                    $("#occupied-bed-modal").removeClass('complete');
                    $("#occupied-bed-modal").modal('show');
                }
            });
        });

        $('#occupied-bed-modal').on('hidden.bs.modal', function (e) {
            $("#changeDeptModalLabel").html('Do you want to hold current bed?');
            $("#assign-bed-submit-btn").show();
            if(!$("#occupied-bed-modal").hasClass('complete')){
                $('input[name="admitted"]').prop('checked', false);
            }
        });

        $(document).on('change', '#select-department-emergency', function (e) {
            var flddept = e.target.value;
            if (flddept === "") {
                showAlert('Select Department.', 'error');
                return false;
            }

            if ($("#encounter_id").val() === "") {
                showAlert('Select patient.', 'error');
                return false;
            }
            encounter_id
            var num = 1;
            $.get('emergency/department-bed/get-related-bed?flddept=' + flddept, function (data) {
                $('.departments-bed-list').empty().html(data.html);
            });
        });

        function saveDepartmentBed(event) {
            var fldcurrlocat = $('#select-department-emergency option:selected').val();
            var fldbed = $("input[name='department_bed']:checked").val();
            var fldencounterval = "";
            var consultant = $("#consultant").val();
            var fldptguardian = $('#js-depositform-guardian').val();
            var fldrelation= $('#js-depositform-relation').val();
            var admitted = "Admitted";
            var fldbillingmode = $("#billing_mode").val();
            var discountMode = $("#discountMode").val();

            if($("#fldencounterval").length > 0){
                fldencounterval = $("#fldencounterval").val();
            }

            if($("#encounter_id").length > 0){
                fldencounterval = $("#encounter_id").val();
            }

            var url = $(event.target).attr("url");
            var holdbed = confirm('Do you want to hold current bed?');

            if (holdbed == true) {

                var formData = {
                    fldcurrlocat: fldcurrlocat,
                    fldbed: fldbed,
                    fldencounterval: fldencounterval,
                    holdbed: holdbed,
                    consultant: consultant,
                    fldptguardian: fldptguardian,
                    fldrelation: fldrelation,
                    admitted: admitted,
                    fldbillingmode: fldbillingmode,
                    discountMode: discountMode,
                };

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert(data.success.message);
                            $('#assign-bed-emergency').modal('hide');
                            $("#get_related_fldcurrlocat").html(null);
                            // $("input[name='department_bed']:checked").parent('div').remove();
                            $.get('emergency/department-locat/get-related-locat?fldencounterval=' + fldencounterval, function (data) {
                                $("#get_related_fldcurrlocat").html(data.fldcurrlocat + ' / ' + fldbed);
                            });
                            $('#patientActionButton').html("Transfer");
                            //location.reload();
                        } else {
                            showAlert(data.error.message);
                        }
                    }
                });
            }
        }

        $('#save-department-bed').click(function (event) {
            saveDepartmentBed(event);
        });

        $("#assign-bed-submit-btn").click(function (event) {
            let btn = $(this);
            btn.html('Updating...').prop('disabled', true);
            setTimeout(() => {

                var fldcurrlocat = $('#select-department-emergency option:selected').val();
                var fldbed = $("input[name='department_bed']:checked").val();
                var fldencounterval = "";
                var consultant = $("#consultant").val();
                var fldptguardian = $('#js-depositform-guardian').val();
                var fldrelation= $('#js-depositform-relation').val();
                var admitted = "Admitted";
                var fldbillingmode = $("#billing_mode").val();
                var discountMode = $("#discountMode").val();

                if($("#fldencounterval").length > 0){
                    fldencounterval = $("#fldencounterval").val();
                }

                if($("#encounter_id").length > 0){
                    fldencounterval = $("#encounter_id").val();
                }

                var url = $(event.target).attr("url");

                var formData = {
                    fldcurrlocat: fldcurrlocat,
                    fldbed: fldbed,
                    fldencounterval: fldencounterval,
                    consultant:consultant,
                    fldptguardian: fldptguardian,
                    fldrelation: fldrelation,
                    admitted: admitted,
                    fldbillingmode: fldbillingmode,
                    discountMode: discountMode,
                };

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert(data.success.message);
                            $("#show-btn").click();

                            $('#occupied-bed-modal').modal('hide');
                            $("#get_related_fldcurrlocat").html(null);
                            // $("input[name='department_bed']:checked").parent('div').remove();
                            $.get('emergency/department-locat/get-related-locat?fldencounterval=' + fldencounterval, function (data) {
                                $("#get_related_fldcurrlocat").html(data.fldcurrlocat + ' / ' + fldbed);
                            });
                            $('#patientActionButton').html("Transfer");
                            //location.reload();
                        } else {
                            showAlert(data.error.message);
                        }
                    },
                    complete: function() {
                        btn.html('No').prop('disabled', false);
                    }
                });
            }, 500);
        });

        // append new bed
        $("#append-assign-bed-submit-btn").click(function (event) {
            let btn = $(this);
            btn.html('Updating...').prop('disabled', true);
            setTimeout(() => {

                var fldcurrlocat = $('#select-department-emergency option:selected').val();
                var fldbed = $("input[name='department_bed']:checked").val();
                var fldencounterval = "";
                var consultant = $("#consultant").val();
                var fldptguardian = $('#js-depositform-guardian').val();
                var fldrelation= $('#js-depositform-relation').val();
                var admitted = "Admitted";
                var fldbillingmode = $("#billing_mode").val();
                var discountMode = $("#discountMode").val();

                if($("#fldencounterval").length > 0){
                    fldencounterval = $("#fldencounterval").val();
                }

                if($("#encounter_id").length > 0){
                    fldencounterval = $("#encounter_id").val();
                }

                var url = $(event.target).attr("url");

                var formData = {
                    fldcurrlocat: fldcurrlocat,
                    fldbed: fldbed,
                    fldencounterval: fldencounterval,
                    consultant: consultant,
                    fldptguardian: fldptguardian,
                    fldrelation: fldrelation,
                    admitted: admitted,
                    fldbillingmode: fldbillingmode,
                    discountMode: discountMode,
                };

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert(data.success.message);
                            $("#show-btn").click();
                            $('#occupied-bed-modal').modal('hide');
                            $("#get_related_fldcurrlocat").html(null);
                            // $("input[name='department_bed']:checked").parent('div').remove();
                            $.get('emergency/department-locat/get-related-locat?fldencounterval=' + fldencounterval, function (data) {
                                $("#get_related_fldcurrlocat").html(data.fldcurrlocat + ' / ' + fldbed);
                            });
                            $('#patientActionButton').html("Transfer");
                            $("#occupied-bed-modal").addClass('complete');
                            //location.reload();
                        } else {
                            showAlert(data.error.message);
                        }
                    },
                    complete: function() {
                        btn.html('Yes').prop('disabled', false);
                    }
                });
            }, 500);
        });
    });
</script>
@endpush
