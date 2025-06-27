@extends('guest_or_user.layouts.main_all_rooms_list')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (session('success'))
        <meta name="success-message" content="{{ session('success') }}">
    @endif
    @if (session('error'))
        <meta name="error-message" content="{{ session('error') }}">
    @endif

    <div class="container-fluid rooms py-5"></div>

    <!-- About MyKost Section Start -->
    <div class="container-fluid rooms py-5">
        <div class="container-fluid banner bg-secondary my-5">
            <div class="container py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <div class="py-4">
                            <h2 class="text-white fw-bold mb-3">Tentang Aplikasi <span style="color: #FFD700;">MyKost</span></h2>
                            <p class="text-dark fs-5 mb-4">
                                MyKost adalah platform pencarian kost modern yang dirancang untuk memudahkan mahasiswa, pekerja, dan perantau
                                menemukan tempat tinggal yang nyaman, terjangkau, dan sesuai kebutuhan — langsung dari smartphone atau laptop Anda.
                            </p>
                            <ul class="text-dark fs-6 mb-4">
                                <li>🔎 Pencarian kost berdasarkan lokasi, harga, dan fasilitas</li>
                                <li>🛏️ Detail lengkap setiap kamar, termasuk foto dan deskripsi</li>
                                <li>💬 Sistem pemesanan & komunikasi langsung dengan pemilik</li>
                                <li>🔔 Notifikasi & riwayat pemesanan realtime</li>
                            </ul>
                            <p class="text-dark">
                                Dengan MyKost, pengalaman mencari tempat tinggal menjadi lebih praktis, cepat, dan aman.
                                Kami hadir untuk mempermudah hidupmu di perantauan.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="position-relative">
                            <img src="/templates/landing/img/mykostt.png" class="img-fluid w-100 rounded" alt="Tampilan Aplikasi MyKost">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About MyKost Section End -->
@endsection
