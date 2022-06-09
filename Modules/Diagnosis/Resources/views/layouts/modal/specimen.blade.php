<div class="modal fade" id="specimen_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
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
{{--                            <input type="text" class="form-control" value="Test" id="fldcategoryfield" readonly>--}}
                            <input type="text" id="specimennamefield" class="form-control">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <a href="javascript:void(0)" class="btn btn-light pull-left" id="specimenaddbutton" style="border: 1px solid #ced4da; width: 85px; margin-right: 242px;"><img src="{{ asset('assets/images/plus.png') }}"> &nbsp;<u>A</u>dd</a>
                            <a href="javascript:void(0)" class="btn btn-light pull-right" id="specimendeletebutton" style="border: 1px solid #ced4da; width: 105px;"><img src="{{ asset('assets/images/cancel.png') }}"> &nbsp;<u>D</u>elete</a>
                            <input type="hidden" id="sampletypetobedeletedroute" value="">
                            <input type="hidden" id="sampletypeidtobedeleted" value="">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important">
                                <table class="dietary-table">
                                    <ul id="sampletypelisting">
                                        @forelse($sampletypes as $sampletype)
                                            <li class="sampletypelist" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="sampletype_item" data-href="{{ route('deletespecimen', $sampletype->fldid) }}" data-id="{{ $sampletype->fldid }}">{{ $sampletype->fldsampletype }}</a></li>
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
    .sampletypelist a:focus {
        background-color:#88b9ed;
    }
</style>
