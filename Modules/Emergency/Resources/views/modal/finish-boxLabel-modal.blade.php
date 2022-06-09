<div class="modal fade" id="finish_box" tabindex="-1" role="dialog" aria-labelledby="finish_boxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Planned Consultations</h4>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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

                                            <td><a href="javascript:;" reset_url="{{ route('emergency.reset.encounter') }}" class="tick_plan_consult" url="{{ route('planned_consultant') }}" fldid="{{$con->fldid}}">  <i class="fa fa-check"></i></a></td>

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>

                        </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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

            window.location.href = '{{ route('reset.encounter') }}';
        } else {
            showAlert("Something went wrong!!");
        }
    }
});

});
    $(document).on("click", ".tick_plan_consult", function () {

// alert('s');
            var encounter_id = $('#encounter_id').val();
            var fldid = $(this).attr('fldid');
            var url = $(this).attr('url');
            var here = $(this);

            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {fldid: fldid, encounter_id: encounter_id},
                success: function (data) {
                    console.log(data);
                    if ($.isEmptyObject(data.error)) {
                        here.parent('td').parent('tr').remove();
                        $("#finish_box").modal('hide');
                        showAlert("Information Saved!!");
                        var resetform = here.attr('reset_url');
                        window.location = resetform;
                    } else {
                        showAlert("Something went wrong!!");
                    }
                }
            });

        });
</script>
