<div class="container-fluid">
    <form>
        @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="res-table">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th>DateTime</th>
                                <th>Consultation</th>
                                <th>Consultant</th>
                                <th>&nbsp;&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody class="list_of_complaint">
                            @if(isset($plannedConsultants))
                                @foreach($plannedConsultants as $con)
                                    <tr>
                                        <td>{{ $con->fldconsulttime }}</td>
                                        <td>{{ $con->fldconsultname }}</td>
                                        <td>{{ $con->flduserid }}</td>

                                        <td>
                                            <a href="javascript:;" reset_url="{{ route('reset.encounter') }}" class="tick_plan_consult" url="{{ route('planned_consultant') }}" fldid="{{$con->fldid}}">
                                            <i class="fa fa-check"></i>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary onclose close_finish" url="{{ route('close_finish') }}">Close</button>
            </div>
    </form>
</div>
<script>
$(document).on("click", ".close_finish", function () {
    var encounter_id = $('#encounter_id').val();
    var url = $(this).attr('url');
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: {encounter_id: encounter_id},
        success: function (data) {
            console.log(data);
            if ($.isEmptyObject(data.error)) {

                window.location.href = data.success.redirectto;
            } else {
                showAlert("Something went wrong!!");
            }
        }
    });
});

$(document).on("click", ".tick_plan_consult", async function () {
    var encounter_id = $('#encounter_id').val();
    var fldid = $(this).attr('fldid');
    var url = $(this).attr('url');
    var here = $(this);
    await $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: {fldid: fldid, encounter_id: encounter_id},
        success: function (data) {
            console.log(data);
            if ($.isEmptyObject(data.error)) {
                here.parent('td').parent('tr').remove();
                $("#finish_box").modal('hide');
                sessionStorage.setItem('save_for_waitingform_trigger',true);
                var resetform = here.attr('reset_url');
                window.location = resetform;
                console.log('teserfdfsdfsdafsdfsdf');
            } else {
                showAlert("Something went wrong!!");
            }
        }
    });
});
</script>