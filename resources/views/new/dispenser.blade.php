@extends('frontend.layouts.master')
@section('content')
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
        <a class="search-toggle iq-waves-effect language-title" href="#">Report</a>
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
                        <h4 class="card-title">
                           Deposit Form
                       </h4>
                   </div>
               </div>
               <div class="iq-card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group form-row align-items-center er-input">
                            <label for="" class="col-sm-3">Encounter Id:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" />
                            </div>
                            <div class="col-sm-5">
                                <button class="btn btn-primary"><i class="fa fa-camera-retro" aria-hidden="true"></i></button>
                                <button class="btn btn-primary"><i class="fa fa-calculator" aria-hidden="true"></i></button>
                                <button class="btn btn-primary"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;Show</button>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center er-input">
                            <label for="" class="col-sm-3">Full Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-row align-items-center er-input">
                            <label for="" class="col-sm-5">Location:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center er-input">
                            <label for="" class="col-sm-5">Expense:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group form-row align-items-center er-input">
                            <label for="" class="col-sm-4">Age/Sex:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center er-input">
                            <label for="" class="col-sm-4">Payment:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" />
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
                <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">Billing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Admission</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent-2">
                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-3">Prev Deposit:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-3">Curr Deposit:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <h5 class="col-6">Expenses</h5>
                                    <div class="col-sm-6 text-right">
                                        <button class="btn btn-primary"><i class="fa fa-sync" aria-hidden="true"></i></button>
                                        <button class="btn btn-primary"><i class="fa fa-code" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive table-dispenser">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row align-items-center er-input">
                                    <div class="col-sm-3">
                                        <div class="custom-control col-3 custom-checkbox custom-checkbox-color-check custom-control-inline">
                                            <input type="checkbox" class="custom-control-input bg-primary" />
                                            <label class="custom-control-label"> Cheque</label>
                                        </div>
                                    </div>
                                    <label for="" class="col-sm-3">Receive Amount:</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <div class="col-sm-5">
                                        <button class="btn btn-primary"><i class="fa fa-calculator" aria-hidden="true"></i></button>
                                        <button class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</button>
                                        <button class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Clear</button>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <h5 class="col-6">Invoices</h5>
                                    <div class="col-sm-6 text-right">
                                        <button class="btn btn-primary"><i class="fa fa-sync" aria-hidden="true"></i></button>
                                        <button class="btn btn-primary"><i class="fa fa-code" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive table-dispenser">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-2">Comment:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-2">Dairy No.:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row align-items-center er-input">
                                    <label for="" class="col-sm-2">Consultant:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary"><i class="ri-stethoscope-fill" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary"><i class="fa fa-mobile" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center er-input">
                                    <div class="col-sm-3">
                                        <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                            <input type="checkbox" class="custom-control-input bg-primary" />
                                            <label class="custom-control-label"> Admission</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;OK</button>
                                        <button class="btn btn-primary"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Status</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive table-dispenser">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>EncID</th>
                                                <th>RegDate</th>
                                                <th>Status</th>
                                                <th>BillMode</th>
                                                <th>Discount</th>
                                                <th>DOA</th>
                                                <th>RegdDept</th>
                                                <th>DiscDate</th>
                                                <th>CurrDept</th>
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
        </div>
    </div>
</div>
</div>
@endsection
