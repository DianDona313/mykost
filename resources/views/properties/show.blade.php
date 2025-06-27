@extends('layouts.admin.app')

@section('content')
    <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- FOTO DAN NAMA DI KIRI -->
            <div class="col-md-4">
                @if ($property->foto)
                    <div class="position-relative mb-3">
                        <img src="{{ asset('storage/properti_foto/' . $property->foto) }}" 
                             class="img-fluid rounded-3 w-100 shadow-sm"
                             style="object-fit: cover; height: 400px;" 
                             alt="Foto Properti">
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge fs-6 px-3 py-2 rounded-pill" style="background-color: #398423; color: white;">
                                <i class="fas fa-home me-1"></i>Properti
                            </span>
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="fw-bold mb-0" style="color: #398423;">{{ $property->nama }}</h4>
                        <div class="border-bottom mx-auto mt-2" style="width: 50px; height: 2px; background-color: #57A438;"></div>
                    </div>
                @endif
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
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-map-marker-alt me-2 mt-1" style="color: #F09F38;"></i>
                                            <div>
                                                <small class="text-muted d-block">Alamat</small>
                                                <span class="fw-medium">{{ $property->alamat }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-city me-2 mt-1" style="color: #57A438;"></i>
                                            <div>
                                                <small class="text-muted d-block">Kota</small>
                                                <span class="fw-medium">{{ $property->kota }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-building me-2 mt-1" style="color: #F6BE68;"></i>
                                            <div>
                                                <small class="text-muted d-block">Jenis Kost</small>
                                                <span class="fw-medium">{{ $property->jeniskost->nama ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-12">
                        <div class="card bg-gradient" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 0;">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-3" style="color: #398423;">
                                    <i class="fas fa-file-alt me-2"></i>Deskripsi
                                </h5>
                                <p class="card-text text-muted mb-0 lh-lg">
                                    {{ $property->deskripsi ?? 'Tidak ada deskripsi tersedia untuk properti ini.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    @if ($property->metode_pembayaran && $property->metode_pembayaran->count())
                        <div class="col-12">
                            <div class="card bg-light" style="border: 2px solid #57A438;">
                                <div class="card-body p-3">
                                    <h5 class="card-title mb-3" style="color: #57A438;">
                                        <i class="fas fa-credit-card me-2"></i>Metode Pembayaran
                                    </h5>
                                    <div class="row g-2">
                                        @foreach ($property->metode_pembayaran as $metode)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center p-2 bg-white rounded shadow-sm">
                                                    <div class="rounded-circle p-2 me-3" style="background-color: rgba(87, 164, 56, 0.1);">
                                                        <i class="fas fa-university" style="color: #57A438;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold text-dark">{{ $metode->nama_bank }}</div>
                                                        <small class="text-muted">{{ $metode->no_rek }}</small>
                                                        <br>
                                                        <small style="color: #57A438;">a.n. {{ $metode->atas_nama }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Peraturan -->
                    <div class="col-12">
                        <div class="card bg-light" style="border: 2px solid #F09F38;">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-3" style="color: #F09F38;">
                                    <i class="fas fa-list-check me-2"></i>Peraturan
                                </h5>
                                @if ($property->peraturans->count() > 0)
                                    <div class="row g-2">
                                        @foreach ($property->peraturans as $peraturan)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center p-2 bg-white rounded shadow-sm">
                                                    <div class="rounded-circle p-1 me-2" style="background-color: rgba(87, 164, 56, 0.1);">
                                                        <i class="fas fa-check" style="color: #57A438;"></i>
                                                    </div>
                                                    <span class="text-dark">{{ $peraturan->nama }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-info-circle text-muted fs-1 mb-2"></i>
                                        <p class="text-muted mb-0">Tidak ada peraturan khusus</p>
                                    </div>
                                @endif
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
                    <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary btn-lg px-4" style="border-color: #398423; color: #398423;">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>

                    @can('properti-edit')
                        @php
                            $user = Auth::user();
                            $isManager = $property->created_by === $user->id;
                        @endphp
                        @if ($user->can('properti-edit-all') || $isManager)
                            <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-lg px-4" style="background-color: #F6BE68; border-color: #F6BE68; color: #333;">
                                <i class="fas fa-edit me-2"></i>Edit Properti
                            </a>
                        @endif
                    @endcan

                    @can('properti-delete')
                        @php
                            $user = Auth::user();
                            $isManager = $property->created_by === $user->id;
                        @endphp
                        @if ($user->can('properti-delete-all') || $isManager)
                            <form action="{{ route('properties.destroy', $property->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus properti {{ $property->nama }}? Tindakan ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-lg px-4" style="background-color: #F09F38; border-color: #F09F38; color: white;">
                                    <i class="fas fa-trash me-2"></i>Hapus Properti
                                </button>
                            </form>
                        @endif
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
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.bg-gradient {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
}

/* Custom hover effects for buttons */
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
