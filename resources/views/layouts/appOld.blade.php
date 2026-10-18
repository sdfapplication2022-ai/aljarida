<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'الجريدة') - AlJarida</title>
    @hasSection('meta') @yield('meta') @else @include('partials.seo') @endif

    <link rel="shortcut icon" href="{{ asset('assets/img/logo/fav-logo10.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins/aos.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins/magnific-popup.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins/mobile.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins/owlcarousel.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins/sidebar.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins/slick-slider.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins/nice-select.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/main.css">
    @stack('styles')

    <script src="{{ asset('assets') }}/js/plugins/jquery-3-6-0.min.js"></script>
</head>
<body class="homepage12-rtl-body" style="background-image: url({{ asset('assets/img/bg/header-bg8.png') }})">

<div class="preloader preloader12">
  <div class="loading-container">
    <div class="loading"></div>
    <div id="loading-icon"><img src="{{ asset('assets/img/logo/logo.png') }}" alt=""></div>
  </div>
</div>

<div class="paginacontainer">
  <div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
    </svg>
  </div>
</div>

@include('partials.header')

@yield('hero')

<div id="view">
    @yield('content')
</div>

@include('partials.footer')

<script src="{{ asset('assets') }}/js/plugins/bootstrap.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/fontawesome.js"></script>
<script src="{{ asset('assets') }}/js/plugins/aos.js"></script>
<script src="{{ asset('assets') }}/js/plugins/counter.js"></script>
<script src="{{ asset('assets') }}/js/plugins/gsap.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/ScrollTrigger.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/Splitetext.js"></script>
<script src="{{ asset('assets') }}/js/plugins/sidebar.js"></script>
<script src="{{ asset('assets') }}/js/plugins/magnific-popup.js"></script>
<script src="{{ asset('assets') }}/js/plugins/mobilemenu.js"></script>
<script src="{{ asset('assets') }}/js/plugins/owlcarousel.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/gsap-animation.js"></script>
<script src="{{ asset('assets') }}/js/plugins/nice-select.js"></script>
<script src="{{ asset('assets') }}/js/plugins/waypoints.js"></script>
<script src="{{ asset('assets') }}/js/plugins/slick-slider.js"></script>
<script src="{{ asset('assets') }}/js/plugins/circle-progress.js"></script>
<script src="{{ asset('assets') }}/js/main.js"></script>
@stack('scripts')
</body>
</html>
