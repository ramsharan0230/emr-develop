<div class="row">
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
              <form action="{{ route('medicines.medicineinfo.adddrug') }}" id="medicineForm" method="POST">
                @csrf
                <input type="hidden" name="fldcodename" id="medicine_fldcodename">
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-3">Medicine Name:</label>
                    <div class="col-sm-9">
                        <input type="text" name="flddrug" id="flddrug" value="{{ old('flddrug') }}" placeholder="" class="form-control" required readonly>
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-3">Dosage Form:</label>
                    <div class="col-sm-4">
                      <select name="fldroute" id="medicineDosage" class="form-control select2 select2DosageForms" required>
                        <option value="">Select Dosage</option>
                        <option value="anal/vaginal" {{ old('fldroute') == 'anal/vaginal' ? 'selected' : '' }}>anal/vaginal</option>
                        <option value="eye/ear" {{ old('fldroute') == 'eye/ear' ? 'selected' : '' }}>eye/ear</option>
                        <option value="fluid" {{ old('fldroute') == 'fluid' ? 'selected' : '' }}>fluid</option>
                        <option value="injection" {{ old('fldroute') == 'injection' ? 'selected' : '' }}>injection</option>
                        <option value="liquid" {{ old('fldroute') == 'liquid' ? 'selected' : '' }}>liquid</option>
                        <option value="oral" {{ old('fldroute') == 'oral' ? 'selected' : '' }}>oral</option>
                        <option value="resp" {{ old('fldroute') == 'resp' ? 'selected' : '' }}>resp</option>
                        <option value="topical" {{ old('fldroute') == 'topical' ? 'selected' : '' }}>topical</option>
                      </select>
                    </div>
                    <label for="" class="col-sm-2">Strength:</label>
                    <div class="col-sm-1">
                      <input type="number" step="any" min="0" name="fldstrength" id="fldstrength" value="{{ old('fldstrength') }}" placeholder="0" class="form-control" required>
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="fldstrunit" id="fldstrunit" value="{{ old('fldstrunit') }}" placeholder="" class="form-control" size="13" required>
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label for="" class="col-sm-3">Min Age (yrs):</label>
                    <div class="col-sm-4">
                      <input type="number" step="any" min="0" name="fldciyear" id="fldciyear" value="{{ old('fldciyear') }}" placeholder="0" class="form-control">
                    </div>
                    <label for="" class="col-sm-2">Reference:</label>
                    <div class="col-sm-3">
                      <input type="text" name="fldreference" id="fldreference" value="{{ old('fldreference') }}" placeholder="" size="20" class="form-control">
                    </div>
                </div>
                <div class="form-group form-row align-items-center er-input">
                    <label class="col-sm-3">Help:</label>
                    <div class="col-sm-4">
                      <input type="text" name="fldhelppage" id="fldhelppage" class="form-control" value="{{ old('fldhelppage') }}" placeholder="" size="20">
                    </div>
                    <div class="col-sm-5">
                       <button type="submit" id="medicineSave" class="btn btn-action btn-primary float-right">Save</button>
                        <a id="clearMedicine" class="btn btn-action btn-primary float-right text-white mr-2">Clear</a>
                    </div>
                </div>
                <div class="table-reponsive table-container">
                  <table class="table table-striped table-bordered table-hover ">
                      <thead class="thead-light">
                          <tr>
                              <th>SNo</th>
                              <th>Brand</th>
                              <th>Batch</th>
                              <th>Expiry</th>
                              <th>SellP</th>
                              <th>Qty</th>
                              <th>Taxable</th>
                              <th>Taxcode</th>
                              <th>Stat</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody class="brand-table-list">
                      </tbody>
                  </table>
              </div>
              </form>
            </div>
        </div>
    </div>
</div>
