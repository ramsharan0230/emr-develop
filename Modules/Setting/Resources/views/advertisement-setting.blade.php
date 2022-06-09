@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid extra-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between align-items-center">
                    <div class="iq-header-title">
                        <div class="form-group">
                            <label class="card-title">
                                Advertisement List
                            </label>
                      </div>
                  </div>
                  <a href="{{ route('advertisement.add') }}" class="btn btn-primary"><i
                        class="ri-add-fill"><span class="pl-1">Add New</span></i>
                    </a>
              </div>
              <div class="iq-card-body">

                <div class="res-table">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-light">
                            <tr>

                                <th>Image</th>
                                <th style="width: 48%;">Title</th>
                                <th>Description</th>

                                <th width="20px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($advertisement as $adv)
                            <tr data-fldpatientval="{{ $adv->id}}">

                                <td class="text-center"> @php $image = $adv->image @endphp
                                    @if($image != "")

                                    <img  src="data:image/jpg;base64,{{ $image }}" alt="" class="img-ad">
                                    @else
                                    <img src="{{ asset('images/user-1.png') }}" alt="" class="img-ad">
                                @endif</td>
                                <td>{{  $adv->title }}</td>
                                <td>{{  $adv->description }}</td>

                                <td>
                                     <a href="{{ route('advertisement.edit',[$adv->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>
                                    
                                    <a href="{{ route('advertisement.delete',[$adv->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Delete?');" data-toggle="confirmation"><i class="fa fa-trash"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


