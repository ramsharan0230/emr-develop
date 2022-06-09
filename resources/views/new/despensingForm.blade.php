@extends('frontend.layouts.master') @section('content')
<div class="iq-top-navbar second-nav">
  <div class="iq-navbar-custom">
    <nav class="navbar navbar-expand-lg navbar-light p-0">
      <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="ri-menu-3-line"></i>
      </button> -->
      <!-- <div class="iq-menu-bt align-self-center">
        <div class="wrapper-menu">
          <div class="main-circle"><i class="ri-more-fill"></i></div>
          <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
        </div>
      </div> -->
      <div class="navbar-collapse">
        <ul class="navbar-nav navbar-list">
          <li class="nav-item">
            <a class="search-toggle iq-waves-effect language-title" href="#">File</a>
          </li>
          <li class="nav-item">
            <a class="search-toggle iq-waves-effect language-title" href="#">Application</a>
          </li>
          <li class="nav-item">
            <a class="search-toggle iq-waves-effect language-title" href="#">Report</a>
          </li>
          <li class="nav-item">
            <a class="search-toggle iq-waves-effect language-title" href="#">History</a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</div>
<!-- TOP Nav Bar END -->
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
            <h3 class="card-title">
              Despensing Form
            </h3>
          </div>
        </div>
        <div class="iq-card-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group form-row">
                <div class="col-sm-8">
                  <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" />
                    <label class="custom-control-label">Outpatient</label>
                  </div>
                  <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" />
                    <label class="custom-control-label">Inpatient</label>
                  </div>
                  <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" />
                    <label class="custom-control-label">Aseptic</label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <input type="text" class="form-control" name="" />
                </div>
                <div class="col-sm-2">
                  <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" />
                    <label class="custom-control-label">Odered</label>
                  </div>
                </div>
              </div>
              <div class="form-group form-row">
                <div class="col-sm-1">
                  <input type="checkbox" name="" value="" />
                </div>
                <div class="col-sm-4">
                  <select name="" class="form-control">
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
                <div class="col-sm-4">
                  <select name="" class="form-control">
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
                <div class="col-sm-3">
                  <input type="text" name="" value="" class="form-control" />
                </div>
              </div>
              <div class="form-group form-row">
                <label class="col-2">Last Date:</label>
                <div class="col-sm-4">
                  <input type="text" name="" value="" class="form-control" />
                </div>
                <div class="col-sm-1">
                  <button class="btn btn-primary btn-sm-in"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                </div>
                <label class="col-1">Qty:</label>
                <div class="col-sm-4">
                  <input type="text" name="" value="" class="form-control" placeholder="0" />
                </div>
              </div>
              <div class="form-group form-row">
                <label class="col-3">Dispensed Amt:</label>
                <div class="col-sm-4">
                  <input type="text" name="" value="" class="form-control" placeholder="0" />
                </div>
                <div class="col-sm-3">
                  <select name="" class="form-control" placeholder="0">
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <select name="" class="form-control" placeholder="0">
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group form-row">
                <div class="col-sm-8">
                  <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" />
                    <label class="custom-control-label">Dispensed</label>
                  </div>
                  <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" />
                    <label class="custom-control-label">Generic</label>
                  </div>
                  <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" />
                    <label class="custom-control-label">Brand</label>
                  </div>
                </div>
                <label class="col-1">Total:</label>
                <div class="col-sm-3">
                  <input type="text" name="" value="" class="form-control" placeholder="0" />
                </div>
              </div>
              <div class="form-group form-row">
                <div class="col-sm-2">
                  <input type="text" name="" value="" class="form-control" placeholder="0" />
                </div>
                <div class="col-sm-3">
                  <select name="" class="form-control">
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <input type="text" name="" value="" class="form-control" placeholder="0" />
                </div>
                <div class="col-sm-3">
                  <input type="text" name="" value="" class="form-control" />
                </div>
                <div class="col-sm-2">
                  <input type="text" name="" value="" class="form-control" placeholder="0" />
                </div>
              </div>
              <div class="form-group form-row">
                <div class="col-sm-2">
                  <input type="checkbox" name="" value="" />
                  <label>Expiry</label>
                </div>
                <label class="col-1">Rate</label>
                <div class="col-sm-9">
                  <input type="text" name="" value="" class="form-control" placeholder="0" />
                </div>
              </div>
              <div class="form-group form-row">
                <label class="col-2">Stock</label>
                <div class="col-sm-10">
                  <input type="text" name="" value="" class="form-control" placeholder="0" />
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
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
