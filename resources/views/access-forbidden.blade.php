@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-sm-12 text-center">
                <div class="iq-error">
                    <h1 class="text-primary">403</h1>
                    <!-- <img src="images/error/01.png" class="img-fluid iq-error-img" alt=""> -->
                    <h2 class="mb-0">Forbidden : Access Denied</h2>
                    <p>Sorry ! You do not have permission to access this module.</p>
                    <a class="btn btn-primary mt-3" href="{{ route('admin.dashboard') }}"><i class="ri-home-4-line"></i> Back to Home</a>
                </div>
            </div>
        </div>
    </div>
@stop
