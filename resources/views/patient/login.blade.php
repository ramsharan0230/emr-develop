<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cogent Health - Sign in</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('new/images/favicon.ico') }}"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/bootstrap.min.css') }}"/>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('patient-portal/css/style.css') }}"/>
    <!-- neuro css -->
    {{--    <link rel="stylesheet" href="{{ asset('new/css/.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('patient-portal/css/responsive.css') }}"/>
</head>

<body class="redGrey">
    <div class="login-block">
        <div class="login-box">
            <div class="company_logo">
                <img src="images/unnamed.png" alt="">
            </div>
            <div class="login-text">
                <p>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</p>
                <span>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</span>
            </div>
            <form action="{{ route('patient.portal.login.submit') }}" method="POST" class="mt-4">
                {{ csrf_field() }}
                <div class="login_innerform">
                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" class="form-control" id="" aria-describedby=""
                                placeholder="Username" name="username">
                    </div>
                    <div class="form-group">
                    <label for="">Passwrod</label>
                        <input type="password" class="form-control" id="exampleInputPassword1"
                                placeholder="Password" name="password">
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember-me">
                        <label class="custom-control-label" for="customCheck1">Keep me logged
                            in</label>
                    </div>
                    <button type="submit" class="login_btn">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>

<!-- Sign in END -->
<!-- Optional JavaScript -->
<script src="{{ asset('patient-portal/js/popper.min.js') }}"></script>
<script src="{{ asset('patient-portal/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('patient-portal/js/script.min.js') }}"></script>

</body>

</html>
