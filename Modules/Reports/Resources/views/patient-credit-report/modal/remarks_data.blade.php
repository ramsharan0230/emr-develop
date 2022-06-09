<div class="row">
    <div class="col-sm-6">
        <h4>Encounter Id:{{$remarks->fldencounterval}}/Bill No.:{{$remarks->fldbillno}}</h4>
    </div>
</div>
{{-- <form method="POST" action="{{ route('post.patient.credit.remarks',[$remarks->fldid]) }}"> --}}
    {{ csrf_field() }}
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="">Remarks</label>
            <textarea rows="8" class="form-control" name="remarks" readonly>{{$remarks->remarks}}</textarea>
        </div>
    </div>
</div>
<div class="row">
    {{-- <div class="col-sm-6">
        <button type="subimt" class="btn btn-info">save</button>
    </div> --}}
</div>
{{-- </form> --}}