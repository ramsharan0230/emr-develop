<div class="modal fade" id="add_food" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" id="encounter_listLabel">Variables</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <input type="text" name="food_name" id="foodnamefield" class="form-control">
                            </div>
                            <br><br>
                            <div class="col-md-12 col-sm-12">
                                <a href="javascript:void(0)" class="btn btn-light pull-left" style="border: 1px solid #ced4da; margin-right: 242px;" id="foodnameaddbutton"><img src="{{ asset('assets/images/plus.png') }}" style="width: 15px;"> &nbsp;<u>A</u>dd</a>
                                <a href="javascript:void(0)" class="btn btn-light pull-right" style="border: 1px solid #ced4da;" id="foodnamedeletebutton"><img src="{{ asset('assets/images/cancel.png') }}" style="width: 15px;"> &nbsp;<u>D</u>elete</a>
                                <input type="hidden" id="foodtobedeletedroute" value="">
                                <input type="hidden" id="foodidtobedeleted" value="">
                            </div>
                            <br><br>
                            <div class="col-md-12 col-sm-12 mt-1">
                                <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important">
                                    <ul id="foodnamelistingmodal">
                                        @forelse($foodlists as $foodlist)
                                            <li class="food-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="food_item" data-href="{{ route('deletefoodname', $foodlist->fldid) }}" data-id="{{ $foodlist->fldid }}">{{ $foodlist->fldfood }}</a></li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
{{--                                <input type="text" name="foodname_bottom" style="margin-right: 117px;">--}}
{{--                                <button class="btn btn-light" style="border: 1px solid #ced4da;"> <i class="fa fa-paste"></i> &nbsp; Import</button>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<style type="text/css">
    .food-list a:focus {
        background-color:#88b9ed;
    }
</style>
