<div class="modal fade" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="encounter_listLabel">Variables</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            {{--categorytype passed from the controller--}}
                            <input type="text" class="form-control" value="{{ $categorytype }}" id="fldcategoryfield" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="categorynamefield" class="form-control">
                        </div>
                        <div class="col-md-12 mt-2">
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="categoryaddaddbutton">
                                <i class="fas fa-plus"></i> Add</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="categorydeletebutton">
                                <i class="fas fa-trash"></i> Delete</a>
                            <input type="hidden" id="categorytobedeletedroute" value="">
                            <input type="hidden" id="categoryidtobedeleted" value="">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important">
                                <table class="dietary-table">
                                    <ul id="categorylistingmodal">
                                        @forelse($pathocategories as $pathocategory)
                                            <li class="category-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="category_item" data-href="{{ route('deletepathocategory', $pathocategory->fldid) }}" data-id="{{ $pathocategory->fldid }}">{{ $pathocategory->flclass }}</a></li>
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
                    <div class="col-sm-12">
{{--                        <input type="text"  style="margin-right: 117px;">--}}
{{--                        <button class="btn btn-light" style="border: 1px solid #ced4da;"> <i class="fa fa-paste"></i> &nbsp; Import</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .category-list a:focus {
        background-color:#88b9ed;
    }
</style>
