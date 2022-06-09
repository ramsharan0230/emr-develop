@extends('frontend.layouts.master')
@section('content')
<style>
    .list {
        height: 400px;
        border: 1px solid #e3e3e3;
        padding 5px;
        border-radius: 5px;
        overflow: auto;
    }

    .list ul {
        list-style: none;
    }

    .list ul li {
        padding: 5px 10px;
    }

    .list ul li:hover {
        background: #144069;
        color: white;
    }

    .list ul li.active {
        background: #144069;
        color: white;
    }

    .check-box {
        border-bottom: 1px solid #e3e3e3;
    }

    .child-box {
        display: flex;
        flex-wrap: wrap;
        margin-left: 50px;
    }

    .inputs {
        display: flex;
        flex-direction: row;
        padding: 2px;
        align-items: center;
    }

    .roles-header {
        padding: 3px 20px;;

    }


</style>
    <div class="container-fluid">
      <ul class="nav nav-tabs">
         {{-- <li class="nav-item">
               <a class="nav-link" style="background-color: unset;" aria-current="page" href="#">Permission View</a>
         </li> --}}
         <li class="nav-item">
               <a class="nav-link active" style="background-color: unset;" aria-current="page" href="#">Add Permission</a>
         </li>
      </ul>
      <div class="row">
         <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title">
                        <h4 class="card-title">Add New Permission</h4>
                  </div>
                  <div class="d-flex flex-row">
                    <button type="button" class="btn btn-outline-primary"><i class="fa fa-sync"></i>&nbsp;Reset</button>
                    <button type="button" class="btn btn-primary ml-1"><i class="fa fa-eye"></i>&nbsp;Preview</button>
                  </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Name</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <input type="text" class="form-control" id="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Description</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <textarea name="flddescription" style="width: 100%" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                        <div class="form-group form-row flex-column align-items-start">
                                            <label class="col-lg-12 col-md-12">Status</label>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="d-flex flex-row">
                                                    <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class=" custom-control-input" id="">
                                                        <label for="" class="custom-control-label">Active</label>
                                                    </div>
                                                    <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class=" custom-control-input" id="">
                                                        <label for="" class="custom-control-label">Inactive</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row justify-content-between align-items-end mb-1">
                                    <h6>Menu</h6>
                                    <div class="inputs">
                                        <input type="checkbox" id="" value="">
                                        <label class="ml-1" for="">All</label>
                                    </div>
                                </div>
                                <div class="col-md-12 p-0 mb-1">
                                    <input type="text" class="form-control" id="" placeholder="Search">
                                </div>
                                <div class="list">
                                    <ul>
                                        <li class="active">Profile Setup</li>
                                        <li class="">Package Setup</li>
                                        <li class="">Service Setup</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row justify-content-between align-items-end mb-1">
                                    <h6>Roles</h6>
                                    <div class="inputs">
                                        <input type="checkbox" id="" value="">
                                        <label class="ml-1" for="">All</label>
                                    </div>
                                </div>
                                <div class="col-md-12 p-0 mb-1">
                                    <input type="text" class="form-control" id="" placeholder="Search">
                                </div>
                                <div class="list">
                                    <div class="check-box">
                                        <div class="inputs roles-header">
                                            <input type="checkbox" id="" value="">
                                            <label class="ml-1" for="">Role Header</label>
                                        </div>
                                        <div class="child-box">
                                            <div class="inputs col-md-6">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Role 1</label>
                                            </div>
                                            <div class="inputs col-md-6">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Role 1</label>
                                            </div>
                                            <div class="inputs col-md-6">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Role 1</label>
                                            </div>
                                            <div class="inputs col-md-6">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Role 1</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="check-box">
                                        <div class="inputs roles-header">
                                            <input type="checkbox" id="" value="">
                                            <label class="ml-1" for="">Role Header</label>
                                        </div>
                                        <div class="child-box">
                                            <div class="inputs col-md-6">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Role 1</label>
                                            </div>
                                            <div class="inputs col-md-6">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Role 1</label>
                                            </div>
                                            <div class="inputs col-md-6">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Role 1</label>
                                            </div>
                                            <div class="inputs col-md-6">
                                                <input type="checkbox" id="" value="">
                                                <label class="ml-1" for="">Role 1</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="float-right mt-1">
                        <button type="button" class="btn btn-primary btn-action">Save</button>
                    </div>
                </div>
            </div>
         </div>
      </div>
   </div>

@endsection
@push('after-script')

@endpush
