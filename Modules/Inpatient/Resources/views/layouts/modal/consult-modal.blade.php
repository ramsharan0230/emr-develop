<div class="modal fade" id="inpatient_consultant_list" tabindex="-1" role="dialog" aria-labelledby="consultant_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h5 class="inpatient__modal_title" id="consultant_listLabel" style="text-align: center;">Choose Consultants</h5>
                    <button type="button" class="close onclose inpatient__modal_close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(!empty($consultants))
                        @foreach($consultants as $con)
                            <div class="form-modal">
                                <input type="radio" name="consultant" class="consultant_choosed form-control" value="{{ $con->fldusername }}">
                                <label> {{ $con->fldusername }}</label>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitconsultant_list" url="{{route('inpatient.save.consultant')}}" data-dismiss="modal">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>