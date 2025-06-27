@extends('layouts.admin.app')
@section('title', 'Profil Pengguna')
@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- FOTO DAN NAMA DI KIRI -->
                    <div class="col-md-4">
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/' . ($profileData->foto ?? 'images/default-avatar.png')) }}"
                                class="img-fluid rounded-circle shadow-sm"
                                style="object-fit: cover; width: 300px; height: 300px;" alt="Foto Profil">

                            <h4 class="fw-bold mt-3 mb-0" style="color: #398423;">
                                {{ $profileData->nama ?? ($profileData->name ?? '-') }}
                            </h4>
                            <div class="border-bottom mx-auto mt-2"
                                style="width: 50px; height: 2px; background-color: #57A438;"></div>

                            @if (auth()->user()->hasRole('Admin'))
                                <span class="badge mt-2" style="background-color: #F09F38; color: white;">Admin</span>
                            @elseif(auth()->user()->hasRole('Pengelola'))
                                <span class="badge mt-2" style="background-color: #F09F38; color: white;">Pengelola</span>
                            @elseif(auth()->user()->hasRole('Penyewa'))
                                <span class="badge mt-2" style="background-color: #F09F38; color: white;">Penyewa</span>
                            @endif

                        </div>
                    </div>

                    <!-- DETAIL DI KANAN -->
                    <div class="col-md-8">
                        <div class="row g-3">
                            <!-- Informasi Dasar -->
                            <div class="col-12">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-3" style="color: #398423;">
                                            <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                        </h5>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
                                                <p><strong>No HP:</strong>
                                                    {{ $profileData->nohp ?? ($profileData->no_telp_pengelola ?? '-') }}</p>
                                                <p><strong>Jenis Kelamin:</strong> {{ $profileData->jenis_kelamin ?? '-' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Alamat:</strong> {{ $profileData->alamat ?? '-' }}</p>
                                                <p><strong>Status Email:</strong>
                                                    @if ($user->email_verified_at)
                                                        <span class="badge bg-success">Terverifikasi</span>
                                                    @else
                                                        <span class="badge"
                                                            style="background-color: #F5365C; color: #ffffff;">
                                                            Belum Terverifikasi
                                                        </span>
                                                    @endif
                                                </p>
                                                @if (isset($profileData->status))
                                                    <p><strong>Status Akun:</strong>
                                                        @if ($profileData->status === 'aktif')
                                                            <span class="badge bg-success">Aktif</span>
                                                        @else
                                                            <span class="badge bg-danger">Tidak Aktif</span>
                                                        @endif
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal -->
                            <div class="col-12">
                                <div class="card bg-gradient"
                                    style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 0;">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-3" style="color: #398423;">
                                            <i class="fas fa-calendar-alt me-2"></i>Waktu
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Bergabung:</strong>
                                                    {{ $profileData->created_at ? $profileData->created_at->format('d M Y H:i') : '-' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Terakhir Update:</strong>
                                                    {{ $profileData->updated_at ? $profileData->updated_at->format('d M Y H:i') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Tambahan -->
                            @if (isset($profileData->pekerjaan) || isset($profileData->pendapatan) || isset($profileData->ktp))
                                <div class="col-12">
                                    <div class="card bg-light" style="border: 2px solid #F09F38;">
                                        <div class="card-body p-3">
                                            <h5 class="card-title mb-3" style="color: #F09F38;">
                                                <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                                            </h5>
                                            <div class="row g-3">
                                                @if (isset($profileData->pekerjaan))
                                                    <div class="col-md-6">
                                                        <p><strong>Pekerjaan:</strong> {{ $profileData->pekerjaan }}</p>
                                                    </div>
                                                @endif
                                                @if (isset($profileData->pendapatan))
                                                    <div class="col-md-6">
                                                        <p><strong>Pendapatan:</strong> Rp
                                                            {{ number_format($profileData->pendapatan, 0, ',', '.') }}</p>
                                                    </div>
                                                @endif
                                                @if (isset($profileData->ktp))
                                                    <div class="col-md-6">
                                                        <p><strong>No. KTP:</strong> {{ $profileData->ktp }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-12 d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg px-4"
                            style="border-color: #398423; color: #398423;">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-lg px-4"
                            style="background-color: #F6BE68; border-color: #F6BE68; color: #333;">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-lg px-4"
                            style="background-color: #F09F38; border-color: #F09F38; color: white;">
                            <i class="fas fa-key me-2"></i>Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .bg-gradient {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
        }

        .btn[style*="#398423"]:hover {
            background-color: #2d6b1c !important;
            border-color: #2d6b1c !important;
        }

        .btn[style*="#F6BE68"]:hover {
            background-color: #f4a832 !important;
            border-color: #f4a832 !important;
        }

        .btn[style*="#F09F38"]:hover {
            background-color: #ec8f22 !important;
            border-color: #ec8f22 !important;
        }
    </style>
@endsection


@push('scripts')
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@endpush
