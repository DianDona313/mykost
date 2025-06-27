<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('index') }}"
            target="_blank">
            <img src="/templates/landing/img/logomykostt.png" width="30px" height="30x"
                class="navbar-brand-img h-100" alt="main_logo">

            <span class="ms-1 font-weight-bold">MyKost</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <!-- Main Navigation -->
        <ul class="navbar-nav">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <!-- Booking Management Section -->
            @canany(['booking-list', 'booking-create'])
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Booking Management</h6>
                </li>
            @endcanany

            @can('booking-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('bookings.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-calendar-check text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Bookings</span>
                    </a>
                </li>
            @endcan



            <!-- Property Management Section -->


            @can('properti-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('properties.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-building text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Properti</span>
                    </a>
                </li>
            @endcan

            @can('room-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('rooms.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-bed text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Kamar</span>
                    </a>
                </li>
            @endcan


            @can('jeniskost-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('jeniskosts.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-home text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Jenis Kost</span>
                    </a>
                </li>
            @endcan

            @can('fasilitas-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fasilitas.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-tools text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Fasilitas</span>
                    </a>
                </li>
            @endcan

            @can('peraturan-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('peraturans.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-clipboard-list text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Peraturan</span>
                    </a>
                </li>
            @endcan

            <!-- User Management Section -->
            @canany(['penyewa-list', 'pengelola-list'])
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">User Management</h6>
                </li>
            @endcanany

            @can('penyewa-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('penyewas.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Penyewa</span>
                    </a>
                </li>
            @endcan

            @can('pengelola-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pengelolas.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-tie text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Pengelola</span>
                    </a>
                </li>
            @endcan

            <!-- Financial Management Section -->
            @canany(['payment-list', 'history_pengeluarans-list', 'kategori_pengeluarans-list',
                'metode_pembayaran-list'])
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Financial Management</h6>
                </li>
            @endcanany

            @can('payment-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('payments.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-credit-card text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Pembayaran</span>
                    </a>
                </li>
            @endcan

            @can('payment-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('laporan.okupansi') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-credit-card text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Laporan Okupansis</span>
                    </a>
                </li>
            @endcan

            @can('kategori_pengeluarans-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pengeluaran-kost.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-receipt text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Pengeluaran</span>
                    </a>
                </li>
            @endcan

            @can('kategori_pengeluarans-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('kategori_pengeluarans.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-tags text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Kategori Pengeluaran</span>
                    </a>
                </li>
            @endcan

            @can('metode_pembayaran-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('metode_pembayarans.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-hand-holding-usd text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Metode Pembayaran</span>
                    </a>
                </li>
            @endcan

            <!-- Communication Section -->
            @can('history_pesans-list')
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Communication</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('history_pesans.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-comments text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Histori Pesan</span>
                    </a>
                </li>
            @endcan

            <!-- System Administration Section -->
            @canany(['user-edit', 'role-list'])
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">System Administration</h6>
                </li>
            @endcanany

            @can('user-edit')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('User.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-users text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">User</span>
                    </a>
                </li>
            @endcan

            @can('role-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('roles.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-shield text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Roles</span>
                    </a>
                </li>
            @endcan

            @can('permission-list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('permissions.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-key text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Permission</span>
                    </a>
                </li>
            @endcan
        </ul>
    </div>

    <!-- Account Pages Section -->
    <ul class="navbar-nav mt-3">
        <li class="nav-item">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account Pages</h6>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('profile.index') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-address-card text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Profil Saya</span>
            </a>
        </li>

        @can('booking-kost')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-home text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Home</span>
                </a>
            </li>
        @endcan
    </ul>
    </div>
</aside>
