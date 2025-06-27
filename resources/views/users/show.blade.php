@extends('layouts.admin.app')
@section('title', 'Detail User')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
        <div class="card-body p-4">
            <div class="row g-4">

                <!-- INFORMASI DETAIL -->
                <div class="col-md-13">
                    <div class="row g-3">
                        <div class="col-13">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body p-3">
                                    <h5 class="card-title mb-3" style="color: #398423;">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <p><strong>Nama:</strong> {{ $user->name }}</p>
                                            <p><strong>Email:</strong> {{ $user->email }}</p>
                                            <p><strong>Status Email:</strong>
                                                @if ($user->email_verified_at)
                                                    <span class="badge bg-success">Terverifikasi</span>
                                                @else
                                                    <span class="badge bg-danger">Belum Terverifikasi</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Created At:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
                                            <p><strong>Updated At:</strong> {{ $user->updated_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- TOMBOL AKSI -->
            <div class="row mt-4 pt-3 border-top">
                <div class="col-12 d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-lg px-4"
                       style="border-color: #398423; color: #398423;">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    @can('user-edit')
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-lg px-4"
                           style="background-color: #F6BE68; border-color: #F6BE68; color: #333;">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    @endcan
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

    .btn[style*="#398423"]:hover {
        background-color: #2d6b1c !important;
        border-color: #2d6b1c !important;
    }

    .btn[style*="#F6BE68"]:hover {
        background-color: #f4a832 !important;
        border-color: #f4a832 !important;
    }
</style>
@endsection
