<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title> COGENT NEURO</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" type="image/ico" href="{{ asset('favicon.ico') }}"/>

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/font-awesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/metisMenu/dist/metisMenu.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/animate.css/animate.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.css') }}"/>

    <!-- App styles -->
    <link rel="stylesheet" href="{{ asset('fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}"/>
    <link rel="stylesheet" href="{{ asset('fonts/pe-icon-7-stroke/css/helper.css') }}"/>
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">

</head>
<body class="blank">
<div class="login-container">
    <div class="row">
        <div class="col-md-12">

            <div class="hpanel">
                <div class="panel-body">
                    <div class="text-center">
                        <img src="{{ asset('img/dashboard_icons/cog-logo.png') }}" style="max-height: 100px;">
                    </div>
                    <hr>
                    @if(Session::has('error_message'))
                        <div class="alert alert-danger">
                            {{ Session::get('error_message') }}
                        </div>
                        <br>
                    @endif

                    @if(Session::has('success_message'))
                        <div class="alert alert-success">
                            {{ Session::get('success_message') }}
                        </div>
                        <br>
                    @endif
                    <form action="{{ route('login.submit') }}" id="loginForm" method="POST">

                        {{ csrf_field() }}

                        {{-- <h4>Database Configuration</h4>--}}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' :'' }}">
                            <label class="control-label" for="username">Username</label>
                            <input class="form-control" type="text" name="username" value="{{ old('username') }}">
                            @if($errors->has('username'))
                                <span class="help-block">{{ $errors->first('username') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' :'' }}">
                            <label class="control-label" for="password">Password</label>
                            <input class="form-control" type="password" name="password" value="{{ old('password') }}">
                            @if($errors->has('password'))
                                <span class="help-block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <button class="btn btn-info btn-block">LOGIN</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Vendor scripts -->
<script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendor/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/metisMenu/dist/metisMenu.min.js') }}"></script>
<script src="{{ asset('vendor/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('vendor/sparkline/index.js') }}"></script>

<!-- App scripts -->
<script src="{{ asset('scripts/homer.js') }}"></script>

</body>
</html>
