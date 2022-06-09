<div class="form-group form-row align-items-center row">
    <div class="col-sm-3">
        <select name="waiting_fldid" class="form-control" id="waiting_fldid">
            <option value="">--Select--</option>
            @foreach($flddept as $fld)
                <option value="{{ $fld->name}}" @if($fld->id==$hospital_department) selected @endif>{{ $fld->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2">
        <input type="text" class="form-control" name="encounter_id_waiting" id="encounter-waiting" placeholder="Encounter">
    </div>
    <div class="col-sm-3">
        <div class="form-group form-row align-items-center er-input">
            <label for="" class="col-sm-3">Form:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="from_date_waiting" autocomplete="off">
                <input type="hidden" name="from_date_waiting" id="from_date_waiting_eng">
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group form-row align-items-center er-input">
            <label for="" class="col-sm-3">To:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="to_date_waiting" autocomplete="off">
                <input type="hidden" name="to_date_waiting" id="to_date_waiting_eng">
            </div>
        </div>
    </div>
    <div class="col-sm-1">
        <button class="btn btn-primary" onclick="FileWaiting.listWaiting()"><i class="ri-refresh-line"></i> Search</button>
    </div>
</div>
<hr>
<div class="res-table">
    <table class="table table-hover table-bordered table-striped">
        <thead class="thead-light">
        <tr>
            <th>Encounter</th>
            <th>Name</th>
            <th>Department</th>
            <th>Date Time</th>
            <th></th>
        </tr>
        </thead>
        <tbody class="waiting-result"></tbody>
    </table>
</div>
<script>
    $(window).ready(function () {
        $('#to_date_waiting').val(AD2BS('{{date('Y-m-d')}}'));
        $('#from_date_waiting').val(AD2BS('{{date('Y-m-d')}}'));

    })

    $('#to_date_waiting').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        npdYearCount: 10 // Options | Number of years to show
    });
    $('#from_date_waiting').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        npdYearCount: 10 // Options | Number of years to show
    });

    var FileWaiting = {
        listWaiting: function () {
            $('#to_date_waiting_eng').val(BS2AD($('#to_date_waiting').val()));
            $('#from_date_waiting_eng').val(BS2AD($('#from_date_waiting').val()));
            var fldid = $('#waiting_fldid').children("option:selected").val()
            var encounter_waiting = $('#encounter-waiting').val()

            var from_date = $('#from_date_waiting_eng').val();
            var to_date = $('#to_date_waiting_eng').val();
            $.ajax({
                url: '<?php echo(route('patient.file.menu.waiting.result')); ?>',
                type: "POST",
                data: {fldid: fldid, encounter: encounter_waiting, from_date:from_date, to_date:to_date},
                success: function (response) {
                    $('.waiting-result').empty();
                    $('.waiting-result').append(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }

    // $(".callencounter").click(function(){
    $('body').on('click', 'a.callencounter', function () {
        $('#to_date_waiting_eng').val(BS2AD($('#to_date_waiting').val()));
        $('#from_date_waiting_eng').val(BS2AD($('#from_date_waiting').val()));
        var encounterid = $(this).attr('encounter_id');
        var consult_id = $(this).attr('consult_id');
        var roomnum = $('.callroom').val();
        if (roomnum == '') {
            alert('Please specify the room no.');
            $('.callroom').focus();
            $("#file-modal").modal('hide');
        }

        $.ajax({
            url: '<?php echo(route('patient.file.menu.call.waiting')); ?>',
            type: "POST",
            dataType: "json",
            data: {encounterid: encounterid, roomnum: roomnum, consult_id: consult_id},
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    $("#file-modal").modal('hide');
                    $('#patient_id_submit').val(data.success.patient);

                } else {
                    showAlert("Something went wrong!!");
                }
            }
        });
    })
</script>
