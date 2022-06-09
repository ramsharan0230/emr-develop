<div class="row">
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
          <form action="#" method="post" id="surgOrthoForm">
            <div class="iq-card-body">
                <div class="form-group form-row align-items-center er-input">
                  <input type="hidden" id="surgCateg">
                    <label for="" class="col-sm-2">Item Name:</label>
                    <div class="col-sm-4">
                      <input type="text" name="flditemname" id="flditemname" value="{{ old('flditemname') }}" placeholder="" class="form-control" readonly required>
                    </div>
                    <label for="" class="col-sm-2">Item Size:</label>
                    <div class="col-sm-4">
                      <input type="text" name="flditemsize" id="flditemsize" value="{{ old('flditemsize') }}" placeholder="" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-inputnew FormData($('#labellingForm')[0]);">
                    <label for="" class="col-sm-2">Item Type:</label>
                    <div class="col-sm-4">
                      <input type="text" name="flditemtype" id="flditemtype" value="{{ old('flditemtype') }}" placeholder="" class="form-control">
                    </div>
                </div>
                <button id="msurgOrthoSave" class="btn btn-action btn-primary float-right">Add</button>
                <a id="clearMsurgOrtho" class="btn btn-action btn-primary float-right text-white mr-1">Clear</a>
            </div>
          </form>
        </div>
    </div>
</div>
