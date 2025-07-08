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

    <!-- Custom CSS for Hamburger Fix -->
    <style>
        .navbar-toggler {
            z-index: 9999 !important;
            position: relative !important;
        }
    </style>
</head>

<body>
    @include('guest_or_user.header_navbar')
    <!-- Hero Start -->
    @include('guest_or_user.banner')
    @include('guest_or_user.fasilitas')
    <!-- Hero End -->
    <!-- Featurs Section Start -->
    {{-- @yield('content') --}}
    <!-- Featurs Section End -->
    <!-- Fruits Shop Start-->
    @include('guest_or_user.kost')
    @include('guest_or_user.kamar_list')
    <!-- Fruits Shop End-->
    <!-- Banner Section Start-->
    @include('guest_or_user.about_us')
    <!-- Banner Section End-->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                <!-- Kiri -->
                <div class="col-lg-6 col-md-12">
                    <h1 class="text-white mb-3 fw-bold">MyKost</h1>
                    <p class="small text-white-50 mb-0">
                        MyKost adalah solusi hunian kost yang nyaman, aman, dan mudah ditemukan untuk para pencari
                        tempat tinggal.
                        Temukan tempat tinggal terbaik sesuai kebutuhan Anda.
                    </p>
                </div>

                <!-- Kanan -->
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
                    <a href="https://www.linkedin.com/in/dian-dona-adelia/" class="text-decoration-underline text-white"
                        target="_blank">
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
    @stack('scripts')

    <!-- Debug Hamburger Click -->
    <script>
        document.querySelector('.navbar-toggler').addEventListener('click', () => {
            console.log('Hamburger clicked');
        });
    </script>
</body>

</html>