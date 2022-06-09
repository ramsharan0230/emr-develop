<div class="modal fade" id="add_category" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                            <input type="text" name="category_name" id="categorynamefield" class="form-control">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <a href="javascript:void(0)" class="btn btn-light pull-left" id="categorynameaddbutton" style="border: 1px solid #ced4da; margin-right: 242px;"><img src="{{ asset('assets/images/plus.png') }}" style="width: 15px;"> &nbsp;<u>A</u>dd</a>
                            <a href="javascript:void(0)" class="btn btn-light pull-right" style="border: 1px solid #ced4da;" id="categorydeletebutton"><img src="{{ asset('assets/images/cancel.png') }}" style="width: 15px;"> &nbsp;<u>D</u>elete</a>
                            <input type="hidden" id="categorytobedeletedroute" value="">
                            <input type="hidden" id="categoryidtobedeleted" value="">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12 mt-1">
                            <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important">
                                <table class="dietary-table">
                                    <ul id="categorylistingmodal">
                                        @forelse($foodtypes as $foodtype)
                                            <li class="foodtype-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="foodtype_item" data-href="{{ route('deletefoodtype', $foodtype->fldid) }}" data-id="{{ $foodtype->fldid }}">{{ $foodtype->fldfoodtype }}</a></li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
{{--                        <input type="text" name="foodcategory_bottom" style="margin-right: 117px;">--}}
{{--                        <button class="btn btn-light" style="border: 1px solid #ced4da;"> <i class="fa fa-paste"></i> &nbsp; Import</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .foodtype-list a:focus {
        background-color:#88b9ed;
    }
</style>

