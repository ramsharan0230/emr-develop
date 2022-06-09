<style>
    .search-result-refere {
        height: 240px;
        background: #fff;
        border: 1px solid #333;
    }
</style>
<div class="modal fade" id="show-refere-modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="inpatient__modal_title">Refer Patient</h4>
                <button type="button" class="close inpatient__modal_close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box">
                                <div class="box__label">
                                    <input type="checkbox" id="abc"/>
                                    <label class="remove_some_css" for="abc"></label>
                                </div>
                                <div class="box__input">
                                    <select name="refer_to_location" class="form-control form-control-sm" id="location">
                                        <option value=""></option>
                                        @if(isset($referlist) and count($referlist) > 0)
                                            @foreach($referlist as $list)
                                                <option value="{{$list->fldlocation}}">{{$list->fldlocation}}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                    <input type="hidden" name="encounterId" value="{{$enpatient->fldencounterval??''}}">
                                </div>
                                <div class="box__icon">
                                    <button>Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="search-result-refere">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="group__box">
                                <div class="box__label">
                                    <label class="label-width">Custom</label>
                                </div>
                                <div class="box__input">
                                    <input type="text" id="fldheadOutcomerefere" value="">
                                </div>
                                <div class="box__icon">
                                    <button type="button" class="btn btn-primary" id="save-refere-modal" url="{{ route('outcome.refere.save') }}" data-dismiss="modal">Save</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
