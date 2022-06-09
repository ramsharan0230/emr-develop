<div class="row">
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <form action="#" method="post" id="sutureForm">
                <div class="iq-card-body">
                    <div class="form-group form-row align-items-center er-input">
                        <input type="hidden" id="sutureSurgCateg">
                        <label for="" class="col-sm-2">Suture Name:</label>
                        <div class="col-sm-4">
                            <input type="text" name="fldsuturename" id="fldsuturename" value="{{ old('fldsuturename') }}" placeholder="" class="form-control" required readonly>
                        </div>
                        <label for="" class="col-sm-2">Suture Size:</label>
                        <div class="col-sm-4">
                        <input type="text" name="fldsurgsize" id="fldsurgsize" value="{{ old('fldsurgsize') }}" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center er-input">
                        <label for="" class="col-sm-2">Suture Type:</label>
                        <div class="col-sm-3">
                            <select class="form-control suture_types select2" name="fldsurgtype" id="fldsurgtype">
                                <option>---Select Type---</option>
                                @if(isset($get_related_suture_types))
                                    @foreach($get_related_suture_types as $suture_types)
                                        <option value="{{ $suture_types->type }}" rel="{{ $suture_types->fldid }}" rel1="{{ $suture_types->code }}">{{ $suture_types->type }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <a href="javascript:;" data-toggle="modal" data-target="#suture-type" class="get_suture_type_variable btn btn-primary btn-sm-in"><i class="ri-add-fill"></i></a>
                            @include('pharmacist::modal.suture-type')
                        </div>
                        <label for="" class="col-sm-2">Suture Code:</label>
                        <div class="col-sm-4">
                        <input type="text" name="fldsurgcode" readonly id="fldsurgcode" value="{{ old('fldsurgcode') }}" placeholder="" class="form-control">
                        </div>
                    </div>
                    <button id="sutureSave" class="btn btn-primary float-right">Add</button>
                    <a id="clearSuture" class="btn btn-primary float-right text-white mr-2">Clear</a>
                </div>
            </form>
        </div>
    </div>
</div>
