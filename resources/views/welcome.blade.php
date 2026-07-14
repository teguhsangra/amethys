<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rakomsis</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{ asset('assets/css/material-kit.css?v=2.1.0') }}" rel="stylesheet" />
</head>

<body class="landing-page sidebar-collapse" style="background-image: url({{asset('background.jpg')}});">

    <nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
        <div class="container">
            <div class="navbar-translate">
                <a class="navbar-brand">
                    <img src="{{asset('rakomsis.png')}}" width="150px" height="100px">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse">
                @if (Route::has('login'))
                <ul class="navbar-nav ml-auto">
                    @auth
                    <li class="nav-item" style="color:#FFF !important;">
                        <a class="nav-link" href="{{url('profile')}}" >
                            <i class="material-icons">person</i> Profile
                        </a>
                    </li>
                    @else
                    <li class="nav-item" style="color:#FFF !important;">
                        <a class="nav-link" rel="tooltip"  href="{{route('login')}}" >
                            <i class="material-icons">desktop_windows</i> Login
                        </a>
                    </li>
                    @endauth
                </ul>
                @endif
            </div>
        </div>
    </nav>

    <div class="page-header header-filter" data-parallax="true" >
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="title" style="color:#FFF !important;">Welcome To Rakomsis</h2>
                    <span>Real Time and Knowledgable Occupancy Management System</span>
                    <br>
                    <br>
                    <br>
                    <p class="lead"  style="color:white;font-size:12px;">
                        Provided By:
                        <br>
                        <a href="http://rakitek.com/" target="_blank">
                            <img class="img-fluid" src="{{asset('logo.png')}}" width="150" height="50">
                        </a>
                    </p>
                </div>
            </div>
        </div>

    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap-material-design.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/material-dashboard.js?v=2.1.0') }}" type="text/javascript"></script>
</body>

</html>
