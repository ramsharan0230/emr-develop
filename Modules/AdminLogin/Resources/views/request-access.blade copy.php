<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>Cogent Health</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/font-awesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/metisMenu/dist/metisMenu.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/animate.css/animate.css') }}"/>
    {{--    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.css') }}" />--}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <!-- App styles -->
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    <script src="{{ asset('js/jquery-3.4.1.js') }}"></script>


    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #17a2b8;
            height: 70vh;
            color: #fff0ff;
        }
    </style>
</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->

<body>
<div class="container">
    <div id="page-content" class="mt-3">

        @if(Session::get('success_message'))
            <div class="alert alert-success containerAlert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {{ Session::get('success_message') }}
            </div>
        @endif

        @if(Session::get('error_message'))
            <div class="alert alert-success containerAlert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {{ Session::get('error_message') }}
            </div>
        @endif

        <h6>
            Request Access
            <span class="required_fields"><strong class="required_color">*</strong> These fields are required.</span>
        </h6>
    </div>
    <form action="{{ route('admin.request.access.store') }}" method="POST" class="form-horizontal form-padding" enctype="multipart/form-data">
        <div class="form-adduser">
            <div class="col-sm-12 mt-3">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label class="col-md-2 control-label border-none" for="admin-first-name">Full Name <span class="required_color">*</span></label>
                    <div class="col-md-3">
                        <input type="text" id="admin-first-name" name="firstname" class="form-control form-control-sm" value="{{ old('firstname') }}" placeholder="First Name" required>
                        <small class="help-block text-danger">{{$errors->first('firstname')}}</small>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="admin-first-name" name="middlename" class="form-control form-control-sm" value="{{ old('middlename') }}" placeholder="Middle Name">
                        <small class="help-block text-danger">{{$errors->first('middlename')}}</small>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="admin-last-name" name="lastname" class="form-control form-control-sm" value="{{ old('lastname') }}" placeholder="Last Name">
                        <small class="help-block text-danger">{{$errors->first('lastname')}}</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 control-label border-none" for="demo-address-input">Username <span class="required_color">*</span></label>
                    <div class="col-md-4">
                        <input type="text" class="form-control form-control-sm" name="username" value="{{ old('username') }}" required>
                        <small class="help-block text-danger">{{$errors->first('username')}}</small>
                    </div>
                    <label class="col-md-2 control-label border-none" for="demo-address-input">Email<span class="required_color">*</span></label>
                    <div class="col-md-4">
                        <input type="text" class="form-control form-control-sm" name="email" value="{{ old('email') }}" required>
                        <small class="help-block text-danger">{{$errors->first('email')}}</small>
                    </div>
                </div>

                <div class="form-group row">

                    <label class="col-md-2 control-label border-none" for="demo-address-input">Password <span class="required_color">*</span></label>
                    <div class="col-md-3">
                        <input type="password" class="form-control form-control-sm" name="password" value="{{ old('password') }}" required>
                        <small class="help-block text-danger">{{$errors->first('password')}}</small>
                    </div>

                    <label class="col-md-2 control-label border-none" for="demo-address-input">Confirm Password <span class="required_color">*</span></label>
                    <div class="col-md-4">
                        <input type="password" class="form-control form-control-sm" name="re_password" value="{{ old('re_password') }}" required>
                        <small class="help-block text-danger">{{$errors->first('re_password')}}</small>
                    </div>

                </div>

                <div class="form-group row">
                    <label class="col-md-2 control-label border-none">Category <span class="required_color">*</span></label>
                    <div class="col-md-9">
                        <select name="catagory">
                            <option value=""></option>
                            <option value="test">test</option>
                        </select>
                        <small class="help-block text-danger">{{$errors->first('catagory')}}</small>
                    </div>
                </div>

                <div class="form-group row mt-3" style="margin-bottom: 10px;">
                    <label class="col-md-4 control-label border-none"></label>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-block btn-primary" name="submit" value="CREATE">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</body>
</html>
