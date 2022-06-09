<div class="modal fade" id="sysconstant_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="encounter_listLabel">Variablesdd</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" value="{{ $categorytype }}" id="fldcategoryfieldsysconstant" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="sysconstantnamefield" class="form-control">
                        </div>
                        <div class="col-md-12 col-sm-12 mt-2">
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="sysconstantaddbutton">
                                <i class="fas fa-plus"></i>&nbsp;Add</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="sysconstantdeletebutton">
                                <i class="fas fa-trash"></i>&nbsp;Delete</a>
                            <input type="hidden" id="sysconstanttobedeletedroute" value="">
                            <input type="hidden" id="sysconstanttobedeleted" value="">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important">
                                <table class="dietary-table">
                                    <ul id="sysconstantlistingmodal">
                                        @forelse($sysconsts as $sysconst)
                                            <li class="sysconstantlist" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="sysconst_item" data-href="{{ route('deletesysconstant', $sysconst->fldsysconst) }}" data-sysconstant="{{ $sysconst->fldsysconst }}">{{ $sysconst->fldsysconst }}</a></li>
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
{{--                        <input type="text" style="margin-right: 117px;">--}}
{{--                        <button class="btn btn-light" style="border: 1px solid #ced4da;"> <i class="fa fa-paste"></i> &nbsp; Import</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .sysconstantlist a:focus {
        background-color:#88b9ed;
    }
</style>
