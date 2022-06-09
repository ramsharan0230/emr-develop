<form class="" method="post" action="{{route('patient.dataview.pdf.sample')}}">
@csrf
    <div class="form-inline">
        <label>Select SampleID of the report</label>
        <select name="sample_id" id="sample_id" class="form-control mb-2 mr-sm-2">
          
            @foreach($samplewise as $sw)
            <option value="{{$sw->fldsampleid}}">{{ $sw->fldsampleid }}</option>
            @endforeach
        </select>
<input type="hidden" name="encounter_id" value="{{$encounter_id}}" />

    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="waiting-result"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
        <input type="submit" name="submit" id="submitsamplelab" class="btn btn-primary" value="OK">
    </div>
</form>

<script>
    $("#submitsamplelab").on('click', function() {

        $('#file-modal').modal('hide');

    });
</script>
