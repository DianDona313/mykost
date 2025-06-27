@extends('layouts.admin.app')

@section('content')
<div class="container">
<div class="card shadow-lg border-0 rounded-lg overflow-hidden">
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- FOTO & IDENTITAS -->
            <div class="col-md-4">
                @if ($penyewa->foto)
                    <div class="position-relative mb-3">
                        <img src="{{ asset('storage/' . $penyewa->foto) }}"
                             class="img-fluid rounded-3 w-100 shadow-sm"
                             style="object-fit: cover; height: 400px;"
                             alt="Foto Penyewa">
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge fs-6 px-3 py-2 rounded-pill" style="background-color: #398423; color: white;">
                                <i class="fas fa-user me-1"></i>Penyewa
                            </span>
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="fw-bold mb-0" style="color: #398423;">{{ $penyewa->nama }}</h4>
                        <div class="border-bottom mx-auto mt-2" style="width: 50px; height: 2px; background-color: #57A438;"></div>
                    </div>
                @endif
            </div>

            <!-- DETAIL -->
            <div class="col-md-8">
                <div class="row g-3">

                    <div class="col-12">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-3" style="color: #398423;">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Penyewa
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-envelope me-2 mt-1" style="color: #F09F38;"></i>
                                            <div>
                                                <small class="text-muted d-block">Email</small>
                                                <span class="fw-medium">{{ $penyewa->email }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-phone me-2 mt-1" style="color: #57A438;"></i>
                                            <div>
                                                <small class="text-muted d-block">No HP</small>
                                                <span class="fw-medium">{{ $penyewa->nohp }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-map-marker-alt me-2 mt-1" style="color: #398423;"></i>
                                            <div>
                                                <small class="text-muted d-block">Alamat</small>
                                                <span class="fw-medium">{{ $penyewa->alamat }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-venus-mars me-2 mt-1" style="color: #F6BE68;"></i>
                                            <div>
                                                <small class="text-muted d-block">Jenis Kelamin</small>
                                                <span class="fw-medium">{{ $penyewa->jenis_kelamin }}</span>
                                            </div>
                                        </div>
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
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                    <a href="{{ route('penyewas.index') }}" class="btn btn-outline-secondary btn-lg px-4" style="border-color: #398423; color: #398423;">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>

                    @can('penyewa-edit')
                        <a href="{{ route('penyewas.edit', $penyewa->id) }}" class="btn btn-lg px-4" style="background-color: #F6BE68; border-color: #F6BE68; color: #333;">
                            <i class="fas fa-edit me-2"></i>Edit Penyewa
                        </a>
                    @endcan
                </div>
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
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
