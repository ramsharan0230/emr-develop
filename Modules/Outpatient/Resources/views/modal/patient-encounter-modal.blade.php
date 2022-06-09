@php
    $segment = Request::segment(1);
    if($segment == 'admin'){
        $segment2 = Request::segment(2);
        $segment3 = Request::segment(3);
        if(!empty($segment3))
        $route = 'admin/'.$segment2 . '/'.$segment3;
        else
        $route = 'admin/'.$segment2;

    }else{
        $route = $segment;
    }
@endphp
<div class="modal fade" id="encounter_list" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="encountercall" action="{{$route != 'admin/laboratory/addition'?route($route):''}}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Choose Encounter ID</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="ajax_response_encounter_list">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" id="submitencounter_list" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
