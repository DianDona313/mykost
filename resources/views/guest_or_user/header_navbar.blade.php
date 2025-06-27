<div class="container-fluid fixed-top">
    <div class="container topbar bg-primary d-none d-lg-block">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#"
                        class="text-white">Palembang</a></small>
                <small class="me-3">
                    <i class="fas fa-globe me-2 text-secondary"></i>
                    <a href="https://mykost.com" class="text-white" target="_blank">mykost.site</a>
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
            <a href="index.html" class="navbar-brand">
                <h1 class="text-primary display-6">MyKost</h1>
            </a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="{{ route('index') }}"
                        class="nav-item nav-link {{ request()->routeIs('index') ? 'active text-success' : '' }}">
                        <i ></i> Home
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
            </div>


            <div class="d-flex m-3 me-0">
                <a href="{{ route('chat_bot') }}" class="position-relative me-4 my-auto">
                    <i class="fa fa-comments fa-2x"></i>
                    <span
                        class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1"
                        style="top: -5px; left: 15px; height: 20px; min-width: 20px;">?</span>
                </a>
            </div>
        </nav>
    </div>
</div>
