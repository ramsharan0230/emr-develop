<!doctype html>
<html lang="en">

<!-- Mirrored from iqonic.design/themes/xray/html/pages-error-500.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Aug 2020 04:59:09 GMT -->
<head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Favicon -->
      <link rel="shortcut icon" href="images/favicon.ico" />
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- Typography CSS -->
      <link rel="stylesheet" href="css/typography.css">
      <!-- Style CSS -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="css/responsive.css">
</head>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
        font-style: normal;
        font-size: 14px;
        line-height: 1.8;
        padding: 0;
        margin: 0;
        color: #a09e9e;
        /* background: #eff7f8; */

    }
    .btn{
        border: 1px solid #72ad5c;
    }
    .text-a {
        color:  rgb(116 177 93);
    }
    .iq-error {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        display: inline-block;
    }
    .text-center {
        text-align: center!important;
    }
   .iq-error h1 {
        font-weight: 900;
        font-size: 16rem;
        line-height: 14rem;
        margin-bottom: 0;
        letter-spacing: 15px;
    }
    h2 {
        font-size: 2.3em;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        margin: 0px;
        line-height: 1.5;
        color: #000023;
    }
    p {
        margin-top: 0;
        margin-bottom: 1rem;
    }
    .btn {
        padding: 0.175rem0.55rem;
    }
    h1 {
        background-color: #144069;
        /* background-image: url(error.jpg); */
        background-repeat: none;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-top: 200px;
        font-size: 120px;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
        font-weight: 800;
        -webkit-font-smoothing: antialiased;
    }
</style>
<body>
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
        <div class="container-fluid p-0">
            <div class="row no-gutters">
                <div class="col-sm-12 text-center">
                    <div class="iq-error error-500">
                        <h1 class="text-primary">{{$errorcode}}</h1>
                        <!-- <img src="images/error/03.png" class="img-fluid iq-error-img" alt=""> -->
                        <h2 class="mb-0">
                            @if(isset($errorMessage) && !is_null($errorMessage))
                                {{ $errorMessage }}
                            @else
                                Oops! This Page is Not Working.
                            @endif
                        </h2>
                        @php
                            $hospital_config = \App\Utils\Options::get('siteconfig');
                            if(isset($hospital_config)){
                                // $hosipitalname =  $hospital_config['system_name']. " , ";
                                $hosipitalname =  $hospital_config['system_name'] ;
                                $hosipitalAddress = $hospital_config['system_address'];

                            }

                        @endphp
                        <p>Please contact IT department at {{ $hosipitalname }}, {{$hosipitalAddress}} .</p>
                        <a class="btn text-a mt-3" href="{{ url('/') }}"><i class="ri-home-4-line"></i>Back to Home</a>

                    </div>
                </div>
            </div>
        </div>
    <!-- Wrapper END -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Appear JavaScript -->
</body>
</html>
