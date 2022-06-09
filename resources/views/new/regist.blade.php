 @extends('frontend.layouts.master')
 @section('content')
 <div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">
              Regist Auto Billing
            </h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="form-group form-row align-items-center">
            <label class="col-sm-2">Group Name</label>
            <div class="col-sm-8">
              <select name="" id="" class="form-control">
                <option value="0">---select---</option>
              </select>
            </div>
            <div class="col-sm-2">
              <a href="#" class="btn btn-primary">View list</a>
            </div>
          </div>
          <div class="form-group form-row align-items-center">
            <label class="col-sm-2">Billing Mode</label>
            <div class="col-sm-10">
              <select name="" id="" class="form-control">
                <option value="0">---select---</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">
              Components
            </h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="form-group form-row align-items-center">
           <div class="col-sm-4">
            <select name="" id="" class="form-control">
              <option value="0">---select---</option>
            </select>
          </div>
          <div class="col-sm-5">
            <select name="" id="" class="form-control">
              <option value="0">---select---</option>
            </select>
          </div>
          <label class="col-sm-1">QTY</label>
          <div class="col-sm-2">
            <input type="text" class="form-control" name="" placeholder="0">
          </div>
        </div>

        <div class="form-group form-row align-items-center">
          <div class="col-sm-4 form-row ">
            <label class="col-sm-2">Timing</label>
            <div class="col-sm-10">
              <select name="" id="" class="form-control">
                <option value="0">---select---</option>
              </select>
            </div>
          </div>
          <div class="col-sm-4 form-row ">
            <label class="col-sm-2">Cutoff</label>
            <div class="col-sm-10">
              <select name="" id="" class="form-control">
                <option value="0">---select---</option>
              </select>
            </div>
          </div>
          <div class="col-sm-4 form-row form-group ">
            <button class="btn btn-primary">Save</button>&nbsp;
            <button class="btn btn-primary">update</button>&nbsp;
            <button class="btn btn-primary"><input type="checkbox" name=""></button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
      <div class="iq-card-body">
        <div class="table-responsive table-scroll-regist">
          <table class="table table-bordered table-sm">
           <tbody></tbody>
         </table>
       </div>
     </div>
   </div>
 </div>
</div>
</div>
@endsection