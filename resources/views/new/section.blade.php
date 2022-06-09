@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
       <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
          <h4 class="card-title">
            All Section
          </h4>
        </div>
      </div>
      <div class="iq-card-body">
        <div class="form-row">
          <div class="col-sm-6">
            <nav aria-label="...">
              <ul class="pagination">
                <li class="page-item disabled">
                  <a
                  class="page-link"
                  href="#"
                  tabindex="-1"
                  aria-disabled="true"
                  >Previous</a
                  >
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item active" aria-current="page">
                  <a class="page-link" href="#"
                  >2 <span class="sr-only">(current)</span></a
                  >
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">Next</a>
                </li>
              </ul>
            </nav>
          </div>
          <div class="col-sm-6">
            <div class="form-group form-row float-right">
              <label class="col-sm-3">Lab No.</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="">
              </div>
              <div class="col-sm-4">
                <button type="button" class="btn btn-primary">Search</button>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive table-scroll-section mt-2">
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th colspan="5"><button type="button" class="btn btn-primary"><i class="fa fa-bars" aria-hidden="true"></i></button>&nbsp;
                &nbsp;Samples Or orders are not confirming</th>
                <th colspan="3">
                  <div class="er-input form-row float-right">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" checked="">
                      <label class="custom-control-label" >Verify All</label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" >
                      <label class="custom-control-label" >Unverify All</label>
                    </div>
                  </div>
                </th>
              </tr>
              <tr>
                <th>Lab No.</th>
                <th>Test Name</th>
                <th>Result</th>
                <th>Abnormal</th>
                <th>Accept</th>
                <th>Reject</th>
                <th>Uploaded Files</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>0123457</td>
                <td>Ps to RBC Morphology</td>
                <td class="er-input"> <input type="text" class="form-control col-3" placeholder="32" name="">&nbsp;<label>%(45.0-75.0</label></td>
                <td><input type="checkbox" placeholder="32" name=""></td>
                <td><input type="checkbox" placeholder="32" name="" checked=""></td>
                <td><input type="checkbox" placeholder="32" name=""></td>
                <td></td>
                <td><button type="button" class="btn btn-primary"><i class="fa fa-notes-medical"></i></button></td>
              </tr>
              <tr>
                <td>0123457</td>
                <td>Ps to RBC Morphology</td>
                <td class="er-input"> <input type="text" class="form-control col-3" placeholder="32" name="">&nbsp;<label>%(45.0-75.0</label></td>
                <td><input type="checkbox" placeholder="32" name=""></td>
                <td><input type="checkbox" placeholder="32" name="" checked=""></td>
                <td><input type="checkbox" placeholder="32" name=""></td>
                <td></td>
                <td><button type="button" class="btn btn-primary"><i class="fa fa-notes-medical"></i></button></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="pagination mt-2">
          <nav aria-label="...">
            <ul class="pagination">
              <li class="page-item disabled">
                <a
                class="page-link"
                href="#"
                tabindex="-1"
                aria-disabled="true"
                >Previous</a
                >
              </li>
              <li class="page-item">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item active" aria-current="page">
                <a class="page-link" href="#"
                >2 <span class="sr-only">(current)</span></a
                >
              </li>
              <li class="page-item">
                <a class="page-link" href="#">3</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">Next</a>
              </li>
            </ul>
          </nav>
        </div>
        <div class="d-flex justify-content-center mt-3">
          <div class="form-group">
           <button class="btn btn-primary">Save</button>
           <button class="btn btn-secondary">Cancel</button>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
</div>
@endsection
