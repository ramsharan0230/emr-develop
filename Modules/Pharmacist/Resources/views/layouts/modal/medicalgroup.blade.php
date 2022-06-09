<div class="modal fade" id="med_group_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="encounter_listLabel">Variables</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 form-group">
                        <input type="text" id="dosageformfield" class="form-control">
                    </div>
                    <div class="col-md-12 col-sm-12 text-right">
                        <a href="javascript:void(0)" class="btn btn-primary  " id="dosageaddbutton" ><i class="fa fa-plus"></i>&nbsp;<u>A</u>dd</a>
                        <a href="javascript:void(0)" class="btn btn-primary" id="dosagedeletebutton"><i class="fa fa-times"></i>&nbsp;<u>D</u>elete</a>
                        <input type="hidden" id="dosagetobedeletedroute" value="">
                        <input type="hidden" id="dosageidtobedeleted" value="">
                    </div>
                    <br><br>
                    <div class="col-md-12 col-sm-12">
                        <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important">
                            <table class="dietary-table">
                                <ul id="dosagelistingmodal" class="list-group">
                                    @forelse($medgroups as $medgroup)
                                        <li class="dosage-list list-group-item" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="dosage_item" data-href="{{ route('pharmacist.protocols.deletemedgroup', $medgroup->fldid) }}" data-id="{{ $medgroup->fldid }}">{{ $medgroup->fldmedgroup }}</a></li>
                                    @empty
                                    @endforelse
                                </ul>
                            </table>
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
    .dosage-list a:focus {
        background-color:#88b9ed;
    }
</style>
