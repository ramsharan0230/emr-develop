<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta
  name="viewport"
  content="width=device-width, initial-scale=1, shrink-to-fit=no"
  />
  <title>Cogent Health - Sign in</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('new/images/favicon.ico') }}" />
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('new/css/bootstrap.min.css') }}" />
  <!-- Fontawesomg css -->
  <link rel="stylesheet" href="{{ asset('new/css/use-fontawesome-all.css') }}"/>
  <!-- Typography CSS -->
  <link rel="stylesheet" href="{{ asset('new/css/typography.css') }}" />
  <!-- Style CSS -->
  <link rel="stylesheet" href="{{ asset('new/css/style.css') }}" />
  <!-- Responsive CSS -->
  <link rel="stylesheet" href="{{ asset('new/css/responsive.css') }}" />
</head>
<style>
  .required_color {
    color: red;
  }
</style>
<body>
  <!-- loader Start -->
  <div class="loader-block sign-in-page page-login">
    <div class="container sign-in-page-bg mt-5 p-0">
      <div class="row no-gutters">
        <div class="col-md-6">
          <div class="sign-in-detail">
            <div class="loader-login-logo"></div>
            <div class="loader-login-banner">
              <div class="loader-login-banner-block"></div>
              <div class="loader-login-banner-text"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6 position-relative">
          <div class="sign-in-from">
            <div class="loader-h1"></div>
            <div class="loader-p"></div>
            <div class="loader-form">
              <div class="loader-label"></div>
              <div class="loader-input"></div>
            </div>
            <div class="loader-form">
              <div class="loader-label"></div>
              <div class="loader-input"></div>
            </div>
            <div class="loader-form">
              <div class="loader-label"></div>
              <div class="loader-input"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- loader END -->
  <!-- Sign in Start -->
  <section class="sign-in-page">
    <div class="container sign-in-page-bg mt-5 p-0">
      <div class="row no-gutters">
        <div class="col-md-6 text-center">
          <div class="sign-in-detail text-white">
            <a class="sign-in-logo mb-5" href="#"
            ><img src="{{ asset('new/images/logo.png') }}" class="img-fluid" alt="logo"
            /></a>
            <div
            class="owl-carousel"
            data-autoplay="true"
            data-loop="true"
            data-nav="false"
            data-dots="true"
            data-items="1"
            data-items-laptop="1"
            data-items-tab="1"
            data-items-mobile="1"
            data-items-mobile-sm="1"
            data-margin="0"
            >
            <!-- <div class="item">
              <img
              src="{{ asset('new/images/login/1.png') }}"
              class="img-fluid mb-4"
              alt="logo"
              />
              <h4 class="mb-1 text-white">Manage your orders</h4>
              <p>
                It is a long established fact that a reader will be
                distracted by the readable content.
              </p>
            </div>
            <div class="item">
              <img
              src="{{ asset('new/images/login/2.png') }}"
              class="img-fluid mb-4"
              alt="logo"
              />
              <h4 class="mb-1 text-white">Manage your orders</h4>
              <p>
                It is a long established fact that a reader will be
                distracted by the readable content.
              </p>
            </div>
            <div class="item">
              <img
              src="{{ asset('new/images/login/3.png') }}"
              class="img-fluid mb-4"
              alt="logo"
              />
              <h4 class="mb-1 text-white">Manage your orders</h4>
              <p>
                It is a long established fact that a reader will be
                distracted by the readable content.
              </p>
            </div> -->
          </div>
        </div>
      </div>
      <div class="col-md-6 position-relative">
        <div class="sign-in-from">
          <h2 class="mb-0">Request Access</h2>
          <form action="{{ route('admin.user.profile.login') }}" method="POST" class="mt-4">

            <div class="form-group">
              <label>Mac Address<span class="required_color">*</span></label>
              <input type="text" class="form-control mb-0"/>
            </div>
            <div class="form-group">
              <label>Username<span class="required_color">*</span></label>
              <input type="text" class="form-control" name="" >
            </div>
            <div class="form-group">
              <label>Category<span class="required_color">*</span></label>
              <input type="text" class="form-control" name="">
            </div>
            <div class="d-inline-block w-100">
              <button type="submit" class="btn btn-primary w-100">
               Create
             </button>
             <div class="d-inline-block w-100 text-center">
              <a href="{{ url('/') }}" class="btnbak mt-1">Back</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</section>
<!-- Sign in END -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{ asset('new/js/jquery.min.js') }}"></script>
<script src="{{ asset('new/js/popper.min.js') }}"></script>
<script src="{{ asset('new/js/bootstrap.min.js') }}"></script>
<!-- Appear JavaScript -->
<script src="{{ asset('new/js/jquery.appear.js') }}"></script>
<!-- Countdown JavaScript -->
<script src="{{ asset('new/js/countdown.min.js') }}"></script>
<!-- Counterup JavaScript -->
<script src="{{ asset('new/js/waypoints.min.js') }}"></script>
<script src="{{ asset('new/js/jquery.counterup.min.js') }}"></script>
<!-- Wow JavaScript -->
<script src="{{ asset('new/js/wow.min.js') }}"></script>
<!-- Apexcharts JavaScript -->
<script src="{{ asset('new/js/apexcharts.js') }}"></script>
<!-- Slick JavaScript -->
<script src="{{ asset('new/js/slick.min.js') }}"></script>
<!-- Select2 JavaScript -->
<script src="{{ asset('new/js/select2.min.js') }}"></script>
<!-- Owl Carousel JavaScript -->
<script src="{{ asset('new/js/owl.carousel.min.js') }}"></script>
<!-- Magnific Popup JavaScript -->
<script src="{{ asset('new/js/jquery.magnific-popup.min.js') }}"></script>
<!-- Smooth Scrollbar JavaScript -->
<script src="{{ asset('new/js/smooth-scrollbar.js') }}"></script>
<!-- Chart Custom JavaScript -->
<script src="{{ asset('new/js/chart-custom.js') }}"></script>
<!-- Custom JavaScript -->
<script src="{{ asset('new/js/custom.js') }}"></script>
</body>
</html>
