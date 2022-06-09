@extends('frontend.layouts.master')
@section('content')
<div class="iq-top-navbar second-nav">
   <div class="iq-navbar-custom">
      <nav class="navbar navbar-expand-lg navbar-light p-0">
         <!-- <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
            >
         <i class="ri-menu-3-line"></i>
         </button>
         <div class="iq-menu-bt align-self-center">
            <div class="wrapper-menu">
               <div class="main-circle"><i class="ri-more-fill"></i></div>
               <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
            </div>
         </div> -->
         <div class="navbar-collapse">
            <ul class="navbar-nav navbar-list">
               <li class="nav-item">
                  <a
                     class="search-toggle iq-waves-effect language-title"
                     href="#"
                     >File <i class="ri-arrow-down-s-line"></i
                     ></a>
                  <div class="iq-sub-dropdown">
                     <a class="iq-sub-card" href="#">Blank Form</a>
                     <a class="iq-sub-card" href="#">Waiting</a>
                     <a class="iq-sub-card" href="#">Search</a>
                     <a class="iq-sub-card" href="#">Last EncID</a>
                  </div>
               </li>
               <li class="nav-item">
                  <a
                     class="search-toggle iq-waves-effect language-title"
                     href="#"
                     >Request <i class="ri-arrow-down-s-line"></i
                     ></a>
                  <div class="iq-sub-dropdown">
                     <a class="iq-sub-card" href="#">Blank Form</a>
                     <a class="iq-sub-card" href="#">Waiting</a>
                     <a class="iq-sub-card" href="#">Search</a>
                     <a class="iq-sub-card" href="#">Last EncID</a>
                  </div>
               </li>
               <li class="nav-item">
                  <a
                     class="search-toggle iq-waves-effect language-title"
                     href="#"
                     >Data Entry <i class="ri-arrow-down-s-line"></i
                     ></a>
                  <div class="iq-sub-dropdown">
                     <a class="iq-sub-card" href="#">Blank Form</a>
                     <a class="iq-sub-card" href="#">Waiting</a>
                     <a class="iq-sub-card" href="#">Search</a>
                     <a class="iq-sub-card" href="#">Last EncID</a>
                  </div>
               </li>
               <li class="nav-item">
                  <a
                     class="search-toggle iq-waves-effect language-title"
                     href="#"
                     >Data View <i class="ri-arrow-down-s-line"></i
                     ></a>
                  <div class="iq-sub-dropdown">
                     <a class="iq-sub-card" href="#">Blank Form</a>
                     <a class="iq-sub-card" href="#">Waiting</a>
                     <a class="iq-sub-card" href="#">Search</a>
                     <a class="iq-sub-card" href="#">Last EncID</a>
                  </div>
               </li>
               <li class="nav-item">
                  <a
                     class="search-toggle iq-waves-effect language-title"
                     href="#"
                     >Report <i class="ri-arrow-down-s-line"></i
                     ></a>
                  <div class="iq-sub-dropdown">
                     <a class="iq-sub-card" href="#">Blank Form</a>
                     <a class="iq-sub-card" href="#">Waiting</a>
                     <a class="iq-sub-card" href="#">Search</a>
                     <a class="iq-sub-card" href="#">Last EncID</a>
                  </div>
               </li>
               <li class="nav-item">
                  <a
                     class="search-toggle iq-waves-effect language-title"
                     href="#"
                     >Outcome <i class="ri-arrow-down-s-line"></i
                     ></a>
                  <div class="iq-sub-dropdown">
                     <a class="iq-sub-card" href="#">Blank Form</a>
                     <a class="iq-sub-card" href="#">Waiting</a>
                     <a class="iq-sub-card" href="#">Search</a>
                     <a class="iq-sub-card" href="#">Last EncID</a>
                  </div>
               </li>
               <li class="nav-item">
                  <a
                     class="search-toggle iq-waves-effect language-title"
                     href="#"
                     >History <i class="ri-arrow-down-s-line"></i
                     ></a>
                  <div class="iq-sub-dropdown">
                     <a class="iq-sub-card" href="#">Blank Form</a>
                     <a class="iq-sub-card" href="#">Waiting</a>
                     <a class="iq-sub-card" href="#">Search</a>
                     <a class="iq-sub-card" href="#">Last EncID</a>
                  </div>
               </li>
            </ul>
         </div>
      </nav>
   </div>
</div>
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">
                     Labelling
                  </h4>
               </div>
            </div>
            <div class="iq-card-body">
               <div class="container">
                  @if(Session::has('error_message'))
                  <div class="alert alert-danger col text-center" style="margin-left: 50px;">
                     <strong class="text-center"> {{ Session::get('error_message') }}</strong>
                  </div>
                  @endif
                  @if(Session::has('success_message'))
                  <div class="alert alert-success">
                     <strong>{{ Session::get('success_message') }} </strong>
                  </div>
                  <br>
                  @endif
                  <div class="row pull-right" style="float: right;margin-bottom: 20px;">
                     <a class="btn btn-success" href="{{ route('create') }}"> <i class="fa fa-plus-circle"></i> Create New Record  </a>
                  </div>
                  <div class="clearfix"></div>
                  <div class="row row_bg" style="margin-bottom: 45px;">
                     <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                           <thead>
                              <tr>
                                 <th>S.N</th>
                                 <th>Patient Name</th>
                                 <th>Encounter No</th>
                                 <th>Location/Bed No.</th>
                                 <th>DoReg</th>
                                 <th>Sex</th>
                                 <th>Status</th>
                                 <th>Operation</th>
                              </tr>
                           </thead>
                           <tbody>
                              @forelse($neuros as $neuro)
                              <tr>
                                 <td>{{ $loop->iteration }}</td>
                                 <td><strong>{{ isset($neuro) ? $neuro->full_name : null  }}</strong></td>
                                 <td>{{ isset($neuro) ? $neuro->encounter_no : null }}</td>
                                 <td>{{ isset($neuro) ? $neuro->bed_no   : null}}</td>
                                 <td>{{ isset($neuro) ? $neuro->doreg  : null}}</td>
                                 <td>{{isset($neuro) ?  $neuro->sex : null }}</td>
                                 <td>{{isset($neuro) ?  $neuro->status  : null}}</td>
                                 <td>
                                    <a href="{{ route('edit', $neuro->encounter_no) }}" class="btn btn-info">
                                    <i class="fa fa-edit"></i>
                                    </a>
                                    <button data-toggle="modal" data-target="#delete-modal" data-url="http://localhost/cogen_health/public/users/1/delete" class="btn btn-danger delete">
                                    <i class="fa fa-trash"></i>
                                    </button>
                                 </td>
                              </tr>
                              @empty
                              <tr>
                                 <td colspan="10" class="text-center"> No Records</td>
                              </tr>
                              @endforelse
                           </tbody>
                        </table>
                        @if ( $neuros->count() > 0 )
                        <div class="pull-right">
                           {{ $neuros->links() }}
                        </div>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection