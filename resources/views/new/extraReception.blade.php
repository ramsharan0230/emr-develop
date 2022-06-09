@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h4 class="card-title">
              Extra Reception
            </h4>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group form-row">
                <label class="col-sm-2">Paid By:</label>
                <div class="col-sm-9">
                  <select class="form-control" value="">
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
                <div class="col-sm-1">
                  <button class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="form-group form-row">
                <label class="col-sm-2">Paid for:</label>
                <div class="col-sm-9">
                  <select class="form-control" value="">
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
                <div class="col-sm-1">
                  <button class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="form-group form-row">
                <label class="col-sm-2">Paid Amt:</label>
                <div class="col-sm-10">
                  <input type="text" name="" value="" class="form-control"/>
                </div>
              </div>
              <div class="form-group form-row">
                <label class="col-sm-2">Recv By:</label>
                <div class="col-sm-10">
                  <input type="text" name="" value="" class="form-control"/>
                </div>
              </div>
              <div class="form-group form-row ">
                <button class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add</button>&nbsp;
                <button class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Save</button>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group form-row">
                <label class="col-sm-2">Address:</label>
                <div class="col-sm-10">
                 <input type="text" name="" value="" class="form-control"/>
               </div>
             </div>
             <div class="form-group form-row">
              <label class="col-sm-2">Refrence:</label>
              <div class="col-sm-10">
                <input type="text" name="" value="" class="form-control"/>
              </div>
            </div>
            <div class="form-group form-row">
              <label class="col-sm-2">Mode:</label>
              <div class="col-sm-10">
                <select class="form-control" value="">
                  <option value=""></option>
                  <option value=""></option>
                  <option value=""></option>
                </select>
              </div>
            </div>
            <div class="form-group form-row">
              <label class="col-sm-2">Post:</label>
              <div class="col-sm-10">
                <input type="text" name="" value="" class="form-control"/>
              </div>
            </div>
            <div class="form-group form-row float-right">
              <button class="btn btn-primary"data-toggle="modal" data-target="#CustomerProfileModal"><i class="fa fa-code" aria-hidden="true"></i>&nbsp;Show</button>&nbsp;
              <button class="btn btn-primary"><i class="fa fa-code" aria-hidden="true"></i>&nbsp;Export</button>
            </div>
            <div id="CustomerProfileModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true" style="display: none;">
              <div class="modal-dialog" role="document">
               <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="gridModalLabel">Customer Profile</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                 <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group form-row">
                      <label class="col-sm-2">Name:</label>
                      <div class="col-sm-9">
                        <select class="form-control" value="">
                          <option value=""></option>
                          <option value=""></option>
                          <option value=""></option>
                        </select>
                      </div>
                      <div class="col-sm-1">
                        <button class="btn btn-primary"><i class="fa fa-sync" aria-hidden="true"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group form-row">
                      <label class="col-sm-2">Address:</label>
                      <div class="col-sm-10">
                        <select class="form-control" value="">
                          <option value=""></option>
                          <option value=""></option>
                          <option value=""></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group form-row ">
                      <button class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Export</button>
                    </div>
                  </div>
                  <div class="col-sm-6 ">
                    <div class="form-group form-row float-right">
                      <button class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add</button>&nbsp;
                      <button class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Delete</button>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="table-responsive table-dispensing">
                      <table class="table table-bordered table-hover table-striped">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary">Save changes</button>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
</div>
<div class="col-sm-12">
  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
    <div class="iq-card-body">
      <div class="table-responsive table-dispensing">
        <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th></th>
              <th>Catogery</th>
              <th>Particulars</th>
              <th>Share %</th>
              <th>Tax %</th>
              <th></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection
