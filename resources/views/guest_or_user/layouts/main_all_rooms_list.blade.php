<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>My Kost</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    @laravelPWA
    <link rel="icon" href="{{ asset('/templates/landing/img/logomykostt.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (session('success'))
        <meta name="success-message" content="{{ session('success') }}">
    @endif
    @if (session('error'))
        <meta name="error-message" content="{{ session('error') }}">
    @endif

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="/templates/landing/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="/templates/landing/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="/templates/landing/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="/templates/landing/css/style.css" rel="stylesheet">

    <!-- Custom CSS for Hamburger and Modal Fix -->
    <style>
        .navbar-toggler {
            z-index: 1070 !important;
            position: relative !important;
        }

        .navbar {
            z-index: 1065 !important;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        .toast-container {
            z-index: 1060 !important;
            top: 70px !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid fixed-top">
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#"
                            class="text-white">Palembang</a></small>
                    <small class="me-3">
                        <i class="fas fa-globe me-2 text-secondary"></i>
                        <a href="https://mykost.site" class="text-white" target="_blank">mykost.site</a>
                    </small>
                </div>
                <div class="top-link pe-2">
                    <a href="#" class="text-white"><small class="text-white mx-2">Murah</small>/</a>
                    <a href="#" class="text-white"><small class="text-white mx-2">Mudah</small>/</a>
                    <a href="#" class="text-white"><small class="text-white ms-2">Santai</small></a>
                </div>
            </div>
        </div>
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="{{ route('index') }}" class="navbar-brand">
                    <h1 class="text-primary display-6">MyKost</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="{{ route('index') }}"
                            class="nav-item nav-link {{ request()->routeIs('index') ? 'active text-success' : '' }}">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                        <a href="{{ route('all_kost') }}"
                            class="nav-item nav-link {{ request()->routeIs('all_kost') ? 'active text-success' : '' }}">
                            <i class="fas fa-home me-1"></i> Kost
                        </a>
                        <a href="{{ route('all_rooms') }}"
                            class="nav-item nav-link {{ request()->routeIs('all_rooms') ? 'active text-success' : '' }}">
                            <i class="fas fa-bed me-1"></i> Kamar
                        </a>
                        <a href="{{ route('about-us') }}"
                            class="nav-item nav-link {{ request()->routeIs('about-us') ? 'active text-success' : '' }}">
                            <i class="fas fa-info-circle me-1"></i> Tentang My Kost
                        </a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> Account
                            </a>
                            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                @if (Auth::check())
                                    <form action="{{ route('logout') }}" method="post">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                                        </button>
                                    </form>
                                    <a href="{{ route('dashboard') }}" class="dropdown-item">
                                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="dropdown-item">
                                        <i class="fas fa-sign-in-alt me-1"></i> Login
                                    </a>
                                    <a href="{{ route('register') }}" class="dropdown-item">
                                        <i class="fas fa-user-plus me-1"></i> Daftar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <a href="{{ route('chat_bot') }}" class="position-relative me-4 my-auto">
                            <i class="fa fa-comments fa-2x"></i>
                            <span
                                class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1"
                                style="top: -5px; left: 15px; height: 20px; min-width: 20px;">?</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    @yield('content')

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6 col-md-12">
                    <h1 class="text-white mb-3 fw-bold">MyKost</h1>
                    <p class="small text-white-50 mb-0">
                        MyKost adalah solusi hunian kost yang nyaman, aman, dan mudah ditemukan untuk para pencari
                        tempat tinggal.
                        Temukan tempat tinggal terbaik sesuai kebutuhan Anda.
                    </p>
                </div>
                <div class="col-lg-6 col-md-12 text-lg-end text-center">
                    <a class="btn btn-sm-square rounded-circle me-2"
                        href="https://www.linkedin.com/in/dian-dona-adelia/" target="_blank"
                        style="border: 2px solid #F09F38; color: #F09F38;">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright bg-dark py-3 border-top border-light">
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-md-6 text-white-50">
                    © {{ date('Y') }} <strong class="text-white">MyKost</strong>. All rights reserved.
                </div>
                <div class="col-md-6 text-md-end text-white-50">
                    Designed & Developed by
                    <a href="https://www.linkedin.com/in/dian-dona-adelia/"
                        class="text-decoration-underline text-white" target="_blank">
                        Dian Dona Adelia
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i
            class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="/templates/landing/lib/easing/easing.min.js"></script>
    <script src="/templates/landing/lib/waypoints/waypoints.min.js"></script>
    <script src="/templates/landing/lib/lightbox/js/lightbox.min.js"></script>
    <script src="/templates/landing/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/templates/landing/js/main.js"></script>

    <!-- Debug Hamburger Click -->
    <script>
        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            console.log('Hamburger menu clicked!');
        });
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                document.querySelector('.navbar-toggler').style.zIndex = '1070';
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
